@extends('layouts.superadmin')

@section('title', 'Detail Pengadaan - Sistem Inventory')

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

    .btn-back {
        background: white;
        color: #1e3c72;
        border: none;
        padding: 10px 24px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-back:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        color: #1e3c72;
    }

    .info-card {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.06);
    }

    .info-card h5 {
        font-size: 18px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #e8eaed;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .info-card h5 i {
        color: #3498db;
    }

    .info-row {
        display: flex;
        padding: 1rem 0;
        border-bottom: 1px solid #f1f3f5;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        width: 200px;
        font-weight: 600;
        color: #7f8c8d;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-value {
        flex: 1;
        color: #2c3e50;
        font-size: 14px;
        font-weight: 500;
    }

    .badge-status {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .badge-pending {
        background: linear-gradient(135deg, #f39c12, #f1c40f);
        color: white;
    }

    .badge-approved {
        background: linear-gradient(135deg, #27ae60, #2ecc71);
        color: white;
    }

    .badge-rejected {
        background: linear-gradient(135deg, #e74c3c, #c0392b);
        color: white;
    }

    .status-update-card {
        background: linear-gradient(135deg, #fff5e6 0%, #ffe8cc 100%);
        border: 2px solid #f39c12;
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 15px rgba(243, 156, 18, 0.1);
    }

    .status-update-card h5 {
        font-size: 18px;
        font-weight: 700;
        color: #d68910;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid rgba(243, 156, 18, 0.2);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn-approve {
        background: linear-gradient(135deg, #27ae60, #2ecc71);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-approve:hover {
        background: linear-gradient(135deg, #229954, #27ae60);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(39, 174, 96, 0.3);
        color: white;
    }

    .btn-reject {
        background: linear-gradient(135deg, #e74c3c, #c0392b);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-reject:hover {
        background: linear-gradient(135deg, #c0392b, #a93226);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
        color: white;
    }

    .detail-table {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.06);
        margin-bottom: 1.5rem;
    }

    .detail-table .table-header {
        background: #f8f9fa;
        padding: 1.5rem 2rem;
        border-bottom: 2px solid #e8eaed;
    }

    .detail-table .table-header h5 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: #2c3e50;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .detail-table .table-header h5 i {
        color: #3498db;
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
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
    }

    .table-modern tbody tr {
        border-bottom: 1px solid #e8eaed;
        transition: all 0.3s ease;
    }

    .table-modern tbody tr:hover {
        background: #f8f9fa;
    }

    .table-modern tbody tr:last-child {
        border-bottom: none;
    }

    .table-modern tbody td {
        padding: 1.2rem 1.5rem;
        vertical-align: middle;
        color: #2c3e50;
        font-size: 14px;
    }

    .badge-satuan {
        background: #ecf0f1;
        color: #7f8c8d;
        padding: 6px 12px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: 600;
    }

    .summary-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        padding: 2rem;
        color: white;
        box-shadow: 0 4px 20px rgba(102, 126, 234, 0.3);
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }

    .summary-row:last-child {
        border-bottom: none;
        padding-top: 1.5rem;
        margin-top: 0.5rem;
        border-top: 2px solid rgba(255, 255, 255, 0.3);
    }

    .summary-label {
        font-size: 15px;
        font-weight: 500;
        opacity: 0.9;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .summary-value {
        font-size: 18px;
        font-weight: 700;
    }

    .summary-value.total {
        font-size: 24px;
    }

    .text-price {
        color: #27ae60;
        font-weight: 700;
    }

    .action-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.06);
    }

    .btn-edit-pengadaan {
        background: linear-gradient(135deg, #f39c12, #e67e22);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-edit-pengadaan:hover {
        background: linear-gradient(135deg, #e67e22, #d35400);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(243, 156, 18, 0.3);
        color: white;
    }

    .btn-delete-pengadaan {
        background: linear-gradient(135deg, #e74c3c, #c0392b);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-delete-pengadaan:hover {
        background: linear-gradient(135deg, #c0392b, #a93226);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
        color: white;
    }

    @media (max-width: 768px) {
        .info-row {
            flex-direction: column;
            gap: 0.5rem;
        }

        .info-label {
            width: 100%;
        }

        .summary-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .page-header {
            padding: 1.5rem;
        }

        .page-header h2 {
            font-size: 22px;
        }
    }
</style>

<!-- Page Header -->
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h2><i class="fas fa-file-invoice me-2"></i>Detail Pengadaan</h2>
            <p>Informasi lengkap transaksi pengadaan barang</p>
        </div>
        <a href="{{ route('superadmin.pengadaan.index') }}" class="btn btn-back">
            <i class="fas fa-arrow-left"></i>Kembali
        </a>
    </div>
</div>

<!-- Alert Messages -->
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    <i class="fas fa-check-circle me-2"></i>
    <strong>Berhasil!</strong> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show">
    <i class="fas fa-exclamation-circle me-2"></i>
    <strong>Error!</strong> {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- Informasi Pengadaan -->
<div class="info-card">
    <h5><i class="fas fa-info-circle"></i>Informasi Pengadaan</h5>
    
    <div class="info-row">
        <div class="info-label">
            <i class="fas fa-hashtag text-muted"></i>ID Pengadaan
        </div>
        <div class="info-value">
            <strong class="text-primary">PO-{{ str_pad($pengadaan->idpengadaan, 4, '0', STR_PAD_LEFT) }}</strong>
        </div>
    </div>

    <div class="info-row">
        <div class="info-label">
            <i class="fas fa-calendar text-muted"></i>Tanggal
        </div>
        <div class="info-value">
            {{ \Carbon\Carbon::parse($pengadaan->timestamp)->format('d/m/Y H:i') }}
        </div>
    </div>

    <div class="info-row">
        <div class="info-label">
            <i class="fas fa-store text-muted"></i>Vendor
        </div>
        <div class="info-value">
            <strong>{{ $pengadaan->nama_vendor }}</strong>
        </div>
    </div>

    <div class="info-row">
        <div class="info-label">
            <i class="fas fa-user text-muted"></i>Dibuat Oleh
        </div>
        <div class="info-value">
            {{ $pengadaan->created_by }}
        </div>
    </div>

    <div class="info-row">
        <div class="info-label">
            <i class="fas fa-flag text-muted"></i>Status
        </div>
        <div class="info-value">
            @if($pengadaan->status == 'P')
                <span class="badge-status badge-pending">
                    <i class="fas fa-clock"></i>Pending
                </span>
            @elseif($pengadaan->status == 'A')
                <span class="badge-status badge-approved">
                    <i class="fas fa-check"></i>Approved
                </span>
            @else
                <span class="badge-status badge-rejected">
                    <i class="fas fa-times"></i>Rejected
                </span>
            @endif
        </div>
    </div>
</div>

<!-- Status Update Form (Hanya muncul jika status Pending) -->
@if($pengadaan->status == 'P')
<div class="status-update-card">
    <h5><i class="fas fa-edit"></i>Ubah Status Pengadaan</h5>
    
    <form action="{{ route('superadmin.pengadaan.updateStatus', $pengadaan->idpengadaan) }}" method="POST">
        @csrf
        @method('PATCH')
        
        <div class="row g-3">
            <div class="col-md-6">
                <button type="submit" 
                        name="status"
                        value="A"
                        class="btn-approve"
                        onclick="return confirm('Yakin APPROVE pengadaan ini?\n\nSetelah approved, pengadaan dapat diproses ke penerimaan.')">
                    <i class="fas fa-check-circle"></i>Approve
                </button>
            </div>
            <div class="col-md-6">
                <button type="submit" 
                        name="status"
                        value="R"
                        class="btn-reject"
                        onclick="return confirm('Yakin REJECT pengadaan ini?\n\nSetelah rejected, pengadaan tidak dapat diproses lebih lanjut.')">
                    <i class="fas fa-times-circle"></i>Reject
                </button>
            </div>
        </div>
    </form>
</div>
@endif

<!-- Detail Barang -->
<div class="detail-table">
    <div class="table-header">
        <h5><i class="fas fa-boxes"></i>Detail Barang</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-modern">
            <thead>
                <tr>
                    <th style="width: 60px;">No</th>
                    <th style="width: 100px;">ID Barang</th>
                    <th>Nama Barang</th>
                    <th style="width: 120px;" class="text-center">Satuan</th>
                    <th style="width: 150px;" class="text-end">Harga Satuan</th>
                    <th style="width: 100px;" class="text-center">Jumlah</th>
                    <th style="width: 150px;" class="text-end">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($details as $index => $item)
                <tr>
                    <td class="text-center">
                        <strong>{{ $index + 1 }}</strong>
                    </td>
                    <td>
                        <span class="text-primary"><strong>#{{ $item->idbarang }}</strong></span>
                    </td>
                    <td>
                        <strong>{{ $item->nama_barang }}</strong>
                    </td>
                    <td class="text-center">
                        <span class="badge-satuan">{{ $item->nama_satuan }}</span>
                    </td>
                    <td class="text-end">
                        <span class="text-muted" style="font-size: 12px;">Rp</span>
                        {{ number_format($item->harga_satuan, 0, ',', '.') }}
                    </td>
                    <td class="text-center">
                        <strong>{{ number_format($item->jumlah, 0, ',', '.') }}</strong>
                    </td>
                    <td class="text-end">
                        <strong class="text-price">
                            Rp {{ number_format($item->sub_total, 0, ',', '.') }}
                        </strong>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Tidak ada detail barang</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Summary -->
<div class="row">
    <div class="col-lg-6 offset-lg-6">
        <div class="summary-card">
            <div class="summary-row">
                <div class="summary-label">
                    <i class="fas fa-calculator"></i>Subtotal
                </div>
                <div class="summary-value">
                    Rp {{ number_format($pengadaan->subtotal_nilai, 0, ',', '.') }}
                </div>
            </div>

            <div class="summary-row">
                <div class="summary-label">
                    <i class="fas fa-percent"></i>PPN ({{ $pengadaan->ppn }}%)
                </div>
                <div class="summary-value">
                    @php
                        $nilaiPPN = $pengadaan->total_nilai - $pengadaan->subtotal_nilai;
                    @endphp
                    Rp {{ number_format($nilaiPPN, 0, ',', '.') }}
                </div>
            </div>

            <div class="summary-row">
                <div class="summary-label">
                    <i class="fas fa-money-bill-wave"></i>TOTAL
                </div>
                <div class="summary-value total">
                    Rp {{ number_format($pengadaan->total_nilai, 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Action Buttons untuk Status Pending -->
@if($pengadaan->status == 'P')
<div class="action-card mt-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h6 class="mb-1"><i class="fas fa-exclamation-triangle text-warning me-2"></i><strong>Status Pending</strong></h6>
            <p class="text-muted mb-0" style="font-size: 13px;">Pengadaan ini dapat diubah atau dihapus</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('superadmin.pengadaan.edit', $pengadaan->idpengadaan) }}" 
               class="btn-edit-pengadaan">
                <i class="fas fa-edit"></i>Edit Pengadaan
            </a>
            <button type="button" 
                    class="btn-delete-pengadaan" 
                    data-bs-toggle="modal" 
                    data-bs-target="#deleteModal">
                <i class="fas fa-trash"></i>Hapus
            </button>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
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
                <p class="mb-1">Apakah Anda yakin ingin menghapus pengadaan ini?</p>
                <div class="alert alert-warning mt-3 mb-0" role="alert">
                    <small>
                        <i class="fas fa-info-circle me-1"></i>
                        <strong>PO-{{ str_pad($pengadaan->idpengadaan, 4, '0', STR_PAD_LEFT) }}</strong>
                        <br>Vendor: {{ $pengadaan->nama_vendor }}
                        <br>Total: Rp {{ number_format($pengadaan->total_nilai, 0, ',', '.') }}
                        <br><br>
                        <strong class="text-danger">⚠️ Peringatan:</strong> Tindakan ini tidak dapat dibatalkan!
                    </small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Batal
                </button>
                <form action="{{ route('superadmin.pengadaan.destroy', $pengadaan->idpengadaan) }}" 
                      method="POST" 
                      class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i> Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto dismiss alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(function() {
                alert.remove();
            }, 500);
        });
    }, 5000);
});
</script>
@endpush