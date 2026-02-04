@extends('base.base')
@section('title', 'Detail User')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card custom-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1">Profil</p>
                    <h5 class="card-title mb-0">Detail User</h5>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('user.edit', $user->id) }} " class="btn btn-outline-primary">Edit</a>
                    <a href="{{ route('user') }}" class="btn btn-outline-secondary btn-wave">Kembali</a>
                </div>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="avatar avatar-xl bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fs-4">
                        {{ strtoupper(substr($user->nama, 0, 1)) }}
                    </div>
                    <div>
                        <h5 class="mb-1">{{ $user->nama }}</h5>
                        <p class="text-muted mb-0">{{ $user->email }}</p>
                    </div>
                </div>

                <div class="row gy-3">
                    <div class="col-md-4">
                        <div class="p-3 border rounded">
                            <p class="text-muted mb-1">Jabatan</p>
                            <h6 class="mb-0">{{ $user->jabatan }}</h6>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="p-3 border rounded">
                            <p class="text-muted mb-1">Divisi</p>
                            <h6 class="mb-0">{{ $user->divisi }}</h6>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="p-3 border rounded">
                            <p class="text-muted mb-1">Lokasi Penugasan</p>
                            <h6 class="mb-0">{{ $user->lokasi_penugasan }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

