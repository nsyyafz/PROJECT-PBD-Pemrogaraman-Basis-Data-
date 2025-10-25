@extends('layouts.superadmin')

@section('content')
@php
    $pageTitle = 'Manajemen Role';
    $pageIcon = 'fa-user-tag';
@endphp

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manajemen Role</h2>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Role</th>
                        <th>Jumlah User</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $role)
                    <tr>
                        <td>{{ $role->idrole }}</td>
                        <td>{{ $role->nama_role }}</td>
                        <td>
                            <span class="badge bg-primary">{{ $role->jumlah_user }} user</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center">Tidak ada data role</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection