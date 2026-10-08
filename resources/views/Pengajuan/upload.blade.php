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

                    @php
                        $extraFotos = $pengajuan->fotos ? $pengajuan->fotos->filter(function($f) {
                            return !$f->pengembalian && !in_array(strtolower($f->kategori), ['kendaraan', 'penyerahan_kunci', 'lainnya']);
                        }) : collect();
                    @endphp

                    @if($extraFotos->count() > 0)
                        <div class="mb-3">
                            <label class="form-label fw-bold">Foto Tambahan Terupload:</label>
                            <div class="row">
                                @foreach($extraFotos as $extra)
                                    <div class="col-md-6 mb-2">
                                        <div class="p-2 border rounded bg-light d-flex justify-content-between align-items-center">
                                            <span class="text-truncate me-2 small fw-semibold">
                                                {{ ucfirst(str_replace('_', ' ', $extra->kategori)) }}
                                            </span>
                                            <a href="{{ asset('storage/'.$extra->path) }}" target="_blank" class="btn btn-sm btn-info">
                                                Lihat Foto
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Container untuk Kolom Foto Tambahan Dinamis -->
                    <div id="extra-foto-container" class="row"></div>

                    <!-- Tombol Tambah Kolom Foto -->
                    <div class="mb-3">
                        <button type="button" id="btn-add-foto" class="btn btn-outline-success">
                            <i class="ri-add-line me-1"></i> Tambah Kolom Foto
                        </button>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    let fotoCounter = 0;
    const container = document.getElementById('extra-foto-container');
    const btnAdd = document.getElementById('btn-add-foto');

    if (btnAdd && container) {
        btnAdd.addEventListener('click', function() {
            fotoCounter++;
            const col = document.createElement('div');
            col.className = 'col-md-6 mb-3 extra-foto-row';
            col.id = `extra-foto-row-${fotoCounter}`;
            col.innerHTML = `
                <div class="card p-3 border shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label mb-0 fw-semibold text-primary">
                            Foto Tambahan #${fotoCounter}
                        </label>
                        <button type="button" class="btn btn-sm btn-outline-danger btn-remove-row" data-target="extra-foto-row-${fotoCounter}">
                            <i class="ri-delete-bin-line me-1"></i> Hapus
                        </button>
                    </div>
                    <input type="file" 
                           class="form-control mb-1" 
                           name="foto_tambahan[]" 
                           accept="image/jpeg,image/jpg,image/png">
                    <small class="form-text text-muted">
                        Format: JPG, JPEG, PNG (Maksimal 5MB)
                    </small>
                </div>
            `;
            container.appendChild(col);
        });

        container.addEventListener('click', function(e) {
            const removeBtn = e.target.closest('.btn-remove-row');
            if (removeBtn) {
                const targetId = removeBtn.getAttribute('data-target');
                const row = document.getElementById(targetId);
                if (row) {
                    row.remove();
                }
            }
        });
    }
});
</script>

@endsection

