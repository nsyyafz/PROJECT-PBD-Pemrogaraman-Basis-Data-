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
        $filter = $request->get('status', 'all'); // default 'all'
        
        // Query berdasarkan filter
        if ($filter == 'aktif') {
            // Ambil satuan aktif dari view_satuan_aktif
            $satuans = DB::select("
                SELECT * FROM view_satuan_aktif
                ORDER BY idsatuan ASC
            ");
        } elseif ($filter == 'nonaktif') {
            // Ambil satuan non-aktif dari view_satuan_nonaktif
            $satuans = DB::select("
                SELECT * FROM view_satuan_nonaktif
                ORDER BY idsatuan ASC
            ");
        } else {
            // Semua data dari view_satuan
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Ambil detail satuan dari view_satuan
        $satuan = DB::select("
            SELECT * FROM view_satuan
            WHERE idsatuan = ?
            LIMIT 1
        ", [$id]);
        
        if (empty($satuan)) {
            abort(404, 'Satuan tidak ditemukan');
        }
        
        $satuan = $satuan[0];
        
        return view('superadmin.satuan.show', compact('satuan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Ambil data satuan
        $satuan = DB::select("
            SELECT * FROM satuan WHERE idsatuan = ?
        ", [$id]);
        
        if (empty($satuan)) {
            abort(404, 'Satuan tidak ditemukan');
        }
        
        $satuan = $satuan[0];
        
        return view('superadmin.satuan.edit', compact('satuan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_satuan' => 'required|max:45|unique:satuan,nama_satuan,' . $id . ',idsatuan',
            'status' => 'required|in:0,1',
        ]);
        
        try {
            DB::update("
                UPDATE satuan 
                SET nama_satuan = ?, 
                    status = ?
                WHERE idsatuan = ?
            ", [
                $request->nama_satuan,
                $request->status,
                $id
            ]);
            
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
                // Jangan hapus, tapi non-aktifkan saja
                DB::update("
                    UPDATE satuan 
                    SET status = 0 
                    WHERE idsatuan = ?
                ", [$id]);
                
                return redirect()
                    ->route('superadmin.satuan.index')
                    ->with('success', 'Satuan tidak dapat dihapus karena masih digunakan. Status diubah menjadi non-aktif.');
            }
            
            // Kalau belum dipakai, baru boleh dihapus
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
    
    /**
     * Toggle status satuan (aktif/non-aktif)
     */
    public function toggleStatus(string $id)
    {
        try {
            // Ambil status saat ini
            $satuan = DB::select("SELECT status FROM satuan WHERE idsatuan = ?", [$id]);
            
            if (empty($satuan)) {
                return back()->with('error', 'Satuan tidak ditemukan');
            }
            
            // Toggle status (1 jadi 0, 0 jadi 1)
            $newStatus = $satuan[0]->status == 1 ? 0 : 1;
            $statusText = $newStatus == 1 ? 'aktif' : 'non-aktif';
            
            DB::update("
                UPDATE satuan 
                SET status = ? 
                WHERE idsatuan = ?
            ", [$newStatus, $id]);
            
            return back()->with('success', "Status satuan berhasil diubah menjadi {$statusText}");
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengubah status: ' . $e->getMessage());
        }
    }
}