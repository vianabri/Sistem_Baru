<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrgUnit extends Model
{
    protected $fillable = [
        'code',
        'name',
        'type',
        'parent_id',
        'head_pegawai_id',
        'alamat',
        'telepon',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function head(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'head_pegawai_id');
    }

    public function pegawais(): HasMany
    {
        return $this->hasMany(Pegawai::class, 'org_unit_id');
    }
}
