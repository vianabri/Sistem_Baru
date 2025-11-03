<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $u = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin Utama', 'password' => Hash::make('password')]
        );
        $u->syncRoles('pengurus');
    }
}
