@extends('layouts.superadmin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Data Retur</h2>
        <a href="{{ route('superadmin.retur.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Buat Retur
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
                            <th>ID Retur</th>
                            <th>Tanggal</th>
                            <th>ID Penerimaan</th>
                            <th>Vendor</th>
                            <th>Petugas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($returs as $retur)
                        <tr>
                            <td>{{ $retur->idretur }}</td>
                            <td>{{ date('d-m-Y H:i', strtotime($retur->tanggal)) }}</td>
                            <td>
                                <a href="{{ route('superadmin.penerimaan.show', $retur->idpenerimaan) }}" 
                                   class="badge bg-secondary text-decoration-none">
                                    #{{ $retur->idpenerimaan }}
                                </a>
                            </td>
                            <td>{{ $retur->nama_vendor }}</td>
                            <td>{{ $retur->petugas }}</td>
                            <td>
                                <a href="{{ route('superadmin.retur.show', $retur->idretur) }}" 
                                   class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                                
                                <form action="{{ route('superadmin.retur.destroy', $retur->idretur) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" 
                                            onclick="return confirm('Yakin hapus retur ini?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data retur</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection