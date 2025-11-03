<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['pengurus','pengawas','manajer','kebag_koorcab','staf'] as $role) {
            Role::findOrCreate($role, 'web');
        }
    }
}