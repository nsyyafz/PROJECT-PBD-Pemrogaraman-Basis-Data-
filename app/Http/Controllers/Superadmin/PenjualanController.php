<?php

namespace App\Http\Controllers\Superadmin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PenjualanController extends Controller
{
    /**
     * Display a listing of penjualan
     * MENGGUNAKAN VIEW view_penjualan_all
     */
    public function index()
    {
        $penjualans = DB::select("
            SELECT * FROM view_penjualan_all
            ORDER BY created_at DESC
        ");
        
        return view('superadmin.penjualan.index', compact('penjualans'));
    }

    /**
     * Show the form for creating a new penjualan
     */
    public function create(Request $request)
    {
        // Ambil barang aktif yang ada stoknya
        $barangs = DB::select("
            SELECT 
                b.idbarang,
                b.nama,
                b.jenis,
                b.harga,
                s.nama_satuan,
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
        
        // Ambil user dari session
        $userId = $request->session()->get('user_id');
        $currentUser = DB::selectOne(
            "SELECT iduser, username FROM user WHERE iduser = ?", 
            [$userId]
        );
        
        // Ambil margin aktif (untuk info saja, SP yang akan pakai)
        $marginAktif = DB::selectOne("
            SELECT persen 
            FROM margin_penjualan 
            WHERE status = 1 
            ORDER BY updated_at DESC 
            LIMIT 1
        ");
        
        return view('superadmin.penjualan.create', compact('barangs', 'currentUser', 'marginAktif'));
    }

    /**
     * AJAX: Get harga jual (PAKAI FUNCTION!)
     */
    public function getHargaJual(Request $request)
    {
        try {
            $idbarang = $request->idbarang;
            
            // CALL FUNCTION (sudah termasuk margin!)
            $result = DB::select("SELECT fn_hitung_harga_jual(?) as harga_jual", [$idbarang]);
            
            // Ambil stok dari kartu_stok
            $stok = DB::selectOne("
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
                'harga_jual' => $result[0]->harga_jual ?? 0,
                'stok_tersedia' => $stok->stok ?? 0
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
     * SP: sp_insert_detail_penjualan
     * TRIGGER: trg_after_insert_penjualan (update kartu_stok + stok barang)
     */
    public function store(Request $request)
    {
        $request->validate([
            'ppn' => 'required|numeric|min:0',
            'barang' => 'required|array|min:1',
            'barang.*.idbarang' => 'required|exists:barang,idbarang',
            'barang.*.jumlah' => 'required|numeric|min:1',
        ], [
            'ppn.required' => 'PPN harus diisi',
            'ppn.numeric' => 'PPN harus berupa angka',
            'barang.required' => 'Minimal 1 barang harus ditambahkan',
            'barang.*.idbarang.required' => 'ID Barang tidak valid',
            'barang.*.idbarang.exists' => 'Barang tidak ditemukan',
            'barang.*.jumlah.required' => 'Jumlah harus diisi',
            'barang.*.jumlah.min' => 'Jumlah minimal 1',
        ]);
        
        DB::beginTransaction();
        
        try {
            // Ambil user dari session
            $iduser = $request->session()->get('user_id') ?? 1;
            
            // 1. Ambil margin aktif
            $margin = DB::selectOne("
                SELECT idmargin_penjualan 
                FROM margin_penjualan 
                WHERE status = 1 
                ORDER BY updated_at DESC 
                LIMIT 1
            ");
            
            if (!$margin) {
                throw new \Exception('Margin penjualan belum diatur. Silakan set margin terlebih dahulu.');
            }
            
            // 2. Insert header penjualan
            $idpenjualan = DB::table('penjualan')->insertGetId([
                'created_at' => now(),
                'subtotal_nilai' => 0,
                'ppn' => $request->ppn,
                'total_nilai' => 0,
                'iduser' => $iduser,
                'idmargin_penjualan' => $margin->idmargin_penjualan
            ]);
            
            // 3. Insert detail menggunakan STORED PROCEDURE
            // SP otomatis:
            // - Validasi stok mencukupi
            // - Hitung harga jual via fn_hitung_harga_jual()
            // - Update subtotal & total di header
            // TRIGGER otomatis:
            // - Insert kartu_stok (jenis 'J')
            // - Kurangi stok barang
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
                ->with('success', 'Penjualan berhasil dibuat! Stok otomatis terupdate.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Handle error spesifik
            $errorMsg = $e->getMessage();
            
            if (str_contains($errorMsg, 'Stok tidak mencukupi')) {
                return back()
                    ->withInput()
                    ->with('error', 'Stok barang tidak mencukupi untuk penjualan ini!');
            }
            
            return back()
                ->withInput()
                ->with('error', 'Gagal membuat penjualan: ' . $errorMsg);
        }
    }

    /**
     * Display the specified penjualan
     * MENGGUNAKAN VIEW view_penjualan_all & view_detail_penjualan_all
     */
    public function show(string $id)
    {
        // Ambil header dari view
        $penjualan = DB::selectOne("
            SELECT * FROM view_penjualan_all
            WHERE idpenjualan = ?
        ", [$id]);
        
        if (!$penjualan) {
            abort(404, 'Penjualan tidak ditemukan');
        }
        
        // Ambil detail dari view (sudah include keuntungan per item)
        $details = DB::select("
            SELECT * FROM view_detail_penjualan_all
            WHERE penjualan_idpenjualan = ?
            ORDER BY iddetail_penjualan ASC
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
            // Cek penjualan exists
            $penjualan = DB::selectOne("
                SELECT idpenjualan FROM penjualan WHERE idpenjualan = ?
            ", [$id]);
            
            if (!$penjualan) {
                throw new \Exception('Penjualan tidak ditemukan');
            }
            
            // Cek apakah sudah ada retur
            $hasRetur = DB::selectOne("
                SELECT COUNT(*) as total 
                FROM retur 
                WHERE idpenjualan = ?
            ", [$id]);
            
            if ($hasRetur->total > 0) {
                throw new \Exception('Penjualan tidak dapat dihapus karena sudah ada retur!');
            }
            
            // 1. Hapus kartu_stok (jenis transaksi 'J' = Jual)
            DB::delete("
                DELETE FROM kartu_stok 
                WHERE jenis_transaksi = 'J' 
                AND idtransaksi = ?
            ", [$id]);
            
            // 2. Hapus detail penjualan
            DB::delete("DELETE FROM detail_penjualan WHERE penjualan_idpenjualan = ?", [$id]);
            
            // 3. Hapus header penjualan
            DB::delete("DELETE FROM penjualan WHERE idpenjualan = ?", [$id]);
            
            DB::commit();
            
            return redirect()
                ->route('superadmin.penjualan.index')
                ->with('success', 'Penjualan berhasil dihapus!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus penjualan: ' . $e->getMessage());
        }
    }
}