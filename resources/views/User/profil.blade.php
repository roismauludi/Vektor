@extends('base.base')
@section('title', 'Edit Profil User')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card custom-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1">Admin / PIC</p>
                    <h5 class="card-title mb-0">Edit Profil User - {{ $user->nama }}</h5>
                </div>
                <a href="{{ route('user.detail', $user->id) }}" class="btn btn-outline-secondary btn-wave">Kembali</a>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <form action="{{ route('user.updateprofil') }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    <input type="hidden" name="id" value="{{ $user->id }}">
                    <div class="row g-4">
                        <div class="col-12">
                            <div class="p-3 bg-light rounded mb-3">
                                <p class="text-muted mb-0 small">
                                    <i class="ri-information-line me-1"></i>
                                    Nama dan username tidak dapat diubah dari sini. Gunakan Edit User (admin) untuk mengubah identitas atau role.
                                </p>
                            </div>
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
                            <input type="text" name="jabatan" id="jabatan" class="form-control @error('jabatan') is-invalid @enderror" value="{{ old('jabatan', $user->jabatan) }}" placeholder="Contoh: Supervisor">
                            @error('jabatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="divisi" class="form-label">Divisi</label>
                            <input type="text" name="divisi" id="divisi" class="form-control @error('divisi') is-invalid @enderror" value="{{ old('divisi', $user->divisi) }}" placeholder="Contoh: Operasional">
                            @error('divisi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="lokasi_penugasan" class="form-label">Lokasi Penugasan</label>
                            <input type="text" name="lokasi_penugasan" id="lokasi_penugasan" class="form-control @error('lokasi_penugasan') is-invalid @enderror" value="{{ old('lokasi_penugasan', $user->lokasi_penugasan) }}" placeholder="Contoh: Batam / Bintan">
                            @error('lokasi_penugasan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <hr>
                            <p class="text-muted small">Ubah password (opsional)</p>
                        </div>
                        <div class="col-md-6">
                            <label for="password" class="form-label">Password Baru</label>
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Kosongkan jika tidak ingin mengubah">
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
                        <button type="submit" class="btn btn-primary btn-wave">Update Profil</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
