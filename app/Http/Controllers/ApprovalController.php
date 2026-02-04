<?php

namespace App\Http\Controllers;

use App\Models\ListAset;
use App\Models\Pengajuan;
use App\Models\PengajuanLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    public function index()
    {
        $pengajuan = Pengajuan::with([
            'creator:id,nama',
            'details',
        ])
            ->orderByDesc('created_at')
            ->get();

        $kodeAset = $pengajuan
            ->pluck('tower_kode')
            ->merge($pengajuan->pluck('central_kode'))
            ->filter()
            ->unique()
            ->values();

        $asetMap = ListAset::whereIn('kode_aset', $kodeAset)->pluck('nama', 'kode_aset');

        return view('Approval.index', compact('pengajuan', 'asetMap'));
    }
    public function detail($id)
    {
        $pengajuan = Pengajuan::with([
            'creator:id,nama',
            'details',
        ])->findOrFail($id);

        return view('Approval.detail', compact('pengajuan'));
    }

    public function approve($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        $pengajuan->progress = 'approved';
        $pengajuan->save();

        PengajuanLog::create([
            'kode_pengajuan' => $pengajuan->kode_pengajuan,
            'aksi' => 'approved',
            'creator_id' => Auth::id(),
            'keterangan'     => 'Pengajuan diapprove oleh ' . Auth::user()->nama,
        ]);

        return back()->with('success', 'Pengajuan berhasil diapprove');
    }

    public function reject(Request $request, $id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        $pengajuan->progress = 'rejected';
        $pengajuan->save();

        PengajuanLog::create([
            'kode_pengajuan' => $pengajuan->kode_pengajuan,
            'aksi' => 'rejected',
            'creator_id' => Auth::id(),
            'keterangan' => $request->keterangan,
        ]);

        return back()->with('success', 'Pengajuan berhasil ditolak');
    }
}
