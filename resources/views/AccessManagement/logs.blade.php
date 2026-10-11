@extends('base.base')
@section('title', 'Log Permission - Hak Akses')
@section('content')

<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="card-title mb-0">
                    <h5 class="mb-0">
                        <i class="ri-history-line me-2" style="color: #f25920;"></i>
                        Audit Log Perubahan Permission & Role
                    </h5>
                    <small class="text-muted">Histori seluruh penyesuaian hak akses user yang dilakukan oleh Administrator</small>
                </div>
                <div>
                    <a href="{{ route('access-management.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="ri-shield-user-line me-1"></i>
                        Kelola Akses User
                    </a>
                </div>
            </div>

            <div class="card-body">
                <!-- Search & Filter Form -->
                <form action="{{ route('access-management.logs') }}" method="GET" class="mb-4">
                    <div class="row g-2 align-items-center">
                        <div class="col-md-5 col-lg-4">
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="ri-search-line"></i>
                                </span>
                                <input type="text" 
                                       name="search" 
                                       class="form-control" 
                                       placeholder="Cari nama user, atau keterangan..." 
                                       value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-filter-3-line me-1"></i>
                                Cari
                            </button>
                            @if(request('search'))
                                <a href="{{ route('access-management.logs') }}" class="btn btn-light ms-1">
                                    <i class="ri-refresh-line me-1"></i>
                                    Reset
                                </a>
                            @endif
                        </div>
                    </div>
                </form>

                <!-- Table Logs -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle text-nowrap w-100">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Waktu & Tanggal</th>
                                <th>Target User</th>
                                <th>Diubah Oleh</th>
                                <th>Aksi</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $index => $log)
                                <tr>
                                    <td>{{ $logs->firstItem() + $index }}</td>
                                    <td>
                                        <small class="text-muted fw-semibold">
                                            <i class="ri-calendar-event-line me-1"></i>
                                            {{ $log->created_at ? $log->created_at->format('d M Y, H:i:s') : '-' }}
                                        </small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="avatar avatar-sm bg-primary-transparent rounded-circle">
                                                <i class="ri-user-line text-primary"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $log->targetUser->nama ?? 'User Terhapus' }}</div>
                                                <small class="text-muted">{{ $log->targetUser->email ?? '-' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            <i class="ri-user-star-line me-1 text-warning"></i>
                                            {{ $log->admin->nama ?? 'Administrator' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($log->aksi == 'update_role')
                                            <span class="badge bg-primary">
                                                <i class="ri-shield-user-line me-1"></i>Update Role
                                            </span>
                                        @elseif($log->aksi == 'update_permissions')
                                            <span class="badge bg-info">
                                                <i class="ri-key-2-line me-1"></i>Update Permissions
                                            </span>
                                        @elseif($log->aksi == 'copy_role')
                                            <span class="badge bg-warning">
                                                <i class="ri-file-copy-line me-1"></i>Copy Role
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">{{ $log->aksi }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="text-wrap" style="max-width: 400px;">
                                            @php
                                                $added = $log->added_permissions;
                                                $removed = $log->removed_permissions;
                                            @endphp

                                            @if(!empty($added) || !empty($removed))
                                                <div class="d-flex flex-column gap-1">
                                                    @if(!empty($added))
                                                        <div>
                                                            <span class="badge bg-success-transparent text-success border border-success-subtle">
                                                                <i class="ri-add-circle-line me-1"></i>Menambah: {{ implode(', ', $added) }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                    @if(!empty($removed))
                                                        <div>
                                                            <span class="badge bg-danger-transparent text-danger border border-danger-subtle">
                                                                <i class="ri-indeterminate-circle-line me-1"></i>Menghapus: {{ implode(', ', $removed) }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            @else
                                                <span>{{ $log->keterangan }}</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="ri-inbox-archive-line fs-1 d-block mb-2 text-secondary"></i>
                                        Belum ada data log perubahan permission yang tercatat.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-end mt-3">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
