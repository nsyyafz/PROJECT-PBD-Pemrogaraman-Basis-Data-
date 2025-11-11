@extends('layouts.superadmin')

@section('title', 'Manajemen User - Sistem Inventory')

@section('content')
@php
    $pageTitle = 'Manajemen User';
    $pageIcon = 'fa-users';
@endphp

<style>
    .page-header {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        border-radius: 12px;
        padding: 2rem 2.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(30, 60, 114, 0.15);
        color: white;
    }

    .page-header h2 {
        font-size: 28px;
        font-weight: 700;
        margin: 0;
    }

    .page-header p {
        margin: 0.5rem 0 0 0;
        opacity: 0.9;
        font-size: 14px;
    }

    .btn-add {
        background: white;
        color: #1e3c72;
        border: none;
        padding: 12px 28px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        color: #1e3c72;
    }

    .btn-add i {
        margin-right: 8px;
    }

    .data-card {
        background: white;
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }

    .data-card .card-header {
        background: #f8f9fa;
        border-bottom: 2px solid #e8eaed;
        padding: 1.5rem 2rem;
    }

    .data-card .card-header h5 {
        margin: 0;
        color: #2c3e50;
        font-weight: 600;
        font-size: 18px;
    }

    .data-card .card-body {
        padding: 0;
    }

    .table-modern {
        margin: 0;
    }

    .table-modern thead {
        background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
        color: white;
    }

    .table-modern thead th {
        padding: 1.2rem 1.5rem;
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
    }

    .table-modern tbody tr {
        border-bottom: 1px solid #e8eaed;
        transition: all 0.3s ease;
    }

    .table-modern tbody tr:hover {
        background: #f8f9fa;
        transform: scale(1.01);
    }

    .table-modern tbody td {
        padding: 1.2rem 1.5rem;
        vertical-align: middle;
        color: #2c3e50;
        font-size: 14px;
    }

    .badge-role {
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .badge-superadmin {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
    }

    .badge-admin {
        background: linear-gradient(135deg, #16a085, #1abc9c);
        color: white;
    }

    .badge-default {
        background: #95a5a6;
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #7f8c8d;
    }

    .empty-state i {
        font-size: 64px;
        margin-bottom: 1.5rem;
        opacity: 0.3;
    }

    .empty-state h4 {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #2c3e50;
    }

    .empty-state p {
        font-size: 14px;
        margin: 0;
    }

    .btn-action {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        border: none;
        transition: all 0.3s ease;
        margin: 0 3px;
    }

    .btn-edit {
        background: #3498db;
        color: white;
    }

    .btn-edit:hover {
        background: #2980b9;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(52, 152, 219, 0.3);
    }

    .btn-delete {
        background: #e74c3c;
        color: white;
    }

    .btn-delete:hover {
        background: #c0392b;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(231, 76, 60, 0.3);
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
        }

        .page-header h2 {
            font-size: 22px;
        }

        .table-modern thead th,
        .table-modern tbody td {
            padding: 1rem;
            font-size: 13px;
        }
    }
</style>

<!-- Page Header -->
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h2><i class="fas fa-users me-2"></i>Manajemen User</h2>
            <p>Kelola data pengguna sistem inventory</p>
        </div>
        <button class="btn btn-add">
            <i class="fas fa-plus"></i>Tambah User
        </button>
    </div>
</div>

<!-- Data Card -->
<div class="data-card">
    <div class="card-header">
        <h5><i class="fas fa-list me-2 text-primary"></i>Daftar User</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th style="width: 80px;">ID</th>
                        <th>Username</th>
                        <th style="width: 200px;">Role</th>
                        <th style="width: 150px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td><strong>#{{ $user->iduser }}</strong></td>
                        <td>
                            <i class="fas fa-user me-2 text-muted"></i>{{ $user->username }}
                        </td>
                        <td>
                            @if($user->nama_role == 'SuperAdmin')
                                <span class="badge badge-role badge-superadmin">
                                    <i class="fas fa-crown me-1"></i>{{ $user->nama_role }}
                                </span>
                            @elseif($user->nama_role == 'Admin')
                                <span class="badge badge-role badge-admin">
                                    <i class="fas fa-user-shield me-1"></i>{{ $user->nama_role }}
                                </span>
                            @else
                                <span class="badge badge-role badge-default">
                                    <i class="fas fa-user me-1"></i>{{ $user->nama_role ?? 'User' }}
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            <button class="btn btn-action btn-edit" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-action btn-delete" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4">
                            <div class="empty-state">
                                <i class="fas fa-users-slash"></i>
                                <h4>Belum Ada Data User</h4>
                                <p>Klik tombol "Tambah User" untuk menambahkan user baru</p>
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