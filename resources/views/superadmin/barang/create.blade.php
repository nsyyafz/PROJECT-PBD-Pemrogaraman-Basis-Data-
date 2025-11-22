@extends('layouts.metis.app')

@section('title', 'Tambah Barang')

@php
    $pageTitle = 'Tambah Barang';
    $pageDescription = 'Tambah data barang baru';
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
                    <i class="bi bi-plus-circle me-2"></i>Form Tambah Barang
                </h5>
            </div>
            
            <div class="card-body">
                <form action="{{ route('superadmin.barang.store') }}" method="POST">
                    @csrf
                    
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
                                   value="{{ old('nama') }}"
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
                                <option value="M" {{ old('jenis') == 'M' ? 'selected' : '' }}>Makanan</option>
                                <option value="N" {{ old('jenis') == 'N' ? 'selected' : '' }}>Non-Makanan</option>
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
                                            {{ old('idsatuan') == $satuan->idsatuan ? 'selected' : '' }}>
                                        {{ $satuan->nama_satuan }}
                                    </option>
                                @endforeach
                            </select>
                            @error('idsatuan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Harga -->
                        <div class="col-md-6 mb-4">
                            <label for="harga" class="form-label">
                                Harga <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" 
                                       class="form-control @error('harga') is-invalid @enderror" 
                                       id="harga" 
                                       name="harga" 
                                       value="{{ old('harga') }}"
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
                    </div>
                    
                    <!-- Info -->
                    <div class="alert alert-info mb-4">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Catatan:</strong> Barang baru akan otomatis berstatus <strong>Aktif</strong>
                    </div>
                    
                    <!-- Buttons -->
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('superadmin.barang.index') }}" class="btn btn-secondary">
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