@extends('layouts.metis.app')

@section('title', 'Edit Satuan')

@php
    $pageTitle = 'Edit Satuan';
    $pageDescription = 'Edit data satuan';
@endphp

@section('page-actions')
<a href="{{ route('superadmin.satuan.index') }}" class="btn btn-secondary">
    <i class="bi bi-arrow-left me-1"></i> Kembali
</a>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-pencil-square me-2"></i>Form Edit Satuan
                </h5>
            </div>
            
            <div class="card-body">
                <form action="{{ route('superadmin.satuan.update', $satuan->idsatuan) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <!-- Nama Satuan -->
                        <div class="col-md-6 mb-3">
                            <label for="nama_satuan" class="form-label">
                                Nama Satuan <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('nama_satuan') is-invalid @enderror" 
                                   id="nama_satuan" 
                                   name="nama_satuan" 
                                   value="{{ old('nama_satuan', $satuan->nama_satuan) }}"
                                   placeholder="Contoh: Kg, Liter, Pcs, Dus"
                                   maxlength="45"
                                   required>
                            @error('nama_satuan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Maksimal 45 karakter</small>
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
                                <option value="1" {{ old('status', $satuan->status ?? 1) == 1 ? 'selected' : '' }}>
                                    Aktif
                                </option>
                                <option value="0" {{ old('status', $satuan->status ?? 1) == 0 ? 'selected' : '' }}>
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
                        <strong>Perhatian:</strong> Menonaktifkan satuan akan membuatnya tidak muncul di daftar satuan aktif
                    </div>
                    
                    <!-- Buttons -->
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('superadmin.satuan.index') }}" class="btn btn-secondary">
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