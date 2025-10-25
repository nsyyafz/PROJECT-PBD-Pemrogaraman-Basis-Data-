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
        $filter = $request->get('status', 'all'); // default 'all'
        
        // Query berdasarkan filter
        if ($filter == 'aktif') {
            // Ambil vendor aktif dari view_vendor_aktif
            $vendors = DB::select("
                SELECT * FROM view_vendor_aktif
                ORDER BY idvendor ASC
            ");
        } elseif ($filter == 'nonaktif') {
            // Ambil vendor non-aktif dari view_vendor_nonaktif
            $vendors = DB::select("
                SELECT * FROM view_vendor_nonaktif
                ORDER BY idvendor ASC
            ");
        } else {
            // Semua data dari view_vendor
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Ambil detail vendor dari view_vendor
        $vendor = DB::select("
            SELECT * FROM view_vendor
            WHERE idvendor = ?
            LIMIT 1
        ", [$id]);
        
        if (empty($vendor)) {
            abort(404, 'Vendor tidak ditemukan');
        }
        
        $vendor = $vendor[0];
        
        return view('superadmin.vendor.show', compact('vendor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Ambil data vendor
        $vendor = DB::select("
            SELECT * FROM vendor WHERE idvendor = ?
        ", [$id]);
        
        if (empty($vendor)) {
            abort(404, 'Vendor tidak ditemukan');
        }
        
        $vendor = $vendor[0];
        
        return view('superadmin.vendor.edit', compact('vendor'));
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
        ]);
        
        try {
            DB::update("
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
                // Jangan hapus, tapi non-aktifkan saja
                DB::update("
                    UPDATE vendor 
                    SET status = 'N' 
                    WHERE idvendor = ?
                ", [$id]);
                
                return redirect()
                    ->route('superadmin.vendor.index')
                    ->with('success', 'Vendor tidak dapat dihapus karena sudah digunakan. Status diubah menjadi non-aktif.');
            }
            
            // Kalau belum dipakai, baru boleh dihapus
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
    
    /**
     * Toggle status vendor (aktif/non-aktif)
     */
    public function toggleStatus(string $id)
    {
        try {
            // Ambil status saat ini
            $vendor = DB::select("SELECT status FROM vendor WHERE idvendor = ?", [$id]);
            
            if (empty($vendor)) {
                return back()->with('error', 'Vendor tidak ditemukan');
            }
            
            // Toggle status ('A' jadi 'N', 'N' jadi 'A')
            $newStatus = $vendor[0]->status == 'A' ? 'N' : 'A';
            $statusText = $newStatus == 'A' ? 'aktif' : 'non-aktif';
            
            DB::update("
                UPDATE vendor 
                SET status = ? 
                WHERE idvendor = ?
            ", [$newStatus, $id]);
            
            return back()->with('success', "Status vendor berhasil diubah menjadi {$statusText}");
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengubah status: ' . $e->getMessage());
        }
    }
}