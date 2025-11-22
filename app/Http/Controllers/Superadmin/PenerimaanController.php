<?php

namespace App\Http\Controllers\Superadmin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PenerimaanController extends Controller
{
    /**
     * Display a listing menggunakan view_penerimaan_all
     */
    public function index(Request $request)
    {
        // Query data dari VIEW
        $penerimaans = DB::select("SELECT * FROM view_penerimaan_all ORDER BY created_at DESC");
        
        // Hitung statistik (opsional)
        $stats = [
            'total' => count($penerimaans),
        ];
        
        return view('superadmin.penerimaan.index', compact('penerimaans', 'stats'));
    }

    /**
     * Display the specified resource (DETAIL LENGKAP)
     * Menggunakan view_detail_penerimaan_all
     */
    public function show(string $id)
    {
        // Ambil header dari view_penerimaan_all
        $penerimaan = DB::selectOne("
            SELECT * FROM view_penerimaan_all
            WHERE idpenerimaan = ?
        ", [$id]);
        
        if (!$penerimaan) {
            abort(404, 'Penerimaan tidak ditemukan');
        }
        
        // Ambil detail dari view_detail_penerimaan_all
        $details = DB::select("
            SELECT * FROM view_detail_penerimaan_all
            WHERE idpenerimaan = ?
            ORDER BY iddetail_penerimaan ASC
        ", [$id]);
        
        return view('superadmin.penerimaan.show', compact('penerimaan', 'details'));
    }

    /**
     * Show the form for creating a new resource.
     * Menggunakan view_pengadaan_belum_selesai
     */
    public function create(Request $request)
    {
        // Ambil pengadaan yang belum selesai (status D atau P)
        $pengadaans = DB::select("
            SELECT * FROM view_pengadaan_belum_selesai
            ORDER BY timestamp DESC
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
     * AJAX: Get Preview Detail Pengadaan untuk dipilih
     * Menggunakan view_detail_pengadaan_lengkap
     */
    public function getDetailPengadaan(Request $request)
    {
        $idpengadaan = $request->idpengadaan;
        
        try {
            // Ambil detail dari VIEW (sudah include sisa belum terima)
            $details = DB::select("
                SELECT 
                    idbarang,
                    nama_barang,
                    nama_satuan,
                    jenis,
                    harga_satuan,
                    jumlah_pesan,
                    jumlah_diterima,
                    sisa_belum_terima,
                    persentase_diterima,
                    status_item
                FROM view_detail_pengadaan_lengkap
                WHERE idpengadaan = ?
                AND sisa_belum_terima > 0
                ORDER BY iddetail_pengadaan
            ", [$idpengadaan]);
            
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
     * MENGGUNAKAN SP sp_insert_detail_penerimaan
     * TRIGGER otomatis: update stok + update status pengadaan
     */
   public function store(Request $request)
    {
        $request->validate([
            'idpengadaan' => 'required|exists:pengadaan,idpengadaan',
            'barang' => 'required|array|min:1',
            'barang.*.idbarang' => 'required|exists:barang,idbarang',
            'barang.*.jumlah_terima' => 'required|numeric|min:0', // ✅ UBAH: min:0 (boleh 0)
        ], [
            'idpengadaan.required' => 'Pengadaan harus dipilih',
            'idpengadaan.exists' => 'Pengadaan tidak valid',
            'barang.required' => 'Minimal 1 barang harus ditambahkan',
            'barang.*.idbarang.required' => 'ID Barang tidak valid',
            'barang.*.idbarang.exists' => 'Barang tidak ditemukan',
            'barang.*.jumlah_terima.required' => 'Jumlah terima harus diisi',
            'barang.*.jumlah_terima.min' => 'Jumlah terima minimal 0', // ✅ UBAH
        ]);
        
        DB::beginTransaction();
        
        try {
            // Ambil user dari session
            $iduser = $request->session()->get('user_id') ?? 1;
            
            // 1. Generate ID penerimaan manual: MAX(idpenerimaan) + 1
            $maxId = DB::selectOne("SELECT COALESCE(MAX(idpenerimaan), 0) as max_id FROM penerimaan");
            $idpenerimaan = $maxId->max_id + 1;
            
            // 2. Insert header penerimaan dengan ID manual
            DB::insert("
                INSERT INTO penerimaan (idpenerimaan, created_at, status, iduser, idpengadaan)
                VALUES (?, NOW(), 'S', ?, ?)
            ", [$idpenerimaan, $iduser, $request->idpengadaan]);
            
            // 3. Insert detail menggunakan STORED PROCEDURE
            // ✅ SKIP barang yang jumlah_terima = 0
            $itemDiterima = 0;
            foreach ($request->barang as $item) {
                // Skip jika jumlah 0
                if ($item['jumlah_terima'] <= 0) {
                    continue;
                }
                
                DB::statement("CALL sp_insert_detail_penerimaan(?, ?, ?)", [
                    $idpenerimaan,
                    $item['idbarang'],
                    $item['jumlah_terima']
                ]);
                
                $itemDiterima++;
            }
            
            // ✅ VALIDASI: Minimal 1 barang yang jumlahnya > 0
            if ($itemDiterima === 0) {
                throw new \Exception('Minimal 1 barang harus memiliki jumlah terima > 0');
            }
            
            DB::commit();
            
            return redirect()
                ->route('superadmin.penerimaan.show', $idpenerimaan)
                ->with('success', 'Penerimaan berhasil dibuat! Stok dan status pengadaan otomatis terupdate.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Gagal membuat penerimaan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     * Harus hapus kartu_stok dulu sebelum hapus penerimaan
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        
        try {
            // 1. Cek apakah penerimaan ada
            $penerimaan = DB::selectOne("
                SELECT idpenerimaan, idpengadaan 
                FROM penerimaan 
                WHERE idpenerimaan = ?
            ", [$id]);
            
            if (!$penerimaan) {
                throw new \Exception('Penerimaan tidak ditemukan');
            }
            
            // 2. Cek apakah sudah ada retur
            $hasRetur = DB::selectOne("
                SELECT COUNT(*) as total 
                FROM retur 
                WHERE idpenerimaan = ?
            ", [$id]);
            
            if ($hasRetur->total > 0) {
                throw new \Exception('Penerimaan tidak dapat dihapus karena sudah ada retur!');
            }
            
            // 3. PENTING: Hapus kartu_stok dulu (foreign key)
            // Jenis transaksi 'T' = Terima/Penerimaan
            DB::delete("
                DELETE FROM kartu_stok 
                WHERE jenis_transaksi = 'T' 
                AND idtransaksi = ?
            ", [$id]);
            
            // 4. Hapus detail penerimaan
            DB::delete("DELETE FROM detail_penerimaan WHERE idpenerimaan = ?", [$id]);
            
            // 5. Hapus header penerimaan
            DB::delete("DELETE FROM penerimaan WHERE idpenerimaan = ?", [$id]);
            
            // 6. Update status pengadaan kembali
            // Cek apakah masih ada penerimaan lain untuk pengadaan ini
            $sisaPenerimaan = DB::selectOne("
                SELECT COUNT(*) as total 
                FROM penerimaan 
                WHERE idpengadaan = ?
            ", [$penerimaan->idpengadaan]);
            
            if ($sisaPenerimaan->total == 0) {
                // Tidak ada penerimaan lagi → status kembali ke Diproses
                DB::update("
                    UPDATE pengadaan 
                    SET status = 'D' 
                    WHERE idpengadaan = ?
                ", [$penerimaan->idpengadaan]);
            } else {
                // Masih ada penerimaan lain → recalculate status
                // Cek apakah masih ada yang belum lengkap
                $adaSisa = DB::selectOne("
                    SELECT COUNT(*) as total
                    FROM detail_pengadaan dp
                    WHERE dp.idpengadaan = ?
                    AND dp.jumlah > COALESCE(
                        (SELECT SUM(dpen.jumlah_terima)
                         FROM penerimaan pen
                         JOIN detail_penerimaan dpen ON pen.idpenerimaan = dpen.idpenerimaan
                         WHERE pen.idpengadaan = ?
                         AND dpen.idbarang = dp.idbarang), 0
                    )
                ", [$penerimaan->idpengadaan, $penerimaan->idpengadaan]);
                
                $newStatus = $adaSisa->total > 0 ? 'P' : 'S';
                DB::update("
                    UPDATE pengadaan 
                    SET status = ? 
                    WHERE idpengadaan = ?
                ", [$newStatus, $penerimaan->idpengadaan]);
            }
            
            DB::commit();
            
            return redirect()
                ->route('superadmin.penerimaan.index')
                ->with('success', 'Penerimaan berhasil dihapus dan status pengadaan diupdate!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('error', 'Gagal menghapus penerimaan: ' . $e->getMessage());
        }
    }
}