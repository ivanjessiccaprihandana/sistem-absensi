<?php

namespace App\Filament\Pages;

use App\Models\Classes;
use App\Models\Students;
use App\Models\Attendances;
use App\Models\Meetings;
use App\Models\Subject;
use Filament\Pages\Page;
use Illuminate\Support\Collection;
use Barryvdh\DomPDF\Facade\Pdf;

class RekapPresensi extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.rekap-presensi';
    protected static ?string $title = 'Rekap Presensi';

    public $filterJurusan = null;
    public $filterKelas = null;
    public $filterPertemuan = null;

    public Collection $jurusanOptions;
    public Collection $kelasListData;
    public Collection $subjectListData;

    public function mount(): void
    {
        $this->subjectListData = Subject::all();
        $this->kelasListData = Classes::all();

        $this->jurusanOptions = $this->subjectListData->pluck('nama')->unique()->filter()->values();

        $this->filterJurusan = $this->jurusanOptions->first();
        $this->filterKelas = null;
        $this->filterPertemuan = null;
    }

    public function getKelasListProperty(): Collection
    {
        if (!$this->filterJurusan) {
            return collect();
        }

        $subjectIds = $this->subjectListData->where('nama', $this->filterJurusan)->pluck('id');

        if ($subjectIds->isEmpty()) {
            return collect();
        }

        $classIdsWithMeetings = Meetings::whereIn('subject_id', $subjectIds)
                                        ->distinct()
                                        ->pluck('class_id');

        if ($classIdsWithMeetings->isEmpty()) {
            return collect();
        }

        return $this->kelasListData->whereIn('id', $classIdsWithMeetings->toArray());
    }

    public function getMeetingListProperty(): Collection
    {
        if (!$this->filterJurusan || !$this->filterKelas) {
            return collect();
        }

        $subjectId = $this->subjectListData->where('nama', $this->filterJurusan)->first()->id ?? null;
        $classId = $this->kelasListData->where('name', $this->filterKelas)->first()->id ?? null;

        if (!$subjectId || !$classId) {
            return collect();
        }

        return Meetings::where('class_id', $classId)
                        ->where('subject_id', $subjectId)
                        ->get();
    }

    public function getRekapSiswaGabungProperty(): Collection
    {
        if (!$this->filterJurusan) {
            return collect();
        }

        $subject = $this->subjectListData->where('nama', $this->filterJurusan)->first();
        if (!$subject) {
            return collect();
        }

        $attendanceQuery = Attendances::query();

        $attendanceQuery->join('students', 'attendances.student_id', '=', 'students.id');
        $attendanceQuery->join('meetings', 'attendances.meeting_id', '=', 'meetings.id');
        $attendanceQuery->join('classes', 'students.class_id', '=', 'classes.id');
        $attendanceQuery->join('subjects', 'meetings.subject_id', '=', 'subjects.id');

        $attendanceQuery->where('meetings.subject_id', $subject->id);

        if ($this->filterKelas) {
            $class = $this->kelasListData->where('name', $this->filterKelas)->first();
            if (!$class) {
                return collect();
            }
            $attendanceQuery->where('classes.id', $class->id);
        }

        if ($this->filterPertemuan) {
            $attendanceQuery->where('attendances.meeting_id', $this->filterPertemuan);
        }

        $rekapData = $attendanceQuery->selectRaw('
                students.name as student_name,
                classes.name as class_name,
                subjects.nama as subject_name,
                COUNT(CASE WHEN attendances.status = "Hadir" THEN 1 END) as hadir_count,
                COUNT(CASE WHEN attendances.status = "Izin" THEN 1 END) as izin_count,
                COUNT(CASE WHEN attendances.status = "Sakit" THEN 1 END) as sakit_count,
                COUNT(CASE WHEN attendances.status = "Alpa" THEN 1 END) as alpa_count,
                COUNT(attendances.id) as total_count
            ')
            ->groupBy('students.id', 'classes.id', 'subjects.id')
            ->get();

        return $rekapData->map(function ($item) {
            return [
                'nama' => $item->student_name,
                'kelas' => $item->class_name,
                'matkul' => $item->subject_name,
                'hadir' => $item->hadir_count,
                'izin' => $item->izin_count,
                'sakit' => $item->sakit_count,
                'alpa' => $item->alpa_count,
                'total' => $item->total_count,
            ];
        });
    }

    public function updatedFilterJurusan(): void
    {
        $this->filterKelas = null;
        $this->filterPertemuan = null;
    }

    public function updatedFilterKelas(): void
    {
        $this->filterPertemuan = null;
    }

    /**
     * Logika untuk mengunduh PDF.
     * Memastikan namaPertemuan selalu didefinisikan.
     */
    public function downloadPdf()
    {
        $namaPertemuan = 'Semua Pertemuan'; // Default value
        if ($this->filterPertemuan) {
            $selectedMeeting = Meetings::find($this->filterPertemuan);
            // Menggunakan 'meeting_number' sebagai asumsi nama kolom yang benar
            // Anda HARUS mengganti 'meeting_number' dengan nama kolom yang sebenarnya di tabel 'meetings' Anda
            if ($selectedMeeting && property_exists($selectedMeeting, 'meeting_number')) {
                $namaPertemuan = "Pertemuan ke-" . $selectedMeeting->meeting_number;
            } else if ($selectedMeeting) {
                // Fallback jika 'meeting_number' tidak ada atau null, tapi objek pertemuan ditemukan
                $namaPertemuan = "Pertemuan ke- (data tidak tersedia)";
            }
        }

        $data = [
            'rekap' => $this->rekapSiswaGabung,
            'filterJurusan' => $this->filterJurusan,
            'filterKelas' => $this->filterKelas,
            'filterPertemuan' => $this->filterPertemuan, // ID pertemuan
            'namaPertemuan' => $namaPertemuan, // Variabel yang sudah dipastikan didefinisikan
        ];

        $pdf = Pdf::loadView('filament.pages.rekap-presensi-pdf', $data);

        return response()->streamDownload(
            fn () => print($pdf->stream()),
            // Menggunakan 'meeting_number' untuk nama file PDF juga
            'rekap-presensi-' . ($this->filterJurusan ?? 'all') . '-' . ($this->filterKelas ?? 'semua-kelas') . '-' . (isset($selectedMeeting) && property_exists($selectedMeeting, 'meeting_number') ? $selectedMeeting->meeting_number : 'all-meetings') . '.pdf'
        );
    }
}
