{{-- resources/views/superadmin/dashboard-superadmin.blade.php --}}
@extends('layouts.metis.app')

@section('title', 'Dashboard SuperAdmin')

@php
    $pageTitle = 'Dashboard SuperAdmin';
    $pageDescription = 'Selamat datang kembali! Berikut ringkasan sistem periode ' . date('d M Y', strtotime($tanggalDari)) . ' - ' . date('d M Y', strtotime($tanggalSampai));
@endphp

@section('page-actions')
<div class="d-flex gap-2">
    <button type="button" class="btn btn-outline-secondary" 
            onclick="window.location.reload()"
            data-bs-toggle="tooltip" 
            title="Refresh data">
        <i class="bi bi-arrow-clockwise icon-hover"></i>
    </button>
    <div class="dropdown">
        <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
            <i class="bi bi-plus-lg me-2"></i>
            Tambah Data
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{{ route('superadmin.barang.create') }}">
                <i class="bi bi-box me-2"></i>Tambah Barang
            </a></li>
            <li><a class="dropdown-item" href="{{ route('superadmin.user.create') }}">
                <i class="bi bi-person me-2"></i>Tambah User
            </a></li>
            <li><a class="dropdown-item" href="{{ route('superadmin.vendor.create') }}">
                <i class="bi bi-truck me-2"></i>Tambah Vendor
            </a></li>
        </ul>
    </div>
</div>
@endsection

@section('content')
<!-- Stats Section: System Management -->
<h5 class="fw-bold text-muted text-uppercase mb-3" style="font-size: 12px; letter-spacing: 1px;">
    <i class="bi bi-gear me-2"></i>System Management
</h5>

<div class="row g-4 mb-4">
    <div class="col-xl-3 col-lg-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="stats-icon bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-people"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-0 text-muted">Total Users</h6>
                        <h3 class="mb-0">{{ number_format($stats['user']) }}</h3>
                        <small class="text-primary">
                            <i class="bi bi-arrow-up"></i> Active
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="stats-icon bg-info bg-opacity-10 text-info">
                            <i class="bi bi-person-badge"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-0 text-muted">Total Role</h6>
                        <h3 class="mb-0">{{ number_format($stats['role']) }}</h3>
                        <small class="text-info">
                            <i class="bi bi-shield-check"></i> Secured
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="stats-icon bg-success bg-opacity-10 text-success">
                            <i class="bi bi-box"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-0 text-muted">Total Barang</h6>
                        <h3 class="mb-0">{{ number_format($stats['barang']) }}</h3>
                        <small class="text-success">
                            <i class="bi bi-arrow-up"></i> In Stock
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="stats-icon bg-warning bg-opacity-10 text-warning">
                            <i class="bi bi-truck"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-0 text-muted">Total Vendor</h6>
                        <h3 class="mb-0">{{ number_format($stats['vendor']) }}</h3>
                        <small class="text-warning">
                            <i class="bi bi-handshake"></i> Partner
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Section: Transaction Management -->
<h5 class="fw-bold text-muted text-uppercase mb-3 mt-4" style="font-size: 12px; letter-spacing: 1px;">
    <i class="bi bi-arrow-left-right me-2"></i>Transaction Management
</h5>

<div class="row g-4 mb-4">
    <div class="col-xl-3 col-lg-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="stats-icon bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-cart-plus"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-0 text-muted">Pengadaan</h6>
                        <h3 class="mb-0">{{ number_format($stats['pengadaan']) }}</h3>
                        <small class="text-primary d-block">
                            Selesai: {{ number_format($detailStats['pengadaan']['selesai']) }} | 
                            Proses: {{ number_format($detailStats['pengadaan']['diproses']) }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="stats-icon bg-info bg-opacity-10 text-info">
                            <i class="bi bi-inbox"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-0 text-muted">Penerimaan</h6>
                        <h3 class="mb-0">{{ number_format($stats['penerimaan']) }}</h3>
                        <small class="text-info d-block">
                            Hari Ini: {{ number_format($detailStats['penerimaan']['penerimaan_hari_ini']) }} | 
                            Bulan: {{ number_format($detailStats['penerimaan']['penerimaan_bulan_ini']) }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="stats-icon bg-success bg-opacity-10 text-success">
                            <i class="bi bi-cart-check"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-0 text-muted">Penjualan</h6>
                        <h3 class="mb-0">{{ number_format($stats['penjualan']) }}</h3>
                        <small class="text-success d-block">
                            <i class="bi bi-currency-dollar"></i> 
                            Rp {{ number_format($detailStats['penjualan']['total_nilai'], 0, ',', '.') }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="stats-icon bg-warning bg-opacity-10 text-warning">
                            <i class="bi bi-arrow-return-left"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-0 text-muted">Retur</h6>
                        <h3 class="mb-0">{{ number_format($stats['retur']) }}</h3>
                        <small class="text-warning">
                            <i class="bi bi-info-circle"></i> Total Return
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Section: Additional Info -->
<h5 class="fw-bold text-muted text-uppercase mb-3 mt-4" style="font-size: 12px; letter-spacing: 1px;">
    <i class="bi bi-info-circle me-2"></i>Additional Information
</h5>

<div class="row g-4 mb-4">
    <div class="col-xl-3 col-lg-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="stats-icon bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-rulers"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-0 text-muted">Total Satuan</h6>
                        <h3 class="mb-0">{{ number_format($stats['satuan']) }}</h3>
                        <small class="text-primary">
                            <i class="bi bi-tag"></i> Units
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="stats-icon bg-info bg-opacity-10 text-info">
                            <i class="bi bi-percent"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-0 text-muted">Total Margin</h6>
                        <h3 class="mb-0">{{ number_format($stats['margin']) }}</h3>
                        <small class="text-info">
                            <i class="bi bi-graph-up"></i> Profit
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="stats-icon bg-success bg-opacity-10 text-success">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-0 text-muted">Kartu Stok</h6>
                        <h3 class="mb-0">{{ number_format($stats['kartu_stok']) }}</h3>
                        <small class="text-success">
                            <i class="bi bi-file-text"></i> Records
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="stats-icon bg-warning bg-opacity-10 text-warning">
                            <i class="bi bi-award"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-0 text-muted">Your Role</h6>
                        <h3 class="mb-0" style="font-size: 1.25rem;">SuperAdmin</h3>
                        <small class="text-warning">
                            <i class="bi bi-star-fill"></i> Full Access
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Activity Statistics Today -->
<h5 class="fw-bold text-muted text-uppercase mb-3 mt-4" style="font-size: 12px; letter-spacing: 1px;">
    <i class="bi bi-activity me-2"></i>Activity Statistics
</h5>

<div class="row g-4 mb-4">
    <div class="col-xl-3 col-lg-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="stats-icon bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-calendar-day"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-0 text-muted">Activity Today</h6>
                        <h3 class="mb-0">{{ number_format($stats['activity_today']) }}</h3>
                        <small class="text-primary">
                            <i class="bi bi-clock"></i> Real-time
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="stats-icon bg-info bg-opacity-10 text-info">
                            <i class="bi bi-calendar-week"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-0 text-muted">Activity Week</h6>
                        <h3 class="mb-0">{{ number_format($stats['activity_week']) }}</h3>
                        <small class="text-info">
                            <i class="bi bi-graph-up"></i> This Week
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="stats-icon bg-success bg-opacity-10 text-success">
                            <i class="bi bi-calendar-month"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-0 text-muted">Activity Month</h6>
                        <h3 class="mb-0">{{ number_format($stats['activity_month']) }}</h3>
                        <small class="text-success">
                            <i class="bi bi-calendar-check"></i> This Month
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="stats-icon bg-warning bg-opacity-10 text-warning">
                            <i class="bi bi-bar-chart"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-0 text-muted">Total Activity</h6>
                        <h3 class="mb-0">{{ number_format($detailStats['activity']['total']) }}</h3>
                        <small class="text-warning">
                            <i class="bi bi-database"></i> All Time
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts & Activity Section -->
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Recent Activities</h5>
                <div class="d-flex gap-2">
                    <select class="form-select form-select-sm" id="activityFilter" style="width: auto;">
                        <option value="all">Semua</option>
                        <option value="Pengadaan">Pengadaan</option>
                        <option value="Penerimaan">Penerimaan</option>
                        <option value="Penjualan">Penjualan</option>
                    </select>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="15%">Jenis</th>
                                <th width="20%">ID Transaksi</th>
                                <th>Deskripsi</th>
                                <th width="15%">User</th>
                                <th width="15%">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentActivities as $activity)
                            <tr>
                                <td>
                                    @if($activity->jenis_aktivitas == 'Pengadaan')
                                        <span class="badge bg-purple">{{ $activity->jenis_aktivitas }}</span>
                                    @elseif($activity->jenis_aktivitas == 'Penerimaan')
                                        <span class="badge bg-cyan">{{ $activity->jenis_aktivitas }}</span>
                                    @elseif($activity->jenis_aktivitas == 'Penjualan')
                                        <span class="badge bg-success">{{ $activity->jenis_aktivitas }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $activity->jenis_aktivitas }}</span>
                                    @endif
                                </td>
                                <td><code>{{ $activity->id }}</code></td>
                                <td>{{ $activity->deskripsi }}</td>
                                <td>
                                    <small class="text-muted">
                                        <i class="bi bi-person"></i> {{ $activity->created_by ?? 'System' }}
                                    </small>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ date('d M Y H:i', strtotime($activity->tanggal)) }}
                                    </small>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    Belum ada aktivitas
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Today's Activity</h5>
            </div>
            <div class="card-body">
                <div class="activity-feed">
                    @forelse($activityToday as $activity)
                    <div class="activity-item">
                        @if($activity->jenis_aktivitas == 'Pengadaan')
                            <div class="activity-icon bg-primary bg-opacity-10 text-primary">
                                <i class="bi bi-cart-plus"></i>
                            </div>
                        @elseif($activity->jenis_aktivitas == 'Penerimaan')
                            <div class="activity-icon bg-info bg-opacity-10 text-info">
                                <i class="bi bi-inbox"></i>
                            </div>
                        @elseif($activity->jenis_aktivitas == 'Penjualan')
                            <div class="activity-icon bg-success bg-opacity-10 text-success">
                                <i class="bi bi-cart-check"></i>
                            </div>
                        @else
                            <div class="activity-icon bg-secondary bg-opacity-10 text-secondary">
                                <i class="bi bi-activity"></i>
                            </div>
                        @endif
                        <div class="activity-content">
                            <p class="mb-1 fw-semibold">{{ $activity->deskripsi }}</p>
                            <small class="text-muted d-block">
                                <i class="bi bi-hash"></i> {{ $activity->id }}
                            </small>
                            <small class="text-muted">
                                <i class="bi bi-clock"></i> {{ date('H:i', strtotime($activity->tanggal)) }}
                            </small>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-calendar-x fs-1 d-block mb-2"></i>
                        <small>Belum ada aktivitas hari ini</small>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Transaction Summary Today -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Today's Summary</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                    <div>
                        <small class="text-muted d-block">Pengadaan</small>
                        <h5 class="mb-0">{{ number_format($detailStats['activity']['pengadaan_today']) }}</h5>
                    </div>
                    <div class="text-end">
                        <i class="bi bi-cart-plus fs-3 text-purple"></i>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                    <div>
                        <small class="text-muted d-block">Penerimaan</small>
                        <h5 class="mb-0">{{ number_format($detailStats['activity']['penerimaan_today']) }}</h5>
                    </div>
                    <div class="text-end">
                        <i class="bi bi-inbox fs-3 text-cyan"></i>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted d-block">Penjualan</small>
                        <h5 class="mb-0">{{ number_format($detailStats['activity']['penjualan_today']) }}</h5>
                    </div>
                    <div class="text-end">
                        <i class="bi bi-cart-check fs-3 text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Custom Colors */
    .bg-purple { background-color: #8b5cf6 !important; }
    .text-purple { color: #8b5cf6 !important; }
    .bg-cyan { background-color: #06b6d4 !important; }
    .text-cyan { color: #06b6d4 !important; }

    /* Activity Feed */
    .activity-feed {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .activity-item {
        display: flex;
        gap: 1rem;
        align-items: start;
    }

    .activity-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.5rem;
        flex-shrink: 0;
    }

    .activity-content p {
        font-size: 0.9rem;
    }

    /* Stats Card Hover Effect */
    .stats-card {
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
    }

    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
    }

    .stats-icon {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.5rem;
        font-size: 1.5rem;
    }
</style>
@endpush

@push('scripts')
<script>
    // Activity Filter
    document.getElementById('activityFilter')?.addEventListener('change', function() {
        const filterValue = this.value;
        const rows = document.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            if (filterValue === 'all') {
                row.style.display = '';
            } else {
                const badge = row.querySelector('.badge');
                if (badge && badge.textContent.trim() === filterValue) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    });

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
</script>
@endpush