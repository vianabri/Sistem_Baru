<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrgUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
public function run(): void
{
    $cabang = \App\Models\OrgUnit::create([
        'code' => 'CBG-01', 'name' => 'Cabang Utama', 'type' => 'Cabang'
    ]);

    $koorcab = \App\Models\OrgUnit::create([
        'code' => 'KC-01', 'name' => 'Koorcab Barat', 'type' => 'Koorcab', 'parent_id' => $cabang->id
    ]);

    \App\Models\OrgUnit::create([
        'code' => 'UNIT-KRD', 'name' => 'Unit Kredit', 'type' => 'Unit', 'parent_id' => $koorcab->id
    ]);
}

}
