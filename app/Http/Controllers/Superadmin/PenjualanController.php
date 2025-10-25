<?php

namespace App\Http\Controllers\Superadmin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PenjualanController extends Controller
{
    /**
     * Display a listing (RINGKAS)
     */
    public function index()
    {
        $penjualans = DB::select("
            SELECT * FROM view_list_penjualan
            ORDER BY tanggal DESC
        ");
        
        return view('superadmin.penjualan.index', compact('penjualans'));
    }

    /**
     * Display the specified resource (DETAIL LENGKAP)
     */
    public function show(string $id)
    {
        // Ambil data dari view_detail_penjualan
        $details = DB::select("
            SELECT * FROM view_detail_penjualan
            WHERE idpenjualan = ?
            ORDER BY iddetail_penjualan ASC
        ", [$id]);
        
        if (empty($details)) {
            abort(404, 'Penjualan tidak ditemukan');
        }
        
        // Data header penjualan
        $penjualan = $details[0];
        
        // Data detail barang
        $detail_barang = $details;
        
        return view('superadmin.penjualan.show', compact('penjualan', 'detail_barang'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil barang aktif dengan stok
        $barangs = DB::select("
            SELECT * FROM view_stok_barang
            WHERE stok_akhir > 0
            ORDER BY nama_barang ASC
        ");
        
        // Ambil margin aktif
        $margins = DB::select("
            SELECT * FROM view_margin_aktif
            ORDER BY created_at DESC
        ");
        
        return view('superadmin.penjualan.create', compact('barangs', 'margins'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'idmargin_penjualan' => 'required|exists:margin_penjualan,idmargin_penjualan',
            'subtotal_nilai' => 'required|numeric|min:0',
            'ppn' => 'required|numeric|min:0',
            'total_nilai' => 'required|numeric|min:0',
            'barang' => 'required|array|min:1',
            'barang.*.idbarang' => 'required|exists:barang,idbarang',
            'barang.*.jumlah' => 'required|numeric|min:1',
            'barang.*.harga_satuan' => 'required|numeric|min:0',
        ]);
        
        DB::beginTransaction();
        
        try {
            $iduser = auth()->user()->iduser;
            
            // Insert penjualan
            DB::insert("
                INSERT INTO penjualan (created_at, subtotal_nilai, ppn, total_nilai, idmargin_penjualan, iduser)
                VALUES (NOW(), ?, ?, ?, ?, ?)
            ", [
                $request->subtotal_nilai,
                $request->ppn,
                $request->total_nilai,
                $request->idmargin_penjualan,
                $iduser
            ]);
            
            $idpenjualan = DB::getPdo()->lastInsertId();
            
            // Insert detail barang & update kartu stok
            foreach ($request->barang as $item) {
                $subtotal = $item['jumlah'] * $item['harga_satuan'];
                
                // Insert detail penjualan
                DB::insert("
                    INSERT INTO detail_penjualan (harga_satuan, jumlah, subtotal, penjualan_idpenjualan, idbarang)
                    VALUES (?, ?, ?, ?, ?)
                ", [
                    $item['harga_satuan'],
                    $item['jumlah'],
                    $subtotal,
                    $idpenjualan,
                    $item['idbarang']
                ]);
                
                // Update kartu stok (keluar)
                $stokSebelum = DB::select("
                    SELECT stock FROM kartu_stok 
                    WHERE idbarang = ? 
                    ORDER BY created_at DESC, idkartu_stok DESC 
                    LIMIT 1
                ", [$item['idbarang']]);
                
                $stokAkhir = ($stokSebelum[0]->stock ?? 0) - $item['jumlah'];
                
                DB::insert("
                    INSERT INTO kartu_stok (jenis_transaksi, masuk, keluar, stock, created_at, idtransaksi, idbarang)
                    VALUES ('J', 0, ?, ?, NOW(), ?, ?)
                ", [
                    $item['jumlah'],
                    $stokAkhir,
                    $idpenjualan,
                    $item['idbarang']
                ]);
            }
            
            DB::commit();
            
            return redirect()
                ->route('superadmin.penjualan.show', $idpenjualan)
                ->with('success', 'Penjualan berhasil dibuat');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Gagal membuat penjualan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Hapus kartu stok terkait
            DB::delete("DELETE FROM kartu_stok WHERE jenis_transaksi = 'J' AND idtransaksi = ?", [$id]);
            
            // Hapus detail penjualan
            DB::delete("DELETE FROM detail_penjualan WHERE penjualan_idpenjualan = ?", [$id]);
            
            // Hapus penjualan
            DB::delete("DELETE FROM penjualan WHERE idpenjualan = ?", [$id]);
            
            return redirect()
                ->route('superadmin.penjualan.index')
                ->with('success', 'Penjualan berhasil dihapus');
                
        } catch (\Exception $e) {
            return redirect()
                ->route('superadmin.penjualan.index')
                ->with('error', 'Gagal menghapus penjualan: ' . $e->getMessage());
        }
    }
}