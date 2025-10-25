@extends('layouts.superadmin')

@section('title', 'Dashboard SuperAdmin - Sistem Inventory')

@section('content')
@php
    $pageTitle = 'Dashboard SuperAdmin';
    $pageIcon = 'fa-chart-pie';
@endphp

<!-- Welcome Card -->
<div class="welcome-card">
    <h2>👋 Selamat Datang, Superadmin!</h2>
    <p class="mb-0 opacity-90">Berikut adalah ringkasan data lengkap sistem inventory Anda</p>
</div>

<!-- Stats Row 1 -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="stat-card card text-white bg-primary shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="opacity-75">Total User</h6>
                        <h2>{{ $stats['user'] }}</h2>
                    </div>
                    <i class="fas fa-users fa-3x"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card card text-white bg-secondary shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="opacity-75">Total Role</h6>
                        <h2>{{ $stats['role'] }}</h2>
                    </div>
                    <i class="fas fa-user-tag fa-3x"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card card text-white bg-success shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="opacity-75">Total Barang</h6>
                        <h2>{{ $stats['barang'] }}</h2>
                    </div>
                    <i class="fas fa-boxes fa-3x"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card card text-white bg-warning shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="opacity-75">Total Vendor</h6>
                        <h2>{{ $stats['vendor'] }}</h2>
                    </div>
                    <i class="fas fa-truck fa-3x"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Row 2 -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="stat-card card text-white bg-info shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="opacity-75">Total Pengadaan</h6>
                        <h2>{{ $stats['pengadaan'] }}</h2>
                    </div>
                    <i class="fas fa-shopping-cart fa-3x"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card card text-white bg-dark shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="opacity-75">Total Penerimaan</h6>
                        <h2>{{ $stats['penerimaan'] }}</h2>
                    </div>
                    <i class="fas fa-inbox fa-3x"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card card text-white bg-danger shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="opacity-75">Total Penjualan</h6>
                        <h2>{{ $stats['penjualan'] }}</h2>
                    </div>
                    <i class="fas fa-cash-register fa-3x"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card card text-white shadow-sm" style="background: #ff6b6b;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="opacity-75">Total Retur</h6>
                        <h2>{{ $stats['retur'] }}</h2>
                    </div>
                    <i class="fas fa-undo fa-3x"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Row 3 -->
<div class="row g-4">
    <div class="col-md-3">
        <div class="stat-card card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted">Total Satuan</h6>
                        <h2 class="text-primary">{{ $stats['satuan'] }}</h2>
                    </div>
                    <i class="fas fa-balance-scale fa-3x text-primary"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted">Total Margin</h6>
                        <h2 class="text-success">{{ $stats['margin'] }}</h2>
                    </div>
                    <i class="fas fa-percentage fa-3x text-success"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted">Total Kartu Stok</h6>
                        <h2 class="text-info">{{ $stats['kartu_stok'] }}</h2>
                    </div>
                    <i class="fas fa-warehouse fa-3x text-info"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card card border-0 shadow-sm" style="background: linear-gradient(135deg, #667eea, #764ba2);">
            <div class="card-body text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="opacity-75">Role Anda</h6>
                        <h3 class="mb-0 fw-bold">Super Admin</h3>
                    </div>
                    <i class="fas fa-crown fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection