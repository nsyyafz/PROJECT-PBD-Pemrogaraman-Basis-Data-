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
        // Ambil parameter filter dari query string (default: all)
        $filter = $request->get('status', 'all');
        
        // Query berdasarkan filter
        if ($filter == 'aktif') {
            // Filter: hanya yang aktif
            $barangs = DB::select("
                SELECT * FROM view_barang_aktif
                ORDER BY idbarang ASC
            ");
        } else {
            // Default: tampilkan semua (aktif + nonaktif)
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
        // Ambil satuan aktif untuk dropdown
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
            'jenis' => 'required|in:M,N', // M=Makanan, N=Non-Makanan
            'idsatuan' => 'required|exists:satuan,idsatuan',
            'harga' => 'required|numeric|min:0',
        ], [
            'nama.required' => 'Nama barang harus diisi',
            'nama.max' => 'Nama barang maksimal 45 karakter',
            'jenis.required' => 'Jenis barang harus dipilih',
            'jenis.in' => 'Jenis barang tidak valid',
            'idsatuan.required' => 'Satuan harus dipilih',
            'idsatuan.exists' => 'Satuan tidak valid',
            'harga.required' => 'Harga harus diisi',
            'harga.numeric' => 'Harga harus berupa angka',
            'harga.min' => 'Harga tidak boleh negatif',
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
        
        // Ambil satuan aktif untuk dropdown
        $satuans = DB::select("
            SELECT idsatuan, nama_satuan 
            FROM satuan 
            WHERE status = 1
            ORDER BY nama_satuan ASC
        ");
        
        return view('superadmin.barang.edit', [
            'barang' => $barang[0],
            'satuans' => $satuans
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama' => 'required|max:45',
            'jenis' => 'required|in:M,N',
            'idsatuan' => 'required|exists:satuan,idsatuan',
            'harga' => 'required|numeric|min:0',
            'status' => 'required|in:0,1',
        ], [
            'nama.required' => 'Nama barang harus diisi',
            'nama.max' => 'Nama barang maksimal 45 karakter',
            'jenis.required' => 'Jenis barang harus dipilih',
            'jenis.in' => 'Jenis barang tidak valid',
            'idsatuan.required' => 'Satuan harus dipilih',
            'idsatuan.exists' => 'Satuan tidak valid',
            'harga.required' => 'Harga harus diisi',
            'harga.numeric' => 'Harga harus berupa angka',
            'harga.min' => 'Harga tidak boleh negatif',
            'status.required' => 'Status harus dipilih',
            'status.in' => 'Status tidak valid',
        ]);
        
        try {
            $affected = DB::update("
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
            
            if ($affected == 0) {
                return back()
                    ->withInput()
                    ->with('warning', 'Tidak ada perubahan data');
            }
            
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
            // Cek apakah barang ada
            $barang = DB::select("SELECT * FROM barang WHERE idbarang = ?", [$id]);
            
            if (empty($barang)) {
                return back()->with('error', 'Barang tidak ditemukan');
            }
            
            // Hapus barang
            DB::delete("DELETE FROM barang WHERE idbarang = ?", [$id]);
            
            return redirect()
                ->route('superadmin.barang.index')
                ->with('success', 'Barang berhasil dihapus');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus barang: ' . $e->getMessage());
        }
    }
}