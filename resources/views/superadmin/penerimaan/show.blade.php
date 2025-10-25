@extends('layouts.superadmin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Detail Penerimaan #{{ $penerimaan->idpenerimaan }}</h2>
        <a href="{{ route('superadmin.penerimaan.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Card Info Penerimaan & Pengadaan --}}
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Informasi Penerimaan</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="40%"><strong>ID Penerimaan</strong></td>
                            <td>: {{ $penerimaan->idpenerimaan }}</td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal Penerimaan</strong></td>
                            <td>: {{ date('d-m-Y H:i:s', strtotime($penerimaan->tanggal_penerimaan)) }}</td>
                        </tr>
                        <tr>
                            <td><strong>Status</strong></td>
                            <td>: 
                                @if($penerimaan->status_desc == 'Pending')
                                    <span class="badge bg-warning">{{ $penerimaan->status_desc }}</span>
                                @elseif($penerimaan->status_desc == 'Approved')
                                    <span class="badge bg-info">{{ $penerimaan->status_desc }}</span>
                                @elseif($penerimaan->status_desc == 'Completed')
                                    <span class="badge bg-success">{{ $penerimaan->status_desc }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Diterima oleh</strong></td>
                            <td>: {{ $penerimaan->user_penerimaan }} ({{ $penerimaan->nama_role }})</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Informasi Pengadaan & Vendor</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="40%"><strong>ID Pengadaan</strong></td>
                            <td>: 
                                <a href="{{ route('superadmin.pengadaan.show', $penerimaan->idpengadaan) }}" 
                                   class="badge bg-secondary">
                                    #{{ $penerimaan->idpengadaan }}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Tgl Pengadaan</strong></td>
                            <td>: {{ date('d-m-Y H:i', strtotime($penerimaan->tanggal_pengadaan)) }}</td>
                        </tr>
                        <tr>
                            <td><strong>Vendor</strong></td>
                            <td>: {{ $penerimaan->nama_vendor }}</td>
                        </tr>
                        <tr>
                            <td><strong>Nilai Pengadaan</strong></td>
                            <td>: Rp {{ number_format($penerimaan->nilai_pengadaan, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Card Detail Barang yang Diterima --}}
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Detail Barang yang Diterima</h5>
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
                            <th>Harga Satuan</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalNilai = 0; @endphp
                        @foreach($detail_barang as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->nama_barang }}</td>
                            <td>{{ $item->nama_satuan }}</td>
                            <td class="text-end">{{ number_format($item->jumlah_terima, 0, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format($item->harga_satuan_terima, 0, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format($item->sub_total_terima, 0, ',', '.') }}</td>
                        </tr>
                        @php $totalNilai += $item->sub_total_terima; @endphp
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="5" class="text-end"><strong>TOTAL PENERIMAAN:</strong></td>
                            <td class="text-end"><strong>Rp {{ number_format($totalNilai, 0, ',', '.') }}</strong></td>
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
                @if($penerimaan->status == 'P')
                    <form action="{{ route('superadmin.penerimaan.update-status', $penerimaan->idpenerimaan) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="A">
                        <button type="submit" class="btn btn-info" onclick="return confirm('Approve penerimaan ini?')">
                            <i class="fas fa-check"></i> Approve
                        </button>
                    </form>
                @endif

                @if($penerimaan->status == 'A')
                    <form action="{{ route('superadmin.penerimaan.update-status', $penerimaan->idpenerimaan) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="C">
                        <button type="submit" class="btn btn-success" onclick="return confirm('Tandai sebagai completed?')">
                            <i class="fas fa-check-double"></i> Complete
                        </button>
                    </form>
                @endif

                @if($penerimaan->status == 'P')
                    <form action="{{ route('superadmin.penerimaan.destroy', $penerimaan->idpenerimaan) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin hapus penerimaan ini?')">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection