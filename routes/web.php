<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Site\SiteController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Superadmin\RoleController;
use App\Http\Controllers\Superadmin\UserController;
use App\Http\Controllers\Superadmin\ReturController;
use App\Http\Controllers\Superadmin\BarangController;
use App\Http\Controllers\Superadmin\MarginController;
use App\Http\Controllers\Superadmin\SatuanController;
use App\Http\Controllers\Superadmin\VendorController;
use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Superadmin\KartuStokController;
use App\Http\Controllers\Superadmin\PengadaanController;
use App\Http\Controllers\Superadmin\PenjualanController;
use App\Http\Controllers\Superadmin\PenerimaanController;
use App\Http\Controllers\Superadmin\DashboardSuperadminController;

// Public Routes
Route::get('/', [SiteController::class, 'index'])->name('home');

Auth::routes();

// SuperAdmin Routes
Route::middleware(['isSuperadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardSuperadminController::class, 'index'])->name('dashboard');
    
    // User Management
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
    Route::post('/user', [UserController::class, 'store'])->name('user.store');
    Route::get('/user/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/user/{id}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('user.destroy');
    
    // Role Management
    Route::get('/role', [RoleController::class, 'index'])->name('role.index');
    Route::get('/role/create', [RoleController::class, 'create'])->name('role.create');
    Route::post('/role', [RoleController::class, 'store'])->name('role.store');
    Route::get('/role/{id}/edit', [RoleController::class, 'edit'])->name('role.edit');
    Route::put('/role/{id}', [RoleController::class, 'update'])->name('role.update');
    Route::delete('/role/{id}', [RoleController::class, 'destroy'])->name('role.destroy');
    
    // Barang Management
    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
    Route::get('/barang/create', [BarangController::class, 'create'])->name('barang.create');
    Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
    Route::get('/barang/{id}', [BarangController::class, 'show'])->name('barang.toggle');
    Route::get('/barang/{id}/edit', [BarangController::class, 'edit'])->name('barang.edit');
    Route::put('/barang/{id}', [BarangController::class, 'update'])->name('barang.update');
    Route::delete('/barang/{id}', [BarangController::class, 'destroy'])->name('barang.destroy');
    
    // Vendor Management
    Route::get('/vendor', [VendorController::class, 'index'])->name('vendor.index');
    Route::get('/vendor/create', [VendorController::class, 'create'])->name('vendor.create');
    Route::post('/vendor', [VendorController::class, 'store'])->name('vendor.store');
    Route::get('/vendor/{id}/edit', [VendorController::class, 'edit'])->name('vendor.edit');
    Route::put('/vendor/{id}', [VendorController::class, 'update'])->name('vendor.update');
    Route::delete('/vendor/{id}', [VendorController::class, 'destroy'])->name('vendor.destroy');
    
    // Satuan Management
    Route::get('/satuan', [SatuanController::class, 'index'])->name('satuan.index');
    Route::get('/satuan/create', [SatuanController::class, 'create'])->name('satuan.create');
    Route::post('/satuan', [SatuanController::class, 'store'])->name('satuan.store');
    Route::get('/satuan/{id}/edit', [SatuanController::class, 'edit'])->name('satuan.edit');
    Route::put('/satuan/{id}', [SatuanController::class, 'update'])->name('satuan.update');
    Route::delete('/satuan/{id}', [SatuanController::class, 'destroy'])->name('satuan.destroy');
    
    // Pengadaan Management
    Route::get('/pengadaan', [PengadaanController::class, 'index'])->name('pengadaan.index');
    Route::post('/pengadaan/ajax/get-harga-barang', [PengadaanController::class, 'getHargaBarang'])
         ->name('pengadaan.getHargaBarang');
    Route::get('/pengadaan/create', [PengadaanController::class, 'create'])->name('pengadaan.create');
    Route::post('/pengadaan', [PengadaanController::class, 'store'])->name('pengadaan.store');
    Route::get('/pengadaan/{id}', [PengadaanController::class, 'show'])->name('pengadaan.show');
    Route::patch('/pengadaan/{id}/update-status', [PengadaanController::class, 'updateStatus'])->name('pengadaan.updateStatus');
    Route::get('/pengadaan/{id}/edit', [PengadaanController::class, 'edit'])->name('pengadaan.edit');
    Route::put('/pengadaan/{id}', [PengadaanController::class, 'update'])->name('pengadaan.update');
    Route::delete('/pengadaan/{id}', [PengadaanController::class, 'destroy'])->name('pengadaan.destroy');
    
    // Penerimaan Management
    Route::get('/penerimaan', [PenerimaanController::class, 'index'])->name('penerimaan.index');
    Route::post('/penerimaan/ajax/get-preview-pengadaan', [PenerimaanController::class, 'getDetailPengadaan'])
        ->name('penerimaan.getDetailPengadaan');
    Route::get('/penerimaan/create', [PenerimaanController::class, 'create'])->name('penerimaan.create');
    Route::post('/penerimaan', [PenerimaanController::class, 'store'])->name('penerimaan.store');
    Route::get('/penerimaan/{id}', [PenerimaanController::class, 'show'])->name('penerimaan.show');
    Route::get('/penerimaan/{id}/edit', [PenerimaanController::class, 'edit'])->name('penerimaan.edit');
    Route::put('/penerimaan/{id}', [PenerimaanController::class, 'update'])->name('penerimaan.update');
    Route::delete('/penerimaan/{id}', [PenerimaanController::class, 'destroy'])->name('penerimaan.destroy');
    
    // Penjualan Management
    Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
    Route::get('/penjualan/create', [PenjualanController::class, 'create'])->name('penjualan.create');
    Route::post('/penjualan', [PenjualanController::class, 'store'])->name('penjualan.store');
    Route::get('/penjualan/{id}/edit', [PenjualanController::class, 'edit'])->name('penjualan.edit');
    Route::put('/penjualan/{id}', [PenjualanController::class, 'update'])->name('penjualan.update');
    Route::delete('/penjualan/{id}', [PenjualanController::class, 'destroy'])->name('penjualan.destroy');
    
    // Retur Management
    Route::get('/retur', [ReturController::class, 'index'])->name('retur.index');
    Route::get('/retur/create', [ReturController::class, 'create'])->name('retur.create');
    Route::post('/retur', [ReturController::class, 'store'])->name('retur.store');
    Route::get('/retur/{id}/edit', [ReturController::class, 'edit'])->name('retur.edit');
    Route::put('/retur/{id}', [ReturController::class, 'update'])->name('retur.update');
    Route::delete('/retur/{id}', [ReturController::class, 'destroy'])->name('retur.destroy');
    
    // Kartu Stok
    Route::get('/kartu-stok', [KartuStokController::class, 'index'])->name('kartuStok.index');
    
    // Margin Penjualan
    Route::get('/margin', [MarginController::class, 'index'])->name('margin.index');
    Route::get('/margin/create', [MarginController::class, 'create'])->name('margin.create');
    Route::post('/margin', [MarginController::class, 'store'])->name('margin.store');
    Route::get('/margin/{id}/edit', [MarginController::class, 'edit'])->name('margin.edit');
    Route::put('/margin/{id}', [MarginController::class, 'update'])->name('margin.update');
    Route::delete('/margin/{id}', [MarginController::class, 'destroy'])->name('margin.destroy');
});

// Admin Routes
Route::middleware(['isAdmin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardAdminController::class, 'index'])->name('dashboard');
    
    // Barang Management
    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
    Route::get('/barang/create', [BarangController::class, 'create'])->name('barang.create');
    Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
    Route::get('/barang/{id}/edit', [BarangController::class, 'edit'])->name('barang.edit');
    Route::put('/barang/{id}', [BarangController::class, 'update'])->name('barang.update');
    Route::delete('/barang/{id}', [BarangController::class, 'destroy'])->name('barang.destroy');
    
    // Vendor Management
    Route::get('/vendor', [VendorController::class, 'index'])->name('vendor.index');
    Route::get('/vendor/create', [VendorController::class, 'create'])->name('vendor.create');
    Route::post('/vendor', [VendorController::class, 'store'])->name('vendor.store');
    Route::get('/vendor/{id}/edit', [VendorController::class, 'edit'])->name('vendor.edit');
    Route::put('/vendor/{id}', [VendorController::class, 'update'])->name('vendor.update');
    Route::delete('/vendor/{id}', [VendorController::class, 'destroy'])->name('vendor.destroy');
    
    // Satuan Management
    Route::get('/satuan', [SatuanController::class, 'index'])->name('satuan.index');
    Route::get('/satuan/create', [SatuanController::class, 'create'])->name('satuan.create');
    Route::post('/satuan', [SatuanController::class, 'store'])->name('satuan.store');
    Route::get('/satuan/{id}/edit', [SatuanController::class, 'edit'])->name('satuan.edit');
    Route::put('/satuan/{id}', [SatuanController::class, 'update'])->name('satuan.update');
    Route::delete('/satuan/{id}', [SatuanController::class, 'destroy'])->name('satuan.destroy');
    
    // Pengadaan Management
    Route::get('/pengadaan', [PengadaanController::class, 'index'])->name('pengadaan.index');
    Route::get('/pengadaan/create', [PengadaanController::class, 'create'])->name('pengadaan.create');
    Route::post('/pengadaan', [PengadaanController::class, 'store'])->name('pengadaan.store');
    Route::get('/pengadaan/{id}/edit', [PengadaanController::class, 'edit'])->name('pengadaan.edit');
    Route::put('/pengadaan/{id}', [PengadaanController::class, 'update'])->name('pengadaan.update');
    Route::delete('/pengadaan/{id}', [PengadaanController::class, 'destroy'])->name('pengadaan.destroy');
    
    // Penerimaan Management
    Route::get('/penerimaan', [PenerimaanController::class, 'index'])->name('penerimaan.index');
    Route::get('/penerimaan/create', [PenerimaanController::class, 'create'])->name('penerimaan.create');
    Route::post('/penerimaan', [PenerimaanController::class, 'store'])->name('penerimaan.store');
    Route::get('/penerimaan/{id}/edit', [PenerimaanController::class, 'edit'])->name('penerimaan.edit');
    Route::put('/penerimaan/{id}', [PenerimaanController::class, 'update'])->name('penerimaan.update');
    Route::delete('/penerimaan/{id}', [PenerimaanController::class, 'destroy'])->name('penerimaan.destroy');
    
    // Penjualan Management
    Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
    Route::get('/penjualan/create', [PenjualanController::class, 'create'])->name('penjualan.create');
    Route::post('/penjualan', [PenjualanController::class, 'store'])->name('penjualan.store');
    Route::get('/penjualan/{id}/edit', [PenjualanController::class, 'edit'])->name('penjualan.edit');
    Route::put('/penjualan/{id}', [PenjualanController::class, 'update'])->name('penjualan.update');
    Route::delete('/penjualan/{id}', [PenjualanController::class, 'destroy'])->name('penjualan.destroy');
    
    // Retur Management
    Route::get('/retur', [ReturController::class, 'index'])->name('retur.index');
    Route::get('/retur/create', [ReturController::class, 'create'])->name('retur.create');
    Route::post('/retur', [ReturController::class, 'store'])->name('retur.store');
    Route::get('/retur/{id}/edit', [ReturController::class, 'edit'])->name('retur.edit');
    Route::put('/retur/{id}', [ReturController::class, 'update'])->name('retur.update');
    Route::delete('/retur/{id}', [ReturController::class, 'destroy'])->name('retur.destroy');
    
    // Kartu Stok
    Route::get('/kartu-stok', [KartuStokController::class, 'index'])->name('kartuStok.index');
});