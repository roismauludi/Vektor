@extends('base.base')
@section('title', 'List Pengajuan')
@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Data Peminjaman</h5>
                <button type="button"
                        class="btn btn-outline-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#modalPilihanPengajuan">
                    Ajukan Peminjaman
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
                                    @if ($pengajuan->jenis === 'tower')
                                        <span class="badge bg-info">Tower</span>
                                    @else
                                        <span class="badge bg-info">Central</span>
                                    @endif
                                </td>

                                <td>
                                    {{ $pengajuan->towerAset->kode_aset?? $pengajuan->centralAset->kode_aset?? '-' }}
                                </td>

                                <td>
                                    {{ $pengajuan->towerAset->nama?? $pengajuan->centralAset->nama?? '-' }}
                                </td>

                                <td>
                                    @php
                                        $status = strtolower($pengajuan->status);
                                    @endphp

                                    @if ($status === 'open')
                                        <span class="badge bg-success">Open</span>

                                    @elseif ($status === 'dipinjam')
                                        <span class="badge bg-warning text-dark">Dipinjam</span>

                                        @elseif ($status === 'cancel')
                                        <span class="badge bg-danger">Cancel</span>

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
                                        <span class="badge bg-primary">Approved</span>
                                    @elseif ($progress === 'in-progress')
                                        <span class="badge bg-warning text-dark">In Progress</span>
                                    @elseif ($progress === 'selesai')
                                        <span class="badge bg-success">Selesai</span>
                                    @elseif ($progress === 'rejected')
                                        <span class="badge bg-danger">Rejected</span>
                                    @else
                                        <span class="badge bg-secondary">
                                            {{ ucfirst($pengajuan->progress) }}
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    @if ($progress === 'approved'
                                        && (!$pengajuan->fotoPath('kendaraan') || !$pengajuan->fotoPath('penyerahan_kunci')))
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
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Pilih Jenis Pengajuan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <a href="{{ route('pengajuan.tower') }}"
                           class="btn btn-outline-primary w-100">
                            Tower
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('pengajuan.central') }}"
                           class="btn btn-outline-primary w-100">
                            Central
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
