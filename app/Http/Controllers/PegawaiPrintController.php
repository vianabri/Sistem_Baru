<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Barryvdh\DomPDF\Facade\Pdf;

class PegawaiPrintController extends Controller
{
    public function cetak()
    {
        $data = Pegawai::orderBy('nama_lengkap')->get();

        $rekapStatus = Pegawai::selectRaw('status_kepegawaian, COUNT(*) as total')
            ->groupBy('status_kepegawaian')
            ->pluck('total', 'status_kepegawaian');

        $rekapDepartemen = Pegawai::selectRaw('departemen, COUNT(*) as total')
            ->groupBy('departemen')
            ->pluck('total', 'departemen');

        $pdf = Pdf::loadView('pdf.pegawai', compact('data', 'rekapStatus', 'rekapDepartemen'))
            ->setPaper('A4', 'portrait');

        return $pdf->download('data-pegawai.pdf');
    }
}
