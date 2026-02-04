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
        'tower_kode',
        'central_kode',
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

    public function towerAset()
    {
        return $this->belongsTo(ListAset::class, 'tower_kode', 'kode_aset');
    }

    public function centralAset()
    {
        return $this->belongsTo(ListAset::class, 'central_kode', 'kode_aset');
    }

    public function fotos(): HasMany
    {
        return $this->hasMany(PengajuanFoto::class, 'kode_pengajuan', 'kode_pengajuan');
    }

    public function fotoPath(string $kategori): ?string
    {
        $kategori = strtolower(trim($kategori));

        $foto = $this->relationLoaded('fotos')
            ? $this->fotos->firstWhere('kategori', $kategori)
            : $this->fotos()->where('kategori', $kategori)->first();

        return $foto?->path;
    }
}
