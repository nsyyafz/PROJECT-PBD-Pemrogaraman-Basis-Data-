@extends('layouts.metis.app')

@section('title', 'Detail Penjualan #' . $penjualan->idpenjualan)

@php
    $pageTitle = 'Detail Penjualan #' . $penjualan->idpenjualan;
    $pageDescription = 'Informasi lengkap penjualan barang';
@endphp

@section('page-actions')
<div class="btn-group" role="group">
    <a href="{{ route('superadmin.penjualan.index') }}" class="btn btn-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
    
    <button onclick="window.print()" class="btn btn-success btn-sm">
        <i class="bi bi-printer me-1"></i> Cetak Invoice
    </button>
</div>
@endsection

@section('content')
<!-- Header Info Card -->
<div class="card mb-3">
    <div class="card-header py-2 bg-success text-white">
        <h6 class="card-title mb-0">
            <i class="bi bi-info-circle me-2"></i>Informasi Penjualan
        </h6>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <!-- ID Penjualan -->
            <div class="col-md-2">
                <label class="form-label form-label-sm text-muted mb-1">ID Penjualan</label>
                <div class="fs-5 fw-bold text-success">#{{ $penjualan->idpenjualan }}</div>
            </div>

            <!-- Tanggal -->
            <div class="col-md-2">
                <label class="form-label form-label-sm text-muted mb-1">Tanggal Transaksi</label>
                <div style="font-size: 0.9rem;">
                    <i class="bi bi-calendar3 text-success"></i> {{ date('d/m/Y', strtotime($penjualan->created_at)) }}
                    <br>
                    <small class="text-muted"><i class="bi bi-clock"></i> {{ date('H:i', strtotime($penjualan->created_at)) }}</small>
                </div>
            </div>

            <!-- User -->
            <div class="col-md-2">
                <label class="form-label form-label-sm text-muted mb-1">Kasir</label>
                <div style="font-size: 0.9rem;">
                    <i class="bi bi-person-circle text-success"></i> {{ $penjualan->username }}
                </div>
            </div>

            <!-- Margin -->
            <div class="col-md-2">
                <label class="form-label form-label-sm text-muted mb-1">Margin Penjualan</label>
                <div>
                    <span class="badge bg-primary" style="font-size: 0.9rem;">{{ $penjualan->margin_persen }}%</span>
                </div>
            </div>

            <!-- Summary -->
            <div class="col-md-4">
                <label class="form-label form-label-sm text-muted mb-1">Total Item/Qty</label>
                <div>
                    <span class="badge bg-info me-1">{{ $penjualan->jumlah_item }} item</span>
                    <span class="badge bg-success">{{ $penjualan->total_qty }} qty</span>
                </div>
            </div>
        </div>

        <hr class="my-3">

        <div class="row g-3">
            <!-- Subtotal -->
            <div class="col-md-3">
                <label class="form-label form-label-sm text-muted mb-1">Subtotal</label>
                <div class="fw-bold">Rp {{ number_format($penjualan->subtotal_nilai, 0, ',', '.') }}</div>
            </div>

            <!-- PPN -->
            <div class="col-md-2">
                <label class="form-label form-label-sm text-muted mb-1">PPN ({{ $penjualan->ppn }}%)</label>
                <div class="fw-bold">Rp {{ number_format($penjualan->subtotal_nilai * $penjualan->ppn / 100, 0, ',', '.') }}</div>
            </div>

            <!-- Total -->
            <div class="col-md-3">
                <label class="form-label form-label-sm text-muted mb-1">Total Bayar</label>
                <div class="fs-5 fw-bold text-success">Rp {{ number_format($penjualan->total_nilai, 0, ',', '.') }}</div>
            </div>

            <!-- Keuntungan -->
            <div class="col-md-4">
                <label class="form-label form-label-sm text-muted mb-1">Total Keuntungan</label>
                <div class="fs-5 fw-bold text-warning">
                    <i class="bi bi-graph-up-arrow me-1"></i>Rp {{ number_format($penjualan->total_keuntungan ?? 0, 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Detail Barang Card -->
<div class="card mb-3">
    <div class="card-header py-2 bg-light">
        <h6 class="card-title mb-0">
            <i class="bi bi-box-seam me-2"></i>Detail Barang yang Dijual
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
                        <th style="width: 110px;" class="text-end">Harga Modal</th>
                        <th style="width: 110px;" class="text-end">Harga Jual</th>
                        <th style="width: 100px;" class="text-center">Jumlah</th>
                        <th style="width: 120px;" class="text-end">Subtotal</th>
                        <th style="width: 120px;" class="text-end">Keuntungan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($details as $index => $detail)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $detail->nama_barang }}</strong>
                            <br>
                            <small class="text-muted">{{ $detail->jenis_barang }}</small>
                        </td>
                        <td class="text-center">{{ $detail->nama_satuan }}</td>
                        <td class="text-end">Rp {{ number_format($detail->harga_beli, 0, ',', '.') }}</td>
                        <td class="text-end">
                            <strong class="text-success">Rp {{ number_format($detail->harga_jual, 0, ',', '.') }}</strong>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-primary">{{ $detail->jumlah }}</span>
                        </td>
                        <td class="text-end">
                            <strong>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</strong>
                        </td>
                        <td class="text-end">
                            <strong class="text-warning">Rp {{ number_format($detail->total_keuntungan, 0, ',', '.') }}</strong>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-3 text-muted">
                            <i class="bi bi-inbox"></i> Tidak ada detail barang
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="table-secondary">
                    <tr>
                        <td colspan="6" class="text-end"><strong>Subtotal:</strong></td>
                        <td class="text-end"><strong>Rp {{ number_format($penjualan->subtotal_nilai, 0, ',', '.') }}</strong></td>
                        <td class="text-end"><strong class="text-warning">Rp {{ number_format($penjualan->total_keuntungan ?? 0, 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <td colspan="6" class="text-end"><strong>PPN ({{ $penjualan->ppn }}%):</strong></td>
                        <td class="text-end"><strong>Rp {{ number_format($penjualan->subtotal_nilai * $penjualan->ppn / 100, 0, ',', '.') }}</strong></td>
                        <td></td>
                    </tr>
                    <tr class="table-success">
                        <td colspan="6" class="text-end"><strong>TOTAL:</strong></td>
                        <td class="text-end">
                            <strong class="text-success fs-6">Rp {{ number_format($penjualan->total_nilai, 0, ',', '.') }}</strong>
                        </td>
                        <td class="text-end">
                            <strong class="text-warning fs-6">Rp {{ number_format($penjualan->total_keuntungan ?? 0, 0, ',', '.') }}</strong>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<!-- Info Kartu Stok -->
<div class="alert alert-info">
    <i class="bi bi-info-circle me-2"></i>
    <strong>Informasi:</strong> Stok untuk semua barang di atas sudah otomatis berkurang di Kartu Stok dengan jenis transaksi <strong>"Jual (J)"</strong>.
</div>
@endsection

@push('scripts')
<script>
// Print style
window.addEventListener('beforeprint', function() {
    document.querySelectorAll('.btn, .alert').forEach(el => {
        el.style.display = 'none';
    });
});

window.addEventListener('afterprint', function() {
    document.querySelectorAll('.btn, .alert').forEach(el => {
        el.style.display = '';
    });
});
</script>
@endpush

@push('styles')
<style>
@media print {
    .btn, .alert, .page-actions {
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
    .card-header {
        background-color: #198754 !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
}
</style>
@endpush