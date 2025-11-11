<?php

namespace App\Http\Controllers\Superadmin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class KartuStokController extends Controller
{
    /**
     * Display a listing - Pilih barang dulu
     */
    public function index()
    {
        // Tampilkan daftar barang dengan stok terkini
        $barangs = DB::select("
            SELECT * FROM view_stok_barang
            ORDER BY nama_barang ASC
        ");

        return view('superadmin.kartuStok.index', compact('barangs'));
    }

    /**
     * Show kartu stok per barang
     */
    public function show(string $idbarang)
    {
        // Info barang
        $barang = DB::select("
            SELECT * FROM view_stok_barang
            WHERE idbarang = ?
            LIMIT 1
        ", [$idbarang]);
        
        if (empty($barang)) {
            abort(404, 'Barang tidak ditemukan');
        }
        
        $barang = $barang[0];
        
        // History kartu stok
        $kartuStok = DB::select("
            SELECT * FROM view_kartu_stok
            WHERE idbarang = ?
            ORDER BY tanggal DESC, idkartu_stok DESC
        ", [$idbarang]);
        
        return view('superadmin.kartuStok.show', compact('barang', 'kartuStok'));
    }

    /**
     * Filter berdasarkan tanggal
     */
    public function filter(Request $request, string $idbarang)
    {
        $request->validate([
            'tanggal_dari' => 'nullable|date',
            'tanggal_sampai' => 'nullable|date|after_or_equal:tanggal_dari',
        ]);
        
        // Info barang
        $barang = DB::select("
            SELECT * FROM view_stok_barang
            WHERE idbarang = ?
            LIMIT 1
        ", [$idbarang]);
        
        if (empty($barang)) {
            abort(404, 'Barang tidak ditemukan');
        }
        
        $barang = $barang[0];
        
        // Query kartu stok dengan filter tanggal
        if ($request->tanggal_dari && $request->tanggal_sampai) {
            $kartuStok = DB::select("
                SELECT * FROM view_kartu_stok
                WHERE idbarang = ?
                AND DATE(tanggal) BETWEEN ? AND ?
                ORDER BY tanggal DESC, idkartu_stok DESC
            ", [$idbarang, $request->tanggal_dari, $request->tanggal_sampai]);
        } elseif ($request->tanggal_dari) {
            $kartuStok = DB::select("
                SELECT * FROM view_kartu_stok
                WHERE idbarang = ?
                AND DATE(tanggal) >= ?
                ORDER BY tanggal DESC, idkartu_stok DESC
            ", [$idbarang, $request->tanggal_dari]);
        } else {
            // Tidak ada filter, tampilkan semua
            $kartuStok = DB::select("
                SELECT * FROM view_kartu_stok
                WHERE idbarang = ?
                ORDER BY tanggal DESC, idkartu_stok DESC
            ", [$idbarang]);
        }

        return view('superadmin.kartuStok.show', compact('barang', 'kartuStok'));
    }

    /**
     * Export ke Excel/PDF (opsional)
     */
    public function export(string $idbarang, string $format = 'pdf')
    {
        // Info barang
        $barang = DB::select("
            SELECT * FROM view_stok_barang
            WHERE idbarang = ?
            LIMIT 1
        ", [$idbarang]);
        
        if (empty($barang)) {
            abort(404, 'Barang tidak ditemukan');
        }
        
        $barang = $barang[0];
        
        // History kartu stok
        $kartuStok = DB::select("
            SELECT * FROM view_kartu_stok
            WHERE idbarang = ?
            ORDER BY tanggal ASC, idkartu_stok ASC
        ", [$idbarang]);
        
        // Return view untuk print/export
        return view('superadmin.kartuStok.print', compact('barang', 'kartuStok'));
    }
}