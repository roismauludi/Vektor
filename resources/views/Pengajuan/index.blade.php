@extends('base.base')
@section('title', 'List Pengajuan')
@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Data Pengajuan</h5>
                <button type="button"
                        class="btn btn-outline-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#modalPilihanPengajuan">
                    Ajukan Pengajuan
                </button>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="file-export" class="table table-bordered text-nowrap w-100">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Pengajuan</th>
                                <th>Kategori</th>
                                <th>Kode Aset</th>
                                <th>Nama Aset</th>
                                <th>Status</th>
                                <th>Progress</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                        @foreach ($listPengajuan as $index => $pengajuan)
                            @php
                                $progress = strtolower($pengajuan->progress);
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $pengajuan->kode_pengajuan }}</td>

                                <td>
                                    <span class="badge bg-info">
                                        {{ optional($pengajuan->aset)->kategori ?? '-' }}
                                    </span>
                                </td>

                                <td>
                                    {{ optional($pengajuan->aset)->kode_aset ?? '-' }}
                                </td>

                                <td>
                                    {{ optional($pengajuan->aset)->nama ?? '-' }}
                                </td>

                                <td>
                                    @php
                                        $status = strtolower($pengajuan->status);
                                    @endphp

                                    @if ($status === 'open')
                                        <span class="badge bg-success">Open</span>

                                    @elseif ($status === 'close')
                                        <span class="badge bg-dark">Close</span>

                                    @else
                                        <span class="badge bg-secondary">
                                            {{ ucfirst($pengajuan->status) }}
                                        </span>
                                    @endif
                                </td>

                                <!-- PROGRESS -->
                                <td>
                                    @if ($progress === 'approved')
                                        <span class="badge bg-primary text-white">Approved</span>
                                    @elseif ($progress === 'in-progress')
                                        <span class="badge bg-warning text-dark">In Progress</span>
                                    @elseif ($progress === 'selesai')
                                        <span class="badge bg-success">Selesai</span>
                                    @elseif ($progress === 'rejected')
                                        <span class="badge bg-danger">Rejected</span>
                                    @elseif ($progress === 'cancel')
                                        <span class="badge bg-danger">Cancel</span>
                                    @else
                                        <span class="badge bg-secondary">
                                            {{ ucfirst($pengajuan->progress) }}
                                        </span>
                                    @endif
                                </td>

                                <td>
                                @role('Security')
                                    @if ($progress === 'approved'
                                        && (! $pengajuan->fotoPath('kendaraan')
                                            || ! $pengajuan->fotoPath('penyerahan_kunci')
                                            || ! $pengajuan->fotoPath('lainnya')))
                                        <a href="{{ route('pengajuan.upload', $pengajuan->id) }}"
                                           class="btn btn-sm btn-success">
                                            <i class="ri-upload-line me-1"></i>
                                            Upload
                                        </a>

                                    @elseif ($progress === 'in-progress')
                                        <a href="{{ route('pengajuan.selesai', $pengajuan->id) }}"
                                           class="btn btn-sm btn-primary">
                                            <i class="ri-upload-line me-1"></i>
                                            Upload Pengembalian
                                        </a>

                                    @elseif ($progress === 'rejected')
                                        <a href="{{ route('pengajuan.edit', $pengajuan->id) }}"
                                           class="btn btn-sm btn-warning">
                                            <i class="ri-edit-line me-1"></i>
                                            Update
                                        </a>
                                        
                                    @endrole

                                    @endif
                                    @if ($progress === 'selesai' && auth()->check() && !auth()->user()->hasAnyRole(['view', 'View', 'karyawan', 'Karyawan']))
                                        <a href="{{ route('pengajuan.export-pdf', $pengajuan->id) }}"
                                           class="btn btn-sm btn-danger ms-1">
                                            <i class="ri-file-pdf-line me-1"></i>
                                            PDF
                                        </a>
                                    @endif
                                    <a href="{{ route('pengajuan.detail', $pengajuan->id) }}"
                                       class="btn btn-sm btn-outline-primary ms-1">
                                        <i class="ri-eye-line me-1"></i>
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>

                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="modalPilihanPengajuan" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-1" style="background-color: #f25920;">
                <h5 class="modal-title text-white fw-semibold">
                    Ajukan Peminjaman
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body py-4 px-4">
                <div class="text-center mb-4">
                    <div class="mb-3">
                        <i class="ri-file-add-line" style="font-size: 3rem; color: #f25920;"></i>
                    </div>
                    <h6 class="fw-semibold mb-2">Buat Pengajuan Peminjaman Baru</h6>
                    <p class="text-muted mb-0 small">Silakan klik tombol di bawah untuk membuat pengajuan peminjaman aset baru</p>
                </div>
                
                <div class="d-grid gap-2">
                    <a href="{{ route('pengajuan.create') }}" class="btn btn-primary btn-lg">
                        <i class="ri-add-circle-line me-2"></i>
                        Buat Pengajuan Baru
                    </a>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="ri-close-line me-2"></i>
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
