@extends('layouts.superadmin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Kartu Stok - {{ $barang->nama_barang }}</h2>
        <a href="{{ route('superadmin.kartuStok.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    {{-- Card Info Barang --}}
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Informasi Barang</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <td width="30%"><strong>ID Barang</strong></td>
                            <td>: {{ $barang->idbarang }}</td>
                        </tr>
                        <tr>
                            <td><strong>Nama Barang</strong></td>
                            <td>: {{ $barang->nama_barang }}</td>
                        </tr>
                        <tr>
                            <td><strong>Jenis Barang</strong></td>
                            <td>: 
                                <span class="badge bg-{{ $barang->jenis_barang == 'M' ? 'primary' : 'success' }}">
                                    {{ $barang->jenis_barang == 'M' ? 'Material' : 'Jasa' }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <td width="30%"><strong>Satuan</strong></td>
                            <td>: {{ $barang->nama_satuan }}</td>
                        </tr>
                        <tr>
                            <td><strong>Harga</strong></td>
                            <td>: Rp {{ number_format($barang->harga, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Stok Akhir</strong></td>
                            <td>: 
                                <span class="badge bg-{{ $barang->stok_akhir > 0 ? 'success' : 'danger' }} fs-5">
                                    {{ number_format($barang->stok_akhir, 0, ',', '.') }} {{ $barang->nama_satuan }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Tanggal --}}
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">Filter Tanggal</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('superadmin.kartuStok.show', $barang->idbarang) }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Tanggal Dari</label>
                    <input type="date" name="tanggal_dari" class="form-control" 
                           value="{{ request('tanggal_dari') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Sampai</label>
                    <input type="date" name="tanggal_sampai" class="form-control" 
                           value="{{ request('tanggal_sampai') }}">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('superadmin.kartuStok.show', $barang->idbarang) }}" 
                       class="btn btn-secondary">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                    <a href="{{ route('superadmin.kartuStok.export', $barang->idbarang) }}" 
                       class="btn btn-success ms-2" target="_blank">
                        <i class="fas fa-print"></i> Cetak
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel History Kartu Stok --}}
    <div class="card">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">History Transaksi Stok</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Jenis Transaksi</th>
                            <th>ID Transaksi</th>
                            <th>Masuk</th>
                            <th>Keluar</th>
                            <th>Stok Akhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kartuStok as $index => $stok)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ date('d-m-Y H:i:s', strtotime($stok->tanggal)) }}</td>
                            <td>
                                @if($stok->jenis_transaksi == 'P')
                                    <span class="badge bg-success">{{ $stok->jenis_transaksi_desc }}</span>
                                @elseif($stok->jenis_transaksi == 'J')
                                    <span class="badge bg-primary">{{ $stok->jenis_transaksi_desc }}</span>
                                @elseif($stok->jenis_transaksi == 'R')
                                    <span class="badge bg-danger">{{ $stok->jenis_transaksi_desc }}</span>
                                @endif
                            </td>
                            <td>
                                @if($stok->jenis_transaksi == 'P')
                                    <a href="{{ route('superadmin.penerimaan.show', $stok->idtransaksi) }}" 
                                       class="badge bg-secondary">
                                        #{{ $stok->idtransaksi }}
                                    </a>
                                @elseif($stok->jenis_transaksi == 'J')
                                    <a href="{{ route('superadmin.penjualan.show', $stok->idtransaksi) }}" 
                                       class="badge bg-secondary">
                                        #{{ $stok->idtransaksi }}
                                    </a>
                                @elseif($stok->jenis_transaksi == 'R')
                                    <a href="{{ route('superadmin.retur.show', $stok->idtransaksi) }}" 
                                       class="badge bg-secondary">
                                        #{{ $stok->idtransaksi }}
                                    </a>
                                @endif
                            </td>
                            <td class="text-end text-success">
                                <strong>{{ $stok->masuk > 0 ? '+' . number_format($stok->masuk, 0, ',', '.') : '-' }}</strong>
                            </td>
                            <td class="text-end text-danger">
                                <strong>{{ $stok->keluar > 0 ? '-' . number_format($stok->keluar, 0, ',', '.') : '-' }}</strong>
                            </td>
                            <td class="text-end">
                                <strong>{{ number_format($stok->stok_akhir, 0, ',', '.') }}</strong>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                Tidak ada transaksi untuk barang ini
                                @if(request('tanggal_dari') || request('tanggal_sampai'))
                                    pada periode yang dipilih
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if(count($kartuStok) > 0)
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="4" class="text-end"><strong>Total:</strong></td>
                            <td class="text-end text-success">
                                <strong>+{{ number_format(array_sum(array_column($kartuStok, 'masuk')), 0, ',', '.') }}</strong>
                            </td>
                            <td class="text-end text-danger">
                                <strong>-{{ number_format(array_sum(array_column($kartuStok, 'keluar')), 0, ',', '.') }}</strong>
                            </td>
                            <td class="text-end">
                                <strong>{{ number_format($barang->stok_akhir, 0, ',', '.') }}</strong>
                            </td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
@endsection