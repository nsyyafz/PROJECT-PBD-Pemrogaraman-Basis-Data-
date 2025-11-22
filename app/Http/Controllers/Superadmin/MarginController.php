<?php

namespace App\Http\Controllers\Superadmin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class MarginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = $request->get('status', 'aktif');
        
        if ($filter == 'aktif') {
            $margins = DB::select("
                SELECT * FROM view_margin_aktif
                ORDER BY created_at DESC
            ");
        } else {
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
        ], [
            'persen.required' => 'Persentase margin harus diisi',
            'persen.numeric' => 'Persentase margin harus berupa angka',
            'persen.min' => 'Persentase margin minimal 0 (0%)',
            'persen.max' => 'Persentase margin maksimal 1 (100%)',
        ]);
        
        try {
            // Ambil ID user dari session
            $iduser = session('user')['iduser'] ?? 1;
            
            DB::insert("
                INSERT INTO margin_penjualan (persen, status, iduser, created_at)
                VALUES (?, 1, ?, NOW())
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
                ->with('error', 'Gagal menambahkan margin: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $margin = DB::select("
            SELECT * FROM margin_penjualan WHERE idmargin_penjualan = ?
        ", [$id]);
        
        if (empty($margin)) {
            abort(404, 'Margin penjualan tidak ditemukan');
        }
        
        return view('superadmin.margin.edit', [
            'margin' => $margin[0]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'persen' => 'required|numeric|min:0|max:1',
            'status' => 'required|in:0,1',
        ], [
            'persen.required' => 'Persentase margin harus diisi',
            'persen.numeric' => 'Persentase margin harus berupa angka',
            'persen.min' => 'Persentase margin minimal 0 (0%)',
            'persen.max' => 'Persentase margin maksimal 1 (100%)',
            'status.required' => 'Status harus dipilih',
            'status.in' => 'Status tidak valid',
        ]);
        
        try {
            $affected = DB::update("
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
            
            if ($affected == 0) {
                return back()
                    ->withInput()
                    ->with('warning', 'Tidak ada perubahan data');
            }
            
            return redirect()
                ->route('superadmin.margin.index')
                ->with('success', 'Margin penjualan berhasil diupdate');
                
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal mengupdate margin: ' . $e->getMessage());
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
                // Jangan hapus, tapi nonaktifkan
                DB::update("
                    UPDATE margin_penjualan 
                    SET status = 0,
                        updated_at = NOW()
                    WHERE idmargin_penjualan = ?
                ", [$id]);
                
                return redirect()
                    ->route('superadmin.margin.index')
                    ->with('warning', 'Margin tidak dapat dihapus karena sudah digunakan dalam penjualan. Status diubah menjadi non-aktif.');
            }
            
            // Kalau belum dipakai, boleh dihapus
            DB::delete("DELETE FROM margin_penjualan WHERE idmargin_penjualan = ?", [$id]);
            
            return redirect()
                ->route('superadmin.margin.index')
                ->with('success', 'Margin penjualan berhasil dihapus');
                
        } catch (\Exception $e) {
            return redirect()
                ->route('superadmin.margin.index')
                ->with('error', 'Gagal menghapus margin: ' . $e->getMessage());
        }
    }
}