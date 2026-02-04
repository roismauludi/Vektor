@extends('base.base')
@section('title', 'Detail Pengajuan')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="row">
            <div class="col-md-6">
                <div class="card custom-card mb-4">
                    <div class="card-header">
                        <p class="text-muted mb-1">Detail Pengajuan</p>
                    </div>

                    <div class="card-body">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th width="35%">Kode Pengajuan</th>
                                    <td>{{ $pengajuan->kode_pengajuan }}</td>
                                </tr>
                                <tr>
                                    <th>Jenis</th>
                                    <td>{{ $pengajuan->jenis }}</td>
                                </tr>
                                <tr>
                                    <th>Kode Aset</th>
                                    <td>{{ $pengajuan->tower_kode ?? $pengajuan->central_kode }}</td>
                                </tr>
                                <tr>
                                    <th>Perusahaan Tamu</th>
                                    <td>{{ $pengajuan->perusahaan_tamu }}</td>
                                </tr>
                                <tr>
                                    <th>Perusahaan Pengutus</th>
                                    <td>{{ $pengajuan->perusahaan_pengutus }}</td>
                                </tr>
                                <tr>
                                    <th>Jumlah Tamu</th>
                                    <td>{{ $pengajuan->jumlah }}</td>
                                </tr>
                                <tr>
                                    <th>Kategori</th>
                                    <td>{{ $pengajuan->kategori }}</td>
                                </tr>
                                <tr>
                                    <th>Keperluan</th>
                                    <td>{{ $pengajuan->keperluan }}</td>
                                </tr>
                                <tr>
                                    <th>Lama Kerja</th>
                                    <td>{{ $pengajuan->lama_berkerja }}</td>
                                </tr>
                                <tr>
                                    <th>Identitas</th>
                                    <td>{{ $pengajuan->identitas }}</td>
                                </tr>
                                <tr>
                                    <th>File Identitas</th>
                                    <td>
                                        <a href="{{ asset('storage/'.$pengajuan->identitas_file) }}" target="_blank"    
                                           class="btn btn-sm btn-primary">
                                            Cek Identitas
                                        </a>    
                                    </td>
                                </tr>
                                <tr>
                                    <th>Masa Berlaku</th>
                                    <td>{{ $pengajuan->masa_berlaku }}</td>
                                </tr>
                                <tr>
                                    <th>Pembuat</th>
                                    <td>{{ $pengajuan->creator->nama }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="badge bg-info">{{ $pengajuan->progress }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Tanggal</th>
                                    <td>{{ $pengajuan->created_at->format('d-m-Y H:i') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card custom-card mb-4">
                    <div class="card-header">
                        <p class="text-muted mb-1">Detail Tamu</p>
                    </div>

                    <div class="card-body">
                        @forelse ($pengajuan->details as $detail)
                            <table class="table table-bordered mb-3">
                                <tbody>
                                    <tr>
                                        <th width="35%">Nama Tamu</th>
                                        <td>{{ $detail->nama_tamu }}</td>
                                    </tr>
                                    <tr>
                                        <th>No Telepon</th>
                                        <td>{{ $detail->no_tlp }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td>{{ $detail->email }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        @empty
                            <p class="text-muted text-center mb-0">
                                Tidak ada data tamu
                            </p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>

        @php
            $progressLower = strtolower(trim($pengajuan->progress));
            $hasPhotos =
                $pengajuan->fotoPath('kendaraan') ||
                $pengajuan->fotoPath('penyerahan_kunci') ||
                $pengajuan->fotoPath('pengembalian_kunci') ||
                $pengajuan->fotoPath('lainnya');
            $isWaitingVerification = str_contains($progressLower, 'menunggu') || str_contains($progressLower, 'verifikasi');
            
            // Tampilkan foto jika: progress approved ATAU sudah ada foto yang diupload
            $canShowPhotos = $progressLower === 'approved' || $hasPhotos;
        @endphp

        <div class="row">
            <div class="col-12">
                <div class="card custom-card mb-4">
                    <div class="card-header">
                        <p class="text-muted mb-1">Foto Dokumentasi</p>
                    </div>

                    <div class="card-body">
                        @if(!$canShowPhotos)
                            <div class="text-center py-5 photo-locked-message">
                                <div class="mb-3">
                                    <i class="ri-lock-line fs-1 text-muted"></i>
                                </div>
                                <h6 class="text-muted mb-2 fw-semibold">Foto Dokumentasi Belum Tersedia</h6>
                                <p class="text-muted small mb-0">
                                    Foto dokumentasi akan ditampilkan setelah pengajuan disetujui atau setelah foto diupload.
                                </p>
                            </div>
                        @else
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="photo-upload-section">
                                        <h6 class="mb-3 fw-semibold">Foto Kendaraan</h6>
                                        @if($pengajuan->fotoPath('kendaraan'))
                                            <div class="text-center">
                                                <a href="#" 
                                                   data-bs-toggle="modal" 
                                                   data-bs-target="#modalFotoKendaraan"
                                                   class="text-decoration-none d-inline-block">
                                                    <img src="{{ asset('storage/'.$pengajuan->fotoPath('kendaraan')) }}" 
                                                         alt="Foto Kendaraan" 
                                                         class="img-thumbnail photo-thumbnail" 
                                                         style="width: 100px; height: 100px; object-fit: cover; cursor: pointer; transition: transform 0.2s;">
                                                    <div class="mt-2">
                                                        <small class="text-muted">
                                                            <i class="ri-eye-line me-1"></i>
                                                            Klik untuk preview
                                                        </small>
                                                    </div>
                                                </a>
                                            </div>
                                        @else
                                            <div class="text-center py-4 border rounded bg-light">
                                                <i class="ri-image-line fs-3 text-muted mb-2 d-block"></i>
                                                <p class="text-muted mb-0 small">Foto kendaraan belum diupload</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="photo-upload-section">
                                        <h6 class="mb-3 fw-semibold">Foto Penyerahan Kunci kepada Vendor</h6>
                                        @if($pengajuan->fotoPath('penyerahan_kunci'))
                                            <div class="text-center">
                                                <a href="#" 
                                                   data-bs-toggle="modal" 
                                                   data-bs-target="#modalFotoPenyerahanKunci"
                                                   class="text-decoration-none d-inline-block">
                                                    <img src="{{ asset('storage/'.$pengajuan->fotoPath('penyerahan_kunci')) }}" 
                                                         alt="Foto Penyerahan Kunci" 
                                                         class="img-thumbnail photo-thumbnail" 
                                                         style="width: 100px; height: 100px; object-fit: cover; cursor: pointer; transition: transform 0.2s;">
                                                    <div class="mt-2">
                                                        <small class="text-muted">
                                                            <i class="ri-eye-line me-1"></i>
                                                            Klik untuk preview
                                                        </small>
                                                    </div>
                                                </a>
                                            </div>
                                        @else
                                            <div class="text-center py-4 border rounded bg-light">
                                                <i class="ri-image-line fs-3 text-muted mb-2 d-block"></i>
                                                <p class="text-muted mb-0 small">Foto penyerahan kunci belum diupload</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="photo-upload-section">
                                        <h6 class="mb-3 fw-semibold">Foto Pengembalian Kunci kepada Security</h6>
                                        @if($pengajuan->fotoPath('pengembalian_kunci'))
                                            <div class="text-center">
                                                <a href="#" 
                                                   data-bs-toggle="modal" 
                                                   data-bs-target="#modalFotoPengembalianKunci"
                                                   class="text-decoration-none d-inline-block">
                                                    <img src="{{ asset('storage/'.$pengajuan->fotoPath('pengembalian_kunci')) }}" 
                                                         alt="Foto Pengembalian Kunci" 
                                                         class="img-thumbnail photo-thumbnail" 
                                                         style="width: 100px; height: 100px; object-fit: cover; cursor: pointer; transition: transform 0.2s;">
                                                    <div class="mt-2">
                                                        <small class="text-muted">
                                                            <i class="ri-eye-line me-1"></i>
                                                            Klik untuk preview
                                                        </small>
                                                    </div>
                                                </a>
                                            </div>
                                        @else
                                            <div class="text-center py-4 border rounded bg-light">
                                                <i class="ri-image-line fs-3 text-muted mb-2 d-block"></i>
                                                <p class="text-muted mb-0 small">Foto pengembalian kunci belum diupload</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="photo-upload-section">
                                        <h6 class="mb-3 fw-semibold">Foto Lainnya</h6>
                                        @if($pengajuan->fotoPath('lainnya'))
                                            <div class="text-center">
                                                <a href="#" 
                                                   data-bs-toggle="modal" 
                                                   data-bs-target="#modalFotoLainnya"
                                                   class="text-decoration-none d-inline-block">
                                                    <img src="{{ asset('storage/'.$pengajuan->fotoPath('lainnya')) }}" 
                                                         alt="Foto Lainnya" 
                                                         class="img-thumbnail photo-thumbnail" 
                                                         style="width: 100px; height: 100px; object-fit: cover; cursor: pointer; transition: transform 0.2s;">
                                                    <div class="mt-2">
                                                        <small class="text-muted">
                                                            <i class="ri-eye-line me-1"></i>
                                                            Klik untuk preview
                                                        </small>
                                                    </div>
                                                </a>
                                            </div>
                                        @else
                                            <div class="text-center py-4 border rounded bg-light">
                                                <i class="ri-image-line fs-3 text-muted mb-2 d-block"></i>
                                                <p class="text-muted mb-0 small">Foto Lainnya belum diupload</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card custom-card">
            <div class="card-header">
                <p class="text-muted mb-1">Riwayat Pengajuan</p>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead class="table-secondary">
                            <tr>
                                <th>#</th>
                                <th>Aksi</th>
                                <th>Keterangan</th>
                                <th>Oleh</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pengajuan->logs as $log)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        @if ($log->aksi === 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif ($log->aksi === 'rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $log->aksi }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $log->keterangan ?? '-' }}</td>
                                    <td>{{ $log->creator->nama }}</td>
                                    <td>{{ $log->created_at->format('d-m-Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">
                                        Belum ada riwayat log
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

                    @if($pengajuan->fotoPath('kendaraan'))
                    <!-- Modal Foto Kendaraan -->
                    <div class="modal fade" id="modalFotoKendaraan" tabindex="-1" aria-labelledby="modalFotoKendaraanLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalFotoKendaraanLabel">Foto Kendaraan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-center">
                                    <img src="{{ asset('storage/'.$pengajuan->fotoPath('kendaraan')) }}" 
                                        alt="Foto Kendaraan" 
                                        class="img-fluid rounded">
                                </div>
                                <div class="modal-footer">
                                    <a href="{{ asset('storage/'.$pengajuan->fotoPath('kendaraan')) }}" 
                                    target="_blank" 
                                    class="btn btn-primary">
                                        <i class="ri-external-link-line me-1"></i>
                                        Buka di Tab Baru
                                    </a>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($pengajuan->fotoPath('penyerahan_kunci'))
                    <!-- Modal Foto Penyerahan Kunci -->
                    <div class="modal fade" id="modalFotoPenyerahanKunci" tabindex="-1" aria-labelledby="modalFotoPenyerahanKunciLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalFotoPenyerahanKunciLabel">Foto Penyerahan Kunci kepada Vendor</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-center">
                                    <img src="{{ asset('storage/'.$pengajuan->fotoPath('penyerahan_kunci')) }}" 
                                        alt="Foto Penyerahan Kunci" 
                                        class="img-fluid rounded">
                                </div>
                                <div class="modal-footer">
                                    <a href="{{ asset('storage/'.$pengajuan->fotoPath('penyerahan_kunci')) }}" 
                                    target="_blank" 
                                    class="btn btn-primary">
                                        <i class="ri-external-link-line me-1"></i>
                                        Buka di Tab Baru
                                    </a>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($pengajuan->fotoPath('pengembalian_kunci'))
                    <!-- Modal Foto Pengembalian Kunci -->
                    <div class="modal fade" id="modalFotoPengembalianKunci" tabindex="-1" aria-labelledby="modalFotoPengembalianKunciLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalFotoPengembalianKunciLabel">Foto Pengembalian Kunci kepada Security</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-center">
                                    <img src="{{ asset('storage/'.$pengajuan->fotoPath('pengembalian_kunci')) }}" 
                                        alt="Foto Pengembalian Kunci" 
                                        class="img-fluid rounded">
                                </div>
                                <div class="modal-footer">
                                    <a href="{{ asset('storage/'.$pengajuan->fotoPath('pengembalian_kunci')) }}" 
                                    target="_blank" 
                                    class="btn btn-primary">
                                        <i class="ri-external-link-line me-1"></i>
                                        Buka di Tab Baru
                                    </a>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($pengajuan->fotoPath('lainnya'))
                    <!-- Modal Foto Lainnya -->
                    <div class="modal fade" id="modalFotoLainnya" tabindex="-1" aria-labelledby="modalFotoLainnyaLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalFotoLainnyaLabel">Foto Lainnya</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-center">
                                    <img src="{{ asset('storage/'.$pengajuan->fotoPath('lainnya')) }}" 
                                        alt="Foto Lainnya" 
                                        class="img-fluid rounded">
                                </div>
                                <div class="modal-footer">
                                    <a href="{{ asset('storage/'.$pengajuan->fotoPath('lainnya')) }}" 
                                    target="_blank" 
                                    class="btn btn-primary">
                                        <i class="ri-external-link-line me-1"></i>
                                        Buka di Tab Baru
                                    </a>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

@push('styles')
<style>
    .photo-thumbnail:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }
    
    .photo-upload-section {
        min-height: 150px;
    }
    
    .photo-locked-message {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 8px;
        padding: 2rem;
    }
</style>
@endpush

@endsection
