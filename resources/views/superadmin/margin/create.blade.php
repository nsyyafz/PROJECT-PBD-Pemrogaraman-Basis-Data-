@extends('layouts.metis.app')

@section('title', 'Tambah Margin Penjualan')

@php
    $pageTitle = 'Tambah Margin Penjualan';
    $pageDescription = 'Tambah data margin penjualan baru';
@endphp

@section('page-actions')
<a href="{{ route('superadmin.margin.index') }}" class="btn btn-secondary">
    <i class="bi bi-arrow-left me-1"></i> Kembali
</a>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-plus-circle me-2"></i>Form Tambah Margin Penjualan
                </h5>
            </div>
            
            <div class="card-body">
                <form action="{{ route('superadmin.margin.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <!-- Persentase Margin -->
                        <div class="col-md-6 mb-4">
                            <label for="persen" class="form-label">
                                Persentase Margin <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="number" 
                                       class="form-control @error('persen') is-invalid @enderror" 
                                       id="persen" 
                                       name="persen" 
                                       value="{{ old('persen') }}"
                                       placeholder="0.00"
                                       min="0"
                                       max="1"
                                       step="0.01"
                                       required>
                                <span class="input-group-text">
                                    <i class="bi bi-percent"></i>
                                </span>
                                @error('persen')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted">Contoh: 0.05 untuk margin 5%</small>
                        </div>
                    </div>
                    
                    <!-- Info -->
                    <div class="alert alert-info mb-4">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Catatan:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Margin baru akan otomatis berstatus <strong>Aktif</strong></li>
                            <li>Margin aktif sebelumnya akan otomatis dinonaktifkan</li>
                            <li>Hanya satu margin yang bisa aktif dalam satu waktu</li>
                        </ul>
                    </div>
                    
                    <!-- Buttons -->
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('superadmin.margin.index') }}" class="btn btn-secondary">
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