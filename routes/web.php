<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Superadmin\RoleController;
use App\Http\Controllers\Superadmin\UserController;
use App\Http\Controllers\Superadmin\BarangController;
use App\Http\Controllers\Superadmin\SatuanController;
use App\Http\Controllers\Superadmin\VendorController;
use App\Http\Controllers\Superadmin\PengadaanController;
use App\Http\Controllers\Superadmin\PenerimaanController;
use App\Http\Controllers\Superadmin\PenjualanController;
use App\Http\Controllers\Superadmin\MarginController;

// Auth Routes
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::get('/login', [AuthController::class, 'showLogin']);
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'superadmin'])->name('dashboard');
    Route::resource('/user', UserController::class);
    Route::resource('/role', RoleController::class);
    Route::resource('/barang', BarangController::class);
    Route::resource('/satuan', SatuanController::class);
    Route::resource('/vendor', VendorController::class);
    Route::resource('/pengadaan', PengadaanController::class);
    Route::resource('/penerimaan', PenerimaanController::class);
    Route::resource('/penjualan', PenjualanController::class);
    Route::resource('/margin', MarginController::class);
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
});
