@extends('layouts.metis.app')

@section('title', 'Edit Vendor')

@php
    $pageTitle = 'Edit Vendor';
    $pageDescription = 'Edit data vendor supplier';
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
                    <i class="bi bi-pencil-square me-2"></i>Form Edit Vendor
                </h5>
            </div>
            
            <div class="card-body">
                <form action="{{ route('superadmin.vendor.update', $vendor->idvendor) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
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
                                       value="{{ old('nama_vendor', $vendor->nama_vendor) }}"
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
                                <option value="P" {{ old('badan_hukum', $vendor->badan_hukum) == 'P' ? 'selected' : '' }}>PT (Perseroan Terbatas)</option>
                                <option value="C" {{ old('badan_hukum', $vendor->badan_hukum) == 'C' ? 'selected' : '' }}>CV (Commanditaire Vennootschap)</option>
                                <option value="U" {{ old('badan_hukum', $vendor->badan_hukum) == 'U' ? 'selected' : '' }}>UD (Usaha Dagang)</option>
                            </select>
                            @error('badan_hukum')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Status -->
                        <div class="col-md-6 mb-4">
                            <label for="status" class="form-label">
                                Status <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" 
                                    name="status" 
                                    required>
                                <option value="A" {{ old('status', $vendor->status ?? 'A') == 'A' ? 'selected' : '' }}>
                                    Aktif
                                </option>
                                <option value="N" {{ old('status', $vendor->status ?? 'A') == 'N' ? 'selected' : '' }}>
                                    Nonaktif
                                </option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Info -->
                    <div class="alert alert-warning mb-4">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Perhatian:</strong> Menonaktifkan vendor akan membuatnya tidak muncul di daftar vendor aktif
                    </div>
                    
                    <!-- Buttons -->
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('superadmin.vendor.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection