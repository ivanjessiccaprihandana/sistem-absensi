<?php

namespace App\Http\Controllers;

use App\Models\Meetings;
use App\Models\Students;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class RekapPresensiController extends Controller
{
    public function download()
    {
        $students = Students::all();
        $meetings = Meetings::with(['subject', 'attendances'])->get(); // pastikan relasi 'attendances' valid

        $pdf = Pdf::loadView('rekap.presensi-pdf', compact('students', 'meetings'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download('rekap-presensi-siswa.pdf');
    }
}
