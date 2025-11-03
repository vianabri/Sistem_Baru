<?php

namespace App\Filament\Widgets;

use App\Models\Pegawai;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PegawaiStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Pegawai', Pegawai::count()),
            Stat::make('Pegawai Tetap', Pegawai::where('status_kepegawaian', 'Tetap')->count()),
            Stat::make('Pegawai Kontrak', Pegawai::where('status_kepegawaian', 'Kontrak')->count()),
            Stat::make('Magang', Pegawai::where('status_kepegawaian', 'Magang')->count()),
        ];
    }
}
