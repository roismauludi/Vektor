<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengajuanDetail extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_detail';

    protected $fillable = [
        'kode_pengajuan',
        'nama_tamu',
        'no_tlp',
        'email',
    ];

    /**
     * Detail selalu terkait dengan satu pengajuan induk melalui kode_pengajuan.
     */
    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(Pengajuan::class, 'kode_pengajuan', 'kode_pengajuan');
    }
}

