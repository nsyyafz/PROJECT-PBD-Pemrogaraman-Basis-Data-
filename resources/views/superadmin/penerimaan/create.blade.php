@extends('layouts.metis.app')

@section('title', 'Buat Penerimaan Baru')

@php
    $pageTitle = 'Buat Penerimaan Baru';
    $pageDescription = 'Form penerimaan barang dari pengadaan';
@endphp

@section('page-actions')
<a href="{{ route('superadmin.penerimaan.index') }}" class="btn btn-secondary btn-sm">
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

<form action="{{ route('superadmin.penerimaan.store') }}" method="POST" id="formPenerimaan">
    @csrf
    
    <!-- Info Header Card -->
    <div class="card mb-3">
        <div class="card-header py-2">
            <h6 class="card-title mb-0">
                <i class="bi bi-info-circle me-2"></i>Informasi Penerimaan
            </h6>
        </div>
        <div class="card-body">
            <div class="row g-2">
                <!-- Pengadaan -->
                <div class="col-md-4">
                    <label class="form-label form-label-sm">Pilih Pengadaan <span class="text-danger">*</span></label>
                    <select name="idpengadaan" id="idpengadaan" class="form-select form-select-sm" required>
                        <option value="">-- Pilih Pengadaan --</option>
                        @foreach($pengadaans as $pengadaan)
                            <option value="{{ $pengadaan->idpengadaan }}" {{ old('idpengadaan') == $pengadaan->idpengadaan ? 'selected' : '' }}>
                                #{{ $pengadaan->idpengadaan }} - {{ $pengadaan->nama_vendor }} ({{ date('d/m/Y', strtotime($pengadaan->timestamp)) }})
                            </option>
                        @endforeach
                    </select>
                    @error('idpengadaan')
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

                <!-- Tanggal (Auto) -->
                <div class="col-md-2">
                    <label class="form-label form-label-sm">
                        Tanggal <span class="badge bg-secondary" style="font-size: 0.6rem;">AUTO</span>
                    </label>
                    <input type="text" class="form-control form-control-sm" value="{{ now()->format('d/m/Y H:i') }}" readonly>
                </div>

                <!-- Info Pengadaan (Hidden awalnya) -->
                <div class="col-md-4" id="infoPengadaan" style="display: none;">
                    <label class="form-label form-label-sm">Vendor</label>
                    <div class="fw-bold" id="namaVendor">-</div>
                    <small class="text-muted" id="statusPengadaan">-</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Barang Card -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center py-2">
            <h6 class="card-title mb-0">
                <i class="bi bi-box-seam me-2"></i>Detail Barang yang Diterima
            </h6>
            <span class="badge bg-info" id="badgeJumlahBarang">0 item</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-sm mb-0" id="tableBarang" style="font-size: 0.8rem;">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 35px;" class="text-center">No</th>
                            <th>Nama Barang</th>
                            <th style="width: 80px;" class="text-center">Satuan</th>
                            <th style="width: 100px;" class="text-end">Harga</th>
                            <th style="width: 80px;" class="text-center">Dipesan</th>
                            <th style="width: 80px;" class="text-center">Diterima</th>
                            <th style="width: 70px;" class="text-center">Sisa</th>
                            <th style="width: 110px;" class="text-center">Terima Sekarang <span class="text-danger">*</span></th>
                            <th style="width: 120px;" class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody id="barangContainer">
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">
                                <i class="bi bi-arrow-up-circle display-4 opacity-25"></i>
                                <p class="mt-2 mb-0">Pilih pengadaan terlebih dahulu</p>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot id="tableFooter" style="display: none;">
                        <tr class="table-primary">
                            <td colspan="8" class="text-end"><strong>TOTAL:</strong></td>
                            <td class="text-end">
                                <strong class="text-success" id="displayTotal">Rp 0</strong>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="card-footer py-2">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="badge bg-warning text-dark">
                        <i class="bi bi-info-circle me-1"></i>Masukkan jumlah yang diterima untuk setiap barang
                    </span>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary btn-sm" id="btnSubmit" disabled>
                        <i class="bi bi-save me-1"></i> Simpan Penerimaan
                    </button>
                    <a href="{{ route('superadmin.penerimaan.index') }}" class="btn btn-secondary btn-sm">
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
let detailBarang = [];

// Event: Pilih pengadaan
document.getElementById('idpengadaan').addEventListener('change', function() {
    const idpengadaan = this.value;
    
    if (!idpengadaan) {
        resetForm();
        return;
    }
    
    // AJAX: Get detail pengadaan
    fetch(`/superadmin/penerimaan/get-detail-pengadaan?idpengadaan=${idpengadaan}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                detailBarang = data.data;
                renderTable();
                
                // Tampilkan info pengadaan
                if (detailBarang.length > 0) {
                    const first = detailBarang[0];
                    document.getElementById('infoPengadaan').style.display = 'block';
                    document.getElementById('namaVendor').textContent = first.nama_vendor || '-';
                    document.getElementById('statusPengadaan').textContent = first.status_pengadaan_text || '-';
                }
                
                document.getElementById('btnSubmit').disabled = false;
            } else {
                alert('Gagal mengambil detail: ' + data.message);
                resetForm();
            }
        })
        .catch(err => {
            console.error(err);
            alert('Terjadi kesalahan saat mengambil data');
            resetForm();
        });
});

// Render tabel barang
function renderTable() {
    const container = document.getElementById('barangContainer');
    
    if (detailBarang.length === 0) {
        container.innerHTML = `
            <tr>
                <td colspan="9" class="text-center py-4 text-muted">
                    <i class="bi bi-inbox display-4 opacity-25"></i>
                    <p class="mt-2 mb-0">Tidak ada barang yang bisa diterima</p>
                </td>
            </tr>
        `;
        document.getElementById('tableFooter').style.display = 'none';
        return;
    }
    
    let html = '';
    detailBarang.forEach((item, index) => {
        html += `
            <tr>
                <td class="text-center">${index + 1}</td>
                <td>
                    <strong>${item.nama_barang}</strong>
                    <br><small class="text-muted">${item.jenis || '-'}</small>
                    <input type="hidden" name="barang[${index}][idbarang]" value="${item.idbarang}">
                </td>
                <td class="text-center">${item.nama_satuan}</td>
                <td class="text-end">Rp ${formatNumber(item.harga_satuan)}</td>
                <td class="text-center"><span class="badge bg-primary">${item.jumlah_pesan}</span></td>
                <td class="text-center"><span class="badge bg-success">${item.jumlah_diterima || 0}</span></td>
                <td class="text-center"><span class="badge bg-warning text-dark">${item.sisa_belum_terima}</span></td>
                <td class="text-center">
                    <input type="number" 
                           name="barang[${index}][jumlah_terima]" 
                           class="form-control form-control-sm text-center jumlah-terima" 
                           data-row="${index}"
                           data-harga="${item.harga_satuan}"
                           min="0" 
                           max="${item.sisa_belum_terima}" 
                           value="0"
                </td>
                <td class="text-end">
                    <strong class="subtotal-display" data-row="${index}">Rp 0</strong>
                </td>
            </tr>
        `;
    });
    
    container.innerHTML = html;
    document.getElementById('tableFooter').style.display = '';
    document.getElementById('badgeJumlahBarang').textContent = detailBarang.length + ' item';
    
    // Attach event listeners
    document.querySelectorAll('.jumlah-terima').forEach(input => {
        input.addEventListener('input', calculateTotal);
    });
}

// Hitung total
function calculateTotal() {
    let total = 0;
    
    document.querySelectorAll('.jumlah-terima').forEach(input => {
        const jumlah = parseFloat(input.value) || 0;
        const harga = parseFloat(input.dataset.harga) || 0;
        const row = input.dataset.row;
        const subtotal = jumlah * harga;
        
        // Update subtotal per row
        document.querySelector(`.subtotal-display[data-row="${row}"]`).textContent = 'Rp ' + formatNumber(subtotal);
        
        total += subtotal;
    });
    
    document.getElementById('displayTotal').textContent = 'Rp ' + formatNumber(total);
}

// Format number
function formatNumber(num) {
    return Math.floor(num).toLocaleString('id-ID');
}

// Reset form
function resetForm() {
    document.getElementById('barangContainer').innerHTML = `
        <tr>
            <td colspan="9" class="text-center py-4 text-muted">
                <i class="bi bi-arrow-up-circle display-4 opacity-25"></i>
                <p class="mt-2 mb-0">Pilih pengadaan terlebih dahulu</p>
            </td>
        </tr>
    `;
    document.getElementById('tableFooter').style.display = 'none';
    document.getElementById('infoPengadaan').style.display = 'none';
    document.getElementById('btnSubmit').disabled = true;
    document.getElementById('badgeJumlahBarang').textContent = '0 item';
}
</script>
@endpush