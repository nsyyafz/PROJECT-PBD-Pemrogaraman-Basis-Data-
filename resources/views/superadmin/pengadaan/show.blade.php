@extends('layouts.superadmin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Detail Pengadaan #{{ $pengadaan->idpengadaan }}</h2>
        <a href="{{ route('superadmin.pengadaan.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Card Info Pengadaan --}}
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Informasi Pengadaan</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="40%"><strong>ID Pengadaan</strong></td>
                            <td>: {{ $pengadaan->idpengadaan }}</td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal</strong></td>
                            <td>: {{ date('d-m-Y H:i:s', strtotime($pengadaan->tanggal_pengadaan)) }}</td>
                        </tr>
                        <tr>
                            <td><strong>Status</strong></td>
                            <td>: 
                                @if($pengadaan->status_desc == 'Pending')
                                    <span class="badge bg-warning">{{ $pengadaan->status_desc }}</span>
                                @elseif($pengadaan->status_desc == 'Approved')
                                    <span class="badge bg-info">{{ $pengadaan->status_desc }}</span>
                                @elseif($pengadaan->status_desc == 'Completed')
                                    <span class="badge bg-success">{{ $pengadaan->status_desc }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Dibuat oleh</strong></td>
                            <td>: {{ $pengadaan->user_pengadaan }} ({{ $pengadaan->nama_role }})</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Informasi Vendor</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="40%"><strong>ID Vendor</strong></td>
                            <td>: {{ $pengadaan->idvendor }}</td>
                        </tr>
                        <tr>
                            <td><strong>Nama Vendor</strong></td>
                            <td>: {{ $pengadaan->nama_vendor }}</td>
                        </tr>
                        <tr>
                            <td><strong>Badan Hukum</strong></td>
                            <td>: 
                                @if($pengadaan->badan_hukum == 'P')
                                    <span class="badge bg-primary">PT</span>
                                @elseif($pengadaan->badan_hukum == 'C')
                                    <span class="badge bg-success">CV</span>
                                @elseif($pengadaan->badan_hukum == 'U')
                                    <span class="badge bg-warning">UD</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Card Detail Barang --}}
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
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
                            <td class="text-end">Rp {{ number_format($item->sub_total, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="6" class="text-end"><strong>Subtotal:</strong></td>
                            <td class="text-end"><strong>Rp {{ number_format($pengadaan->subtotal_nilai, 0, ',', '.') }}</strong></td>
                        </tr>
                        <tr>
                            <td colspan="6" class="text-end"><strong>PPN:</strong></td>
                            <td class="text-end"><strong>Rp {{ number_format($pengadaan->ppn, 0, ',', '.') }}</strong></td>
                        </tr>
                        <tr class="table-success">
                            <td colspan="6" class="text-end"><strong>TOTAL:</strong></td>
                            <td class="text-end"><strong>Rp {{ number_format($pengadaan->total_nilai, 0, ',', '.') }}</strong></td>
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
                @if($pengadaan->status == 'P')
                    <form action="{{ route('superadmin.pengadaan.update-status', $pengadaan->idpengadaan) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="A">
                        <button type="submit" class="btn btn-info" onclick="return confirm('Approve pengadaan ini?')">
                            <i class="fas fa-check"></i> Approve
                        </button>
                    </form>
                @endif

                @if($pengadaan->status == 'A')
                    <form action="{{ route('superadmin.pengadaan.update-status', $pengadaan->idpengadaan) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="C">
                        <button type="submit" class="btn btn-success" onclick="return confirm('Tandai sebagai completed?')">
                            <i class="fas fa-check-double"></i> Complete
                        </button>
                    </form>
                @endif

                @if($pengadaan->status == 'P')
                    <form action="{{ route('superadmin.pengadaan.destroy', $pengadaan->idpengadaan) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin hapus pengadaan ini?')">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection