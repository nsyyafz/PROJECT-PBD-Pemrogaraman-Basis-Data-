@extends('layouts.superadmin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Detail Retur #{{ $retur->idretur }}</h2>
        <a href="{{ route('superadmin.retur.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Card Info Retur & Penerimaan --}}
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Informasi Retur</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="40%"><strong>ID Retur</strong></td>
                            <td>: {{ $retur->idretur }}</td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal Retur</strong></td>
                            <td>: {{ date('d-m-Y H:i:s', strtotime($retur->tanggal_retur)) }}</td>
                        </tr>
                        <tr>
                            <td><strong>Petugas</strong></td>
                            <td>: {{ $retur->user_retur }} ({{ $retur->nama_role }})</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Informasi Penerimaan & Vendor</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="40%"><strong>ID Penerimaan</strong></td>
                            <td>: 
                                <a href="{{ route('superadmin.penerimaan.show', $retur->idpenerimaan) }}" 
                                   class="badge bg-secondary">
                                    #{{ $retur->idpenerimaan }}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Tgl Penerimaan</strong></td>
                            <td>: {{ date('d-m-Y H:i', strtotime($retur->tanggal_penerimaan)) }}</td>
                        </tr>
                        <tr>
                            <td><strong>ID Pengadaan</strong></td>
                            <td>: 
                                <a href="{{ route('superadmin.pengadaan.show', $retur->idpengadaan) }}" 
                                   class="badge bg-secondary">
                                    #{{ $retur->idpengadaan }}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Vendor</strong></td>
                            <td>: {{ $retur->nama_vendor }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Card Detail Barang yang Diretur --}}
    <div class="card mb-4">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0">Detail Barang yang Diretur</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Satuan</th>
                            <th>Jumlah Diterima</th>
                            <th>Jumlah Retur</th>
                            <th>Harga Satuan</th>
                            <th>Nilai Retur</th>
                            <th>Alasan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalRetur = 0; @endphp
                        @foreach($detail_barang as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->nama_barang }}</td>
                            <td>{{ $item->nama_satuan }}</td>
                            <td class="text-end">{{ number_format($item->jumlah_diterima, 0, ',', '.') }}</td>
                            <td class="text-end text-danger">
                                <strong>{{ number_format($item->jumlah_retur, 0, ',', '.') }}</strong>
                            </td>
                            <td class="text-end">Rp {{ number_format($item->harga_satuan_terima, 0, ',', '.') }}</td>
                            <td class="text-end text-danger">
                                <strong>Rp {{ number_format($item->nilai_retur, 0, ',', '.') }}</strong>
                            </td>
                            <td>{{ $item->alasan_retur }}</td>
                        </tr>
                        @php $totalRetur += $item->nilai_retur; @endphp
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr class="table-danger">
                            <td colspan="6" class="text-end"><strong>TOTAL NILAI RETUR:</strong></td>
                            <td class="text-end" colspan="2">
                                <strong>Rp {{ number_format($totalRetur, 0, ',', '.') }}</strong>
                            </td>
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
                
                <form action="{{ route('superadmin.retur.destroy', $retur->idretur) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin hapus retur ini?')">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection