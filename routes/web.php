<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/rekap-presensi/pdf', [RekapPresensiController::class, 'download'])->name('rekap.presensi.pdf');
