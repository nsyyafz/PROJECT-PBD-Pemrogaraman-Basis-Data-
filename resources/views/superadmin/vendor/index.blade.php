@extends('layouts.superadmin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Data Vendor</h2>
        <a href="{{ route('superadmin.vendor.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Vendor
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            {{-- Filter Status --}}
            <div class="mb-3">
                <label class="form-label">Filter Status:</label>
                <div class="btn-group" role="group">
                    <a href="{{ route('superadmin.vendor.index', ['status' => 'all']) }}" 
                       class="btn btn-sm {{ $filter == 'all' ? 'btn-primary' : 'btn-outline-primary' }}">
                        Semua
                    </a>
                    <a href="{{ route('superadmin.vendor.index', ['status' => 'aktif']) }}" 
                       class="btn btn-sm {{ $filter == 'aktif' ? 'btn-success' : 'btn-outline-success' }}">
                        Aktif
                    </a>
                    <a href="{{ route('superadmin.vendor.index', ['status' => 'nonaktif']) }}" 
                       class="btn btn-sm {{ $filter == 'nonaktif' ? 'btn-danger' : 'btn-outline-danger' }}">
                        Nonaktif
                    </a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Vendor</th>
                            <th>Badan Hukum</th>
                            @if($filter == 'all' || $filter == 'aktif')
                                <th>Jumlah Pengadaan</th>
                            @endif
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vendors as $vendor)
                        <tr>
                            <td>{{ $vendor->idvendor }}</td>
                            <td>{{ $vendor->nama_vendor }}</td>
                            <td>
                                <span class="badge bg-primary">
                                    {{ $vendor->jenis_badan_hukum }}
                                </span>
                            </td>
                            @if($filter == 'all' || $filter == 'aktif')
                                <td>
                                    <span class="badge bg-info">
                                        {{ $vendor->jumlah_pengadaan }} Pengadaan
                                    </span>
                                </td>
                            @endif
                            <td>
                                @if($filter == 'aktif')
                                    <span class="badge bg-success">Aktif</span>
                                @elseif($filter == 'nonaktif')
                                    <span class="badge bg-danger">Nonaktif</span>
                                @else
                                    <span class="badge bg-{{ $vendor->status == 'A' ? 'success' : 'danger' }}">
                                        {{ $vendor->status_text }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('superadmin.vendor.edit', $vendor->idvendor) }}" 
                                   class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('superadmin.vendor.destroy', $vendor->idvendor) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" 
                                            onclick="return confirm('Yakin hapus vendor ini?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ $filter == 'nonaktif' ? '5' : '6' }}" class="text-center">
                                Tidak ada data vendor 
                                @if($filter == 'aktif')
                                    aktif
                                @elseif($filter == 'nonaktif')
                                    nonaktif
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection