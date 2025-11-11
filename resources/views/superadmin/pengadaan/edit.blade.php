{{-- File: resources/views/superadmin/pengadaan/edit.blade.php --}}
@extends('layouts.superadmin')

@section('title', 'Edit Pengadaan - Sistem Inventory')

@section('content')

<style>
    .page-header {
        background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
        border-radius: 12px;
        padding: 2rem 2.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(243, 156, 18, 0.15);
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

    .form-card {
        background: white;
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.06);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .form-card .card-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-bottom: 3px solid #f39c12;
        padding: 1.5rem 2rem;
    }

    .form-card .card-header h5 {
        margin: 0;
        color: #2c3e50;
        font-weight: 600;
        font-size: 18px;
    }

    .form-card .card-body {
        padding: 2rem;
    }

    .section-title {
        font-size: 16px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #e8eaed;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-title i {
        color: #f39c12;
        font-size: 18px;
    }

    .form-label {
        color: #2c3e50;
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-bottom: 0.5rem;
    }

    .required-mark {
        color: #e74c3c;
        font-weight: 700;
    }

    .form-control, .form-select {
        border: 2px solid #e8eaed;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #f39c12;
        box-shadow: 0 0 0 0.2rem rgba(243, 156, 18, 0.15);
    }

    .form-control:disabled {
        background: #f8f9fa;
        color: #6c757d;
        font-weight: 600;
    }

    .is-invalid {
        border-color: #e74c3c;
    }

    .invalid-feedback {
        color: #e74c3c;
        font-size: 12px;
        margin-top: 0.25rem;
    }

    .info-box {
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        border-left: 4px solid #2196f3;
        border-radius: 8px;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
    }

    .info-box i {
        color: #2196f3;
        font-size: 18px;
        margin-right: 8px;
    }

    .info-box p {
        margin: 0;
        color: #1976d2;
        font-size: 13px;
        font-weight: 500;
    }

    .add-item-card {
        background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%);
        border: 2px dashed #f39c12;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .add-item-card h6 {
        color: #f39c12;
        font-weight: 700;
        font-size: 15px;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-add-item {
        background: linear-gradient(135deg, #27ae60, #2ecc71);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-add-item:hover {
        background: linear-gradient(135deg, #229954, #27ae60);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(39, 174, 96, 0.3);
        color: white;
    }

    .btn-add-item:disabled {
        background: #95a5a6;
        cursor: not-allowed;
        transform: none;
    }

    .input-group-text {
        background: #e9ecef;
        border: 2px solid #e8eaed;
        border-right: none;
        border-radius: 8px 0 0 8px;
        color: #495057;
        font-weight: 600;
    }

    .input-group .form-control {
        border-left: none;
        border-radius: 0 8px 8px 0;
    }

    .table-modern {
        margin: 0;
        border: 2px solid #e8eaed;
        border-radius: 12px;
        overflow: hidden;
    }

    .table-modern thead {
        background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
        color: white;
    }

    .table-modern thead th {
        padding: 1rem 1.5rem;
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

    .table-modern tbody td {
        padding: 1rem 1.5rem;
        vertical-align: middle;
        color: #2c3e50;
        font-size: 14px;
    }

    .table-modern tfoot {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        font-weight: 600;
    }

    .table-modern tfoot th {
        padding: 1rem 1.5rem;
        color: #2c3e50;
        border-top: 2px solid #dee2e6;
    }

    .table-modern tfoot .total-row {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    }

    .table-modern tfoot .total-row th {
        color: #155724;
        font-size: 16px;
        font-weight: 700;
    }

    .empty-state-table {
        text-align: center;
        padding: 3rem 2rem;
        color: #7f8c8d;
    }

    .empty-state-table i {
        font-size: 48px;
        margin-bottom: 1rem;
        opacity: 0.3;
    }

    .empty-state-table p {
        margin: 0;
        font-size: 14px;
    }

    .input-jumlah {
        border: 2px solid #e8eaed;
        border-radius: 6px;
        text-align: center;
        font-weight: 600;
        width: 80px;
    }

    .btn-delete-item {
        background: #e74c3c;
        color: white;
        border: none;
        border-radius: 6px;
        padding: 6px 12px;
        font-size: 13px;
        transition: all 0.3s ease;
    }

    .btn-delete-item:hover {
        background: #c0392b;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(231, 76, 60, 0.3);
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        padding-top: 2rem;
        border-top: 2px solid #e8eaed;
        margin-top: 2rem;
    }

    .btn-cancel {
        background: #95a5a6;
        color: white;
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-cancel:hover {
        background: #7f8c8d;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(149, 165, 166, 0.3);
        color: white;
    }

    .btn-submit {
        background: linear-gradient(135deg, #f39c12, #e67e22);
        color: white;
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-submit:hover {
        background: linear-gradient(135deg, #e67e22, #f39c12);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(243, 156, 18, 0.3);
    }

    .btn-submit:disabled {
        background: #95a5a6;
        cursor: not-allowed;
        transform: none;
    }

    .text-price {
        color: #27ae60;
        font-weight: 700;
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
        }

        .page-header h2 {
            font-size: 22px;
        }

        .form-card .card-body {
            padding: 1.5rem;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn-cancel, .btn-submit {
            width: 100%;
            justify-content: center;
        }

        .table-modern {
            font-size: 12px;
        }
    }
</style>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h2><i class="fas fa-edit me-2"></i>Edit Pengadaan</h2>
        <p>Ubah data pengadaan yang masih berstatus Pending</p>
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

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        <strong>Validasi Error:</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Form Card -->
<div class="form-card">
    <div class="card-header">
        <h5><i class="fas fa-file-invoice me-2"></i>Form Edit Pengadaan</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('superadmin.pengadaan.update', $pengadaan->idpengadaan) }}" 
              method="POST" 
              id="formPengadaan">
            @csrf
            @method('PUT')
            
            <!-- Info Box -->
            <div class="info-box">
                <i class="fas fa-info-circle"></i>
                <p><strong>Perhatian:</strong> Perubahan akan menghitung ulang semua nilai. Detail lama akan diganti dengan yang baru.</p>
            </div>

            <!-- Header Pengadaan Section -->
            <div class="section-title">
                <i class="fas fa-file-alt"></i>
                Informasi Pengadaan
            </div>

            <div class="row mb-4">
                <div class="col-md-3">
                    <label class="form-label">ID Pengadaan</label>
                    <input type="text" class="form-control" 
                           value="PO-{{ str_pad($pengadaan->idpengadaan, 4, '0', STR_PAD_LEFT) }}" 
                           disabled>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal</label>
                    <input type="text" class="form-control" 
                           value="{{ \Carbon\Carbon::parse($pengadaan->timestamp)->format('d/m/Y H:i') }}" 
                           disabled>
                </div>
                <div class="col-md-3">
                    <label class="form-label">User</label>
                    <input type="text" class="form-control" 
                        value="{{ $currentUser->username ?? $currentUser->nama ?? 'N/A' }}" 
                        disabled>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <input type="text" class="form-control" value="Pending" disabled>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label">Vendor <span class="required-mark">*</span></label>
                    <select name="vendor_idvendor" class="form-select @error('vendor_idvendor') is-invalid @enderror" required>
                        <option value="">-- Pilih Vendor --</option>
                        @foreach($vendors as $v)
                            <option value="{{ $v->idvendor }}" 
                                {{ old('vendor_idvendor', $pengadaan->vendor_idvendor) == $v->idvendor ? 'selected' : '' }}>
                                {{ $v->nama_vendor }} ({{ $v->jenis_badan_hukum }})
                            </option>
                        @endforeach
                    </select>
                    @error('vendor_idvendor')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">PPN (%) <span class="required-mark">*</span></label>
                    <input type="number" 
                           name="ppn" 
                           id="inputPPN" 
                           class="form-control @error('ppn') is-invalid @enderror" 
                           value="{{ old('ppn', $pengadaan->ppn) }}" 
                           min="0" 
                           step="0.01" 
                           required>
                    @error('ppn')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Contoh: 10 untuk PPN 10%</small>
                </div>
            </div>
            
            <!-- Detail Barang Section -->
            <div class="section-title">
                <i class="fas fa-boxes"></i>
                Detail Barang
            </div>

            <!-- Form Tambah Barang -->
            <div class="add-item-card">
                <h6><i class="fas fa-plus-circle"></i> Tambah Barang ke Pengadaan</h6>
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Pilih Barang</label>
                        <select id="selectBarang" class="form-select">
                            <option value="">-- Pilih Barang --</option>
                            @foreach($barangs as $b)
                                <option value="{{ $b->idbarang }}" 
                                        data-nama="{{ $b->nama_barang }}"
                                        data-satuan="{{ $b->nama_satuan }}">
                                    {{ $b->nama_barang }} ({{ $b->nama_satuan }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Harga Satuan</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" id="displayHarga" class="form-control" readonly placeholder="0">
                        </div>
                        <input type="hidden" id="hargaSatuan">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Jumlah</label>
                        <input type="number" id="inputJumlah" class="form-control" min="1" value="1" placeholder="0">
                    </div>
                    <div class="col-md-3">
                        <button type="button" id="btnTambahBarang" class="btn-add-item" disabled>
                            <i class="fas fa-plus me-2"></i>Tambah Barang
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Tabel Detail Barang -->
            <div class="table-responsive mb-4">
                <table class="table table-modern" id="tabelDetailBarang">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="10%">ID Barang</th>
                            <th width="30%">Nama Barang</th>
                            <th width="10%">Satuan</th>
                            <th width="15%" class="text-end">Harga Satuan</th>
                            <th width="10%">Jumlah</th>
                            <th width="15%" class="text-end">Subtotal</th>
                            <th width="5%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyDetailBarang">
                        <tr id="emptyRow">
                            <td colspan="8">
                                <div class="empty-state-table">
                                    <i class="fas fa-shopping-cart"></i>
                                    <p>Belum ada barang. Tambahkan dari form di atas.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="6" class="text-end">Subtotal:</th>
                            <th class="text-end">
                                <span id="displaySubtotal">Rp 0</span>
                            </th>
                            <th></th>
                        </tr>
                        <tr>
                            <th colspan="6" class="text-end">
                                PPN (<span id="displayPPNPercent">{{ $pengadaan->ppn }}</span>%):
                            </th>
                            <th class="text-end">
                                <span id="displayPPNNilai">Rp 0</span>
                            </th>
                            <th></th>
                        </tr>
                        <tr class="total-row">
                            <th colspan="6" class="text-end">Total Nilai:</th>
                            <th class="text-end">
                                <span id="displayTotal" class="text-price">Rp 0</span>
                            </th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="{{ route('superadmin.pengadaan.index') }}" class="btn-cancel">
                    <i class="fas fa-times-circle"></i> Batal
                </a>
                <button type="submit" class="btn-submit" id="btnSubmit" disabled>
                    <i class="fas fa-save"></i> Update Pengadaan
                </button>
            </div>
            
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let detailBarang = [];
    let rowCounter = 0;

    // Elements
    const selectBarang = document.getElementById('selectBarang');
    const displayHarga = document.getElementById('displayHarga');
    const hargaSatuan = document.getElementById('hargaSatuan');
    const btnTambah = document.getElementById('btnTambahBarang');
    const inputJumlah = document.getElementById('inputJumlah');
    const inputPPN = document.getElementById('inputPPN');
    const tbody = document.getElementById('tbodyDetailBarang');
    const emptyRow = document.getElementById('emptyRow');

    // ============================================================
    // EVENT: Pilih Barang - Ambil Harga dari Database
    // ============================================================
    selectBarang.addEventListener('change', function() {
        const idbarang = this.value;
        
        if (!idbarang) {
            displayHarga.value = '0';
            hargaSatuan.value = '';
            btnTambah.disabled = true;
            return;
        }

        btnTambah.disabled = true;
        displayHarga.value = 'Loading...';
        
        // HANYA 1 API CALL: Get Harga Barang
        fetch('{{ route("superadmin.pengadaan.getHargaBarang") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ idbarang: idbarang })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.harga) {
                const harga = parseFloat(data.harga);
                hargaSatuan.value = harga;
                displayHarga.value = formatRupiah(harga);
                btnTambah.disabled = false;
            } else {
                alert('Gagal: ' + (data.message || 'Harga tidak tersedia'));
                displayHarga.value = '0';
                hargaSatuan.value = '';
                btnTambah.disabled = true;
            }
        })
        .catch(error => {
            console.error('Fetch Error:', error);
            alert('Error: ' + error.message);
            displayHarga.value = '0';
            hargaSatuan.value = '';
            btnTambah.disabled = true;
        });
    });

    // ============================================================
    // EVENT: Tambah Barang - Hitung Client-Side (Preview Only)
    // ============================================================
    btnTambah.addEventListener('click', function() {
        const idbarang = selectBarang.value;
        const selectedOption = selectBarang.options[selectBarang.selectedIndex];
        const namaBarang = selectedOption.getAttribute('data-nama');
        const satuan = selectedOption.getAttribute('data-satuan');
        const harga = parseFloat(hargaSatuan.value);
        const jumlah = parseInt(inputJumlah.value);

        // Validasi
        if (!idbarang) {
            alert('Pilih barang terlebih dahulu!');
            return;
        }

        if (!harga || harga <= 0) {
            alert('Harga barang belum tersedia!');
            return;
        }

        if (!jumlah || jumlah <= 0) {
            alert('Masukkan jumlah yang valid!');
            return;
        }

        // Cek duplikat
        const isDuplicate = detailBarang.some(item => item.idbarang === idbarang);
        if (isDuplicate) {
            alert('Barang sudah ditambahkan!');
            return;
        }

        // HITUNG SUBTOTAL CLIENT-SIDE (untuk preview saja)
        // Perhitungan final akan dilakukan di SP saat submit
        const subtotal = harga * jumlah;

        // Tambah ke array
        detailBarang.push({
            id: rowCounter,
            idbarang: idbarang,
            nama: namaBarang,
            satuan: satuan,
            harga: harga,
            jumlah: jumlah,
            subtotal: subtotal
        });

        rowCounter++;

        // Update UI
        renderTable();
        hitungTotalPengadaan();
        updateSubmitButton();

        // Reset form
        selectBarang.value = '';
        displayHarga.value = '0';
        inputJumlah.value = '1';
        hargaSatuan.value = '';
        btnTambah.disabled = true;
    });

    // ============================================================
    // RENDER TABLE
    // ============================================================
    function renderTable() {
        if (detailBarang.length === 0) {
            emptyRow.style.display = 'table-row';
            return;
        }

        emptyRow.style.display = 'none';
        let html = '';

        detailBarang.forEach(function(item, index) {
            html += '<tr data-id="' + item.id + '">';
            html += '<td class="text-center"><strong>' + (index + 1) + '</strong></td>';
            html += '<td><strong class="text-primary">#' + item.idbarang + '</strong></td>';
            html += '<td>' + item.nama + '</td>';
            html += '<td><span class="badge bg-secondary">' + item.satuan + '</span></td>';
            html += '<td class="text-end">Rp ' + formatRupiah(item.harga) + '</td>';
            html += '<td>';
            html += '<input type="number" class="form-control form-control-sm input-jumlah" ';
            html += 'data-id="' + item.id + '" ';
            html += 'value="' + item.jumlah + '" min="1">';
            
            // Hidden inputs untuk form submit
            html += '<input type="hidden" name="barang[' + index + '][idbarang]" value="' + item.idbarang + '">';
            html += '<input type="hidden" name="barang[' + index + '][jumlah]" value="' + item.jumlah + '" ';
            html += 'id="hidden-jumlah-' + item.id + '">';
            html += '</td>';
            html += '<td class="text-end text-price" id="subtotal-' + item.id + '">Rp ' + formatRupiah(item.subtotal) + '</td>';
            html += '<td class="text-center">';
            html += '<button type="button" class="btn-delete-item" data-id="' + item.id + '">';
            html += '<i class="fas fa-trash"></i>';
            html += '</button>';
            html += '</td>';
            html += '</tr>';
        });

        tbody.innerHTML = html;

        // Re-attach event listeners
        attachEventListeners();
    }

    // ============================================================
    // ATTACH EVENT LISTENERS (Update Jumlah & Delete)
    // ============================================================
    function attachEventListeners() {
        // Update Jumlah - HITUNG CLIENT-SIDE
        document.querySelectorAll('.input-jumlah').forEach(function(input) {
            input.addEventListener('change', function() {
                const id = parseInt(this.getAttribute('data-id'));
                const jumlahBaru = parseInt(this.value) || 1;
                
                const index = detailBarang.findIndex(item => item.id === id);
                
                if (index !== -1) {
                    // Update jumlah
                    detailBarang[index].jumlah = jumlahBaru;
                    
                    // Hitung ulang subtotal CLIENT-SIDE (preview)
                    detailBarang[index].subtotal = detailBarang[index].harga * jumlahBaru;

                    // Update UI
                    document.getElementById('subtotal-' + id).textContent = 
                        'Rp ' + formatRupiah(detailBarang[index].subtotal);
                    document.getElementById('hidden-jumlah-' + id).value = jumlahBaru;

                    // Hitung ulang total
                    hitungTotalPengadaan();
                }
            });
        });

        // Delete Item
        document.querySelectorAll('.btn-delete-item').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const id = parseInt(this.getAttribute('data-id'));
                
                if (confirm('Hapus barang ini dari pengadaan?')) {
                    detailBarang = detailBarang.filter(item => item.id !== id);
                    
                    renderTable();
                    hitungTotalPengadaan();
                    updateSubmitButton();
                }
            });
        });
    }

    // ============================================================
    // EVENT: Update PPN
    // ============================================================
    inputPPN.addEventListener('input', function() {
        const ppn = parseFloat(this.value) || 0;
        document.getElementById('displayPPNPercent').textContent = ppn.toFixed(2);
        hitungTotalPengadaan();
    });

    // ============================================================
    // HITUNG TOTAL PENGADAAN - CLIENT SIDE (Preview Only)
    // Perhitungan final akan dilakukan oleh SP saat submit
    // ============================================================
    function hitungTotalPengadaan() {
        // Hitung subtotal dari semua item
        const subtotal = detailBarang.reduce((sum, item) => sum + item.subtotal, 0);
        
        // Ambil PPN
        const ppn = parseFloat(inputPPN.value) || 0;
        
        // Hitung PPN (gunakan FLOOR seperti di function MySQL)
        const nilaiPPN = Math.floor(subtotal * ppn / 100);
        
        // Hitung total
        const total = subtotal + nilaiPPN;
        
        // Update display
        document.getElementById('displaySubtotal').textContent = 'Rp ' + formatRupiah(subtotal);
        document.getElementById('displayPPNNilai').textContent = 'Rp ' + formatRupiah(nilaiPPN);
        document.getElementById('displayTotal').textContent = 'Rp ' + formatRupiah(total);
    }

    // ============================================================
    // UPDATE SUBMIT BUTTON STATE
    // ============================================================
    function updateSubmitButton() {
        document.getElementById('btnSubmit').disabled = (detailBarang.length === 0);
    }

    // ============================================================
    // FORMAT RUPIAH
    // ============================================================
    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID').format(Math.round(angka));
    }

    // ============================================================
    // FORM SUBMIT VALIDATION
    // ============================================================
    document.getElementById('formPengadaan').addEventListener('submit', function(e) {
        if (detailBarang.length === 0) {
            e.preventDefault();
            alert('Tambahkan minimal 1 barang!');
            return false;
        }
        
        // Tampilkan loading state
        const btnSubmit = document.getElementById('btnSubmit');
        btnSubmit.disabled = true;
        btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
    });

    // ============================================================
    // AUTO DISMISS ALERTS
    // ============================================================
    setTimeout(function() {
        document.querySelectorAll('.alert').forEach(function(alert) {
            alert.style.display = 'none';
        });
    }, 5000);
});
</script>
@endpush