<?php

namespace App\Http\Controllers\Superadmin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class KartuStokController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil parameter filter dari query string (default: all)
        $filter = $request->get('filter', 'all');
        
        // Query berdasarkan filter
        if ($filter == 'penerimaan') {
            // Filter: hanya transaksi penerimaan
            $kartuStoks = DB::select("
                SELECT * FROM view_kartu_stok_penerimaan
                ORDER BY created_at DESC, idkartu_stok DESC
            ");
        } elseif ($filter == 'penjualan') {
            // Filter: hanya transaksi penjualan
            $kartuStoks = DB::select("
                SELECT * FROM view_kartu_stok_penjualan
                ORDER BY created_at DESC, idkartu_stok DESC
            ");
        } else {
            // Default: tampilkan semua transaksi (all)
            $kartuStoks = DB::select("
                SELECT * FROM view_kartu_stok_all
                ORDER BY created_at DESC, idkartu_stok DESC
            ");
        }
        
        return view('superadmin.kartu-stok.index', compact('kartuStoks', 'filter'));
    }
}