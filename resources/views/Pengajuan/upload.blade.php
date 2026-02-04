@extends('base.base')
@section('title', 'Upload Foto Pengajuan')

@section('content')

<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                <h5 class="card-title mb-0">Upload Foto Pengajuan - {{ $pengajuan->kode_pengajuan }}</h5>
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

                <form action="{{ route('pengajuan.upload.post', $pengajuan->id) }}" 
                      method="POST" 
                      enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="foto_kendaraan" class="form-label">
                                    Foto Kendaraan <span class="text-danger">*</span>
                                </label>
                                <input type="file" 
                                       class="form-control @error('foto_kendaraan') is-invalid @enderror" 
                                       id="foto_kendaraan" 
                                       name="foto_kendaraan" 
                                       accept="image/jpeg,image/jpg,image/png">
                                <small class="form-text text-muted">
                                    Format: JPG, JPEG, PNG (Maksimal 5MB)
                                </small>
                                @error('foto_kendaraan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                @if($pengajuan->fotoPath('kendaraan'))
                                    <div class="mt-2">
                                        <p class="text-muted mb-1">Foto saat ini:</p>
                                        <a href="{{ asset('storage/'.$pengajuan->fotoPath('kendaraan')) }}" 
                                           target="_blank" 
                                           class="btn btn-sm btn-info">
                                            Lihat Foto
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="foto_penyerahan_kunci" class="form-label">
                                    Foto Penyerahan Kunci kepada Vendor <span class="text-danger">*</span>
                                </label>
                                <input type="file" 
                                       class="form-control @error('foto_penyerahan_kunci') is-invalid @enderror" 
                                       id="foto_penyerahan_kunci" 
                                       name="foto_penyerahan_kunci" 
                                       accept="image/jpeg,image/jpg,image/png">
                                <small class="form-text text-muted">
                                    Format: JPG, JPEG, PNG (Maksimal 5MB)
                                </small>
                                @error('foto_penyerahan_kunci')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                @if($pengajuan->fotoPath('penyerahan_kunci'))
                                    <div class="mt-2">
                                        <p class="text-muted mb-1">Foto saat ini:</p>
                                        <a href="{{ asset('storage/'.$pengajuan->fotoPath('penyerahan_kunci')) }}" 
                                           target="_blank" 
                                           class="btn btn-sm btn-info">
                                            Lihat Foto
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

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

                                @if($pengajuan->fotoPath('lainnya'))
                                    <div class="mt-2">
                                        <p class="text-muted mb-1">Foto saat ini:</p>
                                        <a href="{{ asset('storage/'.$pengajuan->fotoPath('lainnya')) }}" 
                                           target="_blank" 
                                           class="btn btn-sm btn-info">
                                            Lihat Foto
                                        </a>
                                    </div>
                                @endif
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
                            Upload Foto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

