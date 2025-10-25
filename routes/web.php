<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Superadmin\RoleController;
use App\Http\Controllers\Superadmin\UserController;
use App\Http\Controllers\Superadmin\ReturController;
use App\Http\Controllers\Superadmin\BarangController;
use App\Http\Controllers\Superadmin\MarginController;
use App\Http\Controllers\Superadmin\SatuanController;
use App\Http\Controllers\Superadmin\VendorController;
use App\Http\Controllers\Superadmin\LaporanController;
use App\Http\Controllers\Superadmin\KartuStokController;
use App\Http\Controllers\Superadmin\PengadaanController;
use App\Http\Controllers\Superadmin\PenjualanController;
use App\Http\Controllers\Superadmin\PenerimaanController;

// ===================================
// AUTH ROUTES
// ===================================
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::get('/login', [AuthController::class, 'showLogin']);
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ===================================
// SUPERADMIN ROUTES
// ===================================
Route::prefix('superadmin')->name('superadmin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'superadmin'])->name('dashboard');
    
    // Master Data - User & Role
    Route::resource('user', UserController::class);
    Route::resource('role', RoleController::class);
    
    // Master Data - Barang & Satuan
    Route::resource('barang', BarangController::class);
    Route::resource('satuan', SatuanController::class);
    
    // Master Data - Vendor
    Route::resource('vendor', VendorController::class);
    Route::post('vendor/{id}/toggle', [VendorController::class, 'toggleStatus'])
        ->name('vendor.toggle');
    
    // Master Data - Margin Penjualan
    Route::resource('margin', MarginController::class);
    Route::post('margin/{id}/toggle', [MarginController::class, 'toggleStatus'])
        ->name('margin.toggle');
    
    // Transaksi - Pengadaan
    Route::resource('pengadaan', PengadaanController::class);
    Route::patch('pengadaan/{id}/update-status', [PengadaanController::class, 'updateStatus'])
        ->name('pengadaan.update-status');
    
    // Transaksi - Penerimaan
    Route::resource('penerimaan', PenerimaanController::class);
    Route::patch('penerimaan/{id}/update-status', [PenerimaanController::class, 'updateStatus'])
        ->name('penerimaan.update-status');
    
    // Transaksi - Penjualan
    Route::resource('penjualan', PenjualanController::class);
    
    // Transaksi - Retur
    Route::resource('retur', ReturController::class);
    
    // Kartu Stok
    Route::get('kartuStok', [KartuStokController::class, 'index'])
        ->name('kartuStok.index');
    Route::get('kartuStok/{idbarang}', [KartuStokController::class, 'show'])
        ->name('kartuStok.show');
    Route::get('kartuStok/{idbarang}/export', [KartuStokController::class, 'export'])
        ->name('kartuStok.export');
    
    // Laporan (optional - sesuaikan dengan kebutuhan)
    Route::get('laporan', [LaporanController::class, 'penjualan'])
        ->name('laporan.penjualan');
    Route::get('laporan/printPenjualan', [LaporanController::class, 'printPenjualan'])
        ->name('laporan.printPenjualan');
});

// ===================================
// ADMIN ROUTES
// ===================================
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
});