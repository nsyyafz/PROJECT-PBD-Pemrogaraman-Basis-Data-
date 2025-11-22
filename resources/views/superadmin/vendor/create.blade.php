@extends('layouts.metis.app')

@section('title', 'Tambah Vendor')

@php
    $pageTitle = 'Tambah Vendor';
    $pageDescription = 'Tambah data vendor supplier baru';
@endphp

@section('page-actions')
<a href="{{ route('superadmin.vendor.index') }}" class="btn btn-secondary">
    <i class="bi bi-arrow-left me-1"></i> Kembali
</a>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-plus-circle me-2"></i>Form Tambah Vendor
                </h5>
            </div>
            
            <div class="card-body">
                <form action="{{ route('superadmin.vendor.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <!-- Nama Vendor -->
                        <div class="col-md-6 mb-3">
                            <label for="nama_vendor" class="form-label">
                                Nama Vendor <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-building"></i>
                                </span>
                                <input type="text" 
                                       class="form-control @error('nama_vendor') is-invalid @enderror" 
                                       id="nama_vendor" 
                                       name="nama_vendor" 
                                       value="{{ old('nama_vendor') }}"
                                       placeholder="Contoh: PT Maju Sejahtera"
                                       maxlength="100"
                                       required>
                                @error('nama_vendor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted">Maksimal 100 karakter</small>
                        </div>
                        
                        <!-- Badan Hukum -->
                        <div class="col-md-6 mb-3">
                            <label for="badan_hukum" class="form-label">
                                Badan Hukum <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('badan_hukum') is-invalid @enderror" 
                                    id="badan_hukum" 
                                    name="badan_hukum" 
                                    required>
                                <option value="">-- Pilih Badan Hukum --</option>
                                <option value="PT" {{ old('badan_hukum') == 'PT' ? 'selected' : '' }}>PT (Perseroan Terbatas)</option>
                                <option value="CV" {{ old('badan_hukum') == 'CV' ? 'selected' : '' }}>CV (Commanditaire Vennootschap)</option>
                                <option value="UD" {{ old('badan_hukum') == 'UD' ? 'selected' : '' }}>UD (Usaha Dagang)</option>
                                <option value="Koperasi" {{ old('badan_hukum') == 'Koperasi' ? 'selected' : '' }}>Koperasi</option>
                                <option value="Perorangan" {{ old('badan_hukum') == 'Perorangan' ? 'selected' : '' }}>Perorangan</option>
                                <option value="Lainnya" {{ old('badan_hukum') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('badan_hukum')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Alamat -->
                        <div class="col-md-12 mb-3">
                            <label for="alamat" class="form-label">
                                Alamat
                            </label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                      id="alamat" 
                                      name="alamat" 
                                      rows="3"
                                      placeholder="Masukkan alamat lengkap vendor">{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Telepon -->
                        <div class="col-md-6 mb-3">
                            <label for="telepon" class="form-label">
                                Telepon
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-telephone"></i>
                                </span>
                                <input type="text" 
                                       class="form-control @error('telepon') is-invalid @enderror" 
                                       id="telepon" 
                                       name="telepon" 
                                       value="{{ old('telepon') }}"
                                       placeholder="Contoh: 021-1234567"
                                       maxlength="20">
                                @error('telepon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Email -->
                        <div class="col-md-6 mb-4">
                            <label for="email" class="form-label">
                                Email
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}"
                                       placeholder="Contoh: vendor@email.com"
                                       maxlength="100">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Info -->
                    <div class="alert alert-info mb-4">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Catatan:</strong> Vendor baru akan otomatis berstatus <strong>Aktif</strong>
                    </div>
                    
                    <!-- Buttons -->
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('superadmin.vendor.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection