<?php

namespace App\Http\Controllers\Superadmin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SatuanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = $request->get('status', 'all');
        
        if ($filter == 'aktif') {
            $satuans = DB::select("
                SELECT * FROM view_satuan_aktif
                ORDER BY idsatuan ASC
            ");
        } else {
            $satuans = DB::select("
                SELECT * FROM view_satuan
                ORDER BY idsatuan ASC
            ");
        }
        
        return view('superadmin.satuan.index', compact('satuans', 'filter'));
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('superadmin.satuan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_satuan' => 'required|max:45|unique:satuan,nama_satuan',
        ], [
            'nama_satuan.required' => 'Nama satuan harus diisi',
            'nama_satuan.max' => 'Nama satuan maksimal 45 karakter',
            'nama_satuan.unique' => 'Nama satuan sudah ada',
        ]);
        
        try {
            DB::insert("
                INSERT INTO satuan (nama_satuan, status)
                VALUES (?, 1)
            ", [
                $request->nama_satuan
            ]);
            
            return redirect()
                ->route('superadmin.satuan.index')
                ->with('success', 'Satuan berhasil ditambahkan');
                
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal menambahkan satuan: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $satuan = DB::select("
            SELECT * FROM satuan WHERE idsatuan = ?
        ", [$id]);
        
        if (empty($satuan)) {
            abort(404, 'Satuan tidak ditemukan');
        }
        
        return view('superadmin.satuan.edit', [
            'satuan' => $satuan[0]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_satuan' => 'required|max:45|unique:satuan,nama_satuan,' . $id . ',idsatuan',
            'status' => 'required|in:0,1',
        ], [
            'nama_satuan.required' => 'Nama satuan harus diisi',
            'nama_satuan.max' => 'Nama satuan maksimal 45 karakter',
            'nama_satuan.unique' => 'Nama satuan sudah ada',
            'status.required' => 'Status harus dipilih',
            'status.in' => 'Status tidak valid',
        ]);
        
        try {
            $affected = DB::update("
                UPDATE satuan 
                SET nama_satuan = ?, 
                    status = ?
                WHERE idsatuan = ?
            ", [
                $request->nama_satuan,
                $request->status,
                $id
            ]);
            
            if ($affected == 0) {
                return back()
                    ->withInput()
                    ->with('warning', 'Tidak ada perubahan data');
            }
            
            return redirect()
                ->route('superadmin.satuan.index')
                ->with('success', 'Satuan berhasil diupdate');
                
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal mengupdate satuan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Cek apakah satuan masih digunakan oleh barang
            $used = DB::select("
                SELECT COUNT(*) as total 
                FROM barang 
                WHERE idsatuan = ?
            ", [$id]);
            
            if ($used[0]->total > 0) {
                // Jangan hapus, tapi nonaktifkan
                DB::update("
                    UPDATE satuan 
                    SET status = 0 
                    WHERE idsatuan = ?
                ", [$id]);
                
                return redirect()
                    ->route('superadmin.satuan.index')
                    ->with('warning', 'Satuan tidak dapat dihapus karena masih digunakan oleh barang. Status diubah menjadi non-aktif.');
            }
            
            // Kalau belum dipakai, boleh dihapus
            DB::delete("DELETE FROM satuan WHERE idsatuan = ?", [$id]);
            
            return redirect()
                ->route('superadmin.satuan.index')
                ->with('success', 'Satuan berhasil dihapus');
                
        } catch (\Exception $e) {
            return redirect()
                ->route('superadmin.satuan.index')
                ->with('error', 'Gagal menghapus satuan: ' . $e->getMessage());
        }
    }
}