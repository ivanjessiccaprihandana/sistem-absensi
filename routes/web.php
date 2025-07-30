<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RekapPresensiController; // Tambahkan baris ini

Route::get('/', function () {
    return view('home');
});

Route::get('/rekap-presensi/pdf', [RekapPresensiController::class, 'download'])->name('rekap.presensi.pdf');

