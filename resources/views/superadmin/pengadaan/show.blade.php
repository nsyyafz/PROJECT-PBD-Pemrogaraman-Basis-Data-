@extends('layouts.metis.app')

@section('title', 'Detail Pengadaan #' . $pengadaan->idpengadaan)

@php
    $pageTitle = 'Detail Pengadaan #' . $pengadaan->idpengadaan;
    $pageDescription = 'Informasi lengkap pengadaan barang';
@endphp

@section('page-actions')
<div class="btn-group" role="group">
    <a href="{{ route('superadmin.pengadaan.index') }}" class="btn btn-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
    
    @if($pengadaan->status === 'D')
        <a href="{{ route('superadmin.pengadaan.edit', $pengadaan->idpengadaan) }}" class="btn btn-warning btn-sm">
            <i class="bi bi-pencil-square me-1"></i> Edit
        </a>
    @endif
    
    <button onclick="window.print()" class="btn btn-info btn-sm">
        <i class="bi bi-printer me-1"></i> Cetak
    </button>
</div>
@endsection

@section('content')
<!-- Header Info Card -->
<div class="card mb-3">
    <div class="card-header py-2 bg-primary text-white">
        <h6 class="card-title mb-0">
            <i class="bi bi-info-circle me-2"></i>Informasi Pengadaan
        </h6>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <!-- ID Pengadaan -->
            <div class="col-md-2">
                <label class="form-label form-label-sm text-muted mb-1">ID Pengadaan</label>
                <div class="fs-5 fw-bold text-primary">#{{ $pengadaan->idpengadaan }}</div>
            </div>

            <!-- Status -->
            <div class="col-md-2">
                <label class="form-label form-label-sm text-muted mb-1">Status</label>
                <div>
                    @if($pengadaan->status === 'D')
                        <span class="badge bg-warning text-dark" style="font-size: 0.85rem;">
                            <i class="bi bi-hourglass-split"></i> Diproses
                        </span>
                    @elseif($pengadaan->status === 'P')
                        <span class="badge bg-info" style="font-size: 0.85rem;">
                            <i class="bi bi-pie-chart"></i> Sebagian
                        </span>
                    @elseif($pengadaan->status === 'S')
                        <span class="badge bg-success" style="font-size: 0.85rem;">
                            <i class="bi bi-check-circle"></i> Selesai
                        </span>
                    @endif
                </div>
            </div>

            <!-- Tanggal -->
            <div class="col-md-2">
                <label class="form-label form-label-sm text-muted mb-1">Tanggal Transaksi</label>
                <div style="font-size: 0.9rem;">
                    <i class="bi bi-calendar3 text-primary"></i> {{ date('d/m/Y', strtotime($pengadaan->timestamp)) }}
                    <br>
                    <small class="text-muted"><i class="bi bi-clock"></i> {{ date('H:i', strtotime($pengadaan->timestamp)) }}</small>
                </div>
            </div>

            <!-- User -->
            <div class="col-md-2">
                <label class="form-label form-label-sm text-muted mb-1">Dibuat Oleh</label>
                <div style="font-size: 0.9rem;">
                    <i class="bi bi-person-circle text-primary"></i> {{ $pengadaan->username ?? '-' }}
                </div>
            </div>

            <!-- Progress -->
            <div class="col-md-4">
                <label class="form-label form-label-sm text-muted mb-1">Progress Penerimaan</label>
                <div class="progress" style="height: 22px;">
                    <div class="progress-bar {{ $pengadaan->persentase_diterima >= 100 ? 'bg-success' : ($pengadaan->persentase_diterima > 0 ? 'bg-info' : 'bg-warning') }}" 
                         role="progressbar" 
                         style="width: {{ $pengadaan->persentase_diterima }}%; font-size: 0.8rem;">
                        {{ number_format($pengadaan->persentase_diterima, 0) }}%
                    </div>
                </div>
                <small class="text-muted">
                    {{ $pengadaan->total_qty_diterima ?? 0 }}/{{ $pengadaan->total_qty_pesan }} item diterima
                </small>
            </div>
        </div>

        <hr class="my-3">

        <div class="row g-3">
            <!-- Vendor Info -->
            <div class="col-md-6">
                <label class="form-label form-label-sm text-muted mb-1">Vendor</label>
                <div>
                    <strong style="font-size: 1rem;">{{ $pengadaan->nama_vendor }}</strong>
                    <br>
                    <small class="text-muted">{{ $pengadaan->badan_hukum }}</small>
                </div>
            </div>

            <!-- Summary Nilai -->
            <div class="col-md-6">
                <div class="row g-2">
                    <div class="col-4">
                        <label class="form-label form-label-sm text-muted mb-1">Subtotal</label>
                        <div class="fw-bold">Rp {{ number_format($pengadaan->subtotal_nilai, 0, ',', '.') }}</div>
                    </div>
                    <div class="col-3">
                        <label class="form-label form-label-sm text-muted mb-1">PPN ({{ $pengadaan->ppn }}%)</label>
                        <div class="fw-bold">Rp {{ number_format($pengadaan->subtotal_nilai * $pengadaan->ppn / 100, 0, ',', '.') }}</div>
                    </div>
                    <div class="col-5">
                        <label class="form-label form-label-sm text-muted mb-1">Total Nilai</label>
                        <div class="fs-5 fw-bold text-success">Rp {{ number_format($pengadaan->total_nilai, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Detail Barang Card -->
<div class="card mb-3">
    <div class="card-header py-2 bg-light">
        <h6 class="card-title mb-0">
            <i class="bi bi-box-seam me-2"></i>Detail Barang yang Dipesan
        </h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-sm mb-0" style="font-size: 0.8rem;">
                <thead class="table-light">
                    <tr>
                        <th style="width: 35px;" class="text-center">No</th>
                        <th>Nama Barang</th>
                        <th style="width: 80px;" class="text-center">Satuan</th>
                        <th style="width: 110px;" class="text-end">Harga</th>
                        <th style="width: 80px;" class="text-center">Pesan</th>
                        <th style="width: 80px;" class="text-center">Diterima</th>
                        <th style="width: 70px;" class="text-center">Sisa</th>
                        <th style="width: 100px;" class="text-center">Status</th>
                        <th style="width: 120px;" class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($details as $index => $detail)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $detail->nama_barang }}</strong>
                            <br>
                            <small class="text-muted">{{ $detail->jenis }}</small>
                        </td>
                        <td class="text-center">{{ $detail->nama_satuan }}</td>
                        <td class="text-end">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                        <td class="text-center">
                            <span class="badge bg-primary">{{ $detail->jumlah_pesan }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-success">{{ $detail->jumlah_diterima ?? 0 }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-{{ $detail->sisa_belum_terima > 0 ? 'warning' : 'secondary' }}">
                                {{ $detail->sisa_belum_terima ?? 0 }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if($detail->status_item == 'Lengkap')
                                <span class="badge bg-success" style="font-size: 0.7rem;">
                                    <i class="bi bi-check-circle"></i> Lengkap
                                </span>
                            @elseif($detail->status_item == 'Sebagian')
                                <span class="badge bg-info" style="font-size: 0.7rem;">
                                    <i class="bi bi-pie-chart"></i> {{ number_format($detail->persentase_diterima, 0) }}%
                                </span>
                            @else
                                <span class="badge bg-warning text-dark" style="font-size: 0.7rem;">
                                    <i class="bi bi-hourglass"></i> Belum
                                </span>
                            @endif
                        </td>
                        <td class="text-end">
                            <strong>Rp {{ number_format($detail->sub_total, 0, ',', '.') }}</strong>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-3 text-muted">
                            <i class="bi bi-inbox"></i> Tidak ada detail barang
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="table-secondary">
                    <tr>
                        <td colspan="8" class="text-end"><strong>Subtotal:</strong></td>
                        <td class="text-end"><strong>Rp {{ number_format($pengadaan->subtotal_nilai, 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <td colspan="8" class="text-end"><strong>PPN ({{ $pengadaan->ppn }}%):</strong></td>
                        <td class="text-end"><strong>Rp {{ number_format($pengadaan->subtotal_nilai * $pengadaan->ppn / 100, 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr class="table-primary">
                        <td colspan="8" class="text-end"><strong>TOTAL:</strong></td>
                        <td class="text-end">
                            <strong class="text-success fs-6">Rp {{ number_format($pengadaan->total_nilai, 0, ',', '.') }}</strong>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<!-- Riwayat Penerimaan Card (jika ada) -->
@if(count($penerimaans) > 0)
<div class="card">
    <div class="card-header py-2 bg-light">
        <h6 class="card-title mb-0">
            <i class="bi bi-truck me-2"></i>Riwayat Penerimaan Barang
        </h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-sm mb-0" style="font-size: 0.85rem;">
                <thead class="table-light">
                    <tr>
                        <th style="width: 40px;" class="text-center">No</th>
                        <th style="width: 100px;">ID Penerimaan</th>
                        <th style="width: 150px;">Tanggal</th>
                        <th>Dibuat Oleh</th>
                        <th style="width: 100px;" class="text-center">Jumlah Item</th>
                        <th style="width: 120px;" class="text-center">Total Qty</th>
                        <th style="width: 80px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($penerimaans as $index => $penerimaan)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td><strong class="text-info">#{{ $penerimaan->idpenerimaan }}</strong></td>
                        <td>
                            <i class="bi bi-calendar3"></i> {{ date('d/m/Y H:i', strtotime($penerimaan->created_at)) }}
                        </td>
                        <td>{{ $penerimaan->dibuat_oleh }}</td>
                        <td class="text-center">
                            <span class="badge bg-info">{{ $penerimaan->jumlah_item }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-success">{{ $penerimaan->total_qty_terima }}</span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('superadmin.penerimaan.show', $penerimaan->idpenerimaan) }}" 
                               class="btn btn-info btn-sm"
                               data-bs-toggle="tooltip"
                               title="Detail Penerimaan">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@else
<div class="card">
    <div class="card-header py-2 bg-light">
        <h6 class="card-title mb-0">
            <i class="bi bi-truck me-2"></i>Riwayat Penerimaan Barang
        </h6>
    </div>
    <div class="card-body text-center py-4">
        <i class="bi bi-truck display-1 text-muted opacity-25"></i>
        <p class="mt-3 text-muted mb-0">Belum ada penerimaan barang untuk pengadaan ini</p>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// Print style
window.addEventListener('beforeprint', function() {
    document.querySelectorAll('.btn, .card-header').forEach(el => {
        el.style.display = 'none';
    });
});

window.addEventListener('afterprint', function() {
    document.querySelectorAll('.btn, .card-header').forEach(el => {
        el.style.display = '';
    });
});
</script>
@endpush

@push('styles')
<style>
@media print {
    .btn, .page-actions, .card-header {
        display: none !important;
    }
    .card {
        border: 1px solid #000 !important;
        box-shadow: none !important;
        page-break-inside: avoid;
    }
    body {
        font-size: 12pt;
    }
}
</style>
@endpush