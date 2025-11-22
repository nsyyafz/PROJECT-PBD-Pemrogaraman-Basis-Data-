@extends('layouts.metis.app')

@section('title', 'Manajemen User')

@php
    $pageTitle = 'Manajemen User';
    $pageDescription = 'Kelola data pengguna sistem inventory';
@endphp

@section('page-actions')
<a href="{{ route('superadmin.user.create') }}" class="btn btn-primary">
    <i class="bi bi-plus-circle me-1"></i> Tambah User
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
            <i class="bi bi-people me-2"></i>Data User
        </h5>
        <span class="badge bg-primary">Total: {{ count($users) }} data</span>
    </div>
    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 80px;" class="text-center">ID</th>
                        <th>Username</th>
                        <th style="width: 200px;">Role</th>
                        <th style="width: 120px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td class="text-center">
                            <span class="badge bg-secondary">{{ $user->iduser }}</span>
                        </td>
                        <td>
                            <i class="bi bi-person-circle me-2 text-muted"></i>
                            <strong>{{ $user->username }}</strong>
                        </td>
                        <td>
                            @if($user->nama_role == 'SuperAdmin')
                                <span class="badge" style="background: linear-gradient(135deg, #667eea, #764ba2); padding: 6px 14px;">
                                    <i class="bi bi-award-fill me-1"></i>{{ $user->nama_role }}
                                </span>
                            @elseif($user->nama_role == 'Admin')
                                <span class="badge bg-success" style="padding: 6px 14px;">
                                    <i class="bi bi-shield-fill-check me-1"></i>{{ $user->nama_role }}
                                </span>
                            @else
                                <span class="badge bg-secondary" style="padding: 6px 14px;">
                                    <i class="bi bi-person-fill me-1"></i>{{ $user->nama_role ?? 'User' }}
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('superadmin.user.edit', $user->iduser) }}" 
                                   class="btn btn-warning"
                                   data-bs-toggle="tooltip" 
                                   title="Edit User">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <button type="button"
                                        class="btn btn-danger"
                                        data-bs-toggle="tooltip" 
                                        title="Hapus User"
                                        onclick="confirmDelete({{ $user->iduser }}, '{{ $user->username }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                            
                            <!-- Hidden Delete Form -->
                            <form id="delete-form-{{ $user->iduser }}" 
                                  action="{{ route('superadmin.user.destroy', $user->iduser) }}" 
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
                                <i class="bi bi-people display-1 opacity-25"></i>
                                <p class="mt-3 mb-3 fs-5">Tidak ada data user</p>
                                <a href="{{ route('superadmin.user.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-1"></i> Tambah User Pertama
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
function confirmDelete(id, username) {
    if (confirm('Apakah Anda yakin ingin menghapus user "' + username + '"?\n\nUser ID: ' + id)) {
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