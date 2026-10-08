<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\PengajuanLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date',
        ]);

        [$startDate, $endDate] = $this->resolveDateRange($request);

        $stats = $this->buildStats($startDate, $endDate);

        return view('dashboard', array_merge($stats, [
            'startDateValue' => $startDate?->format('Y-m-d'),
            'endDateValue'   => $endDate?->format('Y-m-d'),
            'startDateLabel' => $startDate?->format('d-m-Y'),
            'endDateLabel'   => $endDate?->format('d-m-Y'),
        ]));
    }

    public function stats(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date',
        ]);

        [$startDate, $endDate] = $this->resolveDateRange($request);

        return response()->json($this->buildStats($startDate, $endDate, forJson: true));
    }

    private function resolveDateRange(Request $request): array
    {
        $startDate = $request->input('start_date')
            ? Carbon::parse($request->input('start_date'))->startOfDay()
            : null;
        $endDate = $request->input('end_date')
            ? Carbon::parse($request->input('end_date'))->endOfDay()
            : null;

        if ($startDate && ! $endDate) {
            $endDate = $startDate->copy()->endOfDay();
        }
        if ($endDate && ! $startDate) {
            $startDate = $endDate->copy()->startOfDay();
        }

        if ($startDate && $endDate && $startDate->gt($endDate)) {
            [$startDate, $endDate] = [$endDate->copy()->startOfDay(), $startDate->copy()->endOfDay()];
        }

        return [$startDate, $endDate];
    }

    private function buildStats(?Carbon $startDate, ?Carbon $endDate, bool $forJson = false): array
    {
        $totalPengajuan = Pengajuan::count();

        $rawCounts = Pengajuan::selectRaw('LOWER(progress) as prog, COUNT(*) as total')
            ->groupBy('prog')
            ->pluck('total', 'prog');

        $progressSummary = [
            'Menunggu verifikasi' => $rawCounts['menunggu verifikasi'] ?? 0,
            'Approved'            => $rawCounts['approved'] ?? 0,
            'Rejected'            => ($rawCounts['reject'] ?? 0) + ($rawCounts['rejected'] ?? 0),
            'In-Progress'         => $rawCounts['in-progress'] ?? 0,
            'Selesai'             => $rawCounts['selesai'] ?? 0,
        ];

        $knownTotal = array_sum($progressSummary);
        $progressSummary['Lainnya'] = max(0, $totalPengajuan - $knownTotal);

        $query = Pengajuan::selectRaw('DATE(created_at) as tanggal, COUNT(*) as total');
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }
        $dailySummary = $query->groupBy('tanggal')->orderBy('tanggal', 'desc')->get();

        $totalFiltered = $dailySummary->sum('total');

        $rangeDays = null;
        if ($startDate && $endDate) {
            $rangeDays = $startDate->copy()->startOfDay()->diffInDays($endDate->copy()->startOfDay()) + 1;
        }

        $trendData = $this->getTrendData();
        $recentActivities = $this->getRecentActivities();

        $exportQuery = Pengajuan::with(['aset', 'creator:id,nama'])->orderByDesc('created_at');
        if ($startDate && $endDate) {
            $exportQuery->whereBetween('created_at', [$startDate, $endDate]);
        }
        $exportPengajuan = $forJson ? collect() : $exportQuery->get();

        $base = [
            'totalPengajuan'    => $totalPengajuan,
            'progressSummary'   => $progressSummary,
            'dailySummary'      => $dailySummary,
            'totalFiltered'     => $totalFiltered,
            'rangeDays'         => $rangeDays,
            'trendLabels'       => $trendData['labels'],
            'trendValues'       => $trendData['values'],
            'recentActivities'  => $recentActivities,
            'exportPengajuan'   => $exportPengajuan,
            'updatedAt'         => now()->format('H:i:s'),
        ];

        if ($forJson) {
            return [
                'total_pengajuan'   => $totalPengajuan,
                'progress_summary'  => $progressSummary,
                'total_filtered'    => $totalFiltered,
                'range_days'        => $rangeDays,
                'trend'             => $trendData,
                'recent_activities' => $recentActivities,
                'updated_at'        => now()->toIso8601String(),
            ];
        }

        return $base;
    }

    private function getTrendData(int $days = 7): array
    {
        $start = Carbon::today()->subDays($days - 1)->startOfDay();
        $end = Carbon::today()->endOfDay();

        $counts = Pengajuan::selectRaw('DATE(created_at) as tanggal, COUNT(*) as total')
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('tanggal')
            ->pluck('total', 'tanggal');

        $labels = [];
        $values = [];

        for ($i = 0; $i < $days; $i++) {
            $date = $start->copy()->addDays($i);
            $key = $date->format('Y-m-d');
            $labels[] = $date->translatedFormat('d M');
            $values[] = (int) ($counts[$key] ?? 0);
        }

        return ['labels' => $labels, 'values' => $values];
    }

    private function getRecentActivities(int $limit = 8): array
    {
        return PengajuanLog::with('creator:id,nama')
            ->latest()
            ->limit($limit)
            ->get()
            ->map(function (PengajuanLog $log) {
                return [
                    'kode'       => $log->kode_pengajuan,
                    'aksi'       => $log->aksi,
                    'keterangan' => $log->keterangan,
                    'creator'    => $log->creator?->nama ?? 'Sistem',
                    'time'       => $log->created_at?->diffForHumans(),
                    'timestamp'  => $log->created_at?->toIso8601String(),
                ];
            })
            ->all();
    }

    public function cardDetail($key)
    {
        $query = Pengajuan::with(['aset', 'creator:id,nama'])->orderByDesc('created_at');

        switch (strtolower(trim($key))) {
            case 'menunggu':
                $query->whereRaw('LOWER(progress) = ?', ['menunggu verifikasi']);
                $title = 'Menunggu Verifikasi';
                break;
            case 'approved':
                $query->whereRaw('LOWER(progress) = ?', ['approved']);
                $title = 'Approved';
                break;
            case 'rejected':
                $query->whereIn(DB::raw('LOWER(progress)'), ['reject', 'rejected']);
                $title = 'Rejected';
                break;
            case 'inprogress':
                $query->whereRaw('LOWER(progress) = ?', ['in-progress']);
                $title = 'In-Progress';
                break;
            case 'selesai':
                $query->whereRaw('LOWER(progress) = ?', ['selesai']);
                $title = 'Selesai';
                break;
            case 'total':
            default:
                $title = 'Total Pengajuan';
                break;
        }

        $canExportPdf = auth()->check() && !auth()->user()->hasAnyRole(['view', 'View', 'karyawan', 'Karyawan']);

        $pengajuanList = $query->get()->map(function ($item) use ($canExportPdf) {
            $progLower = strtolower(trim($item->progress));
            return [
                'id'             => $item->id,
                'kode_pengajuan' => $item->kode_pengajuan,
                'kategori'       => $item->kategori,
                'jenis'          => optional($item->aset)->kategori ?? $item->jenis ?? '-',
                'kode_aset'      => optional($item->aset)->kode_aset ?? $item->aset_kode ?? '-',
                'nama_aset'      => optional($item->aset)->nama ?? '-',
                'perusahaan_tamu'=> $item->perusahaan_tamu,
                'progress'       => $item->progress,
                'status'         => $item->status,
                'creator'        => optional($item->creator)->nama ?? '-',
                'created_at'     => optional($item->created_at)->format('d-m-Y H:i'),
                'detail_url'     => route('pengajuan.detail', $item->id),
                'pdf_url'        => ($progLower === 'selesai' && $canExportPdf) ? route('pengajuan.export-pdf', $item->id) : null,
            ];
        });

        return response()->json([
            'title' => $title,
            'count' => $pengajuanList->count(),
            'data'  => $pengajuanList,
        ]);
    }
}
