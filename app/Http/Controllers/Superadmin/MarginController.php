<?php

namespace App\Http\Controllers\Superadmin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MarginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = $request->get('status', 'all'); // default 'all'
        
        // Query berdasarkan filter
        if ($filter == 'aktif') {
            // Ambil margin aktif dari view_margin_aktif
            $margins = DB::select("
                SELECT * FROM view_margin_aktif
                ORDER BY created_at DESC
            ");
        } elseif ($filter == 'nonaktif') {
            // Ambil margin non-aktif dari view_margin_nonaktif
            $margins = DB::select("
                SELECT * FROM view_margin_nonaktif
                ORDER BY created_at DESC
            ");
        } else {
            // Semua data dari view_margin_penjualan
            $margins = DB::select("
                SELECT * FROM view_margin_penjualan
                ORDER BY created_at DESC
            ");
        }
        
        return view('superadmin.margin.index', compact('margins', 'filter'));
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('superadmin.margin.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'persen' => 'required|numeric|min:0|max:1',
        ]);
        
        try {
            $iduser = Auth::id(); // Get current user ID
            
            DB::insert("
                INSERT INTO margin_penjualan (persen, status, iduser, created_at, updated_at)
                VALUES (?, 1, ?, NOW(), NOW())
            ", [
                $request->persen,
                $iduser
            ]);
            
            return redirect()
                ->route('superadmin.margin.index')
                ->with('success', 'Margin penjualan berhasil ditambahkan');
                
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal menambahkan margin penjualan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Ambil detail margin dari view_margin_penjualan
        $margin = DB::select("
            SELECT * FROM view_margin_penjualan
            WHERE idmargin_penjualan = ?
            LIMIT 1
        ", [$id]);
        
        if (empty($margin)) {
            abort(404, 'Margin penjualan tidak ditemukan');
        }
        
        $margin = $margin[0];
        
        return view('superadmin.margin.show', compact('margin'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Ambil data margin
        $margin = DB::select("
            SELECT * FROM margin_penjualan WHERE idmargin_penjualan = ?
        ", [$id]);
        
        if (empty($margin)) {
            abort(404, 'Margin penjualan tidak ditemukan');
        }
        
        $margin = $margin[0];
        
        return view('superadmin.margin.edit', compact('margin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'persen' => 'required|numeric|min:0|max:1',
            'status' => 'required|in:0,1',
        ]);
        
        try {
            DB::update("
                UPDATE margin_penjualan 
                SET persen = ?, 
                    status = ?,
                    updated_at = NOW()
                WHERE idmargin_penjualan = ?
            ", [
                $request->persen,
                $request->status,
                $id
            ]);
            
            return redirect()
                ->route('superadmin.margin.index')
                ->with('success', 'Margin penjualan berhasil diupdate');
                
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal mengupdate margin penjualan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Cek apakah margin masih digunakan dalam penjualan
            $used = DB::select("
                SELECT COUNT(*) as total 
                FROM penjualan 
                WHERE idmargin_penjualan = ?
            ", [$id]);
            
            if ($used[0]->total > 0) {
                // Jangan hapus, tapi non-aktifkan saja
                DB::update("
                    UPDATE margin_penjualan 
                    SET status = 0,
                        updated_at = NOW()
                    WHERE idmargin_penjualan = ?
                ", [$id]);
                
                return redirect()
                    ->route('superadmin.margin.index')
                    ->with('success', 'Margin tidak dapat dihapus karena sudah digunakan. Status diubah menjadi non-aktif.');
            }
            
            // Kalau belum dipakai, baru boleh dihapus
            DB::delete("DELETE FROM margin_penjualan WHERE idmargin_penjualan = ?", [$id]);
            
            return redirect()
                ->route('superadmin.margin.index')
                ->with('success', 'Margin penjualan berhasil dihapus');
                
        } catch (\Exception $e) {
            return redirect()
                ->route('superadmin.margin.index')
                ->with('error', 'Gagal menghapus margin penjualan: ' . $e->getMessage());
        }
    }
    
    /**
     * Toggle status margin (aktif/non-aktif)
     */
    public function toggleStatus(string $id)
    {
        try {
            // Ambil status saat ini
            $margin = DB::select("SELECT status FROM margin_penjualan WHERE idmargin_penjualan = ?", [$id]);
            
            if (empty($margin)) {
                return back()->with('error', 'Margin penjualan tidak ditemukan');
            }
            
            // Toggle status (1 jadi 0, 0 jadi 1)
            $newStatus = $margin[0]->status == 1 ? 0 : 1;
            $statusText = $newStatus == 1 ? 'aktif' : 'non-aktif';
            
            DB::update("
                UPDATE margin_penjualan 
                SET status = ?,
                    updated_at = NOW()
                WHERE idmargin_penjualan = ?
            ", [$newStatus, $id]);
            
            return back()->with('success', "Status margin penjualan berhasil diubah menjadi {$statusText}");
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengubah status: ' . $e->getMessage());
        }
    }
}