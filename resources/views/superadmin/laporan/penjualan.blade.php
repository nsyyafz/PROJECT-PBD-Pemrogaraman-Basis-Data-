@extends('layouts.superadmin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Laporan Penjualan</h2>
    </div>

    {{-- Filter Periode --}}
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Filter Periode</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('superadmin.laporan.penjualan') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Tanggal Dari</label>
                    <input type="date" name="tanggal_dari" class="form-control" 
                           value="{{ $tanggalDari }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Sampai</label>
                    <input type="date" name="tanggal_sampai" class="form-control" 
                           value="{{ $tanggalSampai }}" required>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-filter"></i> Tampilkan
                    </button>
                    <a href="{{ route('superadmin.laporan.printPenjualan', ['tanggal_dari' => $tanggalDari, 'tanggal_sampai' => $tanggalSampai]) }}" 
                       class="btn btn-success" target="_blank">
                        <i class="fas fa-print"></i> Cetak PDF
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Ringkasan --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h6 class="card-title">Total Transaksi</h6>
                    <h2 class="mb-0">{{ $totalTransaksi }}</h2>
                    <small>Transaksi</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h6 class="card-title">Total Omset</h6>
                    <h2 class="mb-0">Rp {{ number_format($totalOmset, 0, ',', '.') }}</h2>
                    <small>Periode {{ date('d/m/Y', strtotime($tanggalDari)) }} - {{ date('d/m/Y', strtotime($tanggalSampai)) }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h6 class="card-title">Rata-rata per Transaksi</h6>
                    <h2 class="mb-0">Rp {{ $totalTransaksi > 0 ? number_format($totalOmset / $totalTransaksi, 0, ',', '.') : 0 }}</h2>
                    <small>Per transaksi</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Barang Terlaris --}}
    <div class="card mb-4">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0">Top 5 Barang Terlaris</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Ranking</th>
                            <th>Nama Barang</th>
                            <th>Total Terjual</th>
                            <th>Total Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($barangTerlaris as $index => $barang)
                        <tr>
                            <td class="text-center">
                                <span class="badge bg-{{ $index == 0 ? 'warning' : ($index == 1 ? 'secondary' : 'info') }} fs-6">
                                    #{{ $index + 1 }}
                                </span>
                            </td>
                            <td>{{ $barang->nama_barang }}</td>
                            <td>{{ number_format($barang->total_terjual, 0, ',', '.') }} {{ $barang->nama_satuan }}</td>
                            <td>Rp {{ number_format($barang->total_nilai, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada data penjualan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Penjualan per Kasir --}}
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">Penjualan per Kasir</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Kasir</th>
                            <th>Jumlah Transaksi</th>
                            <th>Total Omset</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($perKasir as $kasir)
                        <tr>
                            <td>{{ $kasir->kasir }}</td>
                            <td>{{ $kasir->jumlah_transaksi }} transaksi</td>
                            <td>Rp {{ number_format($kasir->total_omset, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">Tidak ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Detail Transaksi --}}
    <div class="card">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Detail Transaksi Penjualan</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>ID Penjualan</th>
                            <th>Tanggal</th>
                            <th>Kasir</th>
                            <th>Total Nilai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penjualans as $index => $penjualan)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $penjualan->idpenjualan }}</td>
                            <td>{{ date('d-m-Y H:i', strtotime($penjualan->tanggal)) }}</td>
                            <td>{{ $penjualan->kasir }}</td>
                            <td>Rp {{ number_format($penjualan->total_nilai, 0, ',', '.') }}</td>
                            <td>
                                <a href="{{ route('superadmin.penjualan.show', $penjualan->idpenjualan) }}" 
                                   class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                Tidak ada transaksi penjualan pada periode ini
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if(count($penjualans) > 0)
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="4" class="text-end"><strong>TOTAL:</strong></td>
                            <td colspan="2"><strong>Rp {{ number_format($totalOmset, 0, ',', '.') }}</strong></td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
@endsection