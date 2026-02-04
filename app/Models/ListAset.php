<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListAset extends Model
{
    protected $fillable = [
        'kode_aset',
        'kategori',
        'nama',
        'lokasi',
    ];

    protected $table = 'list_aset';
}
