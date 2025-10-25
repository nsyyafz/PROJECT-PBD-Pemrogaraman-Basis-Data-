@extends('layouts.superadmin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Data Satuan</h2>
        <a href="{{ route('superadmin.satuan.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Satuan
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
                    <a href="{{ route('superadmin.satuan.index', ['status' => 'all']) }}" 
                       class="btn btn-sm {{ $filter == 'all' ? 'btn-primary' : 'btn-outline-primary' }}">
                        Semua
                    </a>
                    <a href="{{ route('superadmin.satuan.index', ['status' => 'aktif']) }}" 
                       class="btn btn-sm {{ $filter == 'aktif' ? 'btn-success' : 'btn-outline-success' }}">
                        Aktif
                    </a>
                    <a href="{{ route('superadmin.satuan.index', ['status' => 'nonaktif']) }}" 
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
                            <th>Nama Satuan</th>
                            <th>Jumlah Barang</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($satuans as $satuan)
                        <tr>
                            <td>{{ $satuan->idsatuan }}</td>
                            <td>{{ $satuan->nama_satuan }}</td>
                            <td>
                                <span class="badge bg-info">
                                    {{ $satuan->jumlah_barang }} Barang
                                </span>
                            </td>
                            <td>
                                @if($filter == 'aktif')
                                    <span class="badge bg-success">Aktif</span>
                                @elseif($filter == 'nonaktif')
                                    <span class="badge bg-danger">Nonaktif</span>
                                @else
                                    <span class="badge bg-{{ $satuan->status == 1 ? 'success' : 'danger' }}">
                                        {{ $satuan->status_text }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('superadmin.satuan.edit', $satuan->idsatuan) }}" 
                                   class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('superadmin.satuan.destroy', $satuan->idsatuan) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" 
                                            onclick="return confirm('Yakin hapus satuan ini?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">
                                Tidak ada data satuan 
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