@extends('layouts.superadmin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Data Penjualan</h2>
        <a href="{{ route('superadmin.penjualan.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Buat Penjualan
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
            {{-- Tabel RINGKAS --}}
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID Penjualan</th>
                            <th>Tanggal</th>
                            <th>Total Nilai</th>
                            <th>Kasir</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penjualans as $penjualan)
                        <tr>
                            <td>{{ $penjualan->idpenjualan }}</td>
                            <td>{{ date('d-m-Y H:i', strtotime($penjualan->tanggal)) }}</td>
                            <td>Rp {{ number_format($penjualan->total_nilai, 0, ',', '.') }}</td>
                            <td>{{ $penjualan->kasir }}</td>
                            <td>
                                <a href="{{ route('superadmin.penjualan.show', $penjualan->idpenjualan) }}" 
                                   class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                                
                                <form action="{{ route('superadmin.penjualan.destroy', $penjualan->idpenjualan) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" 
                                            onclick="return confirm('Yakin hapus penjualan ini?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data penjualan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection