@extends('base.base')
@section('title', 'Dashboard')
@section('content')
    <style>
        .stat-card-orange {
            background-color: #f25920;
            color: white;
            border: none;
        }
        .stat-icon {
            background-color: rgba(255, 255, 255, 0.2);
        }
        .btn-group .btn-check:checked + .btn-outline-primary {
            background-color: #f25920;
            border-color: #f25920;
            color: white;
        }
        .btn-group .btn-outline-primary {
            transition: all 0.3s ease;
        }
        .btn-group .btn-outline-primary:hover {
            background-color: rgba(255, 122, 0, 0.1);
            border-color: #f25920;
            color: #f25920;
        }
        @media (max-width: 768px) {
            .btn-group {
                display: flex;
                flex-direction: column;
            }
            .btn-group .btn {
                border-radius: 0.375rem !important;
                margin-bottom: 0.5rem;
            }
        }
    </style>

    <div class="row-12">
        <div class="card">
            <div class="card-body">
                <h1>Dashboard</h1>

                @php
                    $summary = $progressSummary ?? [];
                    $menunggu = (int) ($summary['Menunggu verifikasi'] ?? 0);
                    $approved = (int) ($summary['Approved'] ?? 0);
                    $rejected = (int) ($summary['Rejected'] ?? 0);
                    $inprogress = (int) ($summary['In-Progress'] ?? 0);
                    $selesai = (int) ($summary['Selesai'] ?? 0);
                @endphp

                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="card h-100 stat-card-orange">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="text-white-50">Total Pengajuan</div>
                                    <div class="display-6 mb-0 text-white">{{ number_format($totalPengajuan ?? 0) }}</div>
                                </div>
                                <span class="avatar avatar-lg stat-icon">
                                    <i class="ri-file-list-3-line fs-22 text-white"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="card h-100 stat-card-orange">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="text-white-50">Menunggu Verifikasi</div>
                                    <div class="display-6 mb-0 text-white">{{ number_format($menunggu) }}</div>
                                </div>
                                <span class="avatar avatar-lg stat-icon">
                                    <i class="ri-time-line fs-22 text-white"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="card h-100 stat-card-orange">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="text-white-50">Approved</div>
                                    <div class="display-6 mb-0 text-white">{{ number_format($approved) }}</div>
                                </div>
                                <span class="avatar avatar-lg stat-icon">
                                    <i class="ri-checkbox-circle-line fs-22 text-white"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="card h-100 stat-card-orange">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="text-white-50">Rejected</div>
                                    <div class="display-6 mb-0 text-white">{{ number_format($rejected) }}</div>
                                </div>
                                <span class="avatar avatar-lg stat-icon">
                                    <i class="ri-close-circle-line fs-22 text-white"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="card h-100 stat-card-orange">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="text-white-50">In-Progress</div>
                                    <div class="display-6 mb-0 text-white">{{ number_format($inprogress) }}</div>
                                </div>
                                <span class="avatar avatar-lg stat-icon">
                                    <i class="ri-loader-4-line fs-22 text-white"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12 col-md-6 col-xl-4">
                            <div class="card h-100 stat-card-orange">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="text-white-50">Selesai</div>
                                    <div class="display-6 mb-0 text-white">{{ number_format($selesai) }}</div>
                                </div>
                                <span class="avatar avatar-lg stat-icon">
                                    <i class="ri-flag-2-line fs-22 text-white"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card mt-3">
                            <div class="card-body">
                                <h5 class="card-title mb-3">Ringkasan Pengajuan</h5>
                                <div class="mb-4">
                                    <form method="GET" action="{{ route('dashboard') }}" id="filterForm">
                                        <div class="row g-2 align-items-end">
                                            <div class="col-12 col-md-4">
                                                <label for="start_date" class="form-label mb-1">Tanggal mulai</label>
                                                <input
                                                    type="date"
                                                    class="form-control"
                                                    id="start_date"
                                                    name="start_date"
                                                    value="{{ $startDateValue ?? '' }}"
                                                    max="{{ now()->yesterday()->format('Y-m-d') }}"
                                                >
                                            </div>

                                            <div class="col-12 col-md-4">
                                                <label for="end_date" class="form-label mb-1">Tanggal akhir</label>
                                                <input
                                                    type="date"
                                                    class="form-control"
                                                    id="end_date"
                                                    name="end_date"
                                                    value="{{ $endDateValue ?? '' }}"
                                                    max="{{ now()->format('Y-m-d') }}"
                                                >
                                            </div>

                                            <div class="col-12 col-md-4 d-flex gap-2">
                                                <button type="submit" class="btn btn-outline-primary flex-grow-1">
                                                    <i class="ri-filter-3-line me-1"></i> Terapkan
                                                </button>
                                                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                                                    Reset
                                                </a>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                {{-- Summary Card --}}
                                <div class="card stat-card-orange">
                                    <div class="card-body text-center">
                                        <div class="display-4 mb-2 text-white">
                                            {{ number_format($totalFiltered ?? 0) }}
                                        </div>
                                        <div class="text-white">
                                            Total Pengajuan 
                                            @if(!empty($startDateLabel) && !empty($endDateLabel))
                                                ({{ $startDateLabel }} s/d {{ $endDateLabel }})
                                            @else
                                                (Semua Data)
                                            @endif
                                        </div>
                                        @if(isset($dailySummary) && $dailySummary->count() > 0)
                                            <div class="mt-2 text-white-50">
                                                @if(!empty($rangeDays))
                                                    Rata-rata: <strong>{{ number_format($totalFiltered / max(1, $rangeDays), 1) }}</strong> pengajuan/hari
                                                    <div class="small text-white-50">Jumlah hari dalam rentang: {{ number_format($rangeDays) }}</div>
                                                @else
                                                    Total: <strong>{{ $dailySummary->count() }}</strong> hari dengan pengajuan
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection