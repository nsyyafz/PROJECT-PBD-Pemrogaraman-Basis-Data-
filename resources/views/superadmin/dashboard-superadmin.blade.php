@extends('layouts.superadmin')

@section('title', 'Dashboard SuperAdmin - Sistem Inventory')

@section('content')
@php
    $pageTitle = 'Dashboard SuperAdmin';
    $pageIcon = 'fa-chart-pie';
@endphp

<style>
    .dashboard-header {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        border-radius: 12px;
        padding: 2rem 2.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(30, 60, 114, 0.15);
    }

    .dashboard-header h2 {
        color: white;
        font-size: 28px;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .dashboard-header p {
        color: rgba(255, 255, 255, 0.9);
        font-size: 15px;
        margin: 0;
    }

    .stat-card {
        border: none;
        border-radius: 10px;
        transition: all 0.3s ease;
        height: 100%;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
    }

    .stat-card .card-body {
        padding: 1.75rem;
    }

    .stat-card h6 {
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.75rem;
    }

    .stat-card h2 {
        font-size: 32px;
        font-weight: 700;
        margin: 0;
        line-height: 1;
    }

    .stat-card h3 {
        font-size: 20px;
        font-weight: 600;
    }

    .stat-card i {
        opacity: 0.25;
        transition: all 0.3s ease;
    }

    .stat-card:hover i {
        opacity: 0.4;
        transform: scale(1.1);
    }

    /* Classic Color Palette */
    .card-blue {
        background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
        color: white;
    }

    .card-navy {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        color: white;
    }

    .card-teal {
        background: linear-gradient(135deg, #16a085 0%, #1abc9c 100%);
        color: white;
    }

    .card-orange {
        background: linear-gradient(135deg, #e67e22 0%, #f39c12 100%);
        color: white;
    }

    .card-purple {
        background: linear-gradient(135deg, #8e44ad 0%, #9b59b6 100%);
        color: white;
    }

    .card-red {
        background: linear-gradient(135deg, #c0392b 0%, #e74c3c 100%);
        color: white;
    }

    .card-green {
        background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
        color: white;
    }

    .card-indigo {
        background: linear-gradient(135deg, #2c3e91 0%, #3742fa 100%);
        color: white;
    }

    .card-white {
        background: white;
        border: 1px solid #e8eaed;
    }

    .card-white h2 {
        color: #2c3e50;
    }

    .card-white h6 {
        color: #7f8c8d;
    }

    .card-white i {
        color: #bdc3c7;
    }

    .card-crown {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        position: relative;
        overflow: hidden;
    }

    .card-crown::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .section-divider {
        margin: 2.5rem 0;
        border-top: 2px solid #ecf0f1;
    }

    @media (max-width: 768px) {
        .dashboard-header {
            padding: 1.5rem;
        }

        .dashboard-header h2 {
            font-size: 22px;
        }

        .stat-card h2 {
            font-size: 26px;
        }
    }
</style>

<!-- Welcome Card -->
<div class="dashboard-header">
    <h2>👋 Selamat Datang, SuperAdmin</h2>
    <p>Berikut adalah ringkasan data lengkap sistem inventory Anda</p>
</div>

<!-- Stats Row 1: System Management -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="stat-card card card-blue shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Total User</h6>
                        <h2>{{ $stats['user'] }}</h2>
                    </div>
                    <i class="fas fa-users fa-3x"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card card card-navy shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Total Role</h6>
                        <h2>{{ $stats['role'] }}</h2>
                    </div>
                    <i class="fas fa-user-tag fa-3x"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card card card-teal shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Total Barang</h6>
                        <h2>{{ $stats['barang'] }}</h2>
                    </div>
                    <i class="fas fa-boxes fa-3x"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card card card-orange shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Total Vendor</h6>
                        <h2>{{ $stats['vendor'] }}</h2>
                    </div>
                    <i class="fas fa-truck fa-3x"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Row 2: Transaction Management -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="stat-card card card-purple shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Total Pengadaan</h6>
                        <h2>{{ $stats['pengadaan'] }}</h2>
                    </div>
                    <i class="fas fa-shopping-cart fa-3x"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card card card-indigo shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Total Penerimaan</h6>
                        <h2>{{ $stats['penerimaan'] }}</h2>
                    </div>
                    <i class="fas fa-inbox fa-3x"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card card card-green shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Total Penjualan</h6>
                        <h2>{{ $stats['penjualan'] }}</h2>
                    </div>
                    <i class="fas fa-cash-register fa-3x"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card card card-red shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Total Retur</h6>
                        <h2>{{ $stats['retur'] }}</h2>
                    </div>
                    <i class="fas fa-undo fa-3x"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Row 3: Additional Info -->
<div class="row g-4">
    <div class="col-md-3">
        <div class="stat-card card card-white shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Total Satuan</h6>
                        <h2>{{ $stats['satuan'] }}</h2>
                    </div>
                    <i class="fas fa-balance-scale fa-3x"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card card card-white shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Total Margin</h6>
                        <h2>{{ $stats['margin'] }}</h2>
                    </div>
                    <i class="fas fa-percentage fa-3x"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card card card-white shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Total Kartu Stok</h6>
                        <h2>{{ $stats['kartu_stok'] }}</h2>
                    </div>
                    <i class="fas fa-warehouse fa-3x"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card card card-crown shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 style="opacity: 0.85;">Role Anda</h6>
                        <h3 class="mb-0 fw-bold">SuperAdmin</h3>
                    </div>
                    <i class="fas fa-crown fa-3x" style="opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection