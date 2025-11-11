<?php

namespace App\Http\Controllers\Superadmin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ReturController extends Controller
{
    /**
     * Display a listing (RINGKAS)
     */
    public function index()
    {
        $returs = DB::select("
            SELECT * FROM view_list_retur
            ORDER BY tanggal DESC
        ");
        
        return view('superadmin.retur.index', compact('returs'));
    }

    /**
     * Display the specified resource (DETAIL LENGKAP)
     */
    public function show(string $id)
    {
        // Ambil data dari view_detail_retur
        $details = DB::select("
            SELECT * FROM view_detail_retur
            WHERE idretur = ?
            ORDER BY iddetail_retur ASC
        ", [$id]);
        
        if (empty($details)) {
            abort(404, 'Retur tidak ditemukan');
        }
        
        // Data header retur
        $retur = $details[0];
        
        // Data detail barang
        $detail_barang = $details;
        
        return view('superadmin.retur.show', compact('retur', 'detail_barang'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil penerimaan yang sudah completed
        $penerimaans = DB::select("
            SELECT 
                pen.idpenerimaan,
                pen.created_at,
                p.idpengadaan,
                v.nama_vendor
            FROM penerimaan pen
            JOIN pengadaan p ON pen.idpengadaan = p.idpengadaan
            JOIN vendor v ON p.vendor_idvendor = v.idvendor
            WHERE pen.status = 'C'
            ORDER BY pen.created_at DESC
        ");
        
        return view('superadmin.retur.create', compact('penerimaans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'idpenerimaan' => 'required|exists:penerimaan,idpenerimaan',
            'barang' => 'required|array|min:1',
            'barang.*.iddetail_penerimaan' => 'required|exists:detail_penerimaan,iddetail_penerimaan',
            'barang.*.jumlah' => 'required|numeric|min:1',
            'barang.*.alasan' => 'required|string|max:200',
        ]);
        
        DB::beginTransaction();
        
        try {
            $iduser = auth()->user()->iduser;
            
            // Insert retur
            DB::insert("
                INSERT INTO retur (created_at, iduser, idpenerimaan)
                VALUES (NOW(), ?, ?)
            ", [$iduser, $request->idpenerimaan]);
            
            $idretur = DB::getPdo()->lastInsertId();
            
            // Insert detail retur & update kartu stok
            foreach ($request->barang as $item) {
                // Insert detail retur
                DB::insert("
                    INSERT INTO detail_retur (jumlah, alasan, idretur, iddetail_penerimaan)
                    VALUES (?, ?, ?, ?)
                ", [
                    $item['jumlah'],
                    $item['alasan'],
                    $idretur,
                    $item['iddetail_penerimaan']
                ]);
                
                // Ambil idbarang dari detail_penerimaan
                $detailPenerimaan = DB::select("
                    SELECT idbarang FROM detail_penerimaan 
                    WHERE iddetail_penerimaan = ?
                ", [$item['iddetail_penerimaan']]);
                
                $idbarang = $detailPenerimaan[0]->idbarang;
                
                // Update kartu stok (keluar karena retur)
                $stokSebelum = DB::select("
                    SELECT stock FROM kartu_stok 
                    WHERE idbarang = ? 
                    ORDER BY created_at DESC, idkartu_stok DESC 
                    LIMIT 1
                ", [$idbarang]);
                
                $stokAkhir = ($stokSebelum[0]->stock ?? 0) - $item['jumlah'];
                
                DB::insert("
                    INSERT INTO kartu_stok (jenis_transaksi, masuk, keluar, stock, created_at, idtransaksi, idbarang)
                    VALUES ('R', 0, ?, ?, NOW(), ?, ?)
                ", [
                    $item['jumlah'],
                    $stokAkhir,
                    $idretur,
                    $idbarang
                ]);
            }
            
            DB::commit();
            
            return redirect()
                ->route('superadmin.retur.show', $idretur)
                ->with('success', 'Retur berhasil dibuat');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Gagal membuat retur: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Hapus kartu stok terkait
            DB::delete("DELETE FROM kartu_stok WHERE jenis_transaksi = 'R' AND idtransaksi = ?", [$id]);
            
            // Hapus detail retur
            DB::delete("DELETE FROM detail_retur WHERE idretur = ?", [$id]);
            
            // Hapus retur
            DB::delete("DELETE FROM retur WHERE idretur = ?", [$id]);
            
            return redirect()
                ->route('superadmin.retur.index')
                ->with('success', 'Retur berhasil dihapus');
                
        } catch (\Exception $e) {
            return redirect()
                ->route('superadmin.retur.index')
                ->with('error', 'Gagal menghapus retur: ' . $e->getMessage());
        }
    }
}