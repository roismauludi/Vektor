@extends('base.base')
@section('title', 'Profil')
@section('content')

<style>
    .profile-header {
        background: linear-gradient(135deg, #f25920 0%, #ff7849 100%);
        color: white;
        padding: 2rem;
        border-radius: 0.5rem 0.5rem 0 0;
        margin: -1rem -1rem 0 -1rem;
    }
    
    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        font-weight: bold;
        color: #f25920;
        border: 4px solid rgba(255, 255, 255, 0.3);
        margin: 0 auto 1rem;
    }
    
    .profile-info-group {
        margin-bottom: 1.5rem;
    }
    
    .profile-info-group label {
        font-weight: 600;
        color: #6c757d;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }
    
    .profile-info-value {
        font-size: 1rem;
        color: #212529;
        padding: 0.75rem;
        background-color: #f8f9fa;
        border-radius: 0.375rem;
        border-left: 3px solid #f25920;
    }
    
    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #212529;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #f25920;
    }
    
    .badge-role {
        background-color: #f25920;
        color: white;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        border-radius: 2rem;
    }
    
    .info-card {
        border: none;
        box-shadow: 0 2px 4px rgba(0,0,0,0.08);
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .info-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="card custom-card">
            <!-- Profile Header -->
            <div class="profile-header">
                <div class="text-center">
                    <div class="profile-avatar position-relative">
                        @if($user->foto_url)
                            <img src="{{ $user->foto_url }}" alt="Foto Profil" class="w-100 h-100 rounded-circle" style="object-fit: cover;">
                        @else
                            {{ strtoupper(substr($user->nama ?? 'U', 0, 2)) }}
                        @endif
                    </div>
                    <h3 class="mb-2">{{ $user->nama ?? 'User' }}</h3>
                    <p class="mb-3 opacity-75">
                        <i class="ri-mail-line me-2"></i>{{ $user->email ?? '' }}
                    </p>
                    <div>
                        @php
                            $userRoles = $user->roles ?? collect();
                        @endphp
                        @if($userRoles->isNotEmpty())
                            @foreach($userRoles as $role)
                                <span class="badge-role">
                                    <i class="ri-shield-user-line me-1"></i>{{ ucfirst($role->name) }}
                                </span>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="ri-checkbox-circle-line me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="ri-error-warning-line me-2"></i>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <!-- Foto Profil -->
                        <div class="col-12 mb-4">
                            <div class="card info-card">
                                <div class="card-body">
                                    <h5 class="section-title">
                                        <i class="ri-image-line me-2"></i>Foto Profil
                                    </h5>
                                    <div class="d-flex align-items-center gap-4">
                                        <div class="profile-avatar" style="width: 100px; height: 100px; font-size: 2.5rem;">
                                            @if($user->foto_url)
                                                <img src="{{ $user->foto_url }}" alt="Foto Profil" class="w-100 h-100 rounded-circle" style="object-fit: cover;">
                                            @else
                                                {{ strtoupper(substr($user->nama ?? 'U', 0, 2)) }}
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <label for="foto" class="form-label">
                                                <i class="ri-upload-2-line me-1"></i>Unggah Foto Baru
                                            </label>
                                            <input type="file" 
                                                   class="form-control @error('foto') is-invalid @enderror" 
                                                   id="foto" 
                                                   name="foto"
                                                   accept="image/jpeg,image/jpg,image/png,image/gif,image/webp">
                                            <small class="text-muted">Format: JPG, PNG, GIF, WebP. Maksimal 2MB.</small>
                                            @error('foto')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Data Pribadi Section -->
                        <div class="col-lg-6 mb-4">
                            <div class="card info-card h-100">
                                <div class="card-body">
                                    <h5 class="section-title">
                                        <i class="ri-user-line me-2"></i>Data Pribadi
                                    </h5>
                                    
                                    <div class="profile-info-group">
                                        <label>
                                            <i class="ri-user-3-line me-1"></i>Nama Lengkap
                                        </label>
                                        <div class="profile-info-value">
                                            {{ $user->nama ?? '-' }}
                                        </div>
                                    </div>

                                    <div class="profile-info-group">
                                        <label for="email">
                                            <i class="ri-mail-line me-1"></i>Email
                                        </label>
                                        <input type="email" 
                                               class="form-control" 
                                               id="email" 
                                               name="email" 
                                               value="{{ $user->email ?? '' }}"
                                               placeholder="contoh@email.com" readonly>
                                    </div>

                                    <div class="profile-info-group">
                                        <label for="jabatan">
                                            <i class="ri-briefcase-line me-1"></i>Jabatan
                                        </label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="jabatan" 
                                               name="jabatan" 
                                               value="{{ $user->jabatan ?? '' }}"
                                               placeholder="Masukkan jabatan" readonly>
                                    </div>

                                    <div class="profile-info-group">
                                        <label for="divisi">
                                            <i class="ri-building-line me-1"></i>Divisi
                                        </label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="divisi" 
                                               name="divisi" 
                                               value="{{ $user->divisi ?? '' }}"
                                               placeholder="Masukkan divisi" readonly>
                                    </div>

                                    <div class="profile-info-group mb-0">
                                        <label for="lokasi_penugasan">
                                            <i class="ri-map-pin-line me-1"></i>Lokasi Penugasan
                                        </label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="lokasi_penugasan" 
                                               name="lokasi_penugasan" 
                                               value="{{ $user->lokasi_penugasan ?? '' }}"
                                               placeholder="Masukkan lokasi penugasan" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Keamanan Section -->
                        <div class="col-lg-6 mb-4">
                            <div class="card info-card h-100">
                                <div class="card-body">
                                    <h5 class="section-title">
                                        <i class="ri-lock-line me-2"></i>Keamanan
                                    </h5>

                                    <div class="alert alert-info d-flex align-items-start" role="alert">
                                        <i class="ri-information-line me-2 mt-1"></i>
                                        <div>
                                            <strong>Info:</strong> Kosongkan field password jika tidak ingin mengubah password.
                                        </div>
                                    </div>

                                    <div class="profile-info-group">
                                        <label for="current_password">
                                            <i class="ri-lock-password-line me-1"></i>Password Saat Ini
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="ri-shield-keyhole-line"></i>
                                            </span>
                                            <input type="password" 
                                                   class="form-control" 
                                                   id="current_password" 
                                                   name="current_password"
                                                   placeholder="Masukkan password saat ini">
                                        </div>
                                    </div>

                                    <div class="profile-info-group">
                                        <label for="password">
                                            <i class="ri-key-2-line me-1"></i>Password Baru
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="ri-lock-unlock-line"></i>
                                            </span>
                                            <input type="password" 
                                                   class="form-control" 
                                                   id="password" 
                                                   name="password"
                                                   placeholder="Masukkan password baru">
                                        </div>
                                        <small class="text-muted">
                                            <i class="ri-information-line"></i> 
                                            Minimal 8 karakter
                                        </small>
                                    </div>

                                    <div class="profile-info-group mb-0">
                                        <label for="password_confirmation">
                                            <i class="ri-checkbox-circle-line me-1"></i>Konfirmasi Password Baru
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="ri-shield-check-line"></i>
                                            </span>
                                            <input type="password" 
                                                   class="form-control" 
                                                   id="password_confirmation" 
                                                   name="password_confirmation"
                                                   placeholder="Ulangi password baru">
                                        </div>
                                    </div>

                                    <!-- Permissions Info -->
                                    @php
                                        $userPermissions = $user->permissions ?? collect();
                                    @endphp
                                    @if($userPermissions->count() > 0)
                                        <div class="mt-4 pt-3 border-top">
                                            <label class="mb-2">
                                                <i class="ri-shield-user-line me-1"></i>Permissions
                                            </label>
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach($userPermissions->take(5) as $permission)
                                                    <span class="badge bg-light text-dark border">
                                                        <i class="ri-check-line text-success"></i>
                                                        {{ $permission->name }}
                                                    </span>
                                                @endforeach
                                                @if($userPermissions->count() > 5)
                                                    <span class="badge bg-light text-dark border">
                                                        +{{ $userPermissions->count() - 5 }} lainnya
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center">
                                <button type="button" 
                                        class="btn btn-light" 
                                        onclick="window.history.back()">
                                    <i class="ri-arrow-left-line me-1"></i>
                                    Kembali
                                </button>
                                <div>
                                    <button type="reset" class="btn btn-outline-secondary me-2">
                                        <i class="ri-restart-line me-1"></i>
                                        Reset
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ri-save-line me-1"></i>
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection