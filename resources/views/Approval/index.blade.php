@extends('base.base')
@section('title', 'Approval')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card custom-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1">Daftar Pengajuan</p>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Kode</th>
                                <th>Kategori Aset</th>
                                <th>Aset</th>
                                <th>Perusahaan</th>
                                <th>Jumlah Orang</th>
                                <th>Jam</th>
                                <th>Status</th>
                                <th>Progress</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pengajuan as $item)
                                <tr>
                                    <td class="fw-semibold">{{ $item->kode_pengajuan }}</td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ optional($item->aset)->kategori ?? '-' }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ optional($item->aset)->nama ?? '-' }}
                                        @if($item->aset && $item->aset->lokasi)
                                            <br>
                                            <small class="text-muted">{{ $item->aset->lokasi }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div>{{ $item->perusahaan_tamu }}</div>
                                        <small class="text-muted">Pengutus: {{ $item->perusahaan_pengutus }}</small>
                                    </td>
                                    <td class="text-center">{{ $item->jumlah }}</td>
                                    <td>{{ $item->jam_mulai }} - {{ $item->jam_selesai }}</td>
                                    <td>
                                    @php
                                        $status = strtolower($item->status);
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
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    @endif
                                </td>
                                    <td>{{ $item->progress }}</td>
                                    <td>
                                        <a class="btn btn-sm btn-primary" href="{{ route('approval.detail', $item->id) }}">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">Belum ada pengajuan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection