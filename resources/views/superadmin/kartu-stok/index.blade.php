@extends('layouts.metis.app')

@section('title', 'Kartu Stok')

@php
    $pageTitle = 'Kartu Stok';
    $pageDescription = 'Riwayat pergerakan stok barang';
@endphp

@section('content')
<!-- Alert Messages -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-x-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Filter Tabs -->
<div class="card mb-3">
    <div class="card-body py-2">
        <div class="btn-group" role="group">
            <a href="{{ route('superadmin.kartu-stok.index', ['filter' => 'all']) }}" 
               class="btn btn-sm {{ ($filter ?? 'all') == 'all' ? 'btn-primary' : 'btn-outline-primary' }}">
                <i class="bi bi-list-ul me-1"></i> Semua
                <span class="badge bg-light text-dark ms-1">{{ count($kartuStoks) }}</span>
            </a>
            <a href="{{ route('superadmin.kartu-stok.index', ['filter' => 'penerimaan']) }}" 
               class="btn btn-sm {{ ($filter ?? 'all') == 'penerimaan' ? 'btn-success' : 'btn-outline-success' }}">
                <i class="bi bi-box-arrow-in-down me-1"></i> Terima
            </a>
            <a href="{{ route('superadmin.kartu-stok.index', ['filter' => 'penjualan']) }}" 
               class="btn btn-sm {{ ($filter ?? 'all') == 'penjualan' ? 'btn-danger' : 'btn-outline-danger' }}">
                <i class="bi bi-box-arrow-up me-1"></i> Jual
            </a>
        </div>
    </div>
</div>

<!-- Table Card -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center py-2">
        <h6 class="card-title mb-0">
            <i class="bi bi-journal-text me-2"></i>Riwayat Kartu Stok
        </h6>
        <span class="badge bg-primary">{{ count($kartuStoks) }} transaksi</span>
    </div>
    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped table-sm mb-0 align-middle" style="font-size: 0.75rem;">
                <thead class="table-light" style="font-size: 0.75rem;">
                    <tr>
                        <th style="width: 60px;" class="text-center">ID</th>
                        <th style="width: 85px;">Tanggal</th>
                        <th style="width: 60px;" class="text-center">Jenis</th>
                        <th style="width: 80px;" class="text-center">ID Transaksi</th>
                        <th>Barang</th>
                        <th style="width: 70px;" class="text-center">Masuk</th>
                        <th style="width: 70px;" class="text-center">Keluar</th>
                        <th style="width: 80px;" class="text-center">Stok</th>
                        <th style="width: 150px;">Info</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kartuStoks as $stok)
                    <tr>
                        <td class="text-center">
                            <span class="badge bg-secondary" style="font-size: 0.65rem;">{{ $stok->idkartu_stok }}</span>
                        </td>
                        <td>
                            <div style="font-size: 0.7rem;">
                                <i class="bi bi-calendar3"></i> {{ date('d/m/y', strtotime($stok->created_at)) }}
                            </div>
                            <div style="font-size: 0.65rem;">
                                <span class="text-muted"><i class="bi bi-clock"></i> {{ date('H:i', strtotime($stok->created_at)) }}</span>
                            </div>
                        </td>
                        <td class="text-center">
                            @if($stok->jenis_transaksi == 'T')
                                <span class="badge bg-success" style="font-size: 0.7rem;">T</span>
                            @elseif($stok->jenis_transaksi == 'J')
                                <span class="badge bg-danger" style="font-size: 0.7rem;">J</span>
                            @else
                                <span class="badge bg-secondary" style="font-size: 0.7rem;">?</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <strong class="text-primary" style="font-size: 0.75rem;">#{{ $stok->idtransaksi }}</strong>
                        </td>
                        <td>
                            <div style="font-size: 0.75rem;">
                                <strong>{{ $stok->nama_barang }}</strong>
                            </div>
                            <small class="text-muted" style="font-size: 0.65rem;">
                                {{ $stok->jenis_barang == 'M' ? 'Makanan' : 'Non-Makanan' }} | {{ $stok->nama_satuan }}
                            </small>
                        </td>
                        <td class="text-center">
                            @if($stok->masuk > 0)
                                <span class="badge bg-success" style="font-size: 0.65rem;">+{{ $stok->masuk }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($stok->keluar > 0)
                                <span class="badge bg-danger" style="font-size: 0.65rem;">-{{ $stok->keluar }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <strong class="text-primary" style="font-size: 0.75rem;">{{ $stok->stok_akhir }}</strong>
                        </td>
                        <td>
                            @if($stok->jenis_transaksi == 'T')
                                <div style="font-size: 0.7rem;">
                                    <span class="text-muted">Penerimaan #{{ $stok->idtransaksi }}</span>
                                </div>
                                @if(isset($stok->nama_vendor))
                                    <small class="text-muted" style="font-size: 0.65rem;">
                                        <i class="bi bi-shop"></i> {{ Str::limit($stok->nama_vendor, 20) }}
                                    </small>
                                @endif
                            @elseif($stok->jenis_transaksi == 'J')
                                <div style="font-size: 0.7rem;">
                                    <span class="text-muted">Penjualan #{{ $stok->idtransaksi }}</span>
                                </div>
                                @if(isset($stok->created_by))
                                    <small class="text-muted" style="font-size: 0.65rem;">
                                        <i class="bi bi-person"></i> {{ $stok->created_by }}
                                    </small>
                                @endif
                            @else
                                <span class="text-muted" style="font-size: 0.7rem;">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-inbox display-1 opacity-25"></i>
                                <p class="mt-3 mb-3">
                                    @if(($filter ?? 'all') == 'penerimaan')
                                        Tidak ada transaksi penerimaan
                                    @elseif(($filter ?? 'all') == 'penjualan')
                                        Tidak ada transaksi penjualan
                                    @else
                                        Tidak ada data kartu stok
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

<!-- Info Card -->
<div class="card mt-3">
    <div class="card-body py-2">
        <div class="row g-2">
            <div class="col-md-4">
                <div class="d-flex align-items-center">
                    <span class="badge bg-success me-2">T</span>
                    <small><strong>Terima:</strong> Stok masuk dari penerimaan</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-flex align-items-center">
                    <span class="badge bg-danger me-2">J</span>
                    <small><strong>Jual:</strong> Stok keluar untuk penjualan</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-flex align-items-center">
                    <span class="badge bg-primary me-2"><i class="bi bi-journal-text"></i></span>
                    <small><strong>Stok:</strong> Sisa stok setelah transaksi</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto dismiss alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        var alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            var bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});
</script>
@endpush