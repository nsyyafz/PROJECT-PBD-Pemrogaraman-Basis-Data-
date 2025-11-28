<?php

namespace App\Http\Controllers\Superadmin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PengadaanController extends Controller
{
    /**
     * Display a listing of pengadaan
     * Dengan filter status
     */
    public function index(Request $request)
    {
        // Ambil filter status dari query parameter
        $filterStatus = $request->get('status', 'all'); // default: all
        
        // Tentukan VIEW mana yang akan digunakan
        $viewName = match($filterStatus) {
            'diproses' => 'view_pengadaan_diproses',
            'sebagian' => 'view_pengadaan_sebagian',
            'selesai' => 'view_pengadaan_selesai',
            default => 'view_pengadaan_all' // all
        };
        
        // Query data dari VIEW
        $pengadaans = DB::select("SELECT * FROM {$viewName}");
        
        // Hitung statistik untuk badge
        $stats = [
            'all' => DB::selectOne("SELECT COUNT(*) as total FROM view_pengadaan_all")->total,
            'diproses' => DB::selectOne("SELECT COUNT(*) as total FROM view_pengadaan_diproses")->total,
            'sebagian' => DB::selectOne("SELECT COUNT(*) as total FROM view_pengadaan_sebagian")->total,
            'selesai' => DB::selectOne("SELECT COUNT(*) as total FROM view_pengadaan_selesai")->total,
        ];
        
        return view('superadmin.pengadaan.index', compact('pengadaans', 'filterStatus', 'stats'));
    }

    /**
     * Show the form for creating a new pengadaan
     */
    public function create(Request $request)
    {
        // Ambil vendors dari view
        $vendors = DB::select("SELECT * FROM view_vendor_aktif ORDER BY nama_vendor");
        
        // Ambil barangs dari view
        $barangs = DB::select("SELECT * FROM view_barang_aktif ORDER BY nama_barang");
        
        // Ambil user dari session
        $userId = $request->session()->get('user_id');
        
        $currentUser = DB::selectOne(
            "SELECT iduser, username FROM user WHERE iduser = ?", 
            [$userId]
        );
        
        return view('superadmin.pengadaan.create', compact('vendors', 'barangs', 'currentUser'));
    }

    /**
     * API: Get Harga Barang
     * Memanggil FUNCTION fn_get_harga_barang()
     */
    public function getHargaBarang(Request $request)
    {
        try {
            $idbarang = $request->idbarang;
            
            // Panggil FUNCTION fn_get_harga_barang
            $result = DB::select("SELECT fn_get_harga_barang(?) as harga", [$idbarang]);
            
            if (empty($result)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Barang tidak ditemukan'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'harga' => $result[0]->harga ?? 0
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store: Menggunakan Stored Procedure sp_insert_detail_pengadaan
     * ID Manual: Ambil MAX(idpengadaan) + 1
     */
    public function store(Request $request)
    {
        $request->validate([
            'vendor_idvendor' => 'required|exists:vendor,idvendor',
            'ppn' => 'required|numeric|min:0',
            'barang' => 'required|array|min:1',
            'barang.*.idbarang' => 'required|exists:barang,idbarang',
            'barang.*.jumlah' => 'required|integer|min:1',
        ], [
            'vendor_idvendor.required' => 'Vendor harus dipilih',
            'vendor_idvendor.exists' => 'Vendor tidak valid',
            'ppn.required' => 'PPN harus diisi',
            'ppn.numeric' => 'PPN harus berupa angka',
            'barang.required' => 'Minimal 1 barang harus ditambahkan',
            'barang.*.idbarang.required' => 'ID Barang tidak valid',
            'barang.*.idbarang.exists' => 'Barang tidak ditemukan',
            'barang.*.jumlah.required' => 'Jumlah harus diisi',
            'barang.*.jumlah.min' => 'Jumlah minimal 1',
        ]);
        
        DB::beginTransaction();
        
        try {
            // Ambil ID user dari session
            $iduser = session('user_id') ?? 1;
            
            // 1. Generate ID pengadaan manual: MAX(idpengadaan) + 1
            $maxId = DB::selectOne("SELECT COALESCE(MAX(idpengadaan), 0) as max_id FROM pengadaan");
            $idpengadaan = $maxId->max_id + 1;
            
            // 2. Insert header pengadaan dengan ID manual
            // Status default: D = Diproses (belum ada penerimaan)
            DB::insert("
                INSERT INTO pengadaan 
                (idpengadaan, timestamp, user_iduser, status, vendor_idvendor, subtotal_nilai, ppn, total_nilai)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ", [
                $idpengadaan,
                now(),
                $iduser,
                'D', // D = Diproses
                $request->vendor_idvendor,
                0,
                $request->ppn,
                0
            ]);
            
            // 3. Insert detail menggunakan STORED PROCEDURE
            // SP akan otomatis menghitung semua nilai
            foreach ($request->barang as $item) {
                DB::statement("CALL sp_insert_detail_pengadaan(?, ?, ?)", [
                    $idpengadaan,
                    $item['idbarang'],
                    $item['jumlah']
                ]);
            }
            
            DB::commit();
            
            return redirect()
                ->route('superadmin.pengadaan.show', $idpengadaan)
                ->with('success', 'Pengadaan berhasil dibuat dengan ID: ' . $idpengadaan);
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan pengadaan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource
     * Menggunakan view_detail_pengadaan_lengkap
     */
    public function show(string $id)
    {
        // Ambil header pengadaan dari VIEW
        $pengadaan = DB::selectOne("
            SELECT * FROM view_pengadaan_all
            WHERE idpengadaan = ?
        ", [$id]);
        
        if (!$pengadaan) {
            abort(404, 'Pengadaan tidak ditemukan');
        }
        
        // Ambil detail pengadaan dari VIEW LENGKAP
        // VIEW ini sudah include: nama barang, satuan, jumlah diterima, sisa, persentase, status per item
        $details = DB::select("
            SELECT * FROM view_detail_pengadaan_lengkap
            WHERE idpengadaan = ?
            ORDER BY iddetail_pengadaan
        ", [$id]);
        
        // Ambil riwayat penerimaan jika ada
        $penerimaans = DB::select("
            SELECT 
                pen.idpenerimaan,
                pen.created_at,
                u.username as dibuat_oleh,
                COUNT(dpen.iddetail_penerimaan) as jumlah_item,
                SUM(dpen.jumlah_terima) as total_qty_terima
            FROM penerimaan pen
            JOIN user u ON pen.iduser = u.iduser
            LEFT JOIN detail_penerimaan dpen ON pen.idpenerimaan = dpen.idpenerimaan
            WHERE pen.idpengadaan = ?
            GROUP BY pen.idpenerimaan, pen.created_at, u.username
            ORDER BY pen.created_at DESC
        ", [$id]);
        
        return view('superadmin.pengadaan.show', [
            'pengadaan' => $pengadaan,
            'details' => $details,
            'penerimaans' => $penerimaans
        ]);
    }

    /**
     * Show the form for editing the specified pengadaan
     * HANYA untuk status Diproses (D)
     */
    public function edit(string $id)
    {
        // Cek pengadaan exists dan statusnya
        $pengadaan = DB::selectOne("
            SELECT * FROM view_pengadaan_all
            WHERE idpengadaan = ?
        ", [$id]);
        
        if (!$pengadaan) {
            abort(404, 'Pengadaan tidak ditemukan');
        }
        
        // VALIDASI: Hanya status Diproses yang bisa di-edit
        if ($pengadaan->status !== 'D') {
            return redirect()
                ->route('superadmin.pengadaan.index')
                ->with('error', 'Hanya pengadaan dengan status "Diproses" yang dapat diubah!');
        }
        
        // Ambil detail pengadaan yang sudah ada
        $existingDetails = DB::select("
            SELECT 
                dp.*,
                b.nama as nama_barang,
                s.nama_satuan
            FROM detail_pengadaan dp
            JOIN barang b ON dp.idbarang = b.idbarang
            JOIN satuan s ON b.idsatuan = s.idsatuan
            WHERE dp.idpengadaan = ?
            ORDER BY dp.iddetail_pengadaan
        ", [$id]);
        
        // Ambil vendors dari view
        $vendors = DB::select("SELECT * FROM view_vendor_aktif ORDER BY nama_vendor");
        
        // Ambil barangs dari view
        $barangs = DB::select("SELECT * FROM view_barang_aktif ORDER BY nama_barang");
        
        // Ambil user dari session
        $userId = session('user_id');
        $currentUser = DB::selectOne(
            "SELECT iduser, username FROM user WHERE iduser = ?", 
            [$userId]
        );
        
        return view('superadmin.pengadaan.edit', compact(
            'pengadaan', 
            'existingDetails', 
            'vendors', 
            'barangs', 
            'currentUser'
        ));
    }

    /**
     * Update the specified pengadaan
     * Strategi: HAPUS semua detail lama → INSERT ulang dengan SP
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'vendor_idvendor' => 'required|exists:vendor,idvendor',
            'ppn' => 'required|numeric|min:0',
            'barang' => 'required|array|min:1',
            'barang.*.idbarang' => 'required|exists:barang,idbarang',
            'barang.*.jumlah' => 'required|integer|min:1',
        ], [
            'vendor_idvendor.required' => 'Vendor harus dipilih',
            'vendor_idvendor.exists' => 'Vendor tidak valid',
            'ppn.required' => 'PPN harus diisi',
            'ppn.numeric' => 'PPN harus berupa angka',
            'barang.required' => 'Minimal 1 barang harus ditambahkan',
            'barang.*.idbarang.required' => 'ID Barang tidak valid',
            'barang.*.idbarang.exists' => 'Barang tidak ditemukan',
            'barang.*.jumlah.required' => 'Jumlah harus diisi',
            'barang.*.jumlah.min' => 'Jumlah minimal 1',
        ]);
        
        DB::beginTransaction();
        
        try {
            // 1. Cek status pengadaan
            $pengadaan = DB::selectOne("
                SELECT idpengadaan, status 
                FROM pengadaan 
                WHERE idpengadaan = ?
            ", [$id]);
            
            if (!$pengadaan) {
                throw new \Exception('Pengadaan tidak ditemukan');
            }
            
            // VALIDASI: Hanya status Diproses yang bisa di-update
            if ($pengadaan->status !== 'D') {
                throw new \Exception('Hanya pengadaan dengan status "Diproses" yang dapat diubah!');
            }
            
            // 2. Update header pengadaan
            DB::update("
                UPDATE pengadaan 
                SET vendor_idvendor = ?,
                    ppn = ?,
                    subtotal_nilai = 0,
                    total_nilai = 0
                WHERE idpengadaan = ?
            ", [$request->vendor_idvendor, $request->ppn, $id]);
            
            // 3. HAPUS semua detail lama
            DB::delete("DELETE FROM detail_pengadaan WHERE idpengadaan = ?", [$id]);
            
            // 4. INSERT detail baru menggunakan SP
            // SP akan otomatis hitung ulang subtotal & total
            foreach ($request->barang as $item) {
                DB::statement("CALL sp_insert_detail_pengadaan(?, ?, ?)", [
                    $id,
                    $item['idbarang'],
                    $item['jumlah']
                ]);
            }
            
            DB::commit();
            
            return redirect()
                ->route('superadmin.pengadaan.index')
                ->with('success', 'Pengadaan berhasil diperbarui! ID: ' . $id);
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui pengadaan: ' . $e->getMessage());
        }
    }

    /**
     * Delete pengadaan (HANYA untuk status Diproses)
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        
        try {
            // Cek status
            $pengadaan = DB::selectOne("
                SELECT idpengadaan, status 
                FROM pengadaan 
                WHERE idpengadaan = ?
            ", [$id]);
            
            if (!$pengadaan) {
                throw new \Exception('Pengadaan tidak ditemukan');
            }
            
            // VALIDASI: Hanya status Diproses yang bisa dihapus
            if ($pengadaan->status !== 'D') {
                throw new \Exception('Hanya pengadaan dengan status "Diproses" yang dapat dihapus!');
            }
            
            // Cek apakah sudah ada penerimaan
            $hasPenerimaan = DB::selectOne("
                SELECT COUNT(*) as total 
                FROM penerimaan 
                WHERE idpengadaan = ?
            ", [$id]);
            
            if ($hasPenerimaan->total > 0) {
                throw new \Exception('Pengadaan tidak dapat dihapus karena sudah ada penerimaan!');
            }
            
            // Hapus detail dulu (foreign key constraint)
            DB::delete("DELETE FROM detail_pengadaan WHERE idpengadaan = ?", [$id]);
            
            // Hapus header
            DB::delete("DELETE FROM pengadaan WHERE idpengadaan = ?", [$id]);
            
            DB::commit();
            
            return redirect()
                ->route('superadmin.pengadaan.index')
                ->with('success', 'Pengadaan berhasil dihapus!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'Gagal menghapus pengadaan: ' . $e->getMessage());
        }
    }
}