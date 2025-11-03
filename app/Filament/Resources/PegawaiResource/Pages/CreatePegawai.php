<?php

namespace App\Filament\Resources\PegawaiResource\Pages;

use App\Filament\Resources\PegawaiResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;

class CreatePegawai extends CreateRecord
{
    protected static string $resource = PegawaiResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Jika tidak ada user dipilih, buat user baru otomatis
        if (empty($data['user_id'])) {

            // Email harus ada, kalau belum kita bentuk default
            $email = $data['email_kantor'] 
                ?: strtolower(str_replace(' ', '', $data['nama_lengkap'])) . '@example.com';

            $user = User::create([
    'name'     => $data['nama_lengkap'],
    'email'    => $email,
    'password' => Hash::make($data['nip']),
    'must_change_password' => true,
 // Password awal = NIP
            ]);

            // Tentukan role awal otomatis (silakan sesuaikan aturan lembaga)
            if (str_contains(strtolower($data['jabatan']), 'manajer')) {
                $user->assignRole('manajer');
            } elseif (str_contains(strtolower($data['jabatan']), 'kebag') || str_contains(strtolower($data['jabatan']), 'koorcab')) {
                $user->assignRole('kebag');
            } elseif (str_contains(strtolower($data['jabatan']), 'pengawas')) {
                $user->assignRole('pengawas');
            } elseif (str_contains(strtolower($data['jabatan']), 'pengurus')) {
                $user->assignRole('pengurus');
            } else {
                $user->assignRole('staf');
            }

            // Hubungkan user dengan pegawai nanti waktu create selesai
            $data['user_id'] = $user->id;
        }

        return $data;
    }
}
