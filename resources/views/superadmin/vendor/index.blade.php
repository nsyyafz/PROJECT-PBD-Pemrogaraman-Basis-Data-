@extends('layouts.superadmin')

@section('title', 'Data Vendor - Sistem Inventory')

@section('content')
@php
    $pageTitle = 'Data Vendor';
    $pageIcon = 'fa-truck';
@endphp

<style>
    .page-header {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        border-radius: 12px;
        padding: 2rem 2.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(30, 60, 114, 0.15);
        color: white;
    }

    .page-header h2 {
        font-size: 28px;
        font-weight: 700;
        margin: 0;
    }

    .page-header p {
        margin: 0.5rem 0 0 0;
        opacity: 0.9;
        font-size: 14px;
    }

    .btn-add {
        background: white;
        color: #1e3c72;
        border: none;
        padding: 12px 28px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        color: #1e3c72;
    }

    .btn-add i {
        margin-right: 8px;
    }

    .filter-section {
        background: white;
        border-radius: 12px;
        padding: 1.5rem 2rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .filter-section h6 {
        font-size: 14px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .filter-chips {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .filter-chip {
        padding: 10px 20px;
        border-radius: 25px;
        border: 2px solid #e8eaed;
        background: white;
        color: #7f8c8d;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .filter-chip:hover {
        border-color: #1e3c72;
        color: #1e3c72;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(30, 60, 114, 0.15);
    }

    .filter-chip.active {
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        color: white;
        border-color: #1e3c72;
        box-shadow: 0 4px 15px rgba(30, 60, 114, 0.25);
    }

    .filter-chip.active-success {
        background: linear-gradient(135deg, #27ae60, #2ecc71);
        color: white;
        border-color: #27ae60;
    }

    .filter-chip i {
        font-size: 13px;
    }

    .data-card {
        background: white;
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }

    .data-card .card-header {
        background: #f8f9fa;
        border-bottom: 2px solid #e8eaed;
        padding: 1.5rem 2rem;
    }

    .data-card .card-header h5 {
        margin: 0;
        color: #2c3e50;
        font-weight: 600;
        font-size: 18px;
    }

    .data-card .card-body {
        padding: 0;
    }

    .table-modern {
        margin: 0;
    }

    .table-modern thead {
        background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
        color: white;
    }

    .table-modern thead th {
        padding: 1.2rem 1.5rem;
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
        white-space: nowrap;
    }

    .table-modern tbody tr {
        border-bottom: 1px solid #e8eaed;
        transition: all 0.3s ease;
    }

    .table-modern tbody tr:hover {
        background: #f8f9fa;
    }

    .table-modern tbody td {
        padding: 1.2rem 1.5rem;
        vertical-align: middle;
        color: #2c3e50;
        font-size: 14px;
    }

    .badge-status {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .badge-aktif {
        background: linear-gradient(135deg, #27ae60, #2ecc71);
        color: white;
    }

    .badge-nonaktif {
        background: #95a5a6;
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #7f8c8d;
    }

    .empty-state i {
        font-size: 64px;
        margin-bottom: 1.5rem;
        opacity: 0.3;
    }

    .empty-state h4 {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #2c3e50;
    }

    .empty-state p {
        font-size: 14px;
        margin: 0;
    }

    .btn-action {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        border: none;
        transition: all 0.3s ease;
        margin: 0 3px;
    }

    .btn-edit {
        background: #3498db;
        color: white;
    }

    .btn-edit:hover {
        background: #2980b9;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(52, 152, 219, 0.3);
    }

    .btn-delete {
        background: #e74c3c;
        color: white;
    }

    .btn-delete:hover {
        background: #c0392b;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(231, 76, 60, 0.3);
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
        }

        .page-header h2 {
            font-size: 22px;
        }

        .filter-section {
            padding: 1rem 1.5rem;
        }

        .filter-chips {
            flex-direction: column;
        }

        .filter-chip {
            width: 100%;
            justify-content: center;
        }

        .table-modern {
            font-size: 12px;
        }

        .table-modern thead th,
        .table-modern tbody td {
            padding: 1rem;
        }
    }
</style>

<!-- Page Header -->
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h2><i class="fas fa-truck me-2"></i>Data Vendor</h2>
            <p>Kelola data vendor supplier</p>
        </div>
        <a href="{{ route('superadmin.vendor.create') }}" class="btn btn-add">
            <i class="fas fa-plus"></i>Tambah Vendor
        </a>
    </div>
</div>

<!-- Alert Messages -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        <strong>Berhasil!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        <strong>Error!</strong> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>Perhatian!</strong> {{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Filter Section -->
<div class="filter-section">
    <h6><i class="fas fa-filter me-2"></i>Filter Status Vendor</h6>
    <div class="filter-chips">
        <a href="{{ route('superadmin.vendor.index', ['status' => 'all']) }}" 
           class="filter-chip {{ $filter == 'all' ? 'active' : '' }}">
            <i class="fas fa-list"></i>
            Semua Vendor
        </a>
        <a href="{{ route('superadmin.vendor.index', ['status' => 'aktif']) }}" 
           class="filter-chip {{ $filter == 'aktif' ? 'active-success' : '' }}">
            <i class="fas fa-check-circle"></i>
            Vendor Aktif
        </a>
    </div>
</div>

<!-- Data Card -->
<div class="data-card">
    <div class="card-header">
        <h5>
            <i class="fas fa-table me-2 text-primary"></i>
            Daftar Vendor 
            @if($filter == 'aktif')
                <span class="badge badge-status badge-aktif ms-2">
                    <i class="fas fa-check me-1"></i>Aktif
                </span>
            @else
                <span class="badge bg-secondary ms-2">
                    <i class="fas fa-list me-1"></i>Semua
                </span>
            @endif
            <span class="badge bg-info ms-2">
                Total: {{ count($vendors) }}
            </span>
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th style="width: 80px;">ID</th>
                        <th>Nama Vendor</th>
                        <th style="width: 150px;">Badan Hukum</th>
                        
                        @if($filter == 'all')
                            <th style="width: 120px;">Status</th>
                        @endif
                        
                        <th style="width: 180px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vendors as $vendor)
                    <tr>
                        <td><strong>#{{ $vendor->idvendor }}</strong></td>
                        <td>
                            <i class="fas fa-building me-2 text-muted"></i>
                            <strong>{{ $vendor->nama_vendor }}</strong>
                        </td>
                        <td>
                            <span class="badge bg-secondary">
                                {{ $vendor->jenis_badan_hukum ?? 'Lainnya' }}
                            </span>
                        </td>
                        
                        @if($filter == 'all')
                            <td>
                                @if($vendor->status == 'A')
                                    <span class="badge badge-status badge-aktif">
                                        <i class="fas fa-check me-1"></i>Aktif
                                    </span>
                                @else
                                    <span class="badge badge-status badge-nonaktif">
                                        <i class="fas fa-times me-1"></i>Nonaktif
                                    </span>
                                @endif
                            </td>
                        @endif
                        
                        <td class="text-center">
                            <a href="{{ route('superadmin.vendor.edit', $vendor->idvendor) }}" 
                               class="btn btn-action btn-edit" 
                               title="Edit Vendor">
                                <i class="fas fa-edit"></i>
                            </a>
                            
                            <form action="{{ route('superadmin.vendor.destroy', $vendor->idvendor) }}" 
                                  method="POST" 
                                  class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="btn btn-action btn-delete" 
                                        title="Hapus Vendor"
                                        onclick="return confirm('Yakin ingin menghapus vendor ini?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ $filter == 'all' ? 5 : 4 }}">
                            <div class="empty-state">
                                <i class="fas fa-truck"></i>
                                <h4>Belum Ada Data Vendor 
                                    @if($filter == 'aktif')
                                        Aktif
                                    @endif
                                </h4>
                                <p>
                                    @if($filter == 'aktif')
                                        Belum ada vendor dengan status aktif. 
                                        <a href="{{ route('superadmin.vendor.index') }}">Lihat semua vendor</a>
                                    @else
                                        Klik tombol "Tambah Vendor" untuk menambahkan vendor baru
                                    @endif
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection