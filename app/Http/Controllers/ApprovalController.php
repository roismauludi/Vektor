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
            'aset',
        ])
            ->orderByDesc('created_at')
            ->get();
        
        return view('Approval.index', compact('pengajuan'));
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

        return redirect()->route('approval.index')->with('success', 'Pengajuan berhasil diapprove');
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
        return redirect()->route('approval.index')->with('success', 'Pengajuan berhasil ditolak');
    }
}
