@extends('layouts.superadmin')

@section('title', 'Data Penerimaan Barang - Sistem Inventory')

@section('content')

<style>
    .page-header {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        border-radius: 12px;
        padding: 2rem 2.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(30, 60, 114, 0.15);
        color: white;
    }

    .page-header h2 {
        font-size: 28px;
        font-weight: 700;
        margin: 0;
    }

    .page-header p {
        margin: 0.5rem 0 0 0;
        opacity: 0.9;
        font-size: 14px;
    }

    .btn-add {
        background: white;
        color: #1e3c72;
        border: none;
        padding: 12px 28px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        color: #1e3c72;
    }

    .btn-add i {
        margin-right: 8px;
    }

    .filter-section {
        background: white;
        border-radius: 12px;
        padding: 1.5rem 2rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .filter-section h6 {
        font-size: 14px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .filter-chips {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .filter-chip {
        padding: 10px 20px;
        border-radius: 25px;
        border: 2px solid #e8eaed;
        background: white;
        color: #7f8c8d;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .filter-chip:hover {
        border-color: #1e3c72;
        color: #1e3c72;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(30, 60, 114, 0.15);
    }

    .filter-chip.active {
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        color: white;
        border-color: #1e3c72;
        box-shadow: 0 4px 15px rgba(30, 60, 114, 0.25);
    }

    .filter-chip.active-warning {
        background: linear-gradient(135deg, #f39c12, #f1c40f);
        color: white;
        border-color: #f39c12;
    }

    .filter-chip.active-success {
        background: linear-gradient(135deg, #27ae60, #2ecc71);
        color: white;
        border-color: #27ae60;
    }

    .filter-chip.active-info {
        background: linear-gradient(135deg, #3498db, #2980b9);
        color: white;
        border-color: #3498db;
    }

    .filter-chip i {
        font-size: 13px;
    }

    .data-card {
        background: white;
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }

    .data-card .card-header {
        background: #f8f9fa;
        border-bottom: 2px solid #e8eaed;
        padding: 1.5rem 2rem;
    }

    .data-card .card-header h5 {
        margin: 0;
        color: #2c3e50;
        font-weight: 600;
        font-size: 18px;
    }

    .data-card .card-body {
        padding: 0;
    }

    .table-modern {
        margin: 0;
    }

    .table-modern thead {
        background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
        color: white;
    }

    .table-modern thead th {
        padding: 1.2rem 1.5rem;
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
        white-space: nowrap;
    }

    .table-modern tbody tr {
        border-bottom: 1px solid #e8eaed;
        transition: all 0.3s ease;
    }

    .table-modern tbody tr:hover {
        background: #f8f9fa;
    }

    .table-modern tbody td {
        padding: 1.2rem 1.5rem;
        vertical-align: middle;
        color: #2c3e50;
        font-size: 14px;
    }

    .badge-status {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .badge-pending {
        background: linear-gradient(135deg, #f39c12, #f1c40f);
        color: white;
    }

    .badge-approved {
        background: linear-gradient(135deg, #27ae60, #2ecc71);
        color: white;
    }

    .badge-completed {
        background: linear-gradient(135deg, #3498db, #2980b9);
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #7f8c8d;
    }

    .empty-state i {
        font-size: 64px;
        margin-bottom: 1.5rem;
        opacity: 0.3;
    }

    .empty-state h4 {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #2c3e50;
    }

    .empty-state p {
        font-size: 14px;
        margin: 0;
    }

    .btn-action {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        border: none;
        transition: all 0.3s ease;
        margin: 0 3px;
    }

    .btn-view {
        background: #3498db;
        color: white;
    }

    .btn-view:hover {
        background: #2980b9;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(52, 152, 219, 0.3);
    }

    .btn-delete {
        background: #e74c3c;
        color: white;
    }

    .btn-delete:hover {
        background: #c0392b;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(231, 76, 60, 0.3);
    }

    .text-muted-custom {
        color: #7f8c8d;
        font-size: 13px;
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
        }

        .page-header h2 {
            font-size: 22px;
        }

        .filter-section {
            padding: 1rem 1.5rem;
        }

        .filter-chips {
            flex-direction: column;
        }

        .filter-chip {
            width: 100%;
            justify-content: center;
        }

        .table-modern {
            font-size: 12px;
        }

        .table-modern thead th,
        .table-modern tbody td {
            padding: 1rem;
        }
    }
</style>

<!-- Page Header -->
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h2><i class="fas fa-truck-loading me-2"></i>Data Penerimaan Barang</h2>
            <p>Kelola transaksi penerimaan barang dari pengadaan</p>
        </div>
        <a href="{{ route('superadmin.penerimaan.create') }}" class="btn btn-add">
            <i class="fas fa-plus"></i>Tambah Penerimaan
        </a>
    </div>
</div>

<!-- Alert Messages -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        <strong>Berhasil!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        <strong>Error!</strong> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Filter Section -->
<div class="filter-section">
    <h6><i class="fas fa-filter me-2"></i>Filter Status Penerimaan</h6>
    <div class="filter-chips">
        <a href="{{ route('superadmin.penerimaan.index') }}" 
           class="filter-chip {{ $filter == 'all' ? 'active' : '' }}">
            <i class="fas fa-list"></i>
            Semua Penerimaan
        </a>
        <a href="{{ route('superadmin.penerimaan.index', ['status' => 'pending']) }}" 
           class="filter-chip {{ $filter == 'pending' ? 'active-warning' : '' }}">
            <i class="fas fa-clock"></i>
            Pending
        </a>
        <a href="{{ route('superadmin.penerimaan.index', ['status' => 'approved']) }}" 
           class="filter-chip {{ $filter == 'approved' ? 'active-success' : '' }}">
            <i class="fas fa-check-circle"></i>
            Approved
        </a>
        <a href="{{ route('superadmin.penerimaan.index', ['status' => 'completed']) }}" 
           class="filter-chip {{ $filter == 'completed' ? 'active-info' : '' }}">
            <i class="fas fa-check-double"></i>
            Completed
        </a>
    </div>
</div>

<!-- Data Card -->
<div class="data-card">
    <div class="card-header">
        <h5>
            <i class="fas fa-table me-2 text-primary"></i>
            Daftar Penerimaan
            @if($filter == 'pending')
                <span class="badge badge-status badge-pending ms-2">
                    <i class="fas fa-clock me-1"></i>Pending
                </span>
            @elseif($filter == 'approved')
                <span class="badge badge-status badge-approved ms-2">
                    <i class="fas fa-check me-1"></i>Approved
                </span>
            @elseif($filter == 'completed')
                <span class="badge badge-status badge-completed ms-2">
                    <i class="fas fa-check-double me-1"></i>Completed
                </span>
            @else
                <span class="badge bg-secondary ms-2">
                    <i class="fas fa-list me-1"></i>Semua
                </span>
            @endif
            <span class="badge bg-info ms-2">
                Total: {{ count($penerimaans) }}
            </span>
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th style="width: 60px;">No</th>
                        <th style="width: 120px;">ID Penerimaan</th>
                        <th style="width: 150px;">Tanggal</th>
                        <th style="width: 120px;">ID Pengadaan</th>
                        <th>Vendor</th>
                        <th style="width: 100px;">User</th>
                        @if($filter == 'all')
                            <th style="width: 100px;" class="text-center">Status</th>
                        @endif
                        <th style="width: 120px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penerimaans as $index => $item)
                    <tr>
                        <td>
                            <strong>#{{ $index + 1 }}</strong>
                        </td>
                        <td>
                            <strong class="text-primary">
                                TR-{{ str_pad($item->idpenerimaan, 4, '0', STR_PAD_LEFT) }}
                            </strong>
                        </td>
                        <td>
                            <i class="fas fa-calendar me-1 text-muted-custom"></i>
                            <span class="text-muted-custom">
                                {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y H:i') }}
                            </span>
                        </td>
                        <td>
                            <strong class="text-info">
                                PO-{{ str_pad($item->idpengadaan, 4, '0', STR_PAD_LEFT) }}
                            </strong>
                        </td>
                        <td>
                            <i class="fas fa-store me-2 text-muted-custom"></i>
                            <strong>{{ $item->nama_vendor }}</strong>
                        </td>
                        <td>
                            <i class="fas fa-user me-1 text-muted-custom"></i>
                            {{ $item->username }}
                        </td>
                        @if($filter == 'all')
                            <td class="text-center">
                                @if($item->status == 'P')
                                    <span class="badge badge-status badge-pending">
                                        <i class="fas fa-clock me-1"></i>Pending
                                    </span>
                                @elseif($item->status == 'A')
                                    <span class="badge badge-status badge-approved">
                                        <i class="fas fa-check me-1"></i>Approved
                                    </span>
                                @else
                                    <span class="badge badge-status badge-completed">
                                        <i class="fas fa-check-double me-1"></i>Completed
                                    </span>
                                @endif
                            </td>
                        @endif
                        <td class="text-center">
                            <a href="{{ route('superadmin.penerimaan.show', $item->idpenerimaan) }}" 
                               class="btn btn-action btn-view" 
                               title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            
                            @if($item->status == 'P')
                            <button type="button" 
                                    class="btn btn-action btn-delete" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteModal{{ $item->idpenerimaan }}"
                                    title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                            @endif
                        </td>
                    </tr>

                    <!-- Delete Modal -->
                    <div class="modal fade" id="deleteModal{{ $item->idpenerimaan }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title">
                                        <i class="fas fa-exclamation-triangle me-2"></i> 
                                        Konfirmasi Hapus
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p class="mb-1">Apakah Anda yakin ingin menghapus penerimaan ini?</p>
                                    <div class="alert alert-warning mt-3 mb-0" role="alert">
                                        <small>
                                            <i class="fas fa-info-circle me-1"></i>
                                            <strong>TR-{{ str_pad($item->idpenerimaan, 4, '0', STR_PAD_LEFT) }}</strong>
                                            <br>Vendor: {{ $item->nama_vendor }}
                                            <br>Tanggal: {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y H:i') }}
                                        </small>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        Batal
                                    </button>
                                    <form action="{{ route('superadmin.penerimaan.destroy', $item->idpenerimaan) }}" 
                                          method="POST" 
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-trash me-1"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    @empty
                    <tr>
                        <td colspan="{{ $filter == 'all' ? 8 : 7 }}">
                            <div class="empty-state">
                                <i class="fas fa-truck-loading"></i>
                                <h4>Belum Ada Data Penerimaan
                                    @if($filter == 'pending')
                                        Pending
                                    @elseif($filter == 'approved')
                                        Approved
                                    @elseif($filter == 'completed')
                                        Completed
                                    @endif
                                </h4>
                                <p>
                                    @if($filter != 'all')
                                        Belum ada penerimaan dengan status ini. 
                                        <a href="{{ route('superadmin.penerimaan.index') }}">Lihat semua penerimaan</a>
                                    @else
                                        Klik tombol "Tambah Penerimaan" untuk membuat transaksi baru
                                    @endif
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Info Card -->
<div class="alert alert-info mt-3">
    <i class="fas fa-info-circle me-2"></i>
    <strong>Catatan:</strong> Penerimaan dengan status Pending dapat dihapus. Stok barang akan otomatis terupdate melalui trigger database saat penerimaan dibuat.
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto dismiss alerts
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
});
</script>
@endpush