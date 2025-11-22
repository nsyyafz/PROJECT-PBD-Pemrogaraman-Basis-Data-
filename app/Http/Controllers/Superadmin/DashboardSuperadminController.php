<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardSuperadminController extends Controller
{
    /**
     * Display dashboard with statistics and recent activity
     */
    public function index()
    {
        // ============================================
        // DATA MASTER
        // ============================================
        $totalUser = DB::selectOne("SELECT COUNT(*) as total FROM user")->total;
        $totalRole = DB::selectOne("SELECT COUNT(*) as total FROM role")->total;
        $totalVendor = DB::selectOne("SELECT COUNT(*) as total FROM vendor WHERE status = 1")->total;
        $totalBarang = DB::selectOne("SELECT COUNT(*) as total FROM barang WHERE status = 1")->total;
        $totalSatuan = DB::selectOne("SELECT COUNT(*) as total FROM satuan WHERE status = 1")->total;
        $totalMargin = DB::selectOne("SELECT COUNT(*) as total FROM margin_penjualan WHERE status = 1")->total;
        $totalKartuStok = DB::selectOne("SELECT COUNT(*) as total FROM kartu_stok")->total;

        // ============================================
        // RINGKASAN PENGADAAN (dari VIEW)
        // ============================================
        $ringkasanPengadaan = DB::selectOne("SELECT * FROM view_ringkasan_pengadaan");
        $totalPengadaan = $ringkasanPengadaan->total_semua ?? 0;

        // ============================================
        // RINGKASAN PENERIMAAN (dari VIEW)
        // ============================================
        $ringkasanPenerimaan = DB::selectOne("SELECT * FROM view_ringkasan_penerimaan");
        $totalPenerimaan = $ringkasanPenerimaan->total_penerimaan ?? 0;

        // ============================================
        // RINGKASAN PENJUALAN (dari VIEW)
        // ============================================
        $ringkasanPenjualan = DB::selectOne("SELECT * FROM view_ringkasan_penjualan");
        $totalPenjualan = $ringkasanPenjualan->total_penjualan ?? 0;

        // ============================================
        // STATISTIK ACTIVITY (dari VIEW BARU)
        // ============================================
        $statistikActivity = DB::selectOne("SELECT * FROM view_statistik_activity");

        // ============================================
        // RECENT ACTIVITY (10 Terbaru)
        // ============================================
        $recentActivities = DB::select("
            SELECT * FROM view_recent_activity 
            LIMIT 10
        ");

        // ============================================
        // ACTIVITY HARI INI
        // ============================================
        $activityToday = DB::select("SELECT * FROM view_activity_today LIMIT 5");

        // ============================================
        // DATA RETUR
        // ============================================
        $totalRetur = DB::selectOne("SELECT COUNT(*) as total FROM retur")->total ?? 0;

        // ============================================
        // KOMPILASI STATS UNTUK CARDS
        // ============================================
        $stats = [
            // Master Data
            'user' => $totalUser,
            'role' => $totalRole,
            'vendor' => $totalVendor,
            'barang' => $totalBarang,
            'satuan' => $totalSatuan,
            'margin' => $totalMargin,
            
            // Transaksi
            'pengadaan' => $totalPengadaan,
            'penerimaan' => $totalPenerimaan,
            'penjualan' => $totalPenjualan,
            'retur' => $totalRetur,
            'kartu_stok' => $totalKartuStok,
            
            // Activity
            'activity_today' => $statistikActivity->activity_today ?? 0,
            'activity_week' => $statistikActivity->activity_week ?? 0,
            'activity_month' => $statistikActivity->activity_month ?? 0,
        ];

        // ============================================
        // DETAIL STATS UNTUK CHARTS & WIDGETS
        // ============================================
        $detailStats = [
            'pengadaan' => [
                'diproses' => $ringkasanPengadaan->total_diproses ?? 0,
                'sebagian' => $ringkasanPengadaan->total_sebagian ?? 0,
                'selesai' => $ringkasanPengadaan->total_selesai ?? 0,
                'rejected' => $ringkasanPengadaan->total_rejected ?? 0,
                'nilai_selesai' => $ringkasanPengadaan->nilai_selesai ?? 0,
                'nilai_outstanding' => $ringkasanPengadaan->nilai_outstanding ?? 0,
            ],
            'penerimaan' => [
                'total_pengadaan_diterima' => $ringkasanPenerimaan->total_pengadaan_diterima ?? 0,
                'total_qty_diterima' => $ringkasanPenerimaan->total_qty_diterima ?? 0,
                'total_nilai_diterima' => $ringkasanPenerimaan->total_nilai_diterima ?? 0,
                'penerimaan_hari_ini' => $ringkasanPenerimaan->penerimaan_hari_ini ?? 0,
                'penerimaan_bulan_ini' => $ringkasanPenerimaan->penerimaan_bulan_ini ?? 0,
            ],
            'penjualan' => [
                'total_nilai' => $ringkasanPenjualan->total_nilai_penjualan ?? 0,
                'total_subtotal' => $ringkasanPenjualan->total_subtotal ?? 0,
                'rata_rata' => $ringkasanPenjualan->rata_rata_nilai ?? 0,
                'hari_ini' => $ringkasanPenjualan->penjualan_hari_ini ?? 0,
                'nilai_hari_ini' => $ringkasanPenjualan->nilai_hari_ini ?? 0,
                'bulan_ini' => $ringkasanPenjualan->penjualan_bulan_ini ?? 0,
                'nilai_bulan_ini' => $ringkasanPenjualan->nilai_bulan_ini ?? 0,
            ],
            'activity' => [
                'total' => $statistikActivity->total_activity ?? 0,
                'total_pengadaan' => $statistikActivity->total_pengadaan ?? 0,
                'total_penerimaan' => $statistikActivity->total_penerimaan ?? 0,
                'total_penjualan' => $statistikActivity->total_penjualan ?? 0,
                'total_nilai_transaksi' => $statistikActivity->total_nilai_transaksi ?? 0,
                'total_nilai_pengadaan' => $statistikActivity->total_nilai_pengadaan ?? 0,
                'total_nilai_penjualan' => $statistikActivity->total_nilai_penjualan ?? 0,
                'pengadaan_today' => $statistikActivity->pengadaan_today ?? 0,
                'penerimaan_today' => $statistikActivity->penerimaan_today ?? 0,
                'penjualan_today' => $statistikActivity->penjualan_today ?? 0,
            ]
        ];

        // Variabel tanggal untuk layout
        $tanggalDari = date('Y-m-01'); // Awal bulan
        $tanggalSampai = date('Y-m-d'); // Hari ini

        return view('superadmin.dashboard-superadmin', compact(
            'stats', 
            'detailStats', 
            'recentActivities',
            'activityToday',
            'tanggalDari', 
            'tanggalSampai'
        ));
    }

    /**
     * Get activity by date range (AJAX)
     */
    public function getActivityByDate(Request $request)
    {
        $from = $request->from ?? date('Y-m-01');
        $to = $request->to ?? date('Y-m-d');

        $activities = DB::select("
            SELECT * FROM view_recent_activity
            WHERE DATE(tanggal) BETWEEN ? AND ?
            ORDER BY tanggal DESC
            LIMIT 50
        ", [$from, $to]);

        return response()->json([
            'success' => true,
            'data' => $activities
        ]);
    }

    /**
     * Get activity by type (AJAX)
     */
    public function getActivityByType(Request $request)
    {
        $type = $request->type; // Pengadaan, Penerimaan, Penjualan

        $activities = DB::select("
            SELECT * FROM view_recent_activity
            WHERE jenis_aktivitas = ?
            ORDER BY tanggal DESC
            LIMIT 20
        ", [$type]);

        return response()->json([
            'success' => true,
            'data' => $activities
        ]);
    }

    /**
     * Get weekly statistics (AJAX)
     */
    public function getWeeklyStats(Request $request)
    {
        $stats = [];
        
        // Loop 7 hari terakhir
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            
            $daily = DB::selectOne("
                SELECT 
                    DATE(tanggal) as tanggal,
                    COUNT(CASE WHEN jenis_aktivitas = 'Pengadaan' THEN 1 END) as pengadaan,
                    COUNT(CASE WHEN jenis_aktivitas = 'Penerimaan' THEN 1 END) as penerimaan,
                    COUNT(CASE WHEN jenis_aktivitas = 'Penjualan' THEN 1 END) as penjualan
                FROM view_recent_activity
                WHERE DATE(tanggal) = ?
                GROUP BY DATE(tanggal)
            ", [$date]);
            
            $stats[] = [
                'tanggal' => date('d M', strtotime($date)),
                'pengadaan' => $daily->pengadaan ?? 0,
                'penerimaan' => $daily->penerimaan ?? 0,
                'penjualan' => $daily->penjualan ?? 0,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}