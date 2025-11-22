@extends('layouts.metis.app')

@section('title', 'Manajemen Role')

@php
    $pageTitle = 'Manajemen Role';
    $pageDescription = 'Kelola hak akses dan role pengguna sistem';
@endphp

@section('page-actions')
<a href="{{ route('superadmin.role.create') }}" class="btn btn-primary">
    <i class="bi bi-plus-circle me-1"></i> Tambah Role
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

<!-- Table Card -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            <i class="bi bi-shield-lock me-2"></i>Data Role
        </h5>
        <span class="badge bg-primary">Total: {{ count($roles) }} data</span>
    </div>
    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 80px;" class="text-center">ID</th>
                        <th>Nama Role</th>
                        <th style="width: 150px;" class="text-center">Jumlah User</th>
                        <th style="width: 120px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $role)
                    <tr>
                        <td class="text-center">
                            <span class="badge bg-secondary">{{ $role->idrole }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($role->nama_role == 'SuperAdmin')
                                    <span class="badge bg-gradient me-2" style="background: linear-gradient(135deg, #667eea, #764ba2); padding: 8px 10px;">
                                        <i class="bi bi-award-fill"></i>
                                    </span>
                                @elseif($role->nama_role == 'Admin')
                                    <span class="badge bg-success me-2" style="padding: 8px 10px;">
                                        <i class="bi bi-shield-fill-check"></i>
                                    </span>
                                @else
                                    <span class="badge bg-secondary me-2" style="padding: 8px 10px;">
                                        <i class="bi bi-person-fill"></i>
                                    </span>
                                @endif
                                <strong>{{ $role->nama_role }}</strong>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-info" style="font-size: 13px; padding: 6px 14px;">
                                <i class="bi bi-people-fill me-1"></i>{{ $role->jumlah_user }} user
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('superadmin.role.edit', $role->idrole) }}" 
                                   class="btn btn-warning"
                                   data-bs-toggle="tooltip" 
                                   title="Edit Role">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <button type="button"
                                        class="btn btn-danger"
                                        data-bs-toggle="tooltip" 
                                        title="Hapus Role"
                                        onclick="confirmDelete({{ $role->idrole }}, '{{ $role->nama_role }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                            
                            <!-- Hidden Delete Form -->
                            <form id="delete-form-{{ $role->idrole }}" 
                                  action="{{ route('superadmin.role.destroy', $role->idrole) }}" 
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
                                <i class="bi bi-shield-lock display-1 opacity-25"></i>
                                <p class="mt-3 mb-3 fs-5">Tidak ada data role</p>
                                <a href="{{ route('superadmin.role.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-1"></i> Tambah Role Pertama
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
    if (confirm('Apakah Anda yakin ingin menghapus role "' + nama + '"?\n\nRole ID: ' + id)) {
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