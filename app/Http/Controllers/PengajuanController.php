<?php

namespace App\Http\Controllers;

use App\Models\ListAset;
use App\Models\Notification;
use App\Models\Pengajuan;
use App\Models\PengajuanDetail;
use App\Models\PengajuanFoto;
use App\Models\PengajuanLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class PengajuanController extends Controller
{
    private const FOTO_BASE_DIR = 'pengajuan/foto';

    public function list()
    {
        $listPengajuan = Pengajuan::with([
            'aset',
            'fotos'
        ])->orderBy('created_at', 'desc')->get();

        return view('Pengajuan.index', compact('listPengajuan'));
    }

    public function createUmum()
    {
        $kodePengajuanDefault = $this->generateKodePengajuan();

        $kategoriAsetOptions = ListAset::query()
            ->select('kategori')
            ->whereNotNull('kategori')
            ->where('kategori', '!=', '')
            ->distinct()
            ->orderBy('kategori')
            ->pluck('kategori')
            ->values();

        $listAsetsForFilter = ListAset::query()
            ->select(['kode_aset', 'nama', 'lokasi', 'kategori'])
            ->orderBy('kode_aset')
            ->get();

        return view('Pengajuan.create', [
            'kodePengajuanDefault' => $kodePengajuanDefault,
            'kategoriAsetOptions' => $kategoriAsetOptions,
            'listAsetsForFilter' => $listAsetsForFilter,
        ]);
    }

    public function detail($id)
    {
        $pengajuan = Pengajuan::with('fotos')->findOrFail($id);
        $pengajuanDetails = PengajuanDetail::where('kode_pengajuan', $pengajuan->kode_pengajuan)->get();
        $pengajuanLogs = PengajuanLog::where('kode_pengajuan', $pengajuan->kode_pengajuan)->get();
        return view('Pengajuan.detail', compact('pengajuan', 'pengajuanDetails', 'pengajuanLogs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'aset_kode'           => 'required|string|max:255',
            'kategori_aset'       => 'nullable|string|max:255',
            'kode_pengajuan'      => 'nullable|string|max:255',

            'perusahaan_tamu'     => 'required|string|max:255',
            'perusahaan_pengutus' => 'required|string|max:255',

            'jumlah'              => 'required|integer|min:1',
            'kategori'             => 'required|string|in:Dismantel,Maintenance,Perbaikan,Pengecekan',
            'keperluan'           => 'required|string|max:500',

            'jam_mulai'           => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) {
                    $jamMulai = \Carbon\Carbon::createFromFormat('H:i', $value);
                    if ($jamMulai->lt(\Carbon\Carbon::createFromTime(8, 30))) {
                        $fail('Jam mulai minimal pukul 08:30.');
                    }
                },
            ],
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
        ], [
            'jam_selesai.after'   => 'Jam selesai harus setelah jam mulai.',
        ]);

        $asetKode = $validated['aset_kode'];
        $asetModel = ListAset::where('kode_aset', $asetKode)->first();
        $jenis = $asetModel?->kategori;

        $validated['lama_berkerja'] = $validated['jam_mulai'] . ' - ' . $validated['jam_selesai'];

        // Tentukan kode pengajuan final terlebih dahulu
        $kodePengajuan = $validated['kode_pengajuan'] ?? null;
        if (
            ! $kodePengajuan ||
            Pengajuan::where('kode_pengajuan', $kodePengajuan)->exists()
        ) {
            $kodePengajuan = $this->generateKodePengajuan();
        }

        // Simpan file identitas dengan nama diawali kode pengajuan
        $identitasFilePath = null;
        if ($request->hasFile('identitas_file')) {
            $identitasFile = $request->file('identitas_file');
            $identitasExtension = $identitasFile->getClientOriginalExtension();
            $identitasRandom = uniqid();
            $identitasFilename = $kodePengajuan . '_identitas_' . $identitasRandom . '.' . $identitasExtension;

            $identitasFilePath = $identitasFile->storeAs('identitas', $identitasFilename, 'public');
        }

        try {
            DB::transaction(function () use (
                $validated,
                $identitasFilePath,
                $kodePengajuan,
                $asetKode,
                $jenis
            ) {
                $pengajuan = Pengajuan::create([
                    'kode_pengajuan'      => $kodePengajuan,
                    'jenis'               => $jenis,
                    'aset_kode'           => $asetKode,
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
                    'keterangan'     => 'Pengajuan dibuat melalui form umum',
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

        // Kirim notifikasi ke semua PIC
        try {
            $penerima = User::role(['PIC'])->get();
            foreach ($penerima as $user) {
                Notification::create([
                    'user_id' => $user->id,
                    'type'    => 'pengajuan_baru',
                    'title'   => 'Pengajuan Baru Masuk',
                    'message' => 'Pengajuan ' . $kodePengajuan . ' dari ' . Auth::user()->nama . ' menunggu verifikasi Anda.',
                    'data'    => ['kode_pengajuan' => $kodePengajuan],
                ]);
            }
        } catch (\Throwable $e) {
            // Jangan gagalkan proses utama jika notifikasi error
            \Illuminate\Support\Facades\Log::warning('Gagal buat notifikasi pengajuan: ' . $e->getMessage());
        }

        return redirect()
            ->route('pengajuan.list')
            ->with(
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
            'foto_tambahan' => 'nullable|array',
            'foto_tambahan.*' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
        ]);

        try {
            DB::transaction(function () use ($validated, $request, &$pengajuan) {
                if ($request->hasFile('foto_kendaraan')) {
                    $this->upsertFotoKategori(
                        $pengajuan->kode_pengajuan,
                        'kendaraan',
                        $request->file('foto_kendaraan'),
                        self::FOTO_BASE_DIR,
                        false
                    );
                }

                if ($request->hasFile('foto_penyerahan_kunci')) {
                    $this->upsertFotoKategori(
                        $pengajuan->kode_pengajuan,
                        'penyerahan_kunci',
                        $request->file('foto_penyerahan_kunci'),
                        self::FOTO_BASE_DIR,
                        false
                    );
                }

                if ($request->hasFile('foto_lainnya')) {
                    $this->upsertFotoKategori(
                        $pengajuan->kode_pengajuan,
                        'lainnya',
                        $request->file('foto_lainnya'),
                        self::FOTO_BASE_DIR,
                        false
                    );
                }

                if ($request->hasFile('foto_tambahan')) {
                    foreach ($request->file('foto_tambahan') as $index => $file) {
                        if ($file && $file->isValid()) {
                            $kategoriName = 'tambahan_' . ($index + 1) . '_' . time();
                            $this->upsertFotoKategori(
                                $pengajuan->kode_pengajuan,
                                $kategoriName,
                                $file,
                                self::FOTO_BASE_DIR,
                                false
                            );
                        }
                    }
                }

                // Reload pengajuan untuk mendapatkan foto terbaru
                $pengajuan->load('fotos');

                $required = ['kendaraan', 'penyerahan_kunci', 'lainnya'];
                $uploadedCount = PengajuanFoto::where('kode_pengajuan', $pengajuan->kode_pengajuan)
                    ->whereIn('kategori', $required)
                    ->where('pengembalian', false)
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
        
        $listAsets = ListAset::whereRaw('LOWER(kategori) = ?', [$jenis])
            ->orderBy('kode_aset')
            ->get();

        $pengajuanDetails = PengajuanDetail::where('kode_pengajuan', $pengajuan->kode_pengajuan)->get();

        return view('Pengajuan.edit', compact('pengajuan', 'listAsets', 'pengajuanDetails'));
    }

    public function update(Request $request, $id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        
        if (strtolower($pengajuan->progress) !== 'reject' && strtolower($pengajuan->progress) !== 'rejected') {
            return redirect()->route('pengajuan.list')
                ->with('error', 'Pengajuan harus dalam status reject untuk diupdate');
        }

        $validated = $request->validate([
            'jenis'               => 'required|string|max:255',
            'aset_kode'           => 'required|string|max:255',

            'perusahaan_tamu'     => 'required|string|max:255',
            'perusahaan_pengutus' => 'required|string|max:255',

            'jumlah'              => 'required|integer|min:1',
            'kategori'             => 'required|string|in:Dismantel,Maintenance,Perbaikan,Pengecekan',
            'keperluan'           => 'required|string|max:500',

            'jam_mulai'           => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) {
                    $jamMulai = \Carbon\Carbon::createFromFormat('H:i', $value);
                    if ($jamMulai->lt(\Carbon\Carbon::createFromTime(8, 30))) {
                        $fail('Jam mulai minimal pukul 08:30.');
                    }
                },
            ],
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
        ], [
            'jam_selesai.after'   => 'Jam selesai harus setelah jam mulai.',
        ]);

        $validated['lama_berkerja'] =
            $validated['jam_mulai'] . ' - ' . $validated['jam_selesai'];

        $identitasFilePath = $pengajuan->identitas_file;
        if ($request->hasFile('identitas_file')) {
            // Hapus file lama jika ada
            if ($identitasFilePath) {
                Storage::disk('public')->delete($identitasFilePath);
            }

            $identitasFile = $request->file('identitas_file');
            $identitasExtension = $identitasFile->getClientOriginalExtension();
            $identitasRandom = uniqid();
            $identitasFilename = $pengajuan->kode_pengajuan . '_identitas_' . $identitasRandom . '.' . $identitasExtension;

            $identitasFilePath = $identitasFile->storeAs('identitas', $identitasFilename, 'public');
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
                    'aset_kode'           => $validated['aset_kode'],
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

        // Kirim notifikasi ke PIC bahwa pengajuan telah direvisi dan dikirim ulang
        try {
            $penerima = User::role(['PIC'])->get();
            foreach ($penerima as $user) {
                Notification::create([
                    'user_id' => $user->id,
                    'type'    => 'pengajuan_baru',
                    'title'   => 'Pengajuan Direvisi & Dikirim Ulang',
                    'message' => 'Pengajuan ' . $pengajuan->kode_pengajuan . ' dari ' . Auth::user()->nama . ' telah direvisi dan menunggu verifikasi ulang.',
                    'data'    => ['kode_pengajuan' => $pengajuan->kode_pengajuan],
                ]);
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Gagal buat notifikasi update pengajuan: ' . $e->getMessage());
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
            'foto_tambahan' => 'nullable|array',
            'foto_tambahan.*' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
        ]);

        try {
            DB::transaction(function () use ($validated, $request, &$pengajuan) {
                if ($request->hasFile('foto_kendaraan_kembali')) {
                    $this->upsertFotoKategori(
                        $pengajuan->kode_pengajuan,
                        'kendaraan',
                        $request->file('foto_kendaraan_kembali'),
                        self::FOTO_BASE_DIR,
                        true
                    );
                }

                if ($request->hasFile('foto_pengembalian_kunci')) {
                    $this->upsertFotoKategori(
                        $pengajuan->kode_pengajuan,
                        'pengembalian_kunci',
                        $request->file('foto_pengembalian_kunci'),
                        self::FOTO_BASE_DIR,
                        true
                    );
                }

                if ($request->hasFile('foto_lainnya')) {
                    $this->upsertFotoKategori(
                        $pengajuan->kode_pengajuan,
                        'lainnya',
                        $request->file('foto_lainnya'),
                        self::FOTO_BASE_DIR,
                        true
                    );
                }

                if ($request->hasFile('foto_tambahan')) {
                    foreach ($request->file('foto_tambahan') as $index => $file) {
                        if ($file && $file->isValid()) {
                            $kategoriName = 'tambahan_kembali_' . ($index + 1) . '_' . time();
                            $this->upsertFotoKategori(
                                $pengajuan->kode_pengajuan,
                                $kategoriName,
                                $file,
                                self::FOTO_BASE_DIR,
                                true
                            );
                        }
                    }
                }

                $pengajuan->load('fotos');

                $hasAllReturnPhotos =
                    $pengajuan->fotoPath('kendaraan', true)
                    && $pengajuan->fotoPath('pengembalian_kunci', true)
                    && $pengajuan->fotoPath('lainnya', true);

                if ($hasAllReturnPhotos) {
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
        string $storeDir,
        bool $pengembalian = false
    ): void {
        $kategori = strtolower(trim($kategori));

        $existingFoto = PengajuanFoto::where('kode_pengajuan', $kodePengajuan)
            ->where('kategori', $kategori)
            ->where('pengembalian', $pengembalian)
            ->first();

        $isUpdate = $existingFoto !== null;
        $oldPath = $existingFoto?->path;

        // Hapus file lama jika ada
        if ($existingFoto && $existingFoto->path) {
            Storage::disk('public')->delete($existingFoto->path);
        }

        $extension = $file->getClientOriginalExtension();
        $randomName = Str::random(40);
        $fileName = $kodePengajuan . '_' . $randomName . '.' . $extension;

        $path = $file->storeAs($storeDir, $fileName, 'public');

        PengajuanFoto::updateOrCreate(
            [
                'kode_pengajuan' => $kodePengajuan,
                'kategori' => $kategori,
                'pengembalian' => $pengembalian,
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

    public function cancel($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        $pengajuan->progress = 'Cancel';
        $pengajuan->status = 'Close';
        $pengajuan->save();

        PengajuanLog::create([
            'kode_pengajuan' => $pengajuan->kode_pengajuan,
            'creator_id'     => Auth::id(),
            'aksi'           => 'cancel',
            'keterangan'     => 'Pengajuan dibatalkan oleh ' . Auth::user()->nama,
        ]);

        return redirect()->route('pengajuan.list')->with('success', 'Pengajuan berhasil dibatalkan');
    }

    public function exportPdf($id)
    {
        if (auth()->check() && auth()->user()->hasAnyRole(['view', 'View', 'karyawan', 'Karyawan'])) {
            return back()->with('error', 'Role View tidak memiliki akses untuk mengunduh PDF.');
        }

        $pengajuan = Pengajuan::with(['aset', 'creator', 'details', 'fotos', 'logs.creator'])->findOrFail($id);

        if (strtolower(trim($pengajuan->progress)) !== 'selesai') {
            return back()->with('error', 'Export PDF hanya tersedia untuk pengajuan yang telah selesai.');
        }

        $standardCats = ['kendaraan', 'penyerahan_kunci', 'pengembalian_kunci', 'lainnya'];

        $fotoRows = [
            [
                'label' => 'Kendaraan',
                'pergi' => ['kategori' => 'kendaraan', 'pengembalian' => false],
                'kembali' => ['kategori' => 'kendaraan', 'pengembalian' => true],
            ],
            [
                'label' => 'Kunci',
                'pergi' => ['kategori' => 'penyerahan_kunci', 'pengembalian' => false],
                'kembali' => ['kategori' => 'pengembalian_kunci', 'pengembalian' => true],
            ],
            [
                'label' => 'Lainnya',
                'pergi' => ['kategori' => 'lainnya', 'pengembalian' => false],
                'kembali' => ['kategori' => 'lainnya', 'pengembalian' => true],
            ],
        ];

        $extraFotos = $pengajuan->fotos ? $pengajuan->fotos->filter(function($f) use ($standardCats) {
            return !in_array(strtolower(trim($f->kategori)), $standardCats, true);
        }) : collect();

        if ($extraFotos->count() > 0) {
            $extraGroups = [];
            foreach ($extraFotos as $foto) {
                $cat = strtolower(trim($foto->kategori));
                $baseName = preg_replace('/_\d+$/', '', $cat);
                $baseName = str_replace(['tambahan_kembali_', 'tambahan_'], '', $baseName);
                $groupKey = 'tambahan_' . $baseName;
                
                if (!isset($extraGroups[$groupKey])) {
                    $extraGroups[$groupKey] = [
                        'label' => 'Foto Tambahan ' . ($baseName != '' ? $baseName : (count($extraGroups) + 1)),
                        'pergi' => ['kategori' => $foto->kategori, 'pengembalian' => false, 'path' => !$foto->pengembalian ? $foto->path : null],
                        'kembali' => ['kategori' => $foto->kategori, 'pengembalian' => true, 'path' => $foto->pengembalian ? $foto->path : null],
                    ];
                } else {
                    if ($foto->pengembalian) {
                        $extraGroups[$groupKey]['kembali']['path'] = $foto->path;
                        $extraGroups[$groupKey]['kembali']['kategori'] = $foto->kategori;
                    } else {
                        $extraGroups[$groupKey]['pergi']['path'] = $foto->path;
                        $extraGroups[$groupKey]['pergi']['kategori'] = $foto->kategori;
                    }
                }
            }
            foreach ($extraGroups as $extraRow) {
                $fotoRows[] = $extraRow;
            }
        }

        foreach ($fotoRows as &$row) {
            $pergiPath = isset($row['pergi']['path']) ? $row['pergi']['path'] : $pengajuan->fotoPath($row['pergi']['kategori'], false);
            $kembaliPath = isset($row['kembali']['path']) ? $row['kembali']['path'] : $pengajuan->fotoPath($row['kembali']['kategori'], true);
            
            $row['pergi_base64'] = $this->imageToBase64($pergiPath);
            $row['kembali_base64'] = $this->imageToBase64($kembaliPath);
        }
        unset($row);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('Pengajuan.pdf', compact('pengajuan', 'fotoRows'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('Pengajuan_' . $pengajuan->kode_pengajuan . '.pdf');
    }

    private function imageToBase64(?string $relativePath): ?string
    {
        if (!$relativePath) {
            return null;
        }
        $fullPath = storage_path('app/public/' . $relativePath);
        if (!file_exists($fullPath)) {
            $fullPath = public_path('storage/' . $relativePath);
        }
        if (file_exists($fullPath)) {
            $type = pathinfo($fullPath, PATHINFO_EXTENSION);
            $mime = 'image/' . (strtolower($type) === 'png' ? 'png' : 'jpeg');
            $data = file_get_contents($fullPath);
            return 'data:' . $mime . ';base64,' . base64_encode($data);
        }
        return null;
    }
}