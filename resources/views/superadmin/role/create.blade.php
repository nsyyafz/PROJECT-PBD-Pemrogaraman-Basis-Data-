@extends('layouts.metis.app')

@section('title', 'Tambah Role')

@php
    $pageTitle = 'Tambah Role';
    $pageDescription = 'Tambah role pengguna baru';
@endphp

@section('page-actions')
<a href="{{ route('superadmin.role.index') }}" class="btn btn-secondary">
    <i class="bi bi-arrow-left me-1"></i> Kembali
</a>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-plus-circle me-2"></i>Form Tambah Role
                </h5>
            </div>
            
            <div class="card-body">
                <form action="{{ route('superadmin.role.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <!-- Nama Role -->
                        <div class="col-md-6 mb-4">
                            <label for="nama_role" class="form-label">
                                Nama Role <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('nama_role') is-invalid @enderror" 
                                   id="nama_role" 
                                   name="nama_role" 
                                   value="{{ old('nama_role') }}"
                                   placeholder="Contoh: Manager, Staff Gudang"
                                   maxlength="100"
                                   required>
                            @error('nama_role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Maksimal 100 karakter</small>
                        </div>
                    </div>
                    
                    <!-- Info -->
                    <div class="alert alert-info mb-4">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Catatan:</strong> Role baru dapat langsung digunakan untuk assign ke user
                    </div>
                    
                    <!-- Buttons -->
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('superadmin.role.index') }}" class="btn btn-secondary">
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