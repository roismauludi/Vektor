@extends('base.base')
@section('title', 'Upload Foto Pengembalian')

@section('content')

<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                <h5 class="card-title mb-0">Upload Foto Pengembalian - {{ $pengajuan->kode_pengajuan }}</h5>
            </div>

            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('pengajuan.selesai.post', $pengajuan->id) }}" 
                      method="POST" 
                      enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="foto_kendaraan_kembali" class="form-label">
                                    Foto Kendaraan Kembali <span class="text-danger">*</span>
                                </label>
                                <input type="file" 
                                       class="form-control @error('foto_kendaraan_kembali') is-invalid @enderror" 
                                       id="foto_kendaraan_kembali" 
                                       name="foto_kendaraan_kembali" 
                                       accept="image/jpeg,image/jpg,image/png">
                                <small class="form-text text-muted">
                                    Format: JPG, JPEG, PNG (Maksimal 5MB)
                                </small>
                                @error('foto_kendaraan_kembali')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="foto_pengembalian_kunci" class="form-label">
                                    Foto Pengembalian Kunci kepada Security <span class="text-danger">*</span>
                                </label>
                                <input type="file" 
                                       class="form-control @error('foto_pengembalian_kunci') is-invalid @enderror" 
                                       id="foto_pengembalian_kunci" 
                                       name="foto_pengembalian_kunci" 
                                       accept="image/jpeg,image/jpg,image/png">
                                <small class="form-text text-muted">
                                    Format: JPG, JPEG, PNG (Maksimal 5MB)
                                </small>
                                @error('foto_pengembalian_kunci')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="foto_lainnya" class="form-label">
                                    Foto Lainnya <span class="text-danger">*</span>
                                </label>
                                <input type="file" 
                                       class="form-control @error('foto_lainnya') is-invalid @enderror" 
                                       id="foto_lainnya" 
                                       name="foto_lainnya" 
                                       accept="image/jpeg,image/jpg,image/png">
                                <small class="form-text text-muted">
                                    Format: JPG, JPEG, PNG (Maksimal 5MB)
                                </small>
                                @error('foto_lainnya')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 d-flex">
                        <a href="{{ route('pengajuan.list') }}" 
                           class="btn btn-secondary ms-2">
                            <i class="ri-arrow-left-line me-1"></i>
                            Kembali
                        </a>
                        <button type="submit" class="btn btn-primary ms-auto">
                            <i class="ri-upload-line me-1"></i>
                            Selesaikan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

