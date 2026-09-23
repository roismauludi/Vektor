@extends('base.base')
@section('title', 'Dashboard')

@section('page-subtitle')
    Ringkasan pengajuan dan pemantauan realtime
@endsection

@section('page-actions')
    <span class="live-badge">
        <span class="live-dot"></span>
        Live Monitoring
    </span>
    <span class="page-header-meta" id="lastUpdated">
        Diperbarui: {{ $updatedAt ?? now()->format('H:i:s') }}
    </span>
@endsection

@section('content')
    <style>
        :root {
            --vektor-orange: #f25920;
            --vektor-orange-dark: #d94a15;
            --vektor-orange-light: #ff7a45;
            --vektor-orange-soft: rgba(242, 89, 32, 0.08);
            --vektor-orange-glow: rgba(242, 89, 32, 0.25);
        }

        .live-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.35rem 0.85rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.25);
            font-size: 0.8rem;
            font-weight: 600;
            color: #fff;
        }

        .live-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #22c55e;
            box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.6);
            animation: pulse-live 2s infinite;
        }

        @keyframes pulse-live {
            0% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.6); }
            70% { box-shadow: 0 0 0 8px rgba(34, 197, 94, 0); }
            100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
        }

        .stat-card {
            border: none;
            border-radius: 1rem;
            overflow: hidden;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px var(--vektor-orange-glow);
        }

        .stat-card-primary {
            background: linear-gradient(135deg, var(--vektor-orange) 0%, var(--vektor-orange-dark) 100%);
            color: #fff;
        }

        .stat-card-accent {
            background: #fff;
            border-left: 4px solid var(--vektor-orange);
        }

        .stat-card-accent .stat-label {
            color: #64748b;
        }

        .stat-card-accent .stat-value {
            color: var(--vektor-orange);
        }

        .stat-icon-wrap {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }

        .stat-card-primary .stat-icon-wrap {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        .stat-card-accent .stat-icon-wrap {
            background: var(--vektor-orange-soft);
            color: var(--vektor-orange);
        }

        .stat-label {
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 0.25rem;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            line-height: 1.1;
            transition: color 0.3s ease;
        }

        .stat-value.updating {
            color: var(--vektor-orange-light) !important;
        }

        .chart-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            height: 100%;
        }

        .chart-card .card-header {
            background: transparent;
            border-bottom: 1px solid #f1f5f9;
            padding: 1.25rem 1.5rem 0.75rem;
        }

        .chart-card .card-title {
            font-size: 1rem;
            font-weight: 600;
            color: #1a1a2e;
            margin: 0;
        }

        .activity-item {
            display: flex;
            gap: 0.85rem;
            padding: 0.85rem 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: var(--vektor-orange-soft);
            color: var(--vektor-orange);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 1rem;
        }

        .activity-kode {
            font-weight: 600;
            color: var(--vektor-orange);
            font-size: 0.85rem;
        }

        .activity-time {
            font-size: 0.75rem;
            color: #94a3b8;
        }

        .progress-status-bar {
            height: 8px;
            border-radius: 999px;
            background: #f1f5f9;
            overflow: hidden;
        }

        .progress-status-fill {
            height: 100%;
            border-radius: 999px;
            background: linear-gradient(90deg, var(--vektor-orange), var(--vektor-orange-light));
            transition: width 0.6s ease;
        }

        .summary-card {
            background: linear-gradient(135deg, var(--vektor-orange) 0%, #e84d1a 100%);
            border: none;
            border-radius: 1rem;
            color: #fff;
            box-shadow: 0 8px 28px var(--vektor-orange-glow);
        }

        .btn-vektor {
            background: var(--vektor-orange);
            border-color: var(--vektor-orange);
            color: #fff;
        }

        .btn-vektor:hover {
            background: var(--vektor-orange-dark);
            border-color: var(--vektor-orange-dark);
            color: #fff;
        }

        .btn-outline-vektor {
            border-color: var(--vektor-orange);
            color: var(--vektor-orange);
        }

        .btn-outline-vektor:hover {
            background: var(--vektor-orange-soft);
            border-color: var(--vektor-orange);
            color: var(--vektor-orange);
        }

        .empty-activity {
            text-align: center;
            padding: 2rem 1rem;
            color: #94a3b8;
        }

        .status-legend-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.5rem 0;
            font-size: 0.875rem;
        }

        .status-legend-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 0.5rem;
            flex-shrink: 0;
        }
    </style>

    @php
        $summary = $progressSummary ?? [];
        $menunggu = (int) ($summary['Menunggu verifikasi'] ?? 0);
        $approved = (int) ($summary['Approved'] ?? 0);
        $rejected = (int) ($summary['Rejected'] ?? 0);
        $inprogress = (int) ($summary['In-Progress'] ?? 0);
        $selesai = (int) ($summary['Selesai'] ?? 0);
        $total = max(1, $totalPengajuan ?? 1);

        $statCards = [
            ['key' => 'total', 'label' => 'Total Pengajuan', 'value' => $totalPengajuan ?? 0, 'icon' => 'ri-file-list-3-line', 'primary' => true],
            ['key' => 'menunggu', 'label' => 'Menunggu Verifikasi', 'value' => $menunggu, 'icon' => 'ri-time-line', 'primary' => false],
            ['key' => 'approved', 'label' => 'Approved', 'value' => $approved, 'icon' => 'ri-checkbox-circle-line', 'primary' => false],
            ['key' => 'rejected', 'label' => 'Rejected', 'value' => $rejected, 'icon' => 'ri-close-circle-line', 'primary' => false],
            ['key' => 'inprogress', 'label' => 'In-Progress', 'value' => $inprogress, 'icon' => 'ri-loader-4-line', 'primary' => false],
            ['key' => 'selesai', 'label' => 'Selesai', 'value' => $selesai, 'icon' => 'ri-flag-2-line', 'primary' => false],
        ];

        $statusColors = [
            'Menunggu verifikasi' => '#fbbf24',
            'Approved' => '#22c55e',
            'Rejected' => '#ef4444',
            'In-Progress' => '#3b82f6',
            'Selesai' => '#f25920',
        ];
    @endphp

    {{-- Stat Cards --}}
    <div class="row g-3 mb-4">
        @foreach ($statCards as $card)
            <div class="col-12 col-sm-6 col-xl-4">
                <div class="card stat-card h-100 {{ $card['primary'] ? 'stat-card-primary' : 'stat-card-accent' }}">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stat-label {{ $card['primary'] ? 'text-white-50' : '' }}">{{ $card['label'] }}</div>
                            <div class="stat-value mb-0" id="stat-{{ $card['key'] }}" data-stat-key="{{ $card['key'] }}">
                                {{ number_format($card['value']) }}
                            </div>
                        </div>
                        <div class="stat-icon-wrap">
                            <i class="{{ $card['icon'] }}"></i>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Charts & Activity --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-lg-8">
            <div class="card chart-card">
                <div class="card-header">
                    <h5 class="card-title">Tren Pengajuan (7 Hari Terakhir)</h5>
                </div>
                <div class="card-body">
                    <div id="trendChart" style="min-height: 280px;"></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            <div class="card chart-card">
                <div class="card-header">
                    <h5 class="card-title">Distribusi Status</h5>
                </div>
                <div class="card-body">
                    <div id="statusChart" style="min-height: 220px;"></div>
                    <div class="mt-2" id="statusLegend">
                        @foreach ($summary as $label => $count)
                            @if ($label !== 'Lainnya' || $count > 0)
                                <div class="status-legend-item">
                                    <span class="d-flex align-items-center">
                                        <span class="status-legend-dot" style="background: {{ $statusColors[$label] ?? '#94a3b8' }}"></span>
                                        {{ $label }}
                                    </span>
                                    <strong id="legend-{{ \Illuminate\Support\Str::slug($label) }}">{{ number_format($count) }}</strong>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Progress Breakdown & Activity Feed --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-lg-5">
            <div class="card chart-card">
                <div class="card-header">
                    <h5 class="card-title">Progress Breakdown</h5>
                </div>
                <div class="card-body">
                    @foreach (['Menunggu verifikasi' => $menunggu, 'Approved' => $approved, 'In-Progress' => $inprogress, 'Selesai' => $selesai, 'Rejected' => $rejected] as $label => $count)
                        @php $pct = round(($count / $total) * 100, 1); @endphp
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="small fw-medium">{{ $label }}</span>
                                <span class="small text-muted" id="pct-{{ \Illuminate\Support\Str::slug($label) }}">{{ $pct }}%</span>
                            </div>
                            <div class="progress-status-bar">
                                <div class="progress-status-fill" id="bar-{{ \Illuminate\Support\Str::slug($label) }}" style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-7">
            <div class="card chart-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Aktivitas Terbaru</h5>
                    <span class="badge bg-light text-dark" id="activityCount">{{ count($recentActivities ?? []) }} aktivitas</span>
                </div>
                <div class="card-body pt-0" id="activityFeed" style="max-height: 340px; overflow-y: auto;">
                    @forelse ($recentActivities ?? [] as $activity)
                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="ri-history-line"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start">
                                    <span class="activity-kode">{{ $activity['kode'] }}</span>
                                    <span class="activity-time">{{ $activity['time'] }}</span>
                                </div>
                                <div class="small fw-medium">{{ $activity['aksi'] }}</div>
                                <div class="small text-muted">{{ $activity['creator'] }}@if($activity['keterangan']) — {{ Str::limit($activity['keterangan'], 60) }}@endif</div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-activity">
                            <i class="ri-inbox-line fs-2 d-block mb-2"></i>
                            Belum ada aktivitas
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Ringkasan Pengajuan --}}
    <div class="row">
        <div class="col-12">
            <div class="card chart-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                        <h5 class="card-title mb-0">Ringkasan Pengajuan</h5>
                    </div>
                    <form method="GET" action="{{ route('dashboard') }}" id="filterForm">
                        <div class="row g-2 align-items-end mb-4">
                            <div class="col-12 col-md-4">
                                <label for="start_date" class="form-label mb-1">Tanggal mulai</label>
                                <input type="date" class="form-control" id="start_date" name="start_date"
                                    value="{{ $startDateValue ?? '' }}" max="{{ now()->yesterday()->format('Y-m-d') }}">
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="end_date" class="form-label mb-1">Tanggal akhir</label>
                                <input type="date" class="form-control" id="end_date" name="end_date"
                                    value="{{ $endDateValue ?? '' }}" max="{{ now()->format('Y-m-d') }}">
                            </div>
                            <div class="col-12 col-md-4 d-flex gap-2">
                                <button type="submit" class="btn btn-vektor flex-grow-1">
                                    <i class="ri-filter-3-line me-1"></i> Terapkan
                                </button>
                                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Reset</a>
                            </div>
                        </div>
                    </form>

                    <div class="card summary-card">
                        <div class="card-body text-center py-4">
                            <div class="display-4 mb-2 fw-bold" id="totalFiltered">{{ number_format($totalFiltered ?? 0) }}</div>
                            <div>
                                Total Pengajuan
                                @if(!empty($startDateLabel) && !empty($endDateLabel))
                                    ({{ $startDateLabel }} s/d {{ $endDateLabel }})
                                @else
                                    (Semua Data)
                                @endif
                            </div>
                            @if(isset($dailySummary) && $dailySummary->count() > 0)
                                <div class="mt-2 opacity-75 small">
                                    @if(!empty($rangeDays))
                                        Rata-rata: <strong>{{ number_format($totalFiltered / max(1, $rangeDays), 1) }}</strong> pengajuan/hari
                                        &middot; {{ number_format($rangeDays) }} hari
                                    @else
                                        <strong>{{ $dailySummary->count() }}</strong> hari dengan pengajuan
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="table-responsive mt-4">
                        <table id="file-export" class="table table-bordered text-nowrap w-100">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Pengajuan</th>
                                    <th>Kategori</th>
                                    <th>Kode Aset</th>
                                    <th>Nama Aset</th>
                                    <th>Perusahaan Tamu</th>
                                    <th>Progress</th>
                                    <th>Status</th>
                                    <th>Pengaju</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($exportPengajuan ?? [] as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->kode_pengajuan }}</td>
                                        <td>{{ $item->kategori }}</td>
                                        <td>{{ optional($item->aset)->kode_aset ?? $item->aset_kode ?? '-' }}</td>
                                        <td>{{ optional($item->aset)->nama ?? '-' }}</td>
                                        <td>{{ $item->perusahaan_tamu }}</td>
                                        <td>{{ $item->progress }}</td>
                                        <td>{{ $item->status }}</td>
                                        <td>{{ optional($item->creator)->nama ?? '-' }}</td>
                                        <td>{{ optional($item->created_at)->format('d-m-Y H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ORANGE = '#f25920';
    const ORANGE_LIGHT = '#ff7a45';
    const STATUS_COLORS = {
        'Menunggu verifikasi': '#fbbf24',
        'Approved': '#22c55e',
        'Rejected': '#ef4444',
        'In-Progress': '#3b82f6',
        'Selesai': '#f25920',
        'Lainnya': '#94a3b8',
    };

    const initialTrend = {
        labels: @json($trendLabels ?? []),
        values: @json($trendValues ?? []),
    };

    const initialSummary = @json($progressSummary ?? []);

    const statKeyMap = {
        'Menunggu verifikasi': 'menunggu',
        'Approved': 'approved',
        'Rejected': 'rejected',
        'In-Progress': 'inprogress',
        'Selesai': 'selesai',
    };

    const slugify = (text) => text.toLowerCase().replace(/\s+/g, '-');

    // Trend Chart
    const trendChart = new ApexCharts(document.querySelector('#trendChart'), {
        chart: {
            type: 'area',
            height: 280,
            toolbar: { show: false },
            animations: { enabled: true, easing: 'easeinout', speed: 800 },
            fontFamily: 'inherit',
        },
        series: [{ name: 'Pengajuan', data: initialTrend.values }],
        colors: [ORANGE],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.45,
                opacityTo: 0.05,
                stops: [0, 100],
            },
        },
        stroke: { curve: 'smooth', width: 3 },
        dataLabels: { enabled: false },
        xaxis: {
            categories: initialTrend.labels,
            axisBorder: { show: false },
            axisTicks: { show: false },
            labels: { style: { colors: '#64748b', fontSize: '12px' } },
        },
        yaxis: {
            min: 0,
            forceNiceScale: true,
            labels: { style: { colors: '#64748b', fontSize: '12px' } },
        },
        grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
        tooltip: { theme: 'light' },
        markers: {
            size: 5,
            colors: [ORANGE],
            strokeColors: '#fff',
            strokeWidth: 2,
            hover: { size: 7 },
        },
    });
    trendChart.render();

    // Status Donut Chart
    const statusLabels = Object.keys(initialSummary).filter(k => k !== 'Lainnya' || initialSummary[k] > 0);
    const statusValues = statusLabels.map(k => initialSummary[k]);
    const statusColorsArr = statusLabels.map(k => STATUS_COLORS[k] || '#94a3b8');

    const statusChart = new ApexCharts(document.querySelector('#statusChart'), {
        chart: {
            type: 'donut',
            height: 220,
            animations: { enabled: true, easing: 'easeinout', speed: 800 },
            fontFamily: 'inherit',
        },
        series: statusValues,
        labels: statusLabels,
        colors: statusColorsArr,
        plotOptions: {
            pie: {
                donut: {
                    size: '72%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'Total',
                            fontSize: '14px',
                            fontWeight: 600,
                            color: '#64748b',
                        },
                    },
                },
            },
        },
        legend: { show: false },
        dataLabels: { enabled: false },
        stroke: { width: 2, colors: '#fff' },
    });
    statusChart.render();

    function animateValue(el, newVal) {
        const formatted = new Intl.NumberFormat('id-ID').format(newVal);
        if (el.textContent.replace(/\./g, '') !== String(newVal)) {
            el.classList.add('updating');
            el.textContent = formatted;
            setTimeout(() => el.classList.remove('updating'), 600);
        }
    }

    function updateProgressBars(summary, total) {
        const safeTotal = Math.max(1, total);
        const barLabels = ['Menunggu verifikasi', 'Approved', 'In-Progress', 'Selesai', 'Rejected'];
        barLabels.forEach(label => {
            const count = summary[label] || 0;
            const pct = Math.round((count / safeTotal) * 1000) / 10;
            const bar = document.getElementById('bar-' + slugify(label));
            const pctEl = document.getElementById('pct-' + slugify(label));
            if (bar) bar.style.width = pct + '%';
            if (pctEl) pctEl.textContent = pct + '%';
        });
    }

    function renderActivityFeed(activities) {
        const feed = document.getElementById('activityFeed');
        const countEl = document.getElementById('activityCount');
        if (!feed) return;

        countEl.textContent = activities.length + ' aktivitas';

        if (!activities.length) {
            feed.innerHTML = '<div class="empty-activity"><i class="ri-inbox-line fs-2 d-block mb-2"></i>Belum ada aktivitas</div>';
            return;
        }

        feed.innerHTML = activities.map(a => `
            <div class="activity-item">
                <div class="activity-icon"><i class="ri-history-line"></i></div>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start">
                        <span class="activity-kode">${a.kode}</span>
                        <span class="activity-time">${a.time}</span>
                    </div>
                    <div class="small fw-medium">${a.aksi}</div>
                    <div class="small text-muted">${a.creator}${a.keterangan ? ' — ' + a.keterangan.substring(0, 60) : ''}</div>
                </div>
            </div>
        `).join('');
    }

    function updateDashboard(data) {
        animateValue(document.getElementById('stat-total'), data.total_pengajuan);

        Object.entries(statKeyMap).forEach(([label, key]) => {
            const el = document.getElementById('stat-' + key);
            if (el) animateValue(el, data.progress_summary[label] || 0);
        });

        const filteredEl = document.getElementById('totalFiltered');
        if (filteredEl) animateValue(filteredEl, data.total_filtered);

        updateProgressBars(data.progress_summary, data.total_pengajuan);

        // Update donut chart
        const labels = Object.keys(data.progress_summary).filter(k => k !== 'Lainnya' || data.progress_summary[k] > 0);
        const values = labels.map(k => data.progress_summary[k]);
        statusChart.updateOptions({
            labels,
            colors: labels.map(k => STATUS_COLORS[k] || '#94a3b8'),
        });
        statusChart.updateSeries(values);

        // Update trend chart
        if (data.trend) {
            trendChart.updateOptions({ xaxis: { categories: data.trend.labels } });
            trendChart.updateSeries([{ name: 'Pengajuan', data: data.trend.values }]);
        }

        // Update legend counts
        Object.entries(data.progress_summary).forEach(([label, count]) => {
            const el = document.getElementById('legend-' + slugify(label));
            if (el) el.textContent = new Intl.NumberFormat('id-ID').format(count);
        });

        renderActivityFeed(data.recent_activities || []);

        const updatedEl = document.getElementById('lastUpdated');
        if (updatedEl && data.updated_at) {
            const time = new Date(data.updated_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            updatedEl.textContent = 'Diperbarui: ' + time;
        }
    }

    async function fetchStats() {
        try {
            const params = new URLSearchParams(window.location.search);
            const url = '{{ route('dashboard.stats') }}' + (params.toString() ? '?' + params.toString() : '');
            const response = await fetch(url, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            });
            if (!response.ok) return;
            const data = await response.json();
            updateDashboard(data);
        } catch (e) {
            console.warn('Gagal memperbarui dashboard:', e);
        }
    }

    // Realtime polling setiap 10 detik
    setInterval(fetchStats, 10000);

    // Refresh saat tab kembali aktif
    document.addEventListener('visibilitychange', () => {
        if (!document.hidden) fetchStats();
    });
});
</script>
@endpush
