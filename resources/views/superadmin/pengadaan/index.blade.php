@extends('layouts.metis.app')

@section('title', 'Daftar Pengadaan')

@php
    $pageTitle = 'Daftar Pengadaan';
    $pageDescription = 'Kelola data pengadaan barang';
@endphp

@section('page-actions')
<a href="{{ route('superadmin.pengadaan.create') }}" class="btn btn-primary btn-sm">
    <i class="bi bi-plus-circle me-1"></i> Buat Pengadaan Baru
</a>
@endsection

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

@if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Filter Tabs -->
<div class="card mb-3">
    <div class="card-body py-2">
        <div class="btn-group" role="group">
            <a href="{{ route('superadmin.pengadaan.index', ['status' => 'all']) }}" 
               class="btn btn-sm {{ ($filterStatus ?? 'all') == 'all' ? 'btn-primary' : 'btn-outline-primary' }}">
                <i class="bi bi-list-ul me-1"></i> Semua
                <span class="badge bg-light text-dark ms-1">{{ $stats['all'] }}</span>
            </a>
            <a href="{{ route('superadmin.pengadaan.index', ['status' => 'diproses']) }}" 
               class="btn btn-sm {{ ($filterStatus ?? 'all') == 'diproses' ? 'btn-warning' : 'btn-outline-warning' }}">
                <i class="bi bi-hourglass-split me-1"></i> Diproses
                <span class="badge bg-light text-dark ms-1">{{ $stats['diproses'] }}</span>
            </a>
            <a href="{{ route('superadmin.pengadaan.index', ['status' => 'sebagian']) }}" 
               class="btn btn-sm {{ ($filterStatus ?? 'all') == 'sebagian' ? 'btn-info' : 'btn-outline-info' }}">
                <i class="bi bi-pie-chart me-1"></i> Sebagian
                <span class="badge bg-light text-dark ms-1">{{ $stats['sebagian'] }}</span>
            </a>
            <a href="{{ route('superadmin.pengadaan.index', ['status' => 'selesai']) }}" 
               class="btn btn-sm {{ ($filterStatus ?? 'all') == 'selesai' ? 'btn-success' : 'btn-outline-success' }}">
                <i class="bi bi-check-circle me-1"></i> Selesai
                <span class="badge bg-light text-dark ms-1">{{ $stats['selesai'] }}</span>
            </a>
        </div>
    </div>
</div>

<!-- Table Card -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center py-2">
        <h6 class="card-title mb-0">
            <i class="bi bi-cart-check me-2"></i>Data Pengadaan
        </h6>
        <span class="badge bg-primary">Total: {{ count($pengadaans) }} data</span>
    </div>
    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped table-sm mb-0 align-middle" style="font-size: 0.75rem;">
                <thead class="table-light" style="font-size: 0.75rem;">
                    <tr>
                        <th style="width: 45px;">ID</th>
                        <th style="width: 85px;">Tanggal</th>
                        <th style="width: 150px;">Vendor</th>
                        <th style="width: 80px;" class="text-center">Status</th>
                        <th style="width: 105px;" class="text-end">Subtotal</th>
                        <th style="width: 45px;" class="text-center">PPN</th>
                        <th style="width: 105px;" class="text-end">Total</th>
                        <th style="width: 110px;" class="text-center">Progress</th>
                        <th style="width: 100px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengadaans as $item)
                    <tr>
                        <td>
                            <strong class="text-primary">#{{ $item->idpengadaan }}</strong>
                        </td>
                        <td>
                            <div style="font-size: 0.7rem;">
                                <i class="bi bi-calendar3"></i> {{ date('d/m/y', strtotime($item->timestamp)) }}
                            </div>
                            <div style="font-size: 0.65rem;">
                                <span class="text-muted"><i class="bi bi-clock"></i> {{ date('H:i', strtotime($item->timestamp)) }}</span>
                            </div>
                        </td>
                        <td>
                            <div style="font-size: 0.75rem;">
                                <strong>{{ $item->nama_vendor }}</strong>
                            </div>
                            <small class="text-muted" style="font-size: 0.65rem;">{{ $item->badan_hukum }}</small>
                        </td>
                        <td class="text-center">
                            @if($item->status === 'D')
                                <span class="badge bg-warning text-dark" style="font-size: 0.6rem;">
                                    <i class="bi bi-hourglass-split"></i> Diproses
                                </span>
                            @elseif($item->status === 'P')
                                <span class="badge bg-info" style="font-size: 0.6rem;">
                                    <i class="bi bi-pie-chart"></i> Sebagian
                                </span>
                            @elseif($item->status === 'S')
                                <span class="badge bg-success" style="font-size: 0.6rem;">
                                    <i class="bi bi-check-circle"></i> Selesai
                                </span>
                            @else
                                <span class="badge bg-secondary" style="font-size: 0.6rem;">{{ $item->status }}</span>
                            @endif
                        </td>
                        <td class="text-end" style="font-size: 0.7rem;">
                            Rp {{ number_format($item->subtotal_nilai, 0, ',', '.') }}
                        </td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark" style="font-size: 0.65rem;">{{ $item->ppn }}%</span>
                        </td>
                        <td class="text-end">
                            <strong class="text-primary" style="font-size: 0.75rem;">Rp {{ number_format($item->total_nilai, 0, ',', '.') }}</strong>
                        </td>
                        <td>
                            <div class="progress mb-1" style="height: 14px;">
                                <div class="progress-bar {{ $item->persentase_diterima >= 100 ? 'bg-success' : ($item->persentase_diterima > 0 ? 'bg-info' : 'bg-warning') }}" 
                                     role="progressbar" 
                                     style="width: {{ $item->persentase_diterima }}%; font-size: 0.6rem;">
                                    {{ number_format($item->persentase_diterima, 0) }}%
                                </div>
                            </div>
                            <small class="text-muted" style="font-size: 0.65rem;">
                                {{ $item->total_qty_diterima ?? 0 }}/{{ $item->total_qty_pesan }}
                            </small>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group">
                                <!-- Detail - Selalu tampil -->
                                <a href="{{ route('superadmin.pengadaan.show', $item->idpengadaan) }}" 
                                   class="btn btn-info btn-sm"
                                   data-bs-toggle="tooltip" 
                                   title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                
                                <!-- Edit - Tampil untuk status Diproses atau Sebagian -->
                                @if(in_array($item->status, ['D', 'P']))
                                    <a href="{{ route('superadmin.pengadaan.edit', $item->idpengadaan) }}" 
                                       class="btn btn-warning btn-sm"
                                       data-bs-toggle="tooltip" 
                                       title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                @endif
                                
                                <!-- Hapus - Tampil untuk status Diproses yang belum ada penerimaan -->
                                @if($item->status === 'D' && (floatval($item->total_qty_diterima ?? 0) == 0))
                                    <button type="button"
                                            class="btn btn-danger btn-sm"
                                            data-bs-toggle="tooltip" 
                                            title="Hapus"
                                            onclick="confirmDelete({{ $item->idpengadaan }}, '#{{ $item->idpengadaan }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    
                                    <!-- Hidden Delete Form -->
                                    <form id="delete-form-{{ $item->idpengadaan }}" 
                                          action="{{ route('superadmin.pengadaan.destroy', $item->idpengadaan) }}" 
                                          method="POST" 
                                          class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-inbox display-1 opacity-25"></i>
                                <p class="mt-3 mb-3">
                                    @if($filterStatus === 'all')
                                        Belum ada data pengadaan
                                    @elseif($filterStatus === 'diproses')
                                        Tidak ada pengadaan yang sedang diproses
                                    @elseif($filterStatus === 'sebagian')
                                        Tidak ada pengadaan dengan penerimaan sebagian
                                    @else
                                        Tidak ada pengadaan yang selesai
                                    @endif
                                </p>
                                <a href="{{ route('superadmin.pengadaan.create') }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-plus-circle me-1"></i> Buat Pengadaan Pertama
                                </a>
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

@push('scripts')
<script>
// Konfirmasi hapus
function confirmDelete(id, nama) {
    if (confirm('Apakah Anda yakin ingin menghapus pengadaan "' + nama + '"?\n\nPengadaan ID: ' + id)) {
        document.getElementById('delete-form-' + id).submit();
    }
}

// Initialize Bootstrap tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush