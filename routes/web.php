<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\PegawaiPrintController;

Route::get('/pegawai/cetak', [PegawaiPrintController::class, 'cetak'])
    ->middleware(['auth'])
    ->name('pegawai.cetak');
