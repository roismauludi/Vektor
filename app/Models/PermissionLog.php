<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PermissionLog extends Model
{
    use HasFactory;

    protected $table = 'permission_log';

    protected $fillable = [
        'admin_id',
        'user_id',
        'aksi',
        'permissions_lama',
        'permissions_baru',
        'keterangan',
    ];

    protected $casts = [
        'permissions_lama' => 'array',
        'permissions_baru' => 'array',
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function targetUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getAddedPermissionsAttribute(): array
    {
        $lama = $this->permissions_lama ?? [];
        $baru = $this->permissions_baru ?? [];
        return array_values(array_diff($baru, $lama));
    }

    public function getRemovedPermissionsAttribute(): array
    {
        $lama = $this->permissions_lama ?? [];
        $baru = $this->permissions_baru ?? [];
        return array_values(array_diff($lama, $baru));
    }
}
