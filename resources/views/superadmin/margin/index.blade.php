@extends('layouts.superadmin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Data Margin Penjualan</h2>
        <a href="{{ route('superadmin.margin.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Margin
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
                    <a href="{{ route('superadmin.margin.index', ['status' => 'all']) }}" 
                       class="btn btn-sm {{ $filter == 'all' ? 'btn-primary' : 'btn-outline-primary' }}">
                        Semua
                    </a>
                    <a href="{{ route('superadmin.margin.index', ['status' => 'aktif']) }}" 
                       class="btn btn-sm {{ $filter == 'aktif' ? 'btn-success' : 'btn-outline-success' }}">
                        Aktif
                    </a>
                    <a href="{{ route('superadmin.margin.index', ['status' => 'nonaktif']) }}" 
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
                            <th>Persentase</th>
                            <th>Dibuat Oleh</th>
                            @if($filter == 'all')
                                <th>Tanggal Dibuat</th>
                            @endif
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($margins as $margin)
                        <tr>
                            <td>{{ $margin->idmargin_penjualan }}</td>
                            <td>
                                <span class="badge bg-primary fs-6">
                                    {{ $margin->persen_display }}
                                </span>
                            </td>
                            <td>{{ $margin->created_by }}</td>
                            @if($filter == 'all')
                                <td>{{ $margin->tanggal_dibuat ?? date('d-m-Y H:i:s', strtotime($margin->created_at)) }}</td>
                            @endif
                            <td>
                                @if($filter == 'aktif')
                                    <span class="badge bg-success">Aktif</span>
                                @elseif($filter == 'nonaktif')
                                    <span class="badge bg-danger">Nonaktif</span>
                                @else
                                    <span class="badge bg-{{ $margin->status == 1 ? 'success' : 'danger' }}">
                                        {{ $margin->status_text }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('superadmin.margin.edit', $margin->idmargin_penjualan) }}" 
                                   class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('superadmin.margin.destroy', $margin->idmargin_penjualan) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" 
                                            onclick="return confirm('Yakin hapus margin ini?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ $filter == 'all' ? '6' : '5' }}" class="text-center">
                                Tidak ada data margin penjualan 
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