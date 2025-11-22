@extends('layouts.metis.app')

@section('title', 'Daftar Barang')

@php
    $pageTitle = 'Daftar Barang';
    $pageDescription = 'Kelola data barang inventory';
@endphp

@section('page-actions')
<a href="{{ route('superadmin.barang.create') }}" class="btn btn-primary">
    <i class="bi bi-plus-circle me-1"></i> Tambah Barang
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
<div class="card mb-4">
    <div class="card-body py-2">
        <div class="btn-group" role="group">
            <a href="{{ route('superadmin.barang.index', ['status' => 'aktif']) }}" 
               class="btn btn-sm {{ ($filter ?? 'aktif') == 'aktif' ? 'btn-success' : 'btn-outline-success' }}">
                <i class="bi bi-check-circle me-1"></i> Aktif
            </a>
            <a href="{{ route('superadmin.barang.index', ['status' => 'all']) }}" 
               class="btn btn-sm {{ ($filter ?? 'aktif') == 'all' ? 'btn-primary' : 'btn-outline-primary' }}">
                <i class="bi bi-list-ul me-1"></i> Semua
            </a>
        </div>
    </div>
</div>

<!-- Table Card -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            <i class="bi bi-box-seam me-2"></i>Data Barang
        </h5>
        <span class="badge bg-primary">Total: {{ count($barangs) }} data</span>
    </div>
    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 80px;" class="text-center">ID</th>
                        <th>Nama Barang</th>
                        <th style="width: 120px;">Jenis</th>
                        <th style="width: 100px;">Satuan</th>
                        <th style="width: 150px;" class="text-end">Harga</th>
                        <th style="width: 100px;" class="text-center">Status</th>
                        <th style="width: 120px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barangs as $barang)
                    <tr>
                        <td class="text-center">
                            <span class="badge bg-secondary">{{ $barang->idbarang }}</span>
                        </td>
                        <td>
                            <strong>{{ $barang->nama_barang }}</strong>
                        </td>
                        <td>
                            @if($barang->jenis == 'M')
                                <span class="badge bg-success">
                                    <i class="bi bi-egg-fried me-1"></i>Makanan
                                </span>
                            @else
                                <span class="badge bg-info">
                                    <i class="bi bi-box me-1"></i>Non-Makanan
                                </span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-light text-dark">{{ $barang->nama_satuan ?? '-' }}</span>
                        </td>
                        <td class="text-end">
                            <strong class="text-success">Rp {{ number_format($barang->harga, 0, ',', '.') }}</strong>
                        </td>
                        <td class="text-center">
                            @if(($barang->status ?? 1) == 1)
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle me-1"></i>Aktif
                                </span>
                            @else
                                <span class="badge bg-danger">
                                    <i class="bi bi-x-circle me-1"></i>Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('superadmin.barang.edit', $barang->idbarang) }}" 
                                   class="btn btn-warning"
                                   data-bs-toggle="tooltip" 
                                   title="Edit Barang">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <button type="button"
                                        class="btn btn-danger"
                                        data-bs-toggle="tooltip" 
                                        title="Hapus Barang"
                                        onclick="confirmDelete({{ $barang->idbarang }}, '{{ $barang->nama_barang }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                            
                            <!-- Hidden Delete Form -->
                            <form id="delete-form-{{ $barang->idbarang }}" 
                                  action="{{ route('superadmin.barang.destroy', $barang->idbarang) }}" 
                                  method="POST" 
                                  class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-inbox display-1 opacity-25"></i>
                                <p class="mt-3 mb-3 fs-5">
                                    @if(($filter ?? 'aktif') == 'aktif')
                                        Tidak ada barang aktif
                                    @else
                                        Tidak ada data barang
                                    @endif
                                </p>
                                <a href="{{ route('superadmin.barang.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-1"></i> Tambah Barang Pertama
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
// Konfirmasi hapus dengan Sweet Alert style
function confirmDelete(id, nama) {
    if (confirm('Apakah Anda yakin ingin menghapus barang "' + nama + '"?\n\nBarang ID: ' + id)) {
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