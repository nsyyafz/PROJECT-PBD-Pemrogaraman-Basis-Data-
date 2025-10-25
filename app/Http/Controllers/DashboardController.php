<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    // Dashboard Superadmin - Akses penuh semua data
    public function superadmin()
    {
        if (!Session::has('user_id')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu!');
        }

        // Cek role harus superadmin
        if (strtolower(Session::get('role')) != 'superadmin') {
            return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak!');
        }

        // Hitung jumlah data dari setiap tabel
        $totalUser = DB::select("SELECT COUNT(*) as total FROM user")[0]->total;
        $totalRole = DB::select("SELECT COUNT(*) as total FROM role")[0]->total;
        $totalVendor = DB::select("SELECT COUNT(*) as total FROM vendor")[0]->total;
        $totalBarang = DB::select("SELECT COUNT(*) as total FROM barang")[0]->total;
        $totalSatuan = DB::select("SELECT COUNT(*) as total FROM satuan")[0]->total;
        $totalPengadaan = DB::select("SELECT COUNT(*) as total FROM pengadaan")[0]->total;
        $totalPenerimaan = DB::select("SELECT COUNT(*) as total FROM penerimaan")[0]->total;
        $totalRetur = DB::select("SELECT COUNT(*) as total FROM retur")[0]->total;
        $totalPenjualan = DB::select("SELECT COUNT(*) as total FROM penjualan")[0]->total;
        $totalKartuStok = DB::select("SELECT COUNT(*) as total FROM kartu_stok")[0]->total;
        $totalMargin = DB::select("SELECT COUNT(*) as total FROM margin_penjualan")[0]->total;


        $stats = [
            'user' => $totalUser,
            'role' => $totalRole,
            'vendor' => $totalVendor,
            'barang' => $totalBarang,
            'satuan' => $totalSatuan,
            'pengadaan' => $totalPengadaan,
            'penerimaan' => $totalPenerimaan,
            'retur' => $totalRetur,
            'penjualan' => $totalPenjualan,
            'kartu_stok' => $totalKartuStok,
            'margin' => $totalMargin
        ];

        return view('superadmin.dashboard', compact('stats'));
    }

    // Dashboard Admin - Tampilan terbatas
    public function admin()
    {
        if (!Session::has('user_id')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu!');
        }

        // Admin hanya bisa lihat data operasional (tidak bisa lihat user/role)
        $totalVendor = DB::select("SELECT COUNT(*) as total FROM vendor")[0]->total;
        $totalBarang = DB::select("SELECT COUNT(*) as total FROM barang")[0]->total;
        $totalSatuan = DB::select("SELECT COUNT(*) as total FROM satuan")[0]->total;
        $totalPengadaan = DB::select("SELECT COUNT(*) as total FROM pengadaan")[0]->total;
        $totalPenerimaan = DB::select("SELECT COUNT(*) as total FROM penerimaan")[0]->total;
        $totalRetur = DB::select("SELECT COUNT(*) as total FROM retur")[0]->total;
        $totalPenjualan = DB::select("SELECT COUNT(*) as total FROM penjualan")[0]->total;
        $totalKartuStok = DB::select("SELECT COUNT(*) as total FROM kartu_stok")[0]->total;

        $stats = [
            'vendor' => $totalVendor,
            'barang' => $totalBarang,
            'satuan' => $totalSatuan,
            'pengadaan' => $totalPengadaan,
            'penerimaan' => $totalPenerimaan,
            'retur' => $totalRetur,
            'penjualan' => $totalPenjualan,
            'kartu_stok' => $totalKartuStok
        ];

        return view('admin.dashboard', compact('stats'));
    }
}