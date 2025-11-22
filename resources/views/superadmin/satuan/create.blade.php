@extends('layouts.metis.app')

@section('title', 'Tambah Satuan')

@php
    $pageTitle = 'Tambah Satuan';
    $pageDescription = 'Tambah data satuan baru';
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
                    <i class="bi bi-plus-circle me-2"></i>Form Tambah Satuan
                </h5>
            </div>
            
            <div class="card-body">
                <form action="{{ route('superadmin.satuan.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <!-- Nama Satuan -->
                        <div class="col-md-6 mb-4">
                            <label for="nama_satuan" class="form-label">
                                Nama Satuan <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('nama_satuan') is-invalid @enderror" 
                                   id="nama_satuan" 
                                   name="nama_satuan" 
                                   value="{{ old('nama_satuan') }}"
                                   placeholder="Contoh: Kg, Liter, Pcs, Dus"
                                   maxlength="45"
                                   required>
                            @error('nama_satuan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Maksimal 45 karakter</small>
                        </div>
                    </div>
                    
                    <!-- Info -->
                    <div class="alert alert-info mb-4">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Catatan:</strong> Satuan baru akan otomatis berstatus <strong>Aktif</strong>
                    </div>
                    
                    <!-- Buttons -->
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('superadmin.satuan.index') }}" class="btn btn-secondary">
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