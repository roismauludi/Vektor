@extends('base.base')
@section('title', 'Update Data User')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card custom-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1">Formulir</p>
                    <h5 class="card-title mb-0">Edit User</h5>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('user.updateuser') }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    <input type="hidden" name="id" value="{{ $user->id }}">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label for="nama" class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $user->nama) }}" placeholder="Masukkan nama lengkap" required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" name="username" id="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username', $user->username) }}" placeholder="Masukkan Username" required>
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" placeholder="nama@perusahaan.com" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="jabatan" class="form-label">Jabatan</label>
                            <input type="text" name="jabatan" id="jabatan" class="form-control @error('jabatan') is-invalid @enderror" value="{{ old('jabatan', $user->jabatan) }}" placeholder="Contoh: Supervisor" required>
                            @error('jabatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="divisi" class="form-label">Divisi</label>
                            <input type="text" name="divisi" id="divisi" class="form-control @error('divisi') is-invalid @enderror" value="{{ old('divisi', $user->divisi) }}" placeholder="Contoh: Operasional" required>
                            @error('divisi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="lokasi_penugasan" class="form-label">Lokasi Penugasan</label>
                            <input type="text" name="lokasi_penugasan" id="lokasi_penugasan" class="form-control @error('lokasi_penugasan') is-invalid @enderror" value="{{ old('lokasi_penugasan', $user->lokasi_penugasan) }}" placeholder="Contoh: Batam / Bintan" required>
                            @error('lokasi_penugasan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="role" class="form-label">Role</label>
                            <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
                                <option value="">Pilih Role</option>
                                <option value="view" {{ (old('role', $user->roles->first()?->name) == 'view') ? 'selected' : '' }}>View (Read Only)</option>
                                <option value="PIC" {{ (old('role', $user->roles->first()?->name) == 'PIC') ? 'selected' : '' }}>PIC</option>
                                <option value="security" {{ (old('role', $user->roles->first()?->name) == 'security') ? 'selected' : '' }}>Security</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="password" class="form-label">Password Baru (Opsional)</label>
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Kosongkan jika tidak ingin mengubah password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Ulangi password baru">
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('user.detail', $user->id) }}" class="btn btn-light">Batal</a>
                        <button type="submit" class="btn btn-primary btn-wave">Update User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection