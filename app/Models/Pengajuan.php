<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pengajuan extends Model
{
    use HasFactory;

    protected $table = 'pengajuan';

    protected $fillable = [
        'kode_pengajuan',
        'jenis',
        'aset_kode',
        'perusahaan_tamu',
        'perusahaan_pengutus',
        'jumlah',
        'kategori',
        'keperluan',
        'jam_mulai',
        'jam_selesai',
        'lama_berkerja',
        'identitas',
        'identitas_file',
        'masa_berlaku',
        'creator_id',
        'status',
        'progress',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(PengajuanDetail::class, 'kode_pengajuan', 'kode_pengajuan');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(PengajuanLog::class, 'kode_pengajuan', 'kode_pengajuan');
    }

    public function aset(): BelongsTo
    {
        return $this->belongsTo(ListAset::class, 'aset_kode', 'kode_aset');
    }

    public function fotos(): HasMany
    {
        return $this->hasMany(PengajuanFoto::class, 'kode_pengajuan', 'kode_pengajuan');
    }

    public function fotoPath(string $kategori, bool $pengembalian = false): ?string
    {
        $kategori = strtolower(trim($kategori));
        $aliases = $this->fotoKategoriAliases($kategori, $pengembalian);

        $matcher = function ($foto) use ($aliases, $pengembalian) {
            return in_array(strtolower((string) $foto->kategori), $aliases, true)
                && (bool) $foto->pengembalian === $pengembalian;
        };

        $fotos = $this->relationLoaded('fotos')
            ? $this->fotos->filter($matcher)
            : $this->fotos()
                ->whereIn('kategori', $aliases)
                ->where('pengembalian', $pengembalian)
                ->get();

        $foto = $fotos->firstWhere('kategori', $kategori) ?? $fotos->first();

        return $foto?->path;
    }

    private function fotoKategoriAliases(string $kategori, bool $pengembalian): array
    {
        if ($kategori === 'kendaraan' && $pengembalian) {
            return ['kendaraan', 'kendaraan_kembali'];
        }

        if ($kategori === 'lainnya' && $pengembalian) {
            return ['lainnya', 'lainnya_kembali'];
        }

        return [$kategori];
    }
}
