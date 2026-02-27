<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalPengajuan = Pengajuan::count();

        $progressSummary = [
            'Menunggu verifikasi' => Pengajuan::whereRaw('LOWER(progress) = ?', ['menunggu verifikasi'])->count(),
            'Approved'            => Pengajuan::whereRaw('LOWER(progress) = ?', ['approved'])->count(),
            'Rejected'            => Pengajuan::whereRaw('LOWER(progress) IN (?, ?)', ['reject', 'rejected'])->count(),
            'In-Progress'         => Pengajuan::whereRaw('LOWER(progress) = ?', ['in-progress'])->count(),
            'Selesai'             => Pengajuan::whereRaw('LOWER(progress) = ?', ['selesai'])->count(),
        ];

        $knownTotal = array_sum($progressSummary);
        $progressSummary['Lainnya'] = max(0, $totalPengajuan - $knownTotal);

        // Filter rentang tanggal (opsional)
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date',
        ]);

        $startDate = $request->input('start_date')
            ? Carbon::parse($request->input('start_date'))->startOfDay()
            : null;
        $endDate = $request->input('end_date')
            ? Carbon::parse($request->input('end_date'))->endOfDay()
            : null;

        // Kalau user isi salah satu saja, anggap 1 hari
        if ($startDate && ! $endDate) {
            $endDate = $startDate->copy()->endOfDay();
        }
        if ($endDate && ! $startDate) {
            $startDate = $endDate->copy()->startOfDay();
        }

        // Pastikan urutan benar
        if ($startDate && $endDate && $startDate->gt($endDate)) {
            [$startDate, $endDate] = [$endDate->copy()->startOfDay(), $startDate->copy()->endOfDay()];
        }

        $query = Pengajuan::selectRaw('DATE(created_at) as tanggal, COUNT(*) as total');
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }
        $query->groupBy('tanggal')->orderBy('tanggal', 'desc');
        $dailySummary = $query->get();

        $totalFiltered = $dailySummary->sum('total');

        $rangeDays = null;
        if ($startDate && $endDate) {
            $rangeDays = $startDate->copy()->startOfDay()->diffInDays($endDate->copy()->startOfDay()) + 1;
        }

        $startDateValue = $startDate ? $startDate->format('Y-m-d') : null;
        $endDateValue = $endDate ? $endDate->format('Y-m-d') : null;
        $startDateLabel = $startDate ? $startDate->format('d-m-Y') : null;
        $endDateLabel = $endDate ? $endDate->format('d-m-Y') : null;

        return view('dashboard', compact(
            'totalPengajuan', 
            'progressSummary', 
            'dailySummary',
            'totalFiltered',
            'startDateValue',
            'endDateValue',
            'startDateLabel',
            'endDateLabel',
            'rangeDays',
        ));
    }
}
