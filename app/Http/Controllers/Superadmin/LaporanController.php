<?php

namespace App\Http\Controllers\Superadmin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class LaporanController extends Controller
{
    /**
     * Laporan Penjualan
     */
    public function penjualan(Request $request)
    {
        $tanggalDari = $request->get('tanggal_dari', date('Y-m-01')); // Default awal bulan
        $tanggalSampai = $request->get('tanggal_sampai', date('Y-m-d')); // Default hari ini
        
        // Data penjualan dalam periode
        $penjualans = DB::select("
            SELECT * FROM view_list_penjualan
            WHERE DATE(tanggal) BETWEEN ? AND ?
            ORDER BY tanggal DESC
        ", [$tanggalDari, $tanggalSampai]);
        
        // Ringkasan
        $totalTransaksi = count($penjualans);
        $totalOmset = array_sum(array_column($penjualans, 'total_nilai'));
        
        // Barang terlaris (top 5)
        $barangTerlaris = DB::select("
            SELECT 
                b.nama as nama_barang,
                SUM(dpj.jumlah) as total_terjual,
                s.nama_satuan,
                SUM(dpj.subtotal) as total_nilai
            FROM detail_penjualan dpj
            JOIN penjualan pj ON dpj.penjualan_idpenjualan = pj.idpenjualan
            JOIN barang b ON dpj.idbarang = b.idbarang
            JOIN satuan s ON b.idsatuan = s.idsatuan
            WHERE DATE(pj.created_at) BETWEEN ? AND ?
            GROUP BY dpj.idbarang, b.nama, s.nama_satuan
            ORDER BY total_terjual DESC
            LIMIT 5
        ", [$tanggalDari, $tanggalSampai]);
        
        // Penjualan per kasir
        $perKasir = DB::select("
            SELECT 
                u.username as kasir,
                COUNT(pj.idpenjualan) as jumlah_transaksi,
                SUM(pj.total_nilai) as total_omset
            FROM penjualan pj
            JOIN user u ON pj.iduser = u.iduser
            WHERE DATE(pj.created_at) BETWEEN ? AND ?
            GROUP BY pj.iduser, u.username
            ORDER BY total_omset DESC
        ", [$tanggalDari, $tanggalSampai]);
        
        return view('superadmin.laporan.penjualan', compact(
            'penjualans',
            'tanggalDari',
            'tanggalSampai',
            'totalTransaksi',
            'totalOmset',
            'barangTerlaris',
            'perKasir'
        ));
    }
    
    /**
     * Export Laporan Penjualan ke PDF
     */
    public function exportPenjualan(Request $request)
    {
        $tanggalDari = $request->get('tanggal_dari', date('Y-m-01'));
        $tanggalSampai = $request->get('tanggal_sampai', date('Y-m-d'));
        
        // Data penjualan dalam periode
        $penjualans = DB::select("
            SELECT * FROM view_list_penjualan
            WHERE DATE(tanggal) BETWEEN ? AND ?
            ORDER BY tanggal ASC
        ", [$tanggalDari, $tanggalSampai]);
        
        // Ringkasan
        $totalTransaksi = count($penjualans);
        $totalOmset = array_sum(array_column($penjualans, 'total_nilai'));
        
        // Barang terlaris
        $barangTerlaris = DB::select("
            SELECT 
                b.nama as nama_barang,
                SUM(dpj.jumlah) as total_terjual,
                s.nama_satuan,
                SUM(dpj.subtotal) as total_nilai
            FROM detail_penjualan dpj
            JOIN penjualan pj ON dpj.penjualan_idpenjualan = pj.idpenjualan
            JOIN barang b ON dpj.idbarang = b.idbarang
            JOIN satuan s ON b.idsatuan = s.idsatuan
            WHERE DATE(pj.created_at) BETWEEN ? AND ?
            GROUP BY dpj.idbarang, b.nama, s.nama_satuan
            ORDER BY total_terjual DESC
            LIMIT 10
        ", [$tanggalDari, $tanggalSampai]);
        
        return view('superadmin.laporan.print-penjualan', compact(
            'penjualans',
            'tanggalDari',
            'tanggalSampai',
            'totalTransaksi',
            'totalOmset',
            'barangTerlaris'
        ));
    }
}