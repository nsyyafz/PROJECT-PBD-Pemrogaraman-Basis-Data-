@extends('layouts.superadmin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Data Penerimaan</h2>
        <a href="{{ route('superadmin.penerimaan.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Buat Penerimaan
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
                    <a href="{{ route('superadmin.penerimaan.index', ['status' => 'all']) }}" 
                       class="btn btn-sm {{ $filter == 'all' ? 'btn-primary' : 'btn-outline-primary' }}">
                        Semua
                    </a>
                    <a href="{{ route('superadmin.penerimaan.index', ['status' => 'pending']) }}" 
                       class="btn btn-sm {{ $filter == 'pending' ? 'btn-warning' : 'btn-outline-warning' }}">
                        Pending
                    </a>
                    <a href="{{ route('superadmin.penerimaan.index', ['status' => 'approved']) }}" 
                       class="btn btn-sm {{ $filter == 'approved' ? 'btn-info' : 'btn-outline-info' }}">
                        Approved
                    </a>
                    <a href="{{ route('superadmin.penerimaan.index', ['status' => 'completed']) }}" 
                       class="btn btn-sm {{ $filter == 'completed' ? 'btn-success' : 'btn-outline-success' }}">
                        Completed
                    </a>
                </div>
            </div>

            {{-- Tabel RINGKAS --}}
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID Penerimaan</th>
                            <th>Tanggal</th>
                            <th>ID Pengadaan</th>
                            <th>Vendor</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penerimaans as $penerimaan)
                        <tr>
                            <td>{{ $penerimaan->idpenerimaan }}</td>
                            <td>{{ date('d-m-Y H:i', strtotime($penerimaan->tanggal)) }}</td>
                            <td>
                                <a href="{{ route('superadmin.pengadaan.show', $penerimaan->idpengadaan) }}" 
                                   class="badge bg-secondary text-decoration-none">
                                    #{{ $penerimaan->idpengadaan }}
                                </a>
                            </td>
                            <td>{{ $penerimaan->nama_vendor }}</td>
                            <td>
                                @if($penerimaan->status == 'Pending')
                                    <span class="badge bg-warning">{{ $penerimaan->status }}</span>
                                @elseif($penerimaan->status == 'Approved')
                                    <span class="badge bg-info">{{ $penerimaan->status }}</span>
                                @elseif($penerimaan->status == 'Completed')
                                    <span class="badge bg-success">{{ $penerimaan->status }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('superadmin.penerimaan.show', $penerimaan->idpenerimaan) }}" 
                                   class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                                
                                @if($penerimaan->status == 'Pending')
                                    <form action="{{ route('superadmin.penerimaan.destroy', $penerimaan->idpenerimaan) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                onclick="return confirm('Yakin hapus penerimaan ini?')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                Tidak ada data penerimaan
                                @if($filter != 'all')
                                    dengan status {{ $filter }}
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