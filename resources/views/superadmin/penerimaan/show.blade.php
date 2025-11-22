@extends('layouts.metis.app')

@section('title', 'Detail Penerimaan #' . $penerimaan->idpenerimaan)

@php
    $pageTitle = 'Detail Penerimaan #' . $penerimaan->idpenerimaan;
    $pageDescription = 'Informasi lengkap penerimaan barang';
@endphp

@section('page-actions')
<div class="btn-group" role="group">
    <a href="{{ route('superadmin.penerimaan.index') }}" class="btn btn-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
    
    <a href="{{ route('superadmin.pengadaan.show', $penerimaan->idpengadaan) }}" class="btn btn-info btn-sm">
        <i class="bi bi-eye me-1"></i> Lihat Pengadaan
    </a>
    
    <button onclick="window.print()" class="btn btn-success btn-sm">
        <i class="bi bi-printer me-1"></i> Cetak
    </button>
</div>
@endsection

@section('content')
<!-- Header Info Card -->
<div class="card mb-3">
    <div class="card-header py-2 bg-success text-white">
        <h6 class="card-title mb-0">
            <i class="bi bi-info-circle me-2"></i>Informasi Penerimaan
        </h6>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <!-- ID Penerimaan -->
            <div class="col-md-2">
                <label class="form-label form-label-sm text-muted mb-1">ID Penerimaan</label>
                <div class="fs-5 fw-bold text-info">#{{ $penerimaan->idpenerimaan }}</div>
            </div>

            <!-- Status -->
            <div class="col-md-2">
                <label class="form-label form-label-sm text-muted mb-1">Status</label>
                <div>
                    <span class="badge bg-success" style="font-size: 0.85rem;">
                        <i class="bi bi-check-circle"></i> {{ $penerimaan->status_text }}
                    </span>
                </div>
            </div>

            <!-- Tanggal -->
            <div class="col-md-2">
                <label class="form-label form-label-sm text-muted mb-1">Tanggal Penerimaan</label>
                <div style="font-size: 0.9rem;">
                    <i class="bi bi-calendar3 text-success"></i> {{ date('d/m/Y', strtotime($penerimaan->created_at)) }}
                    <br>
                    <small class="text-muted"><i class="bi bi-clock"></i> {{ date('H:i', strtotime($penerimaan->created_at)) }}</small>
                </div>
            </div>

            <!-- User -->
            <div class="col-md-2">
                <label class="form-label form-label-sm text-muted mb-1">Dibuat Oleh</label>
                <div style="font-size: 0.9rem;">
                    <i class="bi bi-person-circle text-success"></i> {{ $penerimaan->created_by }}
                </div>
            </div>

            <!-- ID Pengadaan -->
            <div class="col-md-2">
                <label class="form-label form-label-sm text-muted mb-1">ID Pengadaan</label>
                <div>
                    <a href="{{ route('superadmin.pengadaan.show', $penerimaan->idpengadaan) }}" 
                       class="fs-5 fw-bold text-primary">
                        #{{ $penerimaan->idpengadaan }}
                    </a>
                </div>
            </div>

            <!-- Summary -->
            <div class="col-md-2">
                <label class="form-label form-label-sm text-muted mb-1">Total Item/Qty</label>
                <div>
                    <span class="badge bg-info me-1">{{ $penerimaan->jumlah_item }} item</span>
                    <span class="badge bg-success">{{ $penerimaan->total_qty_terima }} qty</span>
                </div>
            </div>
        </div>

        <hr class="my-3">

        <div class="row g-3">
            <!-- Vendor Info -->
            <div class="col-md-6">
                <label class="form-label form-label-sm text-muted mb-1">Vendor</label>
                <div>
                    <strong style="font-size: 1rem;">{{ $penerimaan->nama_vendor }}</strong>
                    <br>
                    <small class="text-muted">{{ $penerimaan->badan_hukum ?? '-' }}</small>
                </div>
            </div>

            <!-- Pengadaan Info -->
            <div class="col-md-3">
                <label class="form-label form-label-sm text-muted mb-1">Tanggal Pengadaan</label>
                <div style="font-size: 0.9rem;">
                    <i class="bi bi-calendar3"></i> {{ $penerimaan->tanggal_pengadaan_formatted }}
                </div>
            </div>

            <!-- Total Nilai -->
            <div class="col-md-3">
                <label class="form-label form-label-sm text-muted mb-1">Total Nilai Terima</label>
                <div class="fs-5 fw-bold text-success">
                    Rp {{ number_format($penerimaan->total_nilai_terima ?? 0, 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Detail Barang Card -->
<div class="card mb-3">
    <div class="card-header py-2 bg-light">
        <h6 class="card-title mb-0">
            <i class="bi bi-box-seam me-2"></i>Detail Barang yang Diterima
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
                        <th style="width: 80px;" class="text-center">Dipesan</th>
                        <th style="width: 90px;" class="text-center">Diterima Kali Ini</th>
                        <th style="width: 90px;" class="text-center">Total Diterima</th>
                        <th style="width: 70px;" class="text-center">Sisa</th>
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
                        <td class="text-end">Rp {{ number_format($detail->harga_satuan_terima, 0, ',', '.') }}</td>
                        <td class="text-center">
                            <span class="badge bg-primary">{{ $detail->jumlah_pesan }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-info">{{ $detail->jumlah_terima }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-success">{{ $detail->total_diterima_barang_ini }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-{{ $detail->sisa_belum_terima > 0 ? 'warning' : 'secondary' }}">
                                {{ $detail->sisa_belum_terima }}
                            </span>
                        </td>
                        <td class="text-end">
                            <strong>Rp {{ number_format($detail->sub_total_terima, 0, ',', '.') }}</strong>
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
                <tfoot class="table-success">
                    <tr>
                        <td colspan="8" class="text-end"><strong>TOTAL:</strong></td>
                        <td class="text-end">
                            <strong class="text-success fs-6">Rp {{ number_format($penerimaan->total_nilai_terima ?? 0, 0, ',', '.') }}</strong>
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
    <strong>Informasi:</strong> Stok untuk semua barang di atas sudah otomatis terupdate di Kartu Stok dengan jenis transaksi <strong>"Terima (T)"</strong>.
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
}
</style>
@endpush