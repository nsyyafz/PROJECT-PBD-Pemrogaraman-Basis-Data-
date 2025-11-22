@extends('layouts.metis.app')

@section('title', 'Edit Margin Penjualan')

@php
    $pageTitle = 'Edit Margin Penjualan';
    $pageDescription = 'Edit data margin penjualan';
@endphp

@section('page-actions')
<a href="{{ route('superadmin.margin.index') }}" class="btn btn-secondary">
    <i class="bi bi-arrow-left me-1"></i> Kembali
</a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-pencil-square me-2"></i>Form Edit Margin Penjualan
                </h5>
            </div>
            
            <div class="card-body">
                <form action="{{ route('superadmin.margin.update', $margin->idmargin_penjualan) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <!-- Persentase Margin -->
                    <div class="mb-3">
                        <label for="persen" class="form-label">
                            Persentase Margin <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="number" 
                                   class="form-control @error('persen') is-invalid @enderror" 
                                   id="persen" 
                                   name="persen" 
                                   value="{{ old('persen', $margin->persen) }}"
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
                    
                    <!-- Status -->
                    <div class="mb-4">
                        <label for="status" class="form-label">
                            Status <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('status') is-invalid @enderror" 
                                id="status" 
                                name="status" 
                                required>
                            <option value="1" {{ old('status', $margin->status ?? 1) == 1 ? 'selected' : '' }}>
                                <i class="bi bi-check-circle"></i> Aktif
                            </option>
                            <option value="0" {{ old('status', $margin->status ?? 1) == 0 ? 'selected' : '' }}>
                                <i class="bi bi-x-circle"></i> Nonaktif
                            </option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Info -->
                    <div class="alert alert-warning mb-4">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Perhatian:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Hanya satu margin yang bisa aktif dalam satu waktu</li>
                            <li>Jika Anda mengaktifkan margin ini, margin aktif lainnya akan otomatis dinonaktifkan</li>
                            <li>Menonaktifkan margin akan membuat sistem tidak memiliki margin aktif</li>
                        </ul>
                    </div>
                    
                    <!-- Buttons -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('superadmin.margin.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Info Card -->
        <div class="card mt-3">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="bi bi-info-circle text-primary me-2"></i>Informasi Margin
                </h6>
                <table class="table table-sm mb-0">
                    <tr>
                        <td width="150"><strong>ID Margin</strong></td>
                        <td>: #{{ $margin->idmargin_penjualan }}</td>
                    </tr>
                    <tr>
                        <td><strong>Dibuat Oleh</strong></td>
                        <td>: {{ $margin->created_by ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Dibuat</strong></td>
                        <td>: {{ date('d-m-Y H:i', strtotime($margin->created_at ?? now())) }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection