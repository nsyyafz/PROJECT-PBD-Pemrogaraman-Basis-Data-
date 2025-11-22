@extends('layouts.metis.app')

@section('title', 'Edit Pengadaan')

@php
    $pageTitle = 'Edit Pengadaan #' . $pengadaan->idpengadaan;
    $pageDescription = 'Ubah data pengadaan barang';
@endphp

@section('page-actions')
<a href="{{ route('superadmin.pengadaan.index') }}" class="btn btn-secondary btn-sm">
    <i class="bi bi-arrow-left me-1"></i> Kembali
</a>
@endsection

@section('content')
<!-- Alert Messages -->
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-x-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>
        <strong>Terdapat kesalahan:</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Info Warning -->
<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-triangle me-2"></i>
    <strong>Perhatian!</strong> Anda sedang mengubah pengadaan yang sudah ada. Semua detail lama akan dihapus dan diganti dengan data baru.
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>

<form action="{{ route('superadmin.pengadaan.update', $pengadaan->idpengadaan) }}" method="POST" id="formPengadaan">
    @csrf
    @method('PUT')
    
    <!-- Info Header Card -->
    <div class="card mb-3">
        <div class="card-header py-2">
            <h6 class="card-title mb-0">
                <i class="bi bi-info-circle me-2"></i>Informasi Pengadaan
            </h6>
        </div>
        <div class="card-body">
            <div class="row g-2">
                <!-- Vendor -->
                <div class="col-md-3">
                    <label class="form-label form-label-sm">Vendor <span class="text-danger">*</span></label>
                    <select name="vendor_idvendor" id="vendor_idvendor" class="form-select form-select-sm" required>
                        <option value="">-- Pilih Vendor --</option>
                        @foreach($vendors as $vendor)
                            <option value="{{ $vendor->idvendor }}" 
                                {{ old('vendor_idvendor', $pengadaan->vendor_idvendor) == $vendor->idvendor ? 'selected' : '' }}>
                                {{ $vendor->nama_vendor }} ({{ $vendor->badan_hukum }})
                            </option>
                        @endforeach
                    </select>
                    @error('vendor_idvendor')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <!-- User (Auto) -->
                <div class="col-md-2">
                    <label class="form-label form-label-sm">
                        User <span class="badge bg-secondary" style="font-size: 0.6rem;">AUTO</span>
                    </label>
                    <input type="text" class="form-control form-control-sm" value="{{ $currentUser->username ?? 'User' }}" readonly>
                </div>

                <!-- Tanggal Original (Auto) -->
                <div class="col-md-2">
                    <label class="form-label form-label-sm">
                        Tanggal <span class="badge bg-secondary" style="font-size: 0.6rem;">ASLI</span>
                    </label>
                    <input type="text" class="form-control form-control-sm" value="{{ date('d/m/Y H:i', strtotime($pengadaan->timestamp)) }}" readonly>
                </div>

                <!-- Status (Auto) -->
                <div class="col-md-2">
                    <label class="form-label form-label-sm">
                        Status <span class="badge bg-secondary" style="font-size: 0.6rem;">AUTO</span>
                    </label>
                    <input type="text" class="form-control form-control-sm" value="Diproses (D)" readonly>
                </div>

                <!-- PPN (Fixed) -->
                <div class="col-md-1">
                    <label class="form-label form-label-sm">PPN</label>
                    <input type="text" class="form-control form-control-sm" value="10%" readonly>
                    <input type="hidden" name="ppn" id="ppn" value="10">
                </div>

                <!-- Subtotal -->
                <div class="col-md-2">
                    <label class="form-label form-label-sm">Subtotal</label>
                    <input type="text" id="display_subtotal" class="form-control form-control-sm fw-bold text-primary" value="Rp 0" readonly>
                </div>
            </div>

            <div class="row g-2 mt-1">
                <!-- Total -->
                <div class="col-md-2 offset-md-10">
                    <label class="form-label form-label-sm">Total + PPN</label>
                    <input type="text" id="display_total" class="form-control form-control-sm fw-bold text-success" style="font-size: 1rem;" value="Rp 0" readonly>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Barang Card -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center py-2">
            <h6 class="card-title mb-0">
                <i class="bi bi-box-seam me-2"></i>Detail Barang
            </h6>
            <button type="button" class="btn btn-success btn-sm" onclick="addBarangRow()">
                <i class="bi bi-plus-circle me-1"></i> Tambah Barang
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-sm mb-0" id="tableBarang" style="font-size: 0.85rem;">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 40px;" class="text-center">No</th>
                            <th>Barang</th>
                            <th style="width: 140px;">Harga Satuan</th>
                            <th style="width: 100px;">Jumlah</th>
                            <th style="width: 140px;">Subtotal</th>
                            <th style="width: 70px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="barangContainer">
                        <!-- Rows dari JavaScript -->
                    </tbody>
                    <tfoot>
                        <tr class="table-secondary">
                            <td colspan="4" class="text-end"><strong>Subtotal:</strong></td>
                            <td><strong id="displaySubtotal">Rp 0</strong></td>
                            <td></td>
                        </tr>
                        <tr class="table-secondary">
                            <td colspan="4" class="text-end"><strong>PPN (10%):</strong></td>
                            <td><strong id="displayNilaiPPN">Rp 0</strong></td>
                            <td></td>
                        </tr>
                        <tr class="table-primary">
                            <td colspan="4" class="text-end"><strong>TOTAL:</strong></td>
                            <td><strong id="displayTotal" class="text-success">Rp 0</strong></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="card-footer py-2">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="badge bg-warning text-dark">
                        <i class="bi bi-info-circle me-1"></i>Minimal 1 barang harus ditambahkan
                    </span>
                </div>
                <div>
                    <button type="submit" class="btn btn-warning btn-sm">
                        <i class="bi bi-save me-1"></i> Update Pengadaan
                    </button>
                    <a href="{{ route('superadmin.pengadaan.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-x-circle me-1"></i> Batal
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
// Data barang dari server
const barangData = @json($barangs);
const existingDetails = @json($existingDetails);
let rowCounter = 0;

// Tambah baris barang baru
function addBarangRow(idbarang = '', jumlah = 1) {
    rowCounter++;
    const row = `
        <tr id="row-${rowCounter}">
            <td class="text-center align-middle">${rowCounter}</td>
            <td>
                <select name="barang[${rowCounter}][idbarang]" class="form-select form-select-sm barang-select" data-row="${rowCounter}" required>
                    <option value="">-- Pilih Barang --</option>
                    ${barangData.map(b => `<option value="${b.idbarang}" data-harga="${b.harga}" ${b.idbarang == idbarang ? 'selected' : ''}>${b.nama_barang} (${b.nama_satuan})</option>`).join('')}
                </select>
            </td>
            <td>
                <input type="text" class="form-control form-control-sm text-end harga-display" data-row="${rowCounter}" readonly value="Rp 0">
                <input type="hidden" class="harga-value" data-row="${rowCounter}" value="0">
            </td>
            <td>
                <input type="number" name="barang[${rowCounter}][jumlah]" class="form-control form-control-sm text-center jumlah-input" data-row="${rowCounter}" min="1" value="${jumlah}" required>
            </td>
            <td>
                <input type="text" class="form-control form-control-sm text-end subtotal-display" data-row="${rowCounter}" readonly value="Rp 0">
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm" onclick="removeBarangRow(${rowCounter})">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        </tr>
    `;
    
    document.getElementById('barangContainer').insertAdjacentHTML('beforeend', row);
    attachEventListeners(rowCounter);
    
    // Trigger change untuk set harga jika ada idbarang
    if (idbarang) {
        document.querySelector(`select[data-row="${rowCounter}"]`).dispatchEvent(new Event('change'));
    }
    
    renumberRows();
}

// Hapus baris
function removeBarangRow(rowId) {
    document.getElementById(`row-${rowId}`).remove();
    renumberRows();
    calculateTotal();
}

// Renumber baris
function renumberRows() {
    const rows = document.querySelectorAll('#barangContainer tr');
    rows.forEach((row, index) => {
        row.querySelector('td:first-child').textContent = index + 1;
    });
}

// Attach event listeners
function attachEventListeners(rowId) {
    // Event barang select
    const selectBarang = document.querySelector(`select[data-row="${rowId}"]`);
    selectBarang.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const harga = parseFloat(selectedOption.dataset.harga) || 0;
        
        document.querySelector(`.harga-display[data-row="${rowId}"]`).value = formatRupiah(harga);
        document.querySelector(`.harga-value[data-row="${rowId}"]`).value = harga;
        
        calculateRowSubtotal(rowId);
    });
    
    // Event jumlah input
    const inputJumlah = document.querySelector(`.jumlah-input[data-row="${rowId}"]`);
    inputJumlah.addEventListener('input', function() {
        calculateRowSubtotal(rowId);
    });
}

// Hitung subtotal per baris
function calculateRowSubtotal(rowId) {
    const harga = parseFloat(document.querySelector(`.harga-value[data-row="${rowId}"]`).value) || 0;
    const jumlah = parseInt(document.querySelector(`.jumlah-input[data-row="${rowId}"]`).value) || 0;
    const subtotal = harga * jumlah;
    
    document.querySelector(`.subtotal-display[data-row="${rowId}"]`).value = formatRupiah(subtotal);
    
    calculateTotal();
}

// Hitung total keseluruhan
function calculateTotal() {
    let subtotal = 0;
    
    // Sum semua subtotal
    document.querySelectorAll('.subtotal-display').forEach(input => {
        const value = parseFloat(input.value.replace(/[^0-9]/g, '')) || 0;
        subtotal += value;
    });
    
    const ppnPersen = 10;
    const nilaiPPN = Math.floor(subtotal * ppnPersen / 100);
    const total = subtotal + nilaiPPN;
    
    // Update tabel footer
    document.getElementById('displaySubtotal').textContent = formatRupiah(subtotal);
    document.getElementById('displayNilaiPPN').textContent = formatRupiah(nilaiPPN);
    document.getElementById('displayTotal').textContent = formatRupiah(total);
    
    // Update header form
    document.getElementById('display_subtotal').value = formatRupiah(subtotal);
    document.getElementById('display_total').value = formatRupiah(total);
}

// Format rupiah
function formatRupiah(number) {
    return 'Rp ' + Math.floor(number).toLocaleString('id-ID');
}

// Load existing details saat load
document.addEventListener('DOMContentLoaded', function() {
    if (existingDetails.length > 0) {
        existingDetails.forEach(detail => {
            addBarangRow(detail.idbarang, detail.jumlah);
        });
    } else {
        addBarangRow();
    }
});
</script>
@endpush