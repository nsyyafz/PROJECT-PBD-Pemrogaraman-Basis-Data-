@extends('layouts.metis.app')

@section('title', 'Daftar Satuan')

@php
    $pageTitle = 'Daftar Satuan';
    $pageDescription = 'Kelola data satuan barang';
@endphp

@section('page-actions')
<a href="{{ route('superadmin.satuan.create') }}" class="btn btn-primary">
    <i class="bi bi-plus-circle me-1"></i> Tambah Satuan
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
            <a href="{{ route('superadmin.satuan.index', ['status' => 'aktif']) }}" 
               class="btn btn-sm {{ ($filter ?? 'aktif') == 'aktif' ? 'btn-success' : 'btn-outline-success' }}">
                <i class="bi bi-check-circle me-1"></i> Aktif
            </a>
            <a href="{{ route('superadmin.satuan.index', ['status' => 'all']) }}" 
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
            <i class="bi bi-rulers me-2"></i>Data Satuan
        </h5>
        <span class="badge bg-primary">Total: {{ count($satuans) }} data</span>
    </div>
    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 80px;" class="text-center">No</th>
                        <th>Nama Satuan</th>
                        <th style="width: 100px;" class="text-center">Status</th>
                        <th style="width: 120px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($satuans as $index => $satuan)
                    <tr>
                        <td class="text-center">
                            <span class="badge bg-secondary">{{ $index + 1 }}</span>
                        </td>
                        <td>
                            <strong>{{ $satuan->nama_satuan }}</strong>
                        </td>
                        <td class="text-center">
                            @if(($satuan->status ?? 1) == 1)
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
                                <a href="{{ route('superadmin.satuan.edit', $satuan->idsatuan) }}" 
                                   class="btn btn-warning"
                                   data-bs-toggle="tooltip" 
                                   title="Edit Satuan">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <button type="button"
                                        class="btn btn-danger"
                                        data-bs-toggle="tooltip" 
                                        title="Hapus Satuan"
                                        onclick="confirmDelete({{ $satuan->idsatuan }}, '{{ $satuan->nama_satuan }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                            
                            <!-- Hidden Delete Form -->
                            <form id="delete-form-{{ $satuan->idsatuan }}" 
                                  action="{{ route('superadmin.satuan.destroy', $satuan->idsatuan) }}" 
                                  method="POST" 
                                  class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-inbox display-1 opacity-25"></i>
                                <p class="mt-3 mb-3 fs-5">
                                    @if(($filter ?? 'aktif') == 'aktif')
                                        Tidak ada satuan aktif
                                    @else
                                        Tidak ada data satuan
                                    @endif
                                </p>
                                <a href="{{ route('superadmin.satuan.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-1"></i> Tambah Satuan Pertama
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
    if (confirm('Apakah Anda yakin ingin menghapus satuan "' + nama + '"?\n\nSatuan ID: ' + id)) {
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