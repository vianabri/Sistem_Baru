<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pegawai extends Model
{
    protected $fillable = [
        'user_id','nip','nama_lengkap','tempat_lahir','tanggal_lahir','jenis_kelamin',
        'email_kantor','no_hp','jabatan','departemen','lokasi_kerja',
        'tanggal_masuk','status_kepegawaian'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_masuk' => 'date'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function orgUnit(): \Illuminate\Database\Eloquent\Relations\BelongsTo
{
    return $this->belongsTo(\App\Models\OrgUnit::class, 'org_unit_id');
}

}
