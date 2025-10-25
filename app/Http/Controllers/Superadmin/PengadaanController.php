<?php

namespace App\Http\Controllers\Superadmin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PengadaanController extends Controller
{
    /**
     * Display a listing (RINGKAS - hanya kolom penting)
     */
    public function index(Request $request)
    {
        $filter = $request->get('status', 'all');
        
        // Query dari view_list_pengadaan (kolom ringkas)
        if ($filter == 'all') {
            $pengadaans = DB::select("
                SELECT * FROM view_list_pengadaan
                ORDER BY tanggal DESC
            ");
        } else {
            // Filter berdasarkan status (P, A, C)
            $statusCode = match($filter) {
                'pending' => 'P',
                'approved' => 'A',
                'completed' => 'C',
                default => 'P'
            };
            
            $pengadaans = DB::select("
                SELECT * FROM view_list_pengadaan
                WHERE status = ?
                ORDER BY tanggal DESC
            ", [$statusCode]);
        }
        
        return view('superadmin.pengadaan.index', compact('pengadaans', 'filter'));
    }

    /**
     * Display the specified resource (DETAIL LENGKAP)
     */
    public function show(string $id)
    {
        // Ambil data dari view_detail_pengadaan (lengkap dengan detail barang)
        $details = DB::select("
            SELECT * FROM view_detail_pengadaan
            WHERE idpengadaan = ?
            ORDER BY iddetail_pengadaan ASC
        ", [$id]);
        
        if (empty($details)) {
            abort(404, 'Pengadaan tidak ditemukan');
        }
        
        // Data header pengadaan (ambil dari row pertama)
        $pengadaan = $details[0];
        
        // Data detail barang (semua rows)
        $detail_barang = $details;
        
        return view('superadmin.pengadaan.show', compact('pengadaan', 'detail_barang'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil vendor aktif
        $vendors = DB::select("
            SELECT idvendor, nama_vendor, jenis_badan_hukum
            FROM view_vendor_aktif
            ORDER BY nama_vendor ASC
        ");
        
        // Ambil barang aktif
        $barangs = DB::select("
            SELECT idbarang, nama_barang, jenis_barang, harga, nama_satuan
            FROM view_barang_aktif
            ORDER BY nama_barang ASC
        ");
        
        return view('superadmin.pengadaan.create', compact('vendors', 'barangs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'vendor_idvendor' => 'required|exists:vendor,idvendor',
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
            $user_iduser = auth()->user()->iduser;
            
            // Insert pengadaan
            DB::insert("
                INSERT INTO pengadaan (timestamp, status, vendor_idvendor, subtotal_nilai, ppn, total_nilai, user_iduser)
                VALUES (NOW(), 'P', ?, ?, ?, ?, ?)
            ", [
                $request->vendor_idvendor,
                $request->subtotal_nilai,
                $request->ppn,
                $request->total_nilai,
                $user_iduser
            ]);
            
            // Ambil ID pengadaan yang baru dibuat
            $idpengadaan = DB::getPdo()->lastInsertId();
            
            // Insert detail barang
            foreach ($request->barang as $item) {
                $sub_total = $item['jumlah'] * $item['harga_satuan'];
                
                DB::insert("
                    INSERT INTO detail_pengadaan (idpengadaan, idbarang, harga_satuan, jumlah, sub_total)
                    VALUES (?, ?, ?, ?, ?)
                ", [
                    $idpengadaan,
                    $item['idbarang'],
                    $item['harga_satuan'],
                    $item['jumlah'],
                    $sub_total
                ]);
            }
            
            DB::commit();
            
            return redirect()
                ->route('superadmin.pengadaan.show', $idpengadaan)
                ->with('success', 'Pengadaan berhasil dibuat');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Gagal membuat pengadaan: ' . $e->getMessage());
        }
    }

    /**
     * Update status pengadaan
     */
    public function updateStatus(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:P,A,C'
        ]);
        
        try {
            DB::update("
                UPDATE pengadaan 
                SET status = ?
                WHERE idpengadaan = ?
            ", [$request->status, $id]);
            
            $statusText = match($request->status) {
                'P' => 'Pending',
                'A' => 'Approved',
                'C' => 'Completed',
            };
            
            return back()->with('success', "Status pengadaan berhasil diubah menjadi {$statusText}");
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengubah status: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Cek apakah sudah ada penerimaan
            $hasPenerimaan = DB::select("
                SELECT COUNT(*) as total 
                FROM penerimaan 
                WHERE idpengadaan = ?
            ", [$id]);
            
            if ($hasPenerimaan[0]->total > 0) {
                return redirect()
                    ->route('superadmin.pengadaan.index')
                    ->with('error', 'Pengadaan tidak dapat dihapus karena sudah ada penerimaan.');
            }
            
            // Hapus detail pengadaan dulu
            DB::delete("DELETE FROM detail_pengadaan WHERE idpengadaan = ?", [$id]);
            
            // Baru hapus pengadaan
            DB::delete("DELETE FROM pengadaan WHERE idpengadaan = ?", [$id]);
            
            return redirect()
                ->route('superadmin.pengadaan.index')
                ->with('success', 'Pengadaan berhasil dihapus');
                
        } catch (\Exception $e) {
            return redirect()
                ->route('superadmin.pengadaan.index')
                ->with('error', 'Gagal menghapus pengadaan: ' . $e->getMessage());
        }
    }
}