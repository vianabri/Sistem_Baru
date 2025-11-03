<?php

namespace App\Filament\Widgets;

use App\Models\Pegawai;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class PegawaiStatusChart extends ApexChartWidget
{
    protected static ?string $chartId = 'pegawaiStatusChart';
    protected static ?string $heading = 'Distribusi Status Pegawai';

    protected function getOptions(): array
    {
        $status = ['Tetap', 'Kontrak', 'Magang'];
        $counts = array_map(fn($s) => Pegawai::where('status_kepegawaian', $s)->count(), $status);

        return [
            'chart' => [
                'type' => 'donut',
            ],
            'labels' => $status,
            'series' => $counts,
        ];
    }
}
