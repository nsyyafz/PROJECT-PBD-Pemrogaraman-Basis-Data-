<?php

namespace App\Http\Controllers\Superadmin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PenerimaanController extends Controller
{
    /**
     * Display a listing (tanpa view, langsung dari tabel)
     */
    public function index(Request $request)
    {
        $filter = $request->get('status', 'all');
        
        // Query langsung dari tabel dengan JOIN
        if ($filter == 'all') {
            $penerimaans = DB::select("
                SELECT 
                    pen.idpenerimaan,
                    pen.created_at AS tanggal,
                    pen.status,
                    pen.idpengadaan,
                    u.username,
                    v.nama_vendor
                FROM penerimaan pen
                JOIN user u ON pen.iduser = u.iduser
                JOIN pengadaan p ON pen.idpengadaan = p.idpengadaan
                JOIN vendor v ON p.vendor_idvendor = v.idvendor
                ORDER BY pen.created_at DESC
            ");
        } else {
            // Filter berdasarkan status (P, A, C)
            $statusCode = match($filter) {
                'pending' => 'P',
                'approved' => 'A',
                'completed' => 'C',
                default => 'P'
            };
            
            $penerimaans = DB::select("
                SELECT 
                    pen.idpenerimaan,
                    pen.created_at AS tanggal,
                    pen.status,
                    pen.idpengadaan,
                    u.username,
                    v.nama_vendor
                FROM penerimaan pen
                JOIN user u ON pen.iduser = u.iduser
                JOIN pengadaan p ON pen.idpengadaan = p.idpengadaan
                JOIN vendor v ON p.vendor_idvendor = v.idvendor
                WHERE pen.status = ?
                ORDER BY pen.created_at DESC
            ", [$statusCode]);
        }
        
        return view('superadmin.penerimaan.index', compact('penerimaans', 'filter'));
    }

    /**
     * Display the specified resource (DETAIL LENGKAP)
     */
    public function show(string $id)
    {
        // Ambil data dari view_detail_penerimaan
        $details = DB::select("
            SELECT * FROM view_detail_penerimaan
            WHERE idpenerimaan = ?
            ORDER BY iddetail_penerimaan ASC
        ", [$id]);
        
        if (empty($details)) {
            abort(404, 'Penerimaan tidak ditemukan');
        }
        
        // Data header penerimaan
        $penerimaan = $details[0];
        
        // Data detail barang
        $detail_barang = $details;
        
        return view('superadmin.penerimaan.show', compact('penerimaan', 'detail_barang'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Ambil pengadaan yang sudah approved tapi belum selesai diterima
        $pengadaans = DB::select("
            SELECT p.idpengadaan, p.timestamp, v.nama_vendor, p.total_nilai
            FROM pengadaan p
            JOIN vendor v ON p.vendor_idvendor = v.idvendor
            WHERE p.status = 'A'
            ORDER BY p.timestamp DESC
        ");
        
        // Ambil user dari session
        $userId = $request->session()->get('user_id');
        $currentUser = DB::selectOne(
            "SELECT iduser, username FROM user WHERE iduser = ?", 
            [$userId]
        );
        
        return view('superadmin.penerimaan.create', compact('pengadaans', 'currentUser'));
}

    /**
     * AJAX: Get Preview Detail Pengadaan
     * Dipanggil saat user pilih pengadaan di form
     */
    public function getDetailPengadaan(Request $request)
    {
        $idpengadaan = $request->idpengadaan;
        
        try {
            // CALL SP PREVIEW (hanya SELECT, tidak insert!)
            $details = DB::select("CALL sp_preview_detail_pengadaan(?)", [$idpengadaan]);
            
            return response()->json([
                'success' => true,
                'data' => $details
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail pengadaan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * MENGGUNAKAN SP DAN TRIGGER!
     */
    public function store(Request $request)
    {
        $request->validate([
            'idpengadaan' => 'required|exists:pengadaan,idpengadaan',
            'barang' => 'required|array|min:1',
            'barang.*.idbarang' => 'required|exists:barang,idbarang',
            'barang.*.jumlah_terima' => 'required|numeric|min:1',
        ]);
        
        DB::beginTransaction();
        
        try {
            // Ambil user dari session (bukan auth()->user())
            $iduser = $request->session()->get('user_id');
            
            // 1. Insert header penerimaan (manual)
            DB::insert("
                INSERT INTO penerimaan (created_at, status, iduser, idpengadaan)
                VALUES (NOW(), 'P', ?, ?)
            ", [$iduser, $request->idpengadaan]);
            
            $idpenerimaan = DB::getPdo()->lastInsertId();
            
            // 2. Insert detail menggunakan STORED PROCEDURE
            // TRIGGER otomatis update stok!
            foreach ($request->barang as $item) {
                // CALL SP INSERT DETAIL PENERIMAAN
                DB::statement("CALL sp_insert_detail_penerimaan(?, ?, ?)", [
                    $idpenerimaan,
                    $item['idbarang'],
                    $item['jumlah_terima']
                ]);
                
                // NOTE: Tidak perlu update stok manual!
                // Trigger sudah otomatis insert ke kartu_stok!
            }
            
            DB::commit();
            
            return redirect()
                ->route('superadmin.penerimaan.show', $idpenerimaan)
                ->with('success', 'Penerimaan berhasil dibuat dan stok otomatis terupdate!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Gagal membuat penerimaan: ' . $e->getMessage());
        }
    }

    /**
     * Update status penerimaan
     */
    public function updateStatus(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:P,A,C'
        ]);
        
        try {
            DB::update("
                UPDATE penerimaan 
                SET status = ?
                WHERE idpenerimaan = ?
            ", [$request->status, $id]);
            
            $statusText = match($request->status) {
                'P' => 'Pending',
                'A' => 'Approved',
                'C' => 'Completed',
            };
            
            return back()->with('success', "Status penerimaan berhasil diubah menjadi {$statusText}");
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengubah status: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        
        try {
            // Cek apakah sudah ada retur
            $hasRetur = DB::select("
                SELECT COUNT(*) as total 
                FROM retur 
                WHERE idpenerimaan = ?
            ", [$id]);
            
            if ($hasRetur[0]->total > 0) {
                return redirect()
                    ->route('superadmin.penerimaan.index')
                    ->with('error', 'Penerimaan tidak dapat dihapus karena sudah ada retur.');
            }
            
            // PENTING: Hapus kartu_stok dulu (karena foreign key)
            // Jenis transaksi 'T' = Terima/Penerimaan
            DB::delete("
                DELETE FROM kartu_stok 
                WHERE jenis_transaksi = 'T' 
                AND idtransaksi = ?
            ", [$id]);
            
            // Hapus detail penerimaan
            DB::delete("DELETE FROM detail_penerimaan WHERE idpenerimaan = ?", [$id]);
            
            // Hapus penerimaan
            DB::delete("DELETE FROM penerimaan WHERE idpenerimaan = ?", [$id]);
            
            DB::commit();
            
            return redirect()
                ->route('superadmin.penerimaan.index')
                ->with('success', 'Penerimaan berhasil dihapus');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->route('superadmin.penerimaan.index')
                ->with('error', 'Gagal menghapus penerimaan: ' . $e->getMessage());
        }
    }
}