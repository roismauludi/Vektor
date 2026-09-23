@extends('base.base')
@section('title', 'Detail Pengajuan')

@section('content')
@php
    $progressLower = strtolower(trim($pengajuan->progress));
    $isWaitingVerification = str_contains($progressLower, 'menunggu') && str_contains($progressLower, 'verifikasi');
@endphp
<style>
    .photo-item { text-align: center; }
    .photo-pair-title {
        font-size: 0.95rem;
        font-weight: 600;
        margin-bottom: 0.85rem;
        color: #1f2937;
    }
    .photo-frame {
        width: 160px;
        height: 160px;
        margin: 0 auto;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e5e7eb;
        background: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #9ca3af;
        font-size: 1.6rem;
    }
    .photo-frame img {
        width: 160px;
        height: 160px;
        object-fit: cover;
        display: block;
    }
    .photo-frame:hover img {
        transform: scale(1.03);
    }
    .photo-preview-frame {
        width: 100%;
        height: 240px;
        border-radius: 12px;
        overflow: hidden;
        background: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #9ca3af;
        font-size: 2rem;
    }
    .photo-preview-frame img {
        width: 100%;
        height: 240px;
        object-fit: cover;
        display: block;
    }
    .photo-locked-message {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 2rem;
    }
</style>

<div class="row mb-3">
    <div class="col-12">
        <div class="d-flex justify-content-end">
            <a href="{{ route('pengajuan.list') }}" class="btn btn-outline-secondary">
                <i class="ri-arrow-left-line me-1"></i>
                Kembali
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="row">
            <div class="col-md-6">
                <div class="card custom-card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <p class="text-muted mb-0">Detail Pengajuan</p>
                        <div class="d-flex align-items-center gap-2">
                            @role('Security')
                            <form id="cancel-pengajuan-form" action="{{ route('pengajuan.cancel', $pengajuan->id) }}" method="post" class="mb-0">
                                @csrf
                                @method('POST')
                                <button type="button"
                                        id="btn-cancel-pengajuan"
                                        class="btn btn-sm btn-danger"
                                        {{ $isWaitingVerification ? '' : 'disabled' }}>
                                    <i class="ri-close-circle-line me-1"></i>
                                    Cancel Pengajuan
                                </button>
                            </form>
                            @endrole
                            <a href="{{ route('pengajuan.list') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="ri-arrow-right-line ms-1"></i>
                                Kembali
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th width="35%">Kode Pengajuan</th>
                                    <td>{{ $pengajuan->kode_pengajuan }}</td>
                                </tr>
                                <tr>
                                    <th>Jenis / Kategori Aset</th>
                                    <td>{{ optional($pengajuan->aset)->kategori ?? $pengajuan->jenis ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Kode Aset</th>
                                    <td>
                                        {{ optional($pengajuan->aset)->kode_aset ?? $pengajuan->aset_kode ?? '-' }}
                                    </td>
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
            $fotoRows = [
                [
                    'label' => 'Kendaraan',
                    'pergi' => ['kategori' => 'kendaraan', 'pengembalian' => false],
                    'kembali' => ['kategori' => 'kendaraan', 'pengembalian' => true],
                ],
                [
                    'label' => 'Kunci',
                    'pergi' => ['kategori' => 'penyerahan_kunci', 'pengembalian' => false],
                    'kembali' => ['kategori' => 'pengembalian_kunci', 'pengembalian' => true],
                ],
                [
                    'label' => 'Lainnya',
                    'pergi' => ['kategori' => 'lainnya', 'pengembalian' => false],
                    'kembali' => ['kategori' => 'lainnya', 'pengembalian' => true],
                ],
            ];

            $hasPhotos = collect($fotoRows)->contains(function ($row) use ($pengajuan) {
                return $pengajuan->fotoPath($row['pergi']['kategori'], $row['pergi']['pengembalian'])
                    || $pengajuan->fotoPath($row['kembali']['kategori'], $row['kembali']['pengembalian']);
            });

            $canShowPhotos = in_array($progressLower, ['approved', 'in-progress', 'selesai'], true) || $hasPhotos;
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
                                @foreach ($fotoRows as $row)
                                    @php
                                        $pergiPath = $pengajuan->fotoPath($row['pergi']['kategori'], false);
                                        $kembaliPath = $pengajuan->fotoPath($row['kembali']['kategori'], true);
                                        $coverPath = $pergiPath ?: $kembaliPath;
                                        $slug = \Illuminate\Support\Str::slug($row['label']);
                                        $modalId = 'modalFoto-'.$slug;
                                    @endphp
                                    <div class="col-md-4">
                                        <div class="photo-item">
                                            <h6 class="photo-pair-title">{{ $row['label'] }}</h6>
                                            @if ($coverPath)
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#{{ $modalId }}" class="d-inline-block">
                                                    <div class="photo-frame">
                                                        <img src="{{ asset('storage/'.$coverPath) }}" alt="{{ $row['label'] }}">
                                                    </div>
                                                </a>
                                            @else
                                                <div class="photo-frame">
                                                    <i class="ri-image-line"></i>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
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

                    @foreach ($fotoRows as $row)
                        @php
                            $slug = \Illuminate\Support\Str::slug($row['label']);
                            $pergiPath = $pengajuan->fotoPath($row['pergi']['kategori'], false);
                            $kembaliPath = $pengajuan->fotoPath($row['kembali']['kategori'], true);
                        @endphp
                        @if ($pergiPath || $kembaliPath)
                            <div class="modal fade" id="modalFoto-{{ $slug }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">{{ $row['label'] }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <p class="text-center fw-semibold mb-2">Pergi</p>
                                                    <div class="photo-preview-frame">
                                                        @if ($pergiPath)
                                                            <a href="{{ asset('storage/'.$pergiPath) }}" target="_blank" class="d-block w-100 h-100">
                                                                <img src="{{ asset('storage/'.$pergiPath) }}" alt="{{ $row['label'] }} Pergi">
                                                            </a>
                                                        @else
                                                            <i class="ri-image-line"></i>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <p class="text-center fw-semibold mb-2">Kembali</p>
                                                    <div class="photo-preview-frame">
                                                        @if ($kembaliPath)
                                                            <a href="{{ asset('storage/'.$kembaliPath) }}" target="_blank" class="d-block w-100 h-100">
                                                                <img src="{{ asset('storage/'.$kembaliPath) }}" alt="{{ $row['label'] }} Kembali">
                                                            </a>
                                                        @else
                                                            <i class="ri-image-line"></i>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const btnCancel = document.getElementById('btn-cancel-pengajuan');
    if (btnCancel && !btnCancel.disabled) {
        btnCancel.addEventListener('click', function () {
            Swal.fire({
                title: 'Cancel Pengajuan?',
                text: 'Pengajuan ini akan dibatalkan dan tidak dapat dikembalikan. Apakah Anda yakin?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Cancel',
                cancelButtonText: 'Tidak',
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('cancel-pengajuan-form').submit();
                }
            });
        });
    }
});
</script>
@endpush
