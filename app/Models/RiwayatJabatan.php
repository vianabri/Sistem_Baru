<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatJabatan extends Model
{
    protected $fillable = [
        'pegawai_id','tanggal','jenis',
        'dari_jabatan','ke_jabatan',
        'dari_org_unit_id','ke_org_unit_id',
        'keterangan','dibuat_oleh',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function dariUnit(): BelongsTo
    {
        return $this->belongsTo(OrgUnit::class, 'dari_org_unit_id');
    }

    public function keUnit(): BelongsTo
    {
        return $this->belongsTo(OrgUnit::class, 'ke_org_unit_id');
    }

    public function pencatat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }
}
