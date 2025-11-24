@extends('layouts.metis.app')

@section('title', 'Edit Barang')

@php
    $pageTitle = 'Edit Barang';
    $pageDescription = 'Edit data barang inventory';
@endphp

@section('page-actions')
<a href="{{ route('superadmin.barang.index') }}" class="btn btn-secondary">
    <i class="bi bi-arrow-left me-1"></i> Kembali
</a>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-pencil-square me-2"></i>Form Edit Barang
                </h5>
            </div>
            
            <div class="card-body">
                <form action="{{ route('superadmin.barang.update', $barang->idbarang) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <!-- Nama Barang -->
                        <div class="col-md-6 mb-3">
                            <label for="nama" class="form-label">
                                Nama Barang <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('nama') is-invalid @enderror" 
                                   id="nama" 
                                   name="nama" 
                                   value="{{ old('nama', $barang->nama) }}"
                                   placeholder="Contoh: Beras Premium"
                                   maxlength="45"
                                   required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Maksimal 45 karakter</small>
                        </div>
                        
                        <!-- Jenis Barang -->
                        <div class="col-md-6 mb-3">
                            <label for="jenis" class="form-label">
                                Jenis Barang <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('jenis') is-invalid @enderror" 
                                    id="jenis" 
                                    name="jenis" 
                                    required>
                                <option value="">-- Pilih Jenis --</option>
                                <option value="M" {{ old('jenis', $barang->jenis) == 'M' ? 'selected' : '' }}>
                                    Makanan
                                </option>
                                <option value="N" {{ old('jenis', $barang->jenis) == 'N' ? 'selected' : '' }}>
                                    Non-Makanan
                                </option>
                            </select>
                            @error('jenis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Satuan -->
                        <div class="col-md-6 mb-3">
                            <label for="idsatuan" class="form-label">
                                Satuan <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('idsatuan') is-invalid @enderror" 
                                    id="idsatuan" 
                                    name="idsatuan" 
                                    required>
                                <option value="">-- Pilih Satuan --</option>
                                @foreach($satuans as $satuan)
                                    <option value="{{ $satuan->idsatuan }}" 
                                            {{ old('idsatuan', $barang->idsatuan) == $satuan->idsatuan ? 'selected' : '' }}>
                                        {{ $satuan->nama_satuan }}
                                    </option>
                                @endforeach
                            </select>
                            @error('idsatuan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Harga -->
                        <div class="col-md-6 mb-3">
                            <label for="harga" class="form-label">
                                Harga <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" 
                                       class="form-control @error('harga') is-invalid @enderror" 
                                       id="harga" 
                                       name="harga" 
                                       value="{{ old('harga', $barang->harga) }}"
                                       placeholder="0"
                                       min="0"
                                       step="1"
                                       required>
                                @error('harga')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted">Masukkan harga dalam rupiah</small>
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
                                <option value="1" {{ old('status', $barang->status ?? 1) == 1 ? 'selected' : '' }}>
                                    Aktif
                                </option>
                                <option value="0" {{ old('status', $barang->status ?? 1) == 0 ? 'selected' : '' }}>
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
                        <strong>Perhatian:</strong> Menonaktifkan barang akan membuatnya tidak muncul di daftar barang aktif
                    </div>
                    
                    <!-- Buttons -->
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('superadmin.barang.index') }}" class="btn btn-secondary">
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