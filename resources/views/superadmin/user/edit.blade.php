@extends('layouts.metis.app')

@section('title', 'Edit User')

@php
    $pageTitle = 'Edit User';
    $pageDescription = 'Edit data pengguna sistem';
@endphp

@section('page-actions')
<a href="{{ route('superadmin.user.index') }}" class="btn btn-secondary">
    <i class="bi bi-arrow-left me-1"></i> Kembali
</a>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-pencil-square me-2"></i>Form Edit User
                </h5>
            </div>
            
            <div class="card-body">
                <form action="{{ route('superadmin.user.update', $user->iduser) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <!-- Username -->
                        <div class="col-md-6 mb-3">
                            <label for="username" class="form-label">
                                Username <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-person"></i>
                                </span>
                                <input type="text" 
                                       class="form-control @error('username') is-invalid @enderror" 
                                       id="username" 
                                       name="username" 
                                       value="{{ old('username', $user->username) }}"
                                       placeholder="Contoh: johndoe"
                                       maxlength="45"
                                       required>
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted">Maksimal 45 karakter, tanpa spasi</small>
                        </div>
                        
                        <!-- Role -->
                        <div class="col-md-6 mb-3">
                            <label for="idrole" class="form-label">
                                Role <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('idrole') is-invalid @enderror" 
                                    id="idrole" 
                                    name="idrole" 
                                    required>
                                <option value="">-- Pilih Role --</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->idrole }}" 
                                            {{ old('idrole', $user->idrole) == $role->idrole ? 'selected' : '' }}>
                                        {{ $role->nama_role }}
                                    </option>
                                @endforeach
                            </select>
                            @error('idrole')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Password -->
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">
                                Password Baru
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       placeholder="Kosongkan jika tidak ingin mengubah"
                                       minlength="6">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted">Minimal 6 karakter, kosongkan jika tidak ingin mengubah</small>
                        </div>
                        
                        <!-- Password Confirmation -->
                        <div class="col-md-6 mb-4">
                            <label for="password_confirmation" class="form-label">
                                Konfirmasi Password Baru
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-lock-fill"></i>
                                </span>
                                <input type="password" 
                                       class="form-control" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       placeholder="Ulangi password baru"
                                       minlength="6">
                            </div>
                            <small class="text-muted">Harus sama dengan password baru</small>
                        </div>
                    </div>
                    
                    <!-- Info -->
                    <div class="alert alert-warning mb-4">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Perhatian:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Kosongkan field password jika tidak ingin mengubah password</li>
                            <li>Jika mengisi password baru, user harus login ulang dengan password yang baru</li>
                        </ul>
                    </div>
                    
                    <!-- Buttons -->
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('superadmin.user.index') }}" class="btn btn-secondary">
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