@extends('layouts.superadmin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Data Barang</h2>
        <a href="{{ route('superadmin.barang.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Barang
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
                    <a href="{{ route('superadmin.barang.index', ['status' => 'all']) }}" 
                       class="btn btn-sm {{ $filter == 'all' ? 'btn-primary' : 'btn-outline-primary' }}">
                        Semua
                    </a>
                    <a href="{{ route('superadmin.barang.index', ['status' => 'aktif']) }}" 
                       class="btn btn-sm {{ $filter == 'aktif' ? 'btn-success' : 'btn-outline-success' }}">
                        Aktif
                    </a>
                    <a href="{{ route('superadmin.barang.index', ['status' => 'nonaktif']) }}" 
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
                            <th>Nama Barang</th>
                            @if($filter == 'all' || $filter == 'aktif')
                                <th>Jenis Barang</th>
                            @endif
                            <th>Harga</th>
                            <th>Satuan</th>
                            @if($filter == 'all' || $filter == 'aktif')
                                <th>Stok Terakhir</th>
                            @endif
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($barangs as $barang)
                        <tr>
                            <td>{{ $barang->idbarang }}</td>
                            <td>{{ $barang->nama_barang }}</td>
                            @if($filter == 'all' || $filter == 'aktif')
                                <td>{{ $barang->jenis_barang ?? '-' }}</td>
                            @endif
                            <td>Rp {{ number_format($barang->harga, 0, ',', '.') }}</td>
                            <td>{{ $barang->nama_satuan ?? '-' }}</td>
                            @if($filter == 'all' || $filter == 'aktif')
                                <td>{{ $barang->stok_terakhir ?? 0 }}</td>
                            @endif
                            <td>
                                @if($filter == 'aktif')
                                    <span class="badge bg-success">Aktif</span>
                                @elseif($filter == 'nonaktif')
                                    <span class="badge bg-danger">Nonaktif</span>
                                @else
                                    <span class="badge bg-{{ $barang->status == 1 ? 'success' : 'danger' }}">
                                        {{ $barang->status == 1 ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('superadmin.barang.edit', $barang->idbarang) }}" 
                                   class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('superadmin.barang.destroy', $barang->idbarang) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" 
                                            onclick="return confirm('Yakin hapus barang ini?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ $filter == 'nonaktif' ? '6' : '8' }}" class="text-center">
                                Tidak ada data barang 
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