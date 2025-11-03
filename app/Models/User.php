<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = ['name','email','password'];

    protected $hidden = ['password','remember_token'];

    public function pegawai(): HasOne
    {
        return $this->hasOne(Pegawai::class);
    }

    // Izin akses ke panel Filament
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasAnyRole([
            'pengurus','pengawas','manajer','kebag_koorcab','staf'
        ]);
    }
}
