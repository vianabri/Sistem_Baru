<?php

namespace App\Imports;

use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PegawaiImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Cari atau buat user jika email_kantor ada
        $userId = null;

        if (!empty($row['email_kantor'])) {
            $user = User::firstOrCreate(
                ['email' => $row['email_kantor']],
                ['name' => $row['nama_lengkap'], 'password' => Hash::make('password')]
            );

            // Pastikan role minimal staf
            $user->assignRole('staf');

            $userId = $user->id;
        }

        return Pegawai::updateOrCreate(
            ['nip' => $row['nip']],
            [
                'user_id'           => $userId,
                'nama_lengkap'      => $row['nama_lengkap'],
                'tempat_lahir'      => $row['tempat_lahir'],
                'tanggal_lahir'     => $row['tanggal_lahir'],
                'jenis_kelamin'     => $row['jenis_kelamin'],
                'email_kantor'      => $row['email_kantor'],
                'no_hp'             => $row['no_hp'],
                'jabatan'           => $row['jabatan'],
                'departemen'        => $row['departemen'],
                'lokasi_kerja'      => $row['lokasi_kerja'],
                'tanggal_masuk'     => $row['tanggal_masuk'],
                'status_kepegawaian'=> $row['status_kepegawaian'] ?: 'Tetap',
            ]
        );
    }
}
