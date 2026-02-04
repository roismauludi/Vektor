<?php

namespace App\Http\Controllers;

use App\Models\ListAset;
use App\Models\Pengajuan;
use App\Models\PengajuanDetail;
use App\Models\PengajuanFoto;
use App\Models\PengajuanLog;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class PengajuanController extends Controller
{
    private const FOTO_BASE_DIR = 'pengajuan/foto';

    public function list()
    {
        $listPengajuan = Pengajuan::with([
            'towerAset',
            'centralAset',
            'fotos'
        ])->orderBy('created_at', 'desc')->get();

        return view('Pengajuan.index', compact('listPengajuan'));
    }

    public function detail($id)
    {
        $pengajuan = Pengajuan::with('fotos')->findOrFail($id);
        $pengajuanDetails = PengajuanDetail::where('kode_pengajuan', $pengajuan->kode_pengajuan)->get();
        $pengajuanLogs = PengajuanLog::where('kode_pengajuan', $pengajuan->kode_pengajuan)->get();
        return view('Pengajuan.detail', compact('pengajuan', 'pengajuanDetails', 'pengajuanLogs'));
    }

    public function create(string $jenis = 'tower')
    {
        $jenis = strtolower($jenis);
        $allowedJenis = ['tower', 'central'];
        if (! in_array($jenis, $allowedJenis, true)) {
            $jenis = 'tower';
        }

        $identitasOptions = ['KTP', 'SIM'];
        $kodePengajuanDefault = $this->generateKodePengajuan();
        
        $listAsets = ListAset::whereRaw('LOWER(kategori) = ?', [$jenis])
            ->orderBy('kode_aset')
            ->get();

        $view = $jenis === 'central' ? 'Pengajuan.central' : 'Pengajuan.tower';

        return view($view, compact('identitasOptions', 'listAsets', 'kodePengajuanDefault'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis'               => 'required|in:central,tower',
            'tower'               => 'required_if:jenis,tower|string|max:255',
            'central'             => 'required_if:jenis,central|string|max:255',
            'kode_pengajuan'      => 'nullable|string|max:255',

            'perusahaan_tamu'     => 'required|string|max:255',
            'perusahaan_pengutus' => 'required|string|max:255',

            'jumlah'              => 'required|integer|min:1',
            'kategori'             => 'required|string|in:Dismantel,Maintenance,Perbaikan,Pengecekan',
            'keperluan'           => 'required|string|max:500',

            'jam_mulai'           => 'required|date_format:H:i',
            'jam_selesai'         => [
                'required',
                'date_format:H:i',
                'after:jam_mulai',
                function ($attribute, $value, $fail) {
                    $jamSelesai = Carbon::createFromFormat('H:i', $value);
                    if ($jamSelesai->gt(Carbon::createFromTime(17, 0))) {
                        $fail('Jam selesai maksimal pukul 17:00.');
                    }
                },
            ],

            'identitas'           => 'required|in:KTP,SIM',
            'identitas_file'      => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'masa_berlaku'        => 'required|string|max:255',

            'status'              => 'required|string|max:255',
            'progress'            => 'required|string|max:255',

            'details'             => 'required|array|min:1',
            'details.*.nama_tamu' => 'required|string|max:255',
            'details.*.no_tlp'    => 'required|string|max:255',
            'details.*.email'     => 'required|email|max:255',
        ]);

        $validated['lama_berkerja'] =
            $validated['jam_mulai'] . ' - ' . $validated['jam_selesai'];

        $identitasFilePath = null;
        if ($request->hasFile('identitas_file')) {
            $identitasFilePath = $request
                ->file('identitas_file')
                ->store('identitas', 'public');
        }

        $kodePengajuan = $validated['kode_pengajuan'] ?? null;

        try {
            DB::transaction(function () use (
                $validated,
                $identitasFilePath,
                &$kodePengajuan
            ) {
                if (
                    ! $kodePengajuan ||
                    Pengajuan::where('kode_pengajuan', $kodePengajuan)->exists()
                ) {
                    $kodePengajuan = $this->generateKodePengajuan();
                }

                $pengajuan = Pengajuan::create([
                    'kode_pengajuan'      => $kodePengajuan,
                    'jenis'               => $validated['jenis'],
                    'tower_kode'          => $validated['jenis'] === 'tower'
                        ? $validated['tower']
                        : null,
                    'central_kode'        => $validated['jenis'] === 'central'
                        ? $validated['central']
                        : null,
                    'perusahaan_tamu'     => $validated['perusahaan_tamu'],
                    'perusahaan_pengutus' => $validated['perusahaan_pengutus'],
                    'jumlah'              => $validated['jumlah'],
                    'kategori'            => $validated['kategori'],
                    'keperluan'           => $validated['keperluan'],
                    'jam_mulai'           => $validated['jam_mulai'],
                    'jam_selesai'         => $validated['jam_selesai'],
                    'lama_berkerja'       => $validated['lama_berkerja'],
                    'identitas'           => $validated['identitas'],
                    'identitas_file'      => $identitasFilePath,
                    'masa_berlaku'        => $validated['masa_berlaku'],
                    'status'              => $validated['status'],
                    'progress'            => $validated['progress'],
                    'creator_id'          => Auth::id(),
                ]);

                foreach ($validated['details'] as $detail) {
                    PengajuanDetail::create([
                        'kode_pengajuan' => $pengajuan->kode_pengajuan,
                        'creator_id'     => Auth::id(),
                        'nama_tamu'      => $detail['nama_tamu'],
                        'no_tlp'         => $detail['no_tlp'],
                        'email'          => $detail['email'],
                    ]);
                }

                PengajuanLog::create([
                    'kode_pengajuan' => $pengajuan->kode_pengajuan,
                    'creator_id'     => Auth::id(),
                    'aksi'           => 'create',
                    'keterangan'     => 'Pengajuan dibuat melalui form ' . $validated['jenis'],
                ]);
            });
        } catch (\Throwable $th) {
            if ($identitasFilePath) {
                Storage::disk('public')->delete($identitasFilePath);
            }

            return back()
                ->withInput($request->except('details'))
                ->with('error', 'Gagal menyimpan pengajuan: ' . $th->getMessage());
        }

        return back()->with(
            'success',
            'Pengajuan berhasil dibuat dengan kode ' . $kodePengajuan
        );
    }

    private function generateKodePengajuan(): string
    {
        $prefix = 'PMT-'.now()->format('y').'-';

        $latest = Pengajuan::whereYear('created_at', now()->year)
            ->orderByDesc('kode_pengajuan')
            ->value('kode_pengajuan');

        $nextNumber = 1;
        if ($latest && preg_match('/^PMT-\\d{2}-(\\d+)$/', $latest, $matches)) {
            $nextNumber = (int) $matches[1] + 1;
        }

        return $prefix.str_pad((string) $nextNumber, 6, '0', STR_PAD_LEFT);
    }

    public function sendEmail($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);

        $to = optional($pengajuan->details->first())->email;
        if (! $to) {
            return back()->with('error', 'Email tujuan tidak ditemukan');
        }

        Mail::raw(
            "Pengajuan {$pengajuan->kode_pengajuan}\nStatus: {$pengajuan->status}\nProgress: {$pengajuan->progress}",
            function ($message) use ($to, $pengajuan) {
                $message->to($to)
                    ->subject('Informasi Pengajuan ' . $pengajuan->kode_pengajuan);
            }
        );

        return back()->with('success', 'Email berhasil dikirim');
    }

    public function showUploadForm($id)
    {
        $pengajuan = Pengajuan::with('fotos')->findOrFail($id);
        
        if (strtolower($pengajuan->progress) !== 'approved') {
            return redirect()->route('pengajuan.list')
                ->with('error', 'Pengajuan harus dalam status approved untuk upload foto');
        }

        return view('Pengajuan.upload', compact('pengajuan'));
    }

    public function upload(Request $request, $id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        
        // Pastikan progress adalah approved
        if (strtolower($pengajuan->progress) !== 'approved') {
            return redirect()->route('pengajuan.list')
                ->with('error', 'Pengajuan harus dalam status approved untuk upload foto');
        }

        $validated = $request->validate([
            'foto_kendaraan' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
            'foto_penyerahan_kunci' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
            'foto_lainnya' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
        ]);

        try {
            DB::transaction(function () use ($validated, $request, &$pengajuan) {
                if ($request->hasFile('foto_kendaraan')) {
                    $this->upsertFotoKategori(
                        $pengajuan->kode_pengajuan,
                        'kendaraan',
                        $request->file('foto_kendaraan'),
                        self::FOTO_BASE_DIR
                    );
                }

                if ($request->hasFile('foto_penyerahan_kunci')) {
                    $this->upsertFotoKategori(
                        $pengajuan->kode_pengajuan,
                        'penyerahan_kunci',
                        $request->file('foto_penyerahan_kunci'),
                        self::FOTO_BASE_DIR
                    );
                }

                if ($request->hasFile('foto_lainnya')) {
                    $this->upsertFotoKategori(
                        $pengajuan->kode_pengajuan,
                        'lainnya',
                        $request->file('foto_lainnya'),
                        self::FOTO_BASE_DIR
                    );
                }

                // Reload pengajuan untuk mendapatkan foto terbaru
                $pengajuan->load('fotos');

                $required = ['kendaraan', 'penyerahan_kunci', 'lainnya'];
                $uploadedCount = PengajuanFoto::where('kode_pengajuan', $pengajuan->kode_pengajuan)
                    ->whereIn('kategori', $required)
                    ->whereNotNull('path')
                    ->count();

                if ($uploadedCount === count($required)) {
                    $pengajuan->progress = 'in-progress';
                    $pengajuan->save();

                    // Log perubahan progress
                    PengajuanLog::create([
                        'kode_pengajuan' => $pengajuan->kode_pengajuan,
                        'creator_id'     => Auth::id(),
                        'aksi'           => 'update_progress',
                        'keterangan'     => 'Progress berubah menjadi in-progress setelah semua foto diupload',
                    ]);
                }
            });
        } catch (\Throwable $th) {
            return back()
                ->withInput()
                ->with('error', 'Gagal mengupload foto: ' . $th->getMessage());
        }

        return redirect()
            ->route('pengajuan.detail', $pengajuan->id)
            ->with('success', 'Foto berhasil diupload');
    }

    public function edit($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
 
        if (strtolower($pengajuan->progress) !== 'reject' && strtolower($pengajuan->progress) !== 'rejected') {
            return redirect()->route('pengajuan.list')
                ->with('error', 'Pengajuan harus dalam status reject untuk diupdate');
        }

        $jenis = strtolower($pengajuan->jenis);
        $identitasOptions = ['KTP', 'SIM'];
        
        $listAsets = ListAset::whereRaw('LOWER(kategori) = ?', [$jenis])
            ->orderBy('kode_aset')
            ->get();

        $pengajuanDetails = PengajuanDetail::where('kode_pengajuan', $pengajuan->kode_pengajuan)->get();

        $view = $jenis === 'central' ? 'Pengajuan.edit-central' : 'Pengajuan.edit-tower';

        return view($view, compact('pengajuan', 'identitasOptions', 'listAsets', 'pengajuanDetails'));
    }

    public function update(Request $request, $id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        
        if (strtolower($pengajuan->progress) !== 'reject' && strtolower($pengajuan->progress) !== 'rejected') {
            return redirect()->route('pengajuan.list')
                ->with('error', 'Pengajuan harus dalam status reject untuk diupdate');
        }

        $validated = $request->validate([
            'jenis'               => 'required|in:central,tower',
            'tower'               => 'required_if:jenis,tower|string|max:255',
            'central'             => 'required_if:jenis,central|string|max:255',

            'perusahaan_tamu'     => 'required|string|max:255',
            'perusahaan_pengutus' => 'required|string|max:255',

            'jumlah'              => 'required|integer|min:1',
            'kategori'             => 'required|string|in:Dismantel,Maintenance,Perbaikan,Pengecekan',
            'keperluan'           => 'required|string|max:500',

            'jam_mulai'           => 'required|date_format:H:i',
            'jam_selesai'         => [
                'required',
                'date_format:H:i',
                'after:jam_mulai',
                function ($attribute, $value, $fail) {
                    $jamSelesai = Carbon::createFromFormat('H:i', $value);
                    if ($jamSelesai->gt(Carbon::createFromTime(17, 0))) {
                        $fail('Jam selesai maksimal pukul 17:00.');
                    }
                },
            ],

            'identitas'           => 'required|in:KTP,SIM',
            'identitas_file'      => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'masa_berlaku'        => 'required|string|max:255',

            'details'             => 'required|array|min:1',
            'details.*.nama_tamu' => 'required|string|max:255',
            'details.*.no_tlp'    => 'required|string|max:255',
            'details.*.email'     => 'required|email|max:255',
        ]);

        $validated['lama_berkerja'] =
            $validated['jam_mulai'] . ' - ' . $validated['jam_selesai'];

        $identitasFilePath = $pengajuan->identitas_file;
        if ($request->hasFile('identitas_file')) {
            // Hapus file lama jika ada
            if ($identitasFilePath) {
                Storage::disk('public')->delete($identitasFilePath);
            }
            $identitasFilePath = $request
                ->file('identitas_file')
                ->store('identitas', 'public');
        }

        try {
            DB::transaction(function () use (
                $validated,
                $identitasFilePath,
                $pengajuan,
                $request
            ) {
                $pengajuan->update([
                    'jenis'               => $validated['jenis'],
                    'tower_kode'          => $validated['jenis'] === 'tower'? $validated['tower']: null,
                    'central_kode'        => $validated['jenis'] === 'central'? $validated['central']: null,
                    'perusahaan_tamu'     => $validated['perusahaan_tamu'],
                    'perusahaan_pengutus' => $validated['perusahaan_pengutus'],
                    'jumlah'              => $validated['jumlah'],
                    'kategori'            => $validated['kategori'],
                    'keperluan'           => $validated['keperluan'],
                    'jam_mulai'           => $validated['jam_mulai'],
                    'jam_selesai'         => $validated['jam_selesai'],
                    'lama_berkerja'       => $validated['lama_berkerja'],
                    'identitas'           => $validated['identitas'],
                    'identitas_file'      => $identitasFilePath,
                    'masa_berlaku'        => $validated['masa_berlaku'],
                    'progress'            => 'Menunggu verifikasi', // Reset progress setelah update
                ]);

                // Hapus detail lama dan buat yang baru
                PengajuanDetail::where('kode_pengajuan', $pengajuan->kode_pengajuan)->delete();

                foreach ($validated['details'] as $detail) {
                    PengajuanDetail::create([
                        'kode_pengajuan' => $pengajuan->kode_pengajuan,
                        'creator_id'     => Auth::id(),
                        'nama_tamu'      => $detail['nama_tamu'],
                        'no_tlp'         => $detail['no_tlp'],
                        'email'          => $detail['email'],
                    ]);
                }

                PengajuanLog::create([
                    'kode_pengajuan' => $pengajuan->kode_pengajuan,
                    'creator_id'     => Auth::id(),
                    'aksi'           => 'update',
                    'keterangan'     => 'Pengajuan diupdate setelah ditolak - Progress direset menjadi Menunggu verifikasi',
                ]);
            });
        } catch (\Throwable $th) {
            if ($identitasFilePath && $identitasFilePath !== $pengajuan->identitas_file) {
                Storage::disk('public')->delete($identitasFilePath);
            }

            return back()
                ->withInput($request->except('details'))
                ->with('error', 'Gagal mengupdate pengajuan: ' . $th->getMessage());
        }

        return redirect()
            ->route('pengajuan.detail', $pengajuan->id)
            ->with('success', 'Pengajuan berhasil diupdate dan dikembalikan ke status Menunggu verifikasi');
    }

    public function showSelesaiForm($id)
    {
        $pengajuan = Pengajuan::with('fotos')->findOrFail($id);
        
        if (strtolower($pengajuan->progress) !== 'in-progress') {
            return redirect()->route('pengajuan.list')
                ->with('error', 'Pengajuan harus dalam status in-progress untuk upload foto');
        }

        return view('Pengajuan.selesai', compact('pengajuan'));
    }

    public function selesai(Request $request, $id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        
        // Pastikan progress adalah in-progress
        if (strtolower($pengajuan->progress) !== 'in-progress') {
            return redirect()->route('pengajuan.list')
                ->with('error', 'Pengajuan harus dalam status in-progress untuk upload foto');
        }

        $validated = $request->validate([
            'foto_kendaraan_kembali' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
            'foto_pengembalian_kunci' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
            'foto_lainnya' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
        ]);

        try {
            DB::transaction(function () use ($validated, $request, &$pengajuan) {
                if ($request->hasFile('foto_kendaraan_kembali')) {
                    $this->upsertFotoKategori(
                        $pengajuan->kode_pengajuan,
                        'kendaraan_kembali',
                        $request->file('foto_kendaraan_kembali'),
                        self::FOTO_BASE_DIR
                    );
                }


                if ($request->hasFile('foto_pengembalian_kunci')) {
                    $this->upsertFotoKategori(
                        $pengajuan->kode_pengajuan,
                        'pengembalian_kunci',
                        $request->file('foto_pengembalian_kunci'),
                        self::FOTO_BASE_DIR
                    );
                }

                if ($request->hasFile('foto_lainnya')) {
                    $this->upsertFotoKategori(
                        $pengajuan->kode_pengajuan,
                        'lainnya_kembali',
                        $request->file('foto_lainnya'),
                        self::FOTO_BASE_DIR
                    );
                }

                // Reload pengajuan untuk mendapatkan foto terbaru
                $pengajuan->load('fotos');

                $required = ['kendaraan_kembali', 'pengembalian_kunci', 'lainnya_kembali'];
                $uploadedCount = PengajuanFoto::where('kode_pengajuan', $pengajuan->kode_pengajuan)
                    ->whereIn('kategori', $required)
                    ->whereNotNull('path')
                    ->count();

                if ($uploadedCount === count($required)) {
                    $pengajuan->progress = 'selesai';
                    $pengajuan->status = 'Close';
                    $pengajuan->save();

                    // Log perubahan progress dan status
                    PengajuanLog::create([
                        'kode_pengajuan' => $pengajuan->kode_pengajuan,
                        'creator_id'     => Auth::id(),
                        'aksi'           => 'update_progress',
                        'keterangan'     => 'Progress berubah menjadi selesai dan status menjadi Close setelah semua foto diupload',
                    ]);
                }
            });
        } catch (\Throwable $th) {
            return back()
                ->withInput()
                ->with('error', 'Gagal mengupload foto: ' . $th->getMessage());
        }

        return redirect()
            ->route('pengajuan.detail', $pengajuan->id)
            ->with('success', 'Foto berhasil diupload');
    }

    private function upsertFotoKategori(
        string $kodePengajuan,
        string $kategori,
        UploadedFile $file,
        string $storeDir
    ): void {
        $kategori = strtolower(trim($kategori));

        $existingFoto = PengajuanFoto::where('kode_pengajuan', $kodePengajuan)
            ->where('kategori', $kategori)
            ->first();

        $isUpdate = $existingFoto !== null;
        $oldPath = $existingFoto?->path;

        // Hapus file lama jika ada
        if ($existingFoto && $existingFoto->path) {
            Storage::disk('public')->delete($existingFoto->path);
        }

        $path = $file->store($storeDir, 'public');

        PengajuanFoto::updateOrCreate(
            [
                'kode_pengajuan' => $kodePengajuan,
                'kategori' => $kategori,
            ],
            [
                'path' => $path,
                'creator_id' => Auth::id()
            ]
        );

        $kategoriLabel = ucfirst(str_replace('_', ' ', $kategori));
        $aksi = $isUpdate ? 'update_foto' : 'upload_foto';
        $keterangan = $isUpdate 
            ? "Update foto {$kategoriLabel}" . ($oldPath ? " (file lama: {$oldPath})" : '')
            : "Upload foto {$kategoriLabel}";

        PengajuanLog::create([
            'kode_pengajuan' => $kodePengajuan,
            'creator_id'     => Auth::id(),
            'aksi'           => $aksi,
            'keterangan'     => $keterangan,
        ]);
    }
}