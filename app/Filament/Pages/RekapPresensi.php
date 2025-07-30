<?php

namespace App\Filament\Pages;

use App\Models\Attendances;
use App\Models\Classes;
use App\Models\Meetings;
use App\Models\Students;
use App\Models\Subject;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

class RekapPresensi extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.rekap-presensi';
    protected static ?string $title = 'Rekap Presensi';

    public Collection $kelasList;
    public Collection $subjectList;
    public Collection $jurusanList;

    public ?string $filterJurusan = null;
    public ?string $filterKelas = null;
    public ?string $filterPertemuan = null;

    public Collection $rekapSiswaGabung;

    public function mount(): void
    {
        $this->kelasList = Classes::all();
        $this->subjectList = Subject::all();
        $this->jurusanList = $this->subjectList->pluck('nama')->unique()->filter()->values();

        $this->filterJurusan = $this->jurusanList->first();
        $this->filterKelas = null;
        $this->filterPertemuan = null;

        $this->rekapSiswaGabung = $this->getRekapSiswaGabung();
    }

    public function getPertemuanListProperty(): Collection
    {
        if (!$this->filterJurusan || !$this->filterKelas) {
            return collect(); // kosong kalau belum dipilih jurusan dan kelas
        }

        $subject = Subject::where('nama', $this->filterJurusan)->first();
        $kelas = Classes::where('name', $this->filterKelas)->first();

        if (!$subject || !$kelas) {
            return collect();
        }

        return Meetings::where('subject_id', $subject->id)
            ->where('class_id', $kelas->id)
            ->get();
    }

    public function updated($property): void
    {
        if (in_array($property, ['filterJurusan', 'filterKelas', 'filterPertemuan'])) {
            $this->rekapSiswaGabung = $this->getRekapSiswaGabung();
        }
    }

    public function getRekapSiswaGabung(): Collection
    {
        $result = collect();
        $subjectsFiltered = $this->subjectList->where('nama', $this->filterJurusan);

        $kelasFiltered = $this->filterKelas
            ? $this->kelasList->filter(fn($kelas) => $kelas->name == $this->filterKelas)
            : $this->kelasList;

        foreach ($kelasFiltered as $kelas) {
            $students = Students::where('class_id', $kelas->id)->get();

            foreach ($students as $student) {
                foreach ($subjectsFiltered as $subject) {
                    $meetingQuery = Meetings::where('class_id', $kelas->id)
                        ->where('subject_id', $subject->id);

                    if ($this->filterPertemuan) {
                        $meetingQuery->where('id', $this->filterPertemuan);
                    }

                    $meetingIds = $meetingQuery->pluck('id');

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
            'filterKelas' => $this->filterKelas,
            'filterPertemuan' => $this->filterPertemuan,
        ];

        $pdf = Pdf::loadView('filament.pages.rekap-presensi-pdf', $data);
        return response()->streamDownload(
            fn () => print($pdf->stream()),
            'rekap-presensi-' . ($this->filterJurusan ?? 'all') . '-' . ($this->filterKelas ?? 'semua-kelas') . '-' . ($this->filterPertemuan ?? 'semua-pertemuan') . '.pdf'
        );
    }
}
    