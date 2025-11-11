@extends('layouts.superadmin')

@section('title', 'Tambah Penerimaan Barang - Sistem Inventory')

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
        border-bottom: 3px solid #1e3c72;
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
        color: #1e3c72;
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
        border-color: #1e3c72;
        box-shadow: 0 0 0 0.2rem rgba(30, 60, 114, 0.15);
    }

    .form-control:disabled {
        background: #f8f9fa;
        color: #6c757d;
        font-weight: 600;
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

    .btn-load-preview {
        background: linear-gradient(135deg, #3498db, #2980b9);
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

    .btn-load-preview:hover {
        background: linear-gradient(135deg, #2980b9, #3498db);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
    }

    .btn-load-preview:disabled {
        background: #95a5a6;
        cursor: not-allowed;
        transform: none;
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

    .input-terima {
        border: 2px solid #e8eaed;
        border-radius: 6px;
        text-align: center;
        font-weight: 600;
    }

    .input-terima:focus {
        border-color: #27ae60;
        box-shadow: 0 0 0 0.2rem rgba(39, 174, 96, 0.15);
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
        background: linear-gradient(135deg, #27ae60, #2ecc71);
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
        background: linear-gradient(135deg, #2ecc71, #27ae60);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(39, 174, 96, 0.3);
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
    }
</style>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h2><i class="fas fa-truck-loading me-2"></i>Tambah Penerimaan Barang</h2>
        <p>Catat barang yang diterima dari pengadaan</p>
    </div>
</div>

<!-- Alert Messages -->
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        <strong>Error!</strong> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Form Card -->
<div class="form-card">
    <div class="card-header">
        <h5><i class="fas fa-file-invoice me-2"></i>Form Penerimaan Barang</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('superadmin.penerimaan.store') }}" method="POST" id="formPenerimaan">
            @csrf
            
            <!-- Info Box -->
            <div class="info-box">
                <i class="fas fa-info-circle"></i>
                <p><strong>Informasi:</strong> Pilih pengadaan yang sudah disetujui (Approved), lalu sistem akan menampilkan detail barang yang perlu diterima.</p>
            </div>

            <!-- Header Penerimaan Section -->
            <div class="section-title">
                <i class="fas fa-file-alt"></i>
                Informasi Penerimaan
            </div>

            <div class="row mb-4">
                <div class="col-md-3">
                    <label class="form-label">ID Penerimaan</label>
                    <input type="text" class="form-control" value="AUTO GENERATE" disabled>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal</label>
                    <input type="text" class="form-control" value="{{ date('d/m/Y H:i') }}" disabled>
                </div>
                <div class="col-md-3">
                    <label class="form-label">User</label>
                    <input type="text" class="form-control" value="{{ $currentUser->username ?? $currentUser->nama ?? 'N/A' }}" disabled>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <input type="text" class="form-control" value="Pending" disabled>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-9">
                    <label class="form-label">Pilih Pengadaan <span class="required-mark">*</span></label>
                    <select name="idpengadaan" id="ddlPengadaan" class="form-select @error('idpengadaan') is-invalid @enderror" required>
                        <option value="">-- Pilih Pengadaan yang Sudah Approved --</option>
                        @foreach($pengadaans as $p)
                            <option value="{{ $p->idpengadaan }}" {{ old('idpengadaan') == $p->idpengadaan ? 'selected' : '' }}>
                                PO-{{ str_pad($p->idpengadaan, 4, '0', STR_PAD_LEFT) }} 
                                - {{ $p->nama_vendor }} 
                                ({{ \Carbon\Carbon::parse($p->timestamp)->format('d/m/Y') }})
                                - Rp {{ number_format($p->total_nilai, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                    @error('idpengadaan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="button" class="btn-load-preview w-100" id="btnLoadPreview">
                        <i class="fas fa-search"></i> Tampilkan Detail
                    </button>
                </div>
            </div>
            
            <!-- Preview Detail Pengadaan -->
            <div id="previewContainer" style="display: none;">
                <div class="section-title">
                    <i class="fas fa-boxes"></i>
                    Detail Barang yang Dipesan
                </div>

                <div class="info-box" style="background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%); border-left-color: #f39c12;">
                    <i class="fas fa-exclamation-triangle" style="color: #f39c12;"></i>
                    <p style="color: #856404;"><strong>Perhatian:</strong> Edit jumlah yang diterima sesuai kondisi fisik. Hapus baris jika barang belum datang atau tidak lengkap.</p>
                </div>
                
                <div class="table-responsive mb-4">
                    <table class="table table-modern" id="tabelDetailBarang">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="10%">ID Barang</th>
                                <th width="25%">Nama Barang</th>
                                <th width="10%">Satuan</th>
                                <th width="10%">Dipesan</th>
                                <th width="10%">Sisa</th>
                                <th width="12%" class="text-end">Harga</th>
                                <th width="13%">Jumlah Terima</th>
                                <th width="5%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyDetailBarang">
                            <!-- Data akan diisi via AJAX -->
                        </tbody>
                    </table>
                </div>
                
                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="{{ route('superadmin.penerimaan.index') }}" class="btn-cancel">
                        <i class="fas fa-times-circle"></i> Batal
                    </a>
                    <button type="submit" class="btn-submit" id="btnSimpan">
                        <i class="fas fa-save"></i> Simpan Penerimaan
                    </button>
                </div>
            </div>
            
        </form>
    </div>
</div>

@endsection

@push('scripts')
<!-- Load jQuery terlebih dahulu -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    
    // Load preview detail pengadaan
    $('#btnLoadPreview').click(function() {
        const idPengadaan = $('#ddlPengadaan').val();
        
        if (!idPengadaan) {
            alert('Pilih pengadaan terlebih dahulu!');
            return;
        }
        
        // Show loading
        $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memuat...');
        
        // AJAX call SP Preview
        $.ajax({
            url: '{{ route("superadmin.penerimaan.getDetailPengadaan") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                idpengadaan: idPengadaan
            },
            success: function(response) {
                if (response.success) {
                    renderDetailTable(response.data);
                    $('#previewContainer').fadeIn();
                } else {
                    alert('Gagal memuat detail: ' + response.message);
                }
            },
            error: function(xhr) {
                console.error('AJAX Error:', xhr);
                alert('Error: ' + (xhr.responseJSON?.message || 'Terjadi kesalahan'));
            },
            complete: function() {
                $('#btnLoadPreview').prop('disabled', false).html('<i class="fas fa-search"></i> Tampilkan Detail');
            }
        });
    });
    
    // Render tabel detail
    function renderDetailTable(data) {
        let html = '';
        
        console.log('Data dari SP:', data); // Debug: lihat struktur data
        
        if (data.length === 0) {
            html = '<tr><td colspan="9"><div class="empty-state-table"><i class="fas fa-check-circle"></i><p>Semua barang dari pengadaan ini sudah diterima</p></div></td></tr>';
        } else {
            data.forEach((item, index) => {
                // Cek nama kolom yang ada (bisa berbeda tergantung SP/View)
                const idBarang = item.idbarang || item.barang_idbarang;
                const namaBarang = item.nama_barang || item.nama;
                const namaSatuan = item.nama_satuan || item.satuan;
                const jumlahDipesan = item.jumlah_dipesan || item.jumlah;
                const sisaBelumDiterima = item.sisa_belum_diterima || item.sisa || jumlahDipesan;
                const hargaSatuan = item.harga_satuan || item.harga;
                
                html += `
                    <tr data-idbarang="${idBarang}">
                        <td class="text-center"><strong>${index + 1}</strong></td>
                        <td><strong class="text-primary">${idBarang}</strong></td>
                        <td>${namaBarang}</td>
                        <td><span class="badge bg-secondary">${namaSatuan}</span></td>
                        <td><strong>${jumlahDipesan}</strong></td>
                        <td><strong class="text-warning">${sisaBelumDiterima}</strong></td>
                        <td class="text-end">Rp ${formatRupiah(hargaSatuan)}</td>
                        <td>
                            <input 
                                type="number" 
                                class="form-control input-terima" 
                                name="barang[${index}][jumlah_terima]"
                                value="${sisaBelumDiterima}" 
                                min="1" 
                                max="${sisaBelumDiterima}"
                                required
                            >
                            <input type="hidden" name="barang[${index}][idbarang]" value="${idBarang}">
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn-delete-item" title="Hapus baris">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
        }
        
        $('#tbodyDetailBarang').html(html);
    }
    
    // Hapus baris
    $(document).on('click', '.btn-delete-item', function() {
        if (confirm('Hapus barang ini dari penerimaan?')) {
            $(this).closest('tr').remove();
            
            // Re-index form array
            reindexFormArray();

            // Cek jika tidak ada barang
            if ($('#tbodyDetailBarang tr').length === 0) {
                $('#tbodyDetailBarang').html('<tr><td colspan="9"><div class="empty-state-table"><i class="fas fa-inbox"></i><p>Tidak ada barang yang akan diterima. Pilih pengadaan lain atau tambahkan kembali barang.</p></div></td></tr>');
            }
        }
    });
    
    // Re-index form array setelah delete
    function reindexFormArray() {
        $('#tbodyDetailBarang tr').each(function(index) {
            $(this).find('td:first strong').text(index + 1);
            $(this).find('input[name*="barang"]').each(function() {
                const name = $(this).attr('name');
                const newName = name.replace(/\[\d+\]/, `[${index}]`);
                $(this).attr('name', newName);
            });
        });
    }
    
    // Format rupiah
    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID').format(angka);
    }
    
    // Validasi sebelum submit
    $('#formPenerimaan').submit(function(e) {
        const rowCount = $('#tbodyDetailBarang tr').length;
        
        if (rowCount === 0 || $('#tbodyDetailBarang tr td[colspan]').length > 0) {
            e.preventDefault();
            alert('Tidak ada barang yang akan diterima! Tambahkan minimal 1 barang.');
            return false;
        }
        
        // Cek apakah ada input yang kosong atau invalid
        let isValid = true;
        $('.input-terima').each(function() {
            const val = parseInt($(this).val());
            if (!val || val <= 0) {
                isValid = false;
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Jumlah terima harus lebih dari 0 dan tidak boleh kosong!');
            return false;
        }
        
        return true;
    });

    // Auto dismiss alerts
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
    
});
</script>
@endpush