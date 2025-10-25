<?php

namespace App\Http\Controllers\Superadmin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = $request->get('status', 'all'); // default 'all'
        
        // Query berdasarkan filter
        if ($filter == 'aktif') {
            // Ambil barang aktif dari view_barang_aktif
            $barangs = DB::select("
                SELECT * FROM view_barang_aktif
                ORDER BY idbarang ASC
            ");
        } elseif ($filter == 'nonaktif') {
            // Ambil barang non-aktif dari view_barang_nonaktif
            $barangs = DB::select("
                SELECT * FROM view_barang_nonaktif
                ORDER BY idbarang ASC
            ");
        } else {
            // Semua data (gabungan dari view_barang yang punya semua status)
            $barangs = DB::select("
                SELECT * FROM view_barang
                ORDER BY idbarang ASC
            ");
        }
        
        return view('superadmin.barang.index', compact('barangs', 'filter'));
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil data satuan yang aktif untuk dropdown
        $satuans = DB::select("
            SELECT idsatuan, nama_satuan 
            FROM satuan 
            WHERE status = 1
            ORDER BY nama_satuan ASC
        ");
        
        return view('superadmin.barang.create', compact('satuans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|max:45',
            'jenis' => 'required|in:M,A', // M=Makanan, A=Alat
            'idsatuan' => 'required|exists:satuan,idsatuan',
            'harga' => 'required|numeric|min:0',
        ]);
        
        try {
            DB::insert("
                INSERT INTO barang (jenis, nama, idsatuan, harga, status)
                VALUES (?, ?, ?, ?, 1)
            ", [
                $request->jenis,
                $request->nama,
                $request->idsatuan,
                $request->harga
            ]);
            
            return redirect()
                ->route('superadmin.barang.index')
                ->with('success', 'Barang berhasil ditambahkan');
                
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal menambahkan barang: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Ambil detail barang dari view_barang
        $barang = DB::select("
            SELECT * FROM view_barang
            WHERE idbarang = ?
            LIMIT 1
        ", [$id]);
        
        if (empty($barang)) {
            abort(404, 'Barang tidak ditemukan');
        }
        
        $barang = $barang[0];
        
        return view('superadmin.barang.show', compact('barang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Ambil data barang
        $barang = DB::select("
            SELECT * FROM barang WHERE idbarang = ?
        ", [$id]);
        
        if (empty($barang)) {
            abort(404, 'Barang tidak ditemukan');
        }
        
        $barang = $barang[0];
        
        // Ambil data satuan yang aktif
        $satuans = DB::select("
            SELECT idsatuan, nama_satuan 
            FROM satuan 
            WHERE status = 1
            ORDER BY nama_satuan ASC
        ");
        
        return view('superadmin.barang.edit', compact('barang', 'satuans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama' => 'required|max:45',
            'jenis' => 'required|in:M,A',
            'idsatuan' => 'required|exists:satuan,idsatuan',
            'harga' => 'required|numeric|min:0',
            'status' => 'required|in:0,1',
        ]);
        
        try {
            DB::update("
                UPDATE barang 
                SET jenis = ?, 
                    nama = ?, 
                    idsatuan = ?, 
                    harga = ?,
                    status = ?
                WHERE idbarang = ?
            ", [
                $request->jenis,
                $request->nama,
                $request->idsatuan,
                $request->harga,
                $request->status,
                $id
            ]);
            
            return redirect()
                ->route('superadmin.barang.index')
                ->with('success', 'Barang berhasil diupdate');
                
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal mengupdate barang: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Cek apakah barang pernah digunakan dalam transaksi
            $used = DB::select("
                SELECT COUNT(*) as total FROM (
                    SELECT idbarang FROM detail_pengadaan WHERE idbarang = ?
                    UNION ALL
                    SELECT idbarang FROM detail_penerimaan WHERE idbarang = ?
                    UNION ALL
                    SELECT idbarang FROM detail_penjualan WHERE idbarang = ?
                    UNION ALL
                    SELECT idbarang FROM kartu_stok WHERE idbarang = ?
                ) as used_barang
            ", [$id, $id, $id, $id]);
            
            if ($used[0]->total > 0) {
                // Jangan hapus, tapi non-aktifkan saja
                DB::update("
                    UPDATE barang 
                    SET status = 0 
                    WHERE idbarang = ?
                ", [$id]);
                
                return redirect()
                    ->route('superadmin.barang.index')
                    ->with('success', 'Barang tidak dapat dihapus karena sudah digunakan. Status diubah menjadi non-aktif.');
            }
            
            // Kalau belum pernah dipakai, baru boleh dihapus
            DB::delete("DELETE FROM barang WHERE idbarang = ?", [$id]);
            
            return redirect()
                ->route('superadmin.barang.index')
                ->with('success', 'Barang berhasil dihapus');
                
        } catch (\Exception $e) {
            return redirect()
                ->route('superadmin.barang.index')
                ->with('error', 'Gagal menghapus barang: ' . $e->getMessage());
        }
    }
    
    /**
     * Toggle status barang (aktif/non-aktif)
     */
    public function toggleStatus(string $id)
    {
        try {
            // Ambil status saat ini
            $barang = DB::select("SELECT status FROM barang WHERE idbarang = ?", [$id]);
            
            if (empty($barang)) {
                return back()->with('error', 'Barang tidak ditemukan');
            }
            
            // Toggle status (1 jadi 0, 0 jadi 1)
            $newStatus = $barang[0]->status == 1 ? 0 : 1;
            $statusText = $newStatus == 1 ? 'aktif' : 'non-aktif';
            
            DB::update("
                UPDATE barang 
                SET status = ? 
                WHERE idbarang = ?
            ", [$newStatus, $id]);
            
            return back()->with('success', "Status barang berhasil diubah menjadi {$statusText}");
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengubah status: ' . $e->getMessage());
        }
    }
}