<?php

use App\Filament\Pages\RekapPresensi;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/rekap-presensi/pdf', [RekapPresensi::class, 'download'])->name('rekap.presensi.pdf');

