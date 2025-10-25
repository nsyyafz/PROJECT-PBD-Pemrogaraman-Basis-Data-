<?php

namespace App\Http\Controllers\Superadmin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PenerimaanController extends Controller
{
    /**
     * Display a listing (RINGKAS - hanya kolom penting)
     */
    public function index(Request $request)
    {
        $filter = $request->get('status', 'all');
        
        // Query dari view_list_penerimaan (kolom ringkas)
        if ($filter == 'all') {
            $penerimaans = DB::select("
                SELECT * FROM view_list_penerimaan
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
            
            $penerimaans = DB::select("
                SELECT * FROM view_list_penerimaan
                WHERE status = ?
                ORDER BY tanggal DESC
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
    public function create()
    {
        // Ambil pengadaan yang sudah approved tapi belum ada penerimaan
        $pengadaans = DB::select("
            SELECT p.idpengadaan, p.timestamp, v.nama_vendor, p.total_nilai
            FROM pengadaan p
            JOIN vendor v ON p.vendor_idvendor = v.idvendor
            WHERE p.status = 'A'
            AND p.idpengadaan NOT IN (SELECT idpengadaan FROM penerimaan)
            ORDER BY p.timestamp DESC
        ");
        
        return view('superadmin.penerimaan.create', compact('pengadaans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'idpengadaan' => 'required|exists:pengadaan,idpengadaan',
            'barang' => 'required|array|min:1',
            'barang.*.idbarang' => 'required|exists:barang,idbarang',
            'barang.*.jumlah_terima' => 'required|numeric|min:0',
            'barang.*.harga_satuan_terima' => 'required|numeric|min:0',
        ]);
        
        DB::beginTransaction();
        
        try {
            $iduser = auth()->user()->iduser;
            
            // Insert penerimaan
            DB::insert("
                INSERT INTO penerimaan (created_at, status, iduser, idpengadaan)
                VALUES (NOW(), 'P', ?, ?)
            ", [$iduser, $request->idpengadaan]);
            
            $idpenerimaan = DB::getPdo()->lastInsertId();
            
            // Insert detail barang yang diterima
            foreach ($request->barang as $item) {
                $sub_total = $item['jumlah_terima'] * $item['harga_satuan_terima'];
                
                DB::insert("
                    INSERT INTO detail_penerimaan (idpenerimaan, idbarang, jumlah_terima, harga_satuan_terima, sub_total_terima)
                    VALUES (?, ?, ?, ?, ?)
                ", [
                    $idpenerimaan,
                    $item['idbarang'],
                    $item['jumlah_terima'],
                    $item['harga_satuan_terima'],
                    $sub_total
                ]);
                
                // Update kartu stok (masuk)
                $stokSebelum = DB::select("
                    SELECT stock FROM kartu_stok 
                    WHERE idbarang = ? 
                    ORDER BY created_at DESC, idkartu_stok DESC 
                    LIMIT 1
                ", [$item['idbarang']]);
                
                $stokAkhir = ($stokSebelum[0]->stock ?? 0) + $item['jumlah_terima'];
                
                DB::insert("
                    INSERT INTO kartu_stok (jenis_transaksi, masuk, keluar, stock, created_at, idtransaksi, idbarang)
                    VALUES ('P', ?, 0, ?, NOW(), ?, ?)
                ", [
                    $item['jumlah_terima'],
                    $stokAkhir,
                    $idpenerimaan,
                    $item['idbarang']
                ]);
            }
            
            DB::commit();
            
            return redirect()
                ->route('superadmin.penerimaan.show', $idpenerimaan)
                ->with('success', 'Penerimaan berhasil dibuat');
                
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
            
            // Hapus kartu stok terkait
            DB::delete("DELETE FROM kartu_stok WHERE jenis_transaksi = 'P' AND idtransaksi = ?", [$id]);
            
            // Hapus detail penerimaan
            DB::delete("DELETE FROM detail_penerimaan WHERE idpenerimaan = ?", [$id]);
            
            // Hapus penerimaan
            DB::delete("DELETE FROM penerimaan WHERE idpenerimaan = ?", [$id]);
            
            return redirect()
                ->route('superadmin.penerimaan.index')
                ->with('success', 'Penerimaan berhasil dihapus');
                
        } catch (\Exception $e) {
            return redirect()
                ->route('superadmin.penerimaan.index')
                ->with('error', 'Gagal menghapus penerimaan: ' . $e->getMessage());
        }
    }
}