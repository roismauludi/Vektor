@extends('base.base')
@section('title', 'Detail Pengajuan')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card custom-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1">Detail Pengajuan</p>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Kode</th>
                                <th>Jenis</th>
                                <th>Keperluan</th>
                                <th>Jumlah Tamu</th>
                                <th>Nama Tamu</th>
                                <th>No Telepon</th>
                                <th>Lama Kerja</th>
                                <th>Identitas</th>
                                <th>Identitas File</th>
                                <th>Progres</th>
                                <th>Pembuat Pengajuan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        @php
                            $rowspan = $pengajuan->details->count();
                        @endphp

                        @foreach ($pengajuan->details as $index => $detail)
                        <tr>
                            @if ($index === 0)
                                <td rowspan="{{ $rowspan }}" class="fw-semibold">
                                    {{ $pengajuan->kode_pengajuan }}
                                </td>
                                <td rowspan="{{ $rowspan }}">
                                    <span class="badge bg-primary">{{ $pengajuan->jenis }}</span>
                                </td>
                                <td rowspan="{{ $rowspan }}">{{ $pengajuan->keperluan }}</td>
                                <td rowspan="{{ $rowspan }}" class="text-center">
                                    {{ $pengajuan->jumlah }}
                                </td>
                            @endif

                            <td>{{ $detail->nama_tamu }}</td>
                            <td>{{ $detail->no_tlp }}</td>

                            @if ($index === 0)
                                <td rowspan="{{ $rowspan }}">{{ $pengajuan->lama_berkerja }}</td>
                                <td rowspan="{{ $rowspan }}">{{ $pengajuan->identitas }}</td>
                                <td rowspan="{{ $rowspan }}">
                                    <a href="{{ asset('storage/'.$pengajuan->identitas_file) }}" target="_blank"
                                       class="btn btn-info btn-sm">
                                        Cek Identitas
                                    </a>
                                </td>
                                <td rowspan="{{ $rowspan }}" id="progress-container">
                                    <span id="progress-badge" class="badge {{ $pengajuan->progress === 'approved' ? 'bg-success' : ($pengajuan->progress === 'rejected' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                        {{ $pengajuan->progress }}
                                    </span>
                                </td>
                                <td rowspan="{{ $rowspan }}">
                                    {{ $pengajuan->creator->nama }}
                                </td>
                                <td rowspan="{{ $rowspan }}">
                                    <div class="d-grid gap-2">
                                        <button type="button"
                                                class="btn btn-sm btn-success btn-approve"
                                                data-id="{{ $pengajuan->id }}"
                                                {{ $pengajuan->progress !== 'Menunggu verifikasi' ? 'disabled' : '' }}>
                                            Approve
                                        </button>
                                        <button type="button"
                                                class="btn btn-sm btn-danger btn-reject"
                                                data-id="{{ $pengajuan->id }}"
                                                {{ $pengajuan->progress !== 'Menunggu verifikasi' ? 'disabled' : '' }}>
                                            Reject
                                        </button>
                                    </div>
                                </td>
                            @endif
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = '{{ csrf_token() }}';

    function updateUIStatus(newProgress) {
        const badge = document.getElementById('progress-badge');
        if (badge) {
            badge.innerText = newProgress;
            badge.className = 'badge ' + (newProgress === 'approved' ? 'bg-success' : (newProgress === 'rejected' ? 'bg-danger' : 'bg-warning text-dark'));
        }
        document.querySelectorAll('.btn-approve, .btn-reject').forEach(btn => {
            btn.disabled = true;
        });
    }

    document.querySelectorAll('.btn-approve').forEach(button => {
        button.addEventListener('click', function () {
            Swal.fire({
                title: 'Approve Pengajuan?',
                text: 'Apakah Anda yakin ingin menyetujui pengajuan ini?',
                showCancelButton: true,
                confirmButtonText: 'Ya, Approve',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#198754'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.showLoading();
                    fetch(`{{ route('approval.approve', $pengajuan->id) }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ _method: 'PUT' })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            updateUIStatus(data.progress || 'approved');
                            Swal.fire('Berhasil!', data.message, 'success');
                        } else {
                            Swal.fire('Gagal!', data.message || 'Terjadi kesalahan.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Error!', 'Gagal menghubungi server.', 'error');
                    });
                }
            });
        });
    });

    document.querySelectorAll('.btn-reject').forEach(button => {
        button.addEventListener('click', function () {
            Swal.fire({
                title: 'Tolak Pengajuan?',
                input: 'textarea',
                inputLabel: 'Keterangan Reject',
                inputPlaceholder: 'Masukkan alasan penolakan...',
                showCancelButton: true,
                confirmButtonText: 'Ya, Tolak',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#d33',
                preConfirm: (value) => {
                    if (!value) {
                        Swal.showValidationMessage('Keterangan wajib diisi');
                    }
                    return value;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.showLoading();
                    fetch(`{{ route('approval.reject', $pengajuan->id) }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            _method: 'PUT',
                            keterangan: result.value
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            updateUIStatus(data.progress || 'rejected');
                            Swal.fire('Berhasil!', data.message, 'success');
                        } else {
                            Swal.fire('Gagal!', data.message || 'Terjadi kesalahan.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Error!', 'Gagal menghubungi server.', 'error');
                    });
                }
            });
        });
    });
});
</script>
@endsection
