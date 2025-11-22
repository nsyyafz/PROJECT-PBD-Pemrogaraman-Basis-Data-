<?php

namespace App\Http\Controllers\Superadmin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = $request->get('status', 'aktif');
        
        if ($filter == 'aktif') {
            $vendors = DB::select("
                SELECT * FROM view_vendor_aktif
                ORDER BY idvendor ASC
            ");
        } else {
            $vendors = DB::select("
                SELECT * FROM view_vendor
                ORDER BY idvendor ASC
            ");
        }
        
        return view('superadmin.vendor.index', compact('vendors', 'filter'));
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('superadmin.vendor.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_vendor' => 'required|max:100',
            'badan_hukum' => 'required|in:P,C,U',
        ], [
            'nama_vendor.required' => 'Nama vendor harus diisi',
            'nama_vendor.max' => 'Nama vendor maksimal 100 karakter',
            'badan_hukum.required' => 'Badan hukum harus dipilih',
            'badan_hukum.in' => 'Badan hukum hanya boleh PT (P), CV (C), atau UD (U)',
        ]);
        
        try {
            DB::insert("
                INSERT INTO vendor (nama_vendor, badan_hukum, status)
                VALUES (?, ?, 'A')
            ", [
                $request->nama_vendor,
                $request->badan_hukum
            ]);
            
            return redirect()
                ->route('superadmin.vendor.index')
                ->with('success', 'Vendor berhasil ditambahkan');
                
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal menambahkan vendor: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $vendor = DB::select("
            SELECT * FROM vendor WHERE idvendor = ?
        ", [$id]);
        
        if (empty($vendor)) {
            abort(404, 'Vendor tidak ditemukan');
        }
        
        return view('superadmin.vendor.edit', [
            'vendor' => $vendor[0]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_vendor' => 'required|max:100',
            'badan_hukum' => 'required|in:P,C,U',
            'status' => 'required|in:A,N',
        ], [
            'nama_vendor.required' => 'Nama vendor harus diisi',
            'nama_vendor.max' => 'Nama vendor maksimal 100 karakter',
            'badan_hukum.required' => 'Badan hukum harus dipilih',
            'badan_hukum.in' => 'Badan hukum hanya boleh PT (P), CV (C), atau UD (U)',
            'status.required' => 'Status harus dipilih',
            'status.in' => 'Status tidak valid',
        ]);
        
        try {
            $affected = DB::update("
                UPDATE vendor 
                SET nama_vendor = ?, 
                    badan_hukum = ?, 
                    status = ?
                WHERE idvendor = ?
            ", [
                $request->nama_vendor,
                $request->badan_hukum,
                $request->status,
                $id
            ]);
            
            if ($affected == 0) {
                return back()
                    ->withInput()
                    ->with('warning', 'Tidak ada perubahan data');
            }
            
            return redirect()
                ->route('superadmin.vendor.index')
                ->with('success', 'Vendor berhasil diupdate');
                
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal mengupdate vendor: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Cek apakah vendor masih digunakan dalam pengadaan
            $used = DB::select("
                SELECT COUNT(*) as total 
                FROM pengadaan 
                WHERE vendor_idvendor = ?
            ", [$id]);
            
            if ($used[0]->total > 0) {
                // Jangan hapus, tapi nonaktifkan
                DB::update("
                    UPDATE vendor 
                    SET status = 'N' 
                    WHERE idvendor = ?
                ", [$id]);
                
                return redirect()
                    ->route('superadmin.vendor.index')
                    ->with('warning', 'Vendor tidak dapat dihapus karena sudah digunakan dalam pengadaan. Status diubah menjadi non-aktif.');
            }
            
            // Kalau belum dipakai, boleh dihapus
            DB::delete("DELETE FROM vendor WHERE idvendor = ?", [$id]);
            
            return redirect()
                ->route('superadmin.vendor.index')
                ->with('success', 'Vendor berhasil dihapus');
                
        } catch (\Exception $e) {
            return redirect()
                ->route('superadmin.vendor.index')
                ->with('error', 'Gagal menghapus vendor: ' . $e->getMessage());
        }
    }
}