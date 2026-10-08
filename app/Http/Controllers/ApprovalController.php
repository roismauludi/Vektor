<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Pengajuan;
use App\Models\PengajuanLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\User;

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

    public function approve(Request $request, $id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        $pengajuan->progress = 'approved';
        $pengajuan->save();

        PengajuanLog::create([
            'kode_pengajuan' => $pengajuan->kode_pengajuan,
            'aksi'           => 'approved',
            'creator_id'     => Auth::id(),
            'keterangan'     => 'Pengajuan diapprove oleh ' . Auth::user()->nama,
        ]);

        // Kirim notifikasi ke Security (termasuk pembuat pengajuan)
        $penerima = User::role(['Security'])->pluck('id')->toArray();
        if ($pengajuan->creator_id) {
            $penerima[] = $pengajuan->creator_id;
        }
        $targetUserIds = array_unique(array_filter($penerima));

        foreach ($targetUserIds as $userId) {
            try {
                Notification::create([
                    'user_id' => $userId,
                    'type'    => 'pengajuan_diverifikasi',
                    'title'   => 'Pengajuan Disetujui',
                    'message' => 'Pengajuan ' . $pengajuan->kode_pengajuan . ' telah disetujui oleh ' . Auth::user()->nama . '.',
                    'data'    => ['kode_pengajuan' => $pengajuan->kode_pengajuan, 'status' => 'approved'],
                ]);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Gagal buat notifikasi approve: ' . $e->getMessage());
            }
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Pengajuan berhasil diapprove',
                'progress' => $pengajuan->progress,
            ]);
        }

        return redirect()->route('approval.index')->with('success', 'Pengajuan berhasil diapprove');
    }

    public function reject(Request $request, $id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        $pengajuan->progress = 'rejected';
        $pengajuan->save();

        PengajuanLog::create([
            'kode_pengajuan' => $pengajuan->kode_pengajuan,
            'aksi'           => 'rejected',
            'creator_id'     => Auth::id(),
            'keterangan'     => $request->keterangan,
        ]);

        // Kirim notifikasi ke Security (termasuk pembuat pengajuan)
        $penerima = User::role(['Security'])->pluck('id')->toArray();
        if ($pengajuan->creator_id) {
            $penerima[] = $pengajuan->creator_id;
        }
        $targetUserIds = array_unique(array_filter($penerima));

        foreach ($targetUserIds as $userId) {
            try {
                Notification::create([
                    'user_id' => $userId,
                    'type'    => 'pengajuan_diverifikasi',
                    'title'   => 'Pengajuan Ditolak',
                    'message' => 'Pengajuan ' . $pengajuan->kode_pengajuan . ' ditolak oleh ' . Auth::user()->nama . '. Alasan: ' . ($request->keterangan ?? '-'),
                    'data'    => ['kode_pengajuan' => $pengajuan->kode_pengajuan, 'status' => 'rejected'],
                ]);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Gagal buat notifikasi reject: ' . $e->getMessage());
            }
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Pengajuan berhasil ditolak',
                'progress' => $pengajuan->progress,
            ]);
        }

        return redirect()->route('approval.index')->with('success', 'Pengajuan berhasil ditolak');
    }
}
