<?php

namespace App\Filament\Pages;

use App\Models\Classes;
use App\Models\Students;
use App\Models\Attendances;
use App\Models\Meetings;
use App\Models\Subject;
use Filament\Pages\Page;
use Illuminate\Support\Collection;
use Barryvdh\DomPDF\Facade\Pdf; // Tambahkan ini di bagian use

class RekapPresensi extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.rekap-presensi';
    protected static ?string $title = 'Rekap Presensi';

    public Collection $kelasList;
    public Collection $subjectList;
    public Collection $jurusanList; // Tambahkan property jurusanList
    public ?string $filterJurusan = null;
    public Collection $rekapSiswaGabung;

    public function mount(): void
    {
        $this->kelasList = Classes::all();
        $this->subjectList = Subject::all();
        $this->jurusanList = $this->subjectList->pluck('nama')->unique()->filter()->values(); // Ambil dari nama matkul
        $this->filterJurusan = $this->jurusanList->first(); // Default jurusan dari nama matkul

        $this->rekapSiswaGabung = $this->getRekapSiswaGabung();
    }

    public function updatedFilterJurusan()
    {
        $this->rekapSiswaGabung = $this->getRekapSiswaGabung();
    }

    public function getRekapMatkul()
    {
        return $this->subjectList->map(function ($subject) {
            $meetingIds = Meetings::where('subject_id', $subject->id)->pluck('id');
            $att = Attendances::whereIn('meeting_id', $meetingIds)->get();
            return [
                'matkul' => $subject->nama,
                'hadir' => $att->where('status', 'Hadir')->count(),
                'izin' => $att->where('status', 'Izin')->count(),
                'sakit' => $att->where('status', 'Sakit')->count(),
                'alpa' => $att->where('status', 'Alpa')->count(),
                'total' => $att->count(),
            ];
        });
    }

    public function getRekapSiswaGabung()
    {
        $result = collect();
        // Filter subject berdasarkan nama matkul yang dipilih sebagai "jurusan"
        $subjectsFiltered = $this->subjectList->where('nama', $this->filterJurusan);

        foreach ($this->kelasList as $kelas) {
            $students = Students::where('class_id', $kelas->id)->with('class')->get();
            foreach ($students as $student) {
                foreach ($subjectsFiltered as $subject) {
                    $meetingIds = Meetings::where('class_id', $kelas->id)
                        ->where('subject_id', $subject->id)
                        ->pluck('id');
                    $att = Attendances::where('student_id', $student->id)
                        ->whereIn('meeting_id', $meetingIds)
                        ->get();
                    $result->push([
                        'nama' => $student->nama ?? $student->name,
                        'kelas' => $kelas->name,
                        'matkul' => $subject->nama,
                        'hadir' => $att->where('status', 'Hadir')->count(),
                        'izin' => $att->where('status', 'Izin')->count(),
                        'sakit' => $att->where('status', 'Sakit')->count(),
                        'alpa' => $att->where('status', 'Alpa')->count(),
                        'total' => $att->count(),
                    ]);
                }
            }
        }
        return $result;
    }

    public function downloadPdf()
    {
        $data = [
            'rekap' => $this->rekapSiswaGabung,
            'filterJurusan' => $this->filterJurusan,
        ];
        $pdf = Pdf::loadView('filament.pages.rekap-presensi-pdf', $data);
        return response()->streamDownload(
            fn() => print($pdf->stream()),
            'rekap-presensi-' . ($this->filterJurusan ?? 'all') . '.pdf'
        );
    }
}
