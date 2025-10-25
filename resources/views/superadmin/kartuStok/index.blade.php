@extends('layouts.superadmin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Kartu Stok Barang</h2>
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Pilih Barang untuk Melihat Kartu Stok</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Barang</th>
                            <th>Jenis Barang</th>
                            <th>Satuan</th>
                            <th>Harga</th>
                            <th>Stok Akhir</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($barangs as $barang)
                        <tr>
                            <td>{{ $barang->idbarang }}</td>
                            <td>{{ $barang->nama_barang }}</td>
                            <td>
                                <span class="badge bg-{{ $barang->jenis_barang == 'M' ? 'primary' : 'success' }}">
                                    {{ $barang->jenis_barang == 'M' ? 'Makanan' : 'Alat' }}
                                </span>
                            </td>
                            <td>{{ $barang->nama_satuan }}</td>
                            <td>Rp {{ number_format($barang->harga, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge bg-{{ $barang->stok_akhir > 0 ? 'success' : 'danger' }} fs-6">
                                    {{ number_format($barang->stok_akhir, 0, ',', '.') }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('superadmin.kartuStok.show', $barang->idbarang) }}" 
                                   class="btn btn-sm btn-info">
                                    <i class="fas fa-history"></i> Lihat Kartu Stok
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data barang</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection