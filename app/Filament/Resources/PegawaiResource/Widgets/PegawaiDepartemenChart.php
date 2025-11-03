<?php

namespace App\Filament\Widgets;

use App\Models\Pegawai;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class PegawaiDepartemenChart extends ApexChartWidget
{
     protected static ?string $chartId =  'pegawaiDepartemenChart';
    protected static ?string $heading = 'Pegawai per Departemen';

    protected function getOptions(): array
    {
        $departemen = Pegawai::whereNotNull('departemen')
            ->distinct()
            ->pluck('departemen');

        $counts = $departemen->map(fn($d) =>
            Pegawai::where('departemen', $d)->count()
        )->toArray();

        return [
            'chart' => [
                'type' => 'bar',
            ],
            'xaxis' => [
                'categories' => $departemen,
            ],
            'series' => [
                [
                    'name' => 'Jumlah Pegawai',
                    'data' => $counts,
                ]
            ],
        ];
    }
}
