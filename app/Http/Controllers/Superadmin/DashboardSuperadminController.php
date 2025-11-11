<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardSuperadminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Hitung jumlah data dari setiap tabel
        $totalUser = DB::selectOne("SELECT COUNT(*) as total FROM user")->total;
        $totalRole = DB::selectOne("SELECT COUNT(*) as total FROM role")->total;
        $totalVendor = DB::selectOne("SELECT COUNT(*) as total FROM vendor")->total;
        $totalBarang = DB::selectOne("SELECT COUNT(*) as total FROM barang")->total;
        $totalSatuan = DB::selectOne("SELECT COUNT(*) as total FROM satuan")->total;
        $totalPengadaan = DB::selectOne("SELECT COUNT(*) as total FROM pengadaan")->total;
        $totalPenerimaan = DB::selectOne("SELECT COUNT(*) as total FROM penerimaan")->total;
        $totalRetur = DB::selectOne("SELECT COUNT(*) as total FROM retur")->total;
        $totalPenjualan = DB::selectOne("SELECT COUNT(*) as total FROM penjualan")->total;
        $totalKartuStok = DB::selectOne("SELECT COUNT(*) as total FROM kartu_stok")->total;
        $totalMargin = DB::selectOne("SELECT COUNT(*) as total FROM margin_penjualan")->total;

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

        // Tambahkan variabel tanggal untuk layout
        $tanggalDari = date('Y-m-01'); // Awal bulan
        $tanggalSampai = date('Y-m-d'); // Hari ini

        return view('superadmin.dashboard-superadmin', compact('stats', 'tanggalDari', 'tanggalSampai'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
