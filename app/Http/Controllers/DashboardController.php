<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPengajuan = Pengajuan::count();

        // Ringkasan progress (untuk diagram)
        $progressSummary = [
            'Menunggu verifikasi' => Pengajuan::whereRaw('LOWER(progress) = ?', ['menunggu verifikasi'])->count(),
            'Approved'            => Pengajuan::whereRaw('LOWER(progress) = ?', ['approved'])->count(),
            'Rejected'            => Pengajuan::whereRaw('LOWER(progress) IN (?, ?)', ['reject', 'rejected'])->count(),
            'In-Progress'         => Pengajuan::whereRaw('LOWER(progress) = ?', ['in-progress'])->count(),
            'Selesai'             => Pengajuan::whereRaw('LOWER(progress) = ?', ['selesai'])->count(),
        ];

        $knownTotal = array_sum($progressSummary);
        $progressSummary['Lainnya'] = max(0, $totalPengajuan - $knownTotal);

        // Ringkasan pengajuan per hari (berdasarkan created_at)
        $dailySummary = Pengajuan::selectRaw('DATE(created_at) as tanggal, COUNT(*) as total')
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();

        return view('dashboard', compact('totalPengajuan', 'progressSummary', 'dailySummary'));
    }
}
