<?php

namespace App\Http\Controllers\Superadmin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PengadaanController extends Controller
{
    /**
     * Display a listing of pengadaan
     */
    public function index()
    {
        $pengadaans = DB::select("
            SELECT p.*, v.nama_vendor
            FROM pengadaan p
            JOIN vendor v ON p.vendor_idvendor = v.idvendor
            ORDER BY p.timestamp DESC
        ");
        
        return view('superadmin.pengadaan.index', compact('pengadaans'));
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
     * API: Get Harga Barang (Call Function)
     * INI SATU-SATUNYA API YANG DIPERLUKAN!
     * Digunakan untuk preview harga saat user pilih barang
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
     * Store: SEMUA PERHITUNGAN DILAKUKAN DI STORED PROCEDURE!
     * SP akan otomatis:
     * 1. Ambil harga via fn_get_harga_barang()
     * 2. Hitung subtotal via fn_hitung_subtotal_item()
     * 3. Update subtotal_nilai di header (SUM dari detail)
     * 4. Hitung PPN via fn_hitung_ppn()
     * 5. Hitung total_nilai via fn_hitung_total_pengadaan()
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
            
            // 1. Insert header pengadaan (subtotal dan total masih 0)
            $idpengadaan = DB::table('pengadaan')->insertGetId([
                'timestamp' => now(),
                'user_iduser' => $iduser,
                'status' => 'P', // P = Pending
                'vendor_idvendor' => $request->vendor_idvendor,
                'subtotal_nilai' => 0,
                'ppn' => $request->ppn,
                'total_nilai' => 0
            ]);
            
            // 2. Insert detail pengadaan menggunakan STORED PROCEDURE
            // SP AKAN OTOMATIS MENGHITUNG SEMUANYA!
            foreach ($request->barang as $item) {
                DB::statement("CALL sp_insert_detail_pengadaan(?, ?, ?)", [
                    $idpengadaan,
                    $item['idbarang'],
                    $item['jumlah']
                ]);
            }
            
            DB::commit();
            
            return redirect()
                ->route('superadmin.pengadaan.index')
                ->with('success', 'Pengadaan berhasil dibuat dengan ID: ' . $idpengadaan);
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan pengadaan: ' . $e->getMessage());
        }
    }

    /**
     * Update Status Pengadaan
     * Status: P = Pending, A = Approved, R = Rejected
     */
    public function updateStatus(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:P,A,R',
        ]);
        
        DB::beginTransaction();
        
        try {
            // Cek pengadaan exists
            $pengadaan = DB::selectOne("
                SELECT idpengadaan, status 
                FROM pengadaan 
                WHERE idpengadaan = ?
            ", [$id]);
            
            if (!$pengadaan) {
                throw new \Exception('Pengadaan tidak ditemukan');
            }
            
            // Validasi: Tidak bisa ubah status jika sudah ada penerimaan
            $hasPenerimaan = DB::selectOne("
                SELECT COUNT(*) as total 
                FROM penerimaan 
                WHERE idpengadaan = ?
            ", [$id]);
            
            if ($hasPenerimaan->total > 0 && $request->status === 'R') {
                throw new \Exception('Tidak dapat reject pengadaan yang sudah ada penerimaan');
            }
            
            // Update status
            DB::update("
                UPDATE pengadaan 
                SET status = ?
                WHERE idpengadaan = ?
            ", [$request->status, $id]);
            
            DB::commit();
            
            $statusText = [
                'P' => 'Pending',
                'A' => 'Approved',
                'R' => 'Rejected'
            ];
            
            return back()->with('success', 'Status pengadaan berhasil diubah menjadi: ' . $statusText[$request->status]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengubah status: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Ambil header pengadaan
        $pengadaan = DB::select("
            SELECT 
                p.*,
                v.nama_vendor,
                u.username as created_by,
                DATE_FORMAT(p.timestamp, '%d-%m-%Y %H:%i') as tanggal_formatted
            FROM pengadaan p
            JOIN vendor v ON p.vendor_idvendor = v.idvendor
            JOIN user u ON p.user_iduser = u.iduser
            WHERE p.idpengadaan = ?
        ", [$id]);
        
        if (empty($pengadaan)) {
            abort(404, 'Pengadaan tidak ditemukan');
        }
        
        // Ambil detail pengadaan
        $details = DB::select("
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
        
        return view('superadmin.pengadaan.show', [
            'pengadaan' => $pengadaan[0],
            'details' => $details
        ]);
    }
    /**
     * Show the form for editing the specified pengadaan
     * HANYA untuk status Pending (P)
     */
    public function edit(string $id)
    {
        // Cek pengadaan exists dan statusnya
        $pengadaan = DB::selectOne("
            SELECT p.*, v.nama_vendor
            FROM pengadaan p
            JOIN vendor v ON p.vendor_idvendor = v.idvendor
            WHERE p.idpengadaan = ?
        ", [$id]);
        
        if (!$pengadaan) {
            abort(404, 'Pengadaan tidak ditemukan');
        }
        
        // VALIDASI: Hanya status Pending yang bisa di-edit
        if ($pengadaan->status !== 'P') {
            return redirect()
                ->route('superadmin.pengadaan.index')
                ->with('error', 'Hanya pengadaan dengan status Pending yang dapat diubah!');
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
            // 1. Cek status pengadaan (hanya Pending yang bisa di-update)
            $pengadaan = DB::selectOne("
                SELECT idpengadaan, status 
                FROM pengadaan 
                WHERE idpengadaan = ?
            ", [$id]);
            
            if (!$pengadaan) {
                throw new \Exception('Pengadaan tidak ditemukan');
            }
            
            if ($pengadaan->status !== 'P') {
                throw new \Exception('Hanya pengadaan dengan status Pending yang dapat diubah!');
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
     * Delete pengadaan (HANYA untuk status Pending)
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
            
            if ($pengadaan->status !== 'P') {
                throw new \Exception('Hanya pengadaan dengan status Pending yang dapat dihapus!');
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

