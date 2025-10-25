@extends('layouts.superadmin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Detail Penjualan #{{ $penjualan->idpenjualan }}</h2>
        <a href="{{ route('superadmin.penjualan.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Card Info Penjualan --}}
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Informasi Penjualan</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="40%"><strong>ID Penjualan</strong></td>
                            <td>: {{ $penjualan->idpenjualan }}</td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal</strong></td>
                            <td>: {{ date('d-m-Y H:i:s', strtotime($penjualan->tanggal_penjualan)) }}</td>
                        </tr>
                        <tr>
                            <td><strong>Kasir</strong></td>
                            <td>: {{ $penjualan->kasir }} ({{ $penjualan->nama_role }})</td>
                        </tr>
                        <tr>
                            <td><strong>Margin</strong></td>
                            <td>: 
                                <span class="badge bg-success fs-6">
                                    {{ ($penjualan->margin_persen * 100) }}%
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Ringkasan Nilai</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="40%"><strong>Subtotal</strong></td>
                            <td>: Rp {{ number_format($penjualan->subtotal_nilai, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td><strong>PPN</strong></td>
                            <td>: Rp {{ number_format($penjualan->ppn, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td><strong><h5>TOTAL</h5></strong></td>
                            <td><strong><h5>: Rp {{ number_format($penjualan->total_nilai, 0, ',', '.') }}</h5></strong></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Card Detail Barang --}}
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">Detail Barang</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Jenis</th>
                            <th>Satuan</th>
                            <th>Jumlah</th>
                            <th>Harga Satuan</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($detail_barang as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->nama_barang }}</td>
                            <td>
                                <span class="badge bg-{{ $item->jenis_barang == 'M' ? 'primary' : 'success' }}">
                                    {{ $item->jenis_barang == 'M' ? 'Material' : 'Jasa' }}
                                </span>
                            </td>
                            <td>{{ $item->nama_satuan }}</td>
                            <td class="text-end">{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="6" class="text-end"><strong>Subtotal:</strong></td>
                            <td class="text-end"><strong>Rp {{ number_format($penjualan->subtotal_nilai, 0, ',', '.') }}</strong></td>
                        </tr>
                        <tr>
                            <td colspan="6" class="text-end"><strong>PPN:</strong></td>
                            <td class="text-end"><strong>Rp {{ number_format($penjualan->ppn, 0, ',', '.') }}</strong></td>
                        </tr>
                        <tr class="table-success">
                            <td colspan="6" class="text-end"><strong>TOTAL:</strong></td>
                            <td class="text-end"><strong>Rp {{ number_format($penjualan->total_nilai, 0, ',', '.') }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    {{-- Tombol Aksi --}}
    <div class="card">
        <div class="card-body">
            <div class="d-flex gap-2">
                <button onclick="window.print()" class="btn btn-secondary">
                    <i class="fas fa-print"></i> Cetak
                </button>
                
                <form action="{{ route('superadmin.penjualan.destroy', $penjualan->idpenjualan) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin hapus penjualan ini?')">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection