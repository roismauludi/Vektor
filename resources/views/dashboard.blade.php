@extends('base.base')
@section('title', 'Dashboard')
@section('content')
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
                        <div class="card h-100">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="text-muted">Total Pengajuan</div>
                                    <div class="display-6 mb-0">{{ number_format($totalPengajuan ?? 0) }}</div>
                                </div>
                                <span class="avatar avatar-lg bg-primary-transparent">
                                    <i class="ri-file-list-3-line fs-22"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="card h-100">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="text-muted">Menunggu Verifikasi</div>
                                    <div class="display-6 mb-0">{{ number_format($menunggu) }}</div>
                                </div>
                                <span class="avatar avatar-lg bg-info-transparent">
                                    <i class="ri-time-line fs-22"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="card h-100">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="text-muted">Approved</div>
                                    <div class="display-6 mb-0">{{ number_format($approved) }}</div>
                                </div>
                                <span class="avatar avatar-lg bg-success-transparent">
                                    <i class="ri-checkbox-circle-line fs-22"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="card h-100">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="text-muted">Rejected</div>
                                    <div class="display-6 mb-0">{{ number_format($rejected) }}</div>
                                </div>
                                <span class="avatar avatar-lg bg-danger-transparent">
                                    <i class="ri-close-circle-line fs-22"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="card h-100">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="text-muted">In-Progress</div>
                                    <div class="display-6 mb-0">{{ number_format($inprogress) }}</div>
                                </div>
                                <span class="avatar avatar-lg bg-warning-transparent">
                                    <i class="ri-loader-4-line fs-22"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="card h-100">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="text-muted">Selesai</div>
                                    <div class="display-6 mb-0">{{ number_format($selesai) }}</div>
                                </div>
                                <span class="avatar avatar-lg bg-primary-transparent">
                                    <i class="ri-flag-2-line fs-22"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection