@extends('layouts.metis.app')

@section('title', 'Daftar Penjualan')

@php
    $pageTitle = 'Daftar Penjualan';
    $pageDescription = 'Kelola data penjualan barang';
@endphp

@section('page-actions')
<a href="{{ route('superadmin.penjualan.create') }}" class="btn btn-primary btn-sm">
    <i class="bi bi-plus-circle me-1"></i> Buat Penjualan Baru
</a>
@endsection

@section('content')
<!-- Alert Messages -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-x-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Table Card -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center py-2">
        <h6 class="card-title mb-0">
            <i class="bi bi-cart-check me-2"></i>Data Penjualan
        </h6>
        <span class="badge bg-primary">{{ count($penjualans) }} transaksi</span>
    </div>
    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped table-sm mb-0 align-middle" style="font-size: 0.75rem;">
                <thead class="table-light" style="font-size: 0.75rem;">
                    <tr>
                        <th style="width: 45px;">ID</th>
                        <th style="width: 85px;">Tanggal</th>
                        <th style="width: 100px;">User</th>
                        <th style="width: 80px;" class="text-center">Jumlah Item</th>
                        <th style="width: 90px;" class="text-center">Total Qty</th>
                        <th style="width: 110px;" class="text-end">Subtotal</th>
                        <th style="width: 50px;" class="text-center">PPN</th>
                        <th style="width: 110px;" class="text-end">Total</th>
                        <th style="width: 80px;" class="text-center">Margin</th>
                        <th style="width: 110px;" class="text-end">Keuntungan</th>
                        <th style="width: 100px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penjualans as $item)
                    <tr>
                        <td>
                            <strong class="text-success">#{{ $item->idpenjualan }}</strong>
                        </td>
                        <td>
                            <div style="font-size: 0.7rem;">
                                <i class="bi bi-calendar3"></i> {{ date('d/m/y', strtotime($item->created_at)) }}
                            </div>
                            <div style="font-size: 0.65rem;">
                                <span class="text-muted"><i class="bi bi-clock"></i> {{ date('H:i', strtotime($item->created_at)) }}</span>
                            </div>
                        </td>
                        <td style="font-size: 0.75rem;">
                            <i class="bi bi-person-circle"></i> {{ $item->username }}
                        </td>
                        <td class="text-center">
                            <span class="badge bg-info" style="font-size: 0.65rem;">{{ $item->jumlah_item ?? 0 }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-success" style="font-size: 0.65rem;">{{ $item->total_qty ?? 0 }}</span>
                        </td>
                        <td class="text-end" style="font-size: 0.7rem;">
                            Rp {{ number_format($item->subtotal_nilai, 0, ',', '.') }}
                        </td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark" style="font-size: 0.65rem;">{{ $item->ppn }}%</span>
                        </td>
                        <td class="text-end">
                            <strong class="text-success" style="font-size: 0.75rem;">Rp {{ number_format($item->total_nilai, 0, ',', '.') }}</strong>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-primary" style="font-size: 0.65rem;">{{ $item->margin_persen }}%</span>
                        </td>
                        <td class="text-end">
                            <strong class="text-primary" style="font-size: 0.75rem;">Rp {{ number_format($item->total_keuntungan ?? 0, 0, ',', '.') }}</strong>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group">
                                <!-- Detail -->
                                <a href="{{ route('superadmin.penjualan.show', $item->idpenjualan) }}" 
                                   class="btn btn-info btn-sm"
                                   data-bs-toggle="tooltip" 
                                   title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                
                                <!-- Hapus -->
                                <button type="button"
                                        class="btn btn-danger btn-sm"
                                        data-bs-toggle="tooltip" 
                                        title="Hapus"
                                        onclick="confirmDelete({{ $item->idpenjualan }}, '#{{ $item->idpenjualan }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                            
                            <!-- Hidden Delete Form -->
                            <form id="delete-form-{{ $item->idpenjualan }}" 
                                  action="{{ route('superadmin.penjualan.destroy', $item->idpenjualan) }}" 
                                  method="POST" 
                                  class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-inbox display-1 opacity-25"></i>
                                <p class="mt-3 mb-3">Belum ada data penjualan</p>
                                <a href="{{ route('superadmin.penjualan.create') }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-plus-circle me-1"></i> Buat Penjualan Pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Konfirmasi hapus
function confirmDelete(id, nama) {
    if (confirm('Apakah Anda yakin ingin menghapus penjualan "' + nama + '"?\n\nPenjualan ID: ' + id + '\n\nStok akan dikembalikan!')) {
        document.getElementById('delete-form-' + id).submit();
    }
}

// Initialize Bootstrap tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush