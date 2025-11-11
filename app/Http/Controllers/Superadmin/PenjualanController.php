<?php

namespace App\Http\Controllers\Superadmin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PenjualanController extends Controller
{
    /**
     * Display a listing of penjualan
     */
    public function index()
    {
        $penjualans = DB::select("
            SELECT p.*, u.username
            FROM penjualan p
            JOIN user u ON p.iduser = u.iduser
            ORDER BY p.created_at DESC
        ");
        
        return view('superadmin.penjualan.index', compact('penjualans'));
    }

    /**
     * Show the form for creating a new penjualan
     */
    public function create()
    {
        // Ambil barang aktif yang ada stoknya
        $barangs = DB::select("
            SELECT b.*, s.nama_satuan,
                   IFNULL((
                       SELECT stock 
                       FROM kartu_stok 
                       WHERE idbarang = b.idbarang 
                       ORDER BY idkartu_stok DESC 
                       LIMIT 1
                   ), 0) as stok_tersedia
            FROM barang b
            JOIN satuan s ON b.idsatuan = s.idsatuan
            WHERE b.status = 1
            HAVING stok_tersedia > 0
            ORDER BY b.nama
        ");
        
        return view('superadmin.penjualan.create', compact('barangs'));
    }

    /**
     * AJAX: Get harga jual (PAKAI FUNCTION!)
     */
    public function getHargaJual(Request $request)
    {
        $idbarang = $request->idbarang;
        
        try {
            // CALL FUNCTION (sudah termasuk margin!)
            $result = DB::select("SELECT fn_hitung_harga_jual(?) as harga_jual", [$idbarang]);
            
            // Ambil stok
            $stok = DB::select("
                SELECT IFNULL((
                    SELECT stock 
                    FROM kartu_stok 
                    WHERE idbarang = ? 
                    ORDER BY idkartu_stok DESC 
                    LIMIT 1
                ), 0) as stok
            ", [$idbarang]);
            
            return response()->json([
                'success' => true,
                'harga_jual' => $result[0]->harga_jual,
                'stok_tersedia' => $stok[0]->stok
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store penjualan (PAKAI SP & TRIGGER!)
     */
    public function store(Request $request)
    {
        $request->validate([
            'ppn' => 'required|numeric|min:0',
            'barang' => 'required|array|min:1',
            'barang.*.idbarang' => 'required|exists:barang,idbarang',
            'barang.*.jumlah' => 'required|numeric|min:1',
        ]);
        
        DB::beginTransaction();
        
        try {
            $iduser = auth()->user()->iduser;
            
            // 1. Insert header penjualan (manual)
            DB::insert("
                INSERT INTO penjualan (created_at, subtotal_nilai, ppn, total_nilai, iduser)
                VALUES (NOW(), 0, ?, 0, ?)
            ", [$request->ppn, $iduser]);
            
            $idpenjualan = DB::getPdo()->lastInsertId();
            
            // 2. Insert detail menggunakan STORED PROCEDURE
            // SP otomatis update subtotal & total
            // TRIGGER otomatis kurangi stok!
            foreach ($request->barang as $item) {
                DB::statement("CALL sp_insert_detail_penjualan(?, ?, ?)", [
                    $idpenjualan,
                    $item['idbarang'],
                    $item['jumlah']
                ]);
            }
            
            DB::commit();
            
            return redirect()
                ->route('superadmin.penjualan.show', $idpenjualan)
                ->with('success', 'Penjualan berhasil dibuat dan stok otomatis terupdate!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Handle error stok tidak cukup
            if (str_contains($e->getMessage(), 'Stok tidak mencukupi')) {
                return back()
                    ->withInput()
                    ->with('error', 'Stok barang tidak mencukupi untuk penjualan ini!');
            }
            
            return back()
                ->withInput()
                ->with('error', 'Gagal membuat penjualan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified penjualan
     */
    public function show(string $id)
    {
        $penjualan = DB::selectOne("
            SELECT p.*, u.username
            FROM penjualan p
            JOIN user u ON p.iduser = u.iduser
            WHERE p.idpenjualan = ?
        ", [$id]);
        
        if (!$penjualan) {
            abort(404);
        }
        
        $details = DB::select("
            SELECT dp.*, b.nama as nama_barang, s.nama_satuan
            FROM detail_penjualan dp
            JOIN barang b ON dp.idbarang = b.idbarang
            JOIN satuan s ON b.idsatuan = s.idsatuan
            WHERE dp.penjualan_idpenjualan = ?
        ", [$id]);
        
        return view('superadmin.penjualan.show', compact('penjualan', 'details'));
    }

    /**
     * Remove the specified penjualan
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        
        try {
            // Cek apakah sudah ada retur
            $hasRetur = DB::selectOne("
                SELECT COUNT(*) as total FROM retur WHERE idpenjualan = ?
            ", [$id]);
            
            if ($hasRetur->total > 0) {
                return back()->with('error', 'Penjualan tidak dapat dihapus karena sudah ada retur');
            }
            
            // Hapus kartu_stok (jenis transaksi 'J' = Jual)
            DB::delete("
                DELETE FROM kartu_stok 
                WHERE jenis_transaksi = 'J' 
                AND idtransaksi = ?
            ", [$id]);
            
            // Hapus detail
            DB::delete("DELETE FROM detail_penjualan WHERE penjualan_idpenjualan = ?", [$id]);
            
            // Hapus header
            DB::delete("DELETE FROM penjualan WHERE idpenjualan = ?", [$id]);
            
            DB::commit();
            
            return redirect()
                ->route('superadmin.penjualan.index')
                ->with('success', 'Penjualan berhasil dihapus');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }
}