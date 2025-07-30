<?php

namespace App\Filament\Pages;

use App\Models\Classes; // Menggunakan nama model baru
use App\Models\Students; // Menggunakan nama model baru
use App\Models\Attendances; // Menggunakan nama model baru
use App\Models\Meetings; // Menggunakan nama model baru
use App\Models\Subject; // Model baru untuk mata pelajaran/jurusan
use Filament\Pages\Page;
use Illuminate\Support\Collection;
use Barryvdh\DomPDF\Facade\Pdf; // Dipertahankan untuk fungsi PDF

class RekapPresensi extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.rekap-presensi';
    protected static ?string $title = 'Rekap Presensi';

    public $filterJurusan = null;
    public $filterKelas = null;
    public $filterPertemuan = null; // Properti ini dikembalikan untuk dropdown Pertemuan

    // Properti untuk menyimpan data yang dimuat di awal (mount)
    public Collection $jurusanOptions;
    public Collection $kelasListData;
    public Collection $subjectListData;

    /**
     * Metode mount() dipanggil saat komponen Livewire diinisialisasi.
     * Digunakan untuk memuat data awal dan mengatur filter default.
     */
    public function mount(): void
    {
        // Muat semua data Subject dan Classes sekali saja
        $this->subjectListData = Subject::all();
        $this->kelasListData = Classes::all();

        // Isi opsi dropdown Jurusan dari nama mata pelajaran yang unik
        $this->jurusanOptions = $this->subjectListData->pluck('nama')->unique()->filter()->values();

        // Atur filter Jurusan awal ke yang pertama tersedia, atau null jika tidak ada
        $this->filterJurusan = $this->jurusanOptions->first();
        $this->filterKelas = null;
        $this->filterPertemuan = null;
    }

    /**
     * Properti komputasi untuk mendapatkan daftar kelas untuk dropdown 'Kelas'.
     * Memfilter kelas berdasarkan 'Jurusan' (nama Mata Pelajaran) yang dipilih.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getKelasListProperty(): Collection
    {
        // Jika filterJurusan belum dipilih, kembalikan koleksi kosong
        if (!$this->filterJurusan) {
            return collect();
        }

        // Dapatkan ID mata pelajaran untuk jurusan yang dipilih
        $subjectIds = $this->subjectListData->where('nama', $this->filterJurusan)->pluck('id');

        if ($subjectIds->isEmpty()) {
            return collect();
        }

        // Dapatkan ID kelas yang memiliki pertemuan terkait dengan mata pelajaran ini
        $classIdsWithMeetings = Meetings::whereIn('subject_id', $subjectIds)
                                        ->distinct()
                                        ->pluck('class_id');

        if ($classIdsWithMeetings->isEmpty()) {
            return collect();
        }

        // Filter data kelas yang sudah dimuat berdasarkan ID kelas yang ditemukan
        return $this->kelasListData->whereIn('id', $classIdsWithMeetings->toArray());
    }

    /**
     * Properti komputasi untuk mendapatkan daftar pertemuan untuk dropdown 'Pertemuan'.
     * Memfilter pertemuan berdasarkan 'Jurusan' (nama Mata Pelajaran) dan 'Kelas' (nama Kelas) yang dipilih.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getMeetingListProperty(): Collection
    {
        // Jika filterJurusan atau filterKelas belum dipilih, kembalikan koleksi kosong
        if (!$this->filterJurusan || !$this->filterKelas) {
            return collect();
        }

        // Dapatkan ID mata pelajaran untuk jurusan yang dipilih
        $subjectId = $this->subjectListData->where('nama', $this->filterJurusan)->first()->id ?? null;

        // Dapatkan ID kelas untuk nama kelas yang dipilih
        $classId = $this->kelasListData->where('name', $this->filterKelas)->first()->id ?? null;

        if (!$subjectId || !$classId) {
            return collect();
        }

        // Ambil pertemuan berdasarkan ID kelas dan ID mata pelajaran
        return Meetings::where('class_id', $classId)
                        ->where('subject_id', $subjectId)
                        ->get();
    }

    /**
     * Properti komputasi untuk mendapatkan data rekap presensi siswa.
     * Metode ini melakukan query database secara efisien menggunakan join dan agregasi.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getRekapSiswaGabungProperty(): Collection
    {
        // Jika filterJurusan belum dipilih, kembalikan koleksi kosong
        if (!$this->filterJurusan) {
            return collect();
        }

        // Dapatkan objek mata pelajaran untuk jurusan yang dipilih
        $subject = $this->subjectListData->where('nama', $this->filterJurusan)->first();
        if (!$subject) {
            return collect();
        }

        // Mulai dengan query pada tabel Attendances
        $attendanceQuery = Attendances::query();

        // Lakukan join dengan tabel Students, Meetings, Classes, dan Subjects
        $attendanceQuery->join('students', 'attendances.student_id', '=', 'students.id');
        $attendanceQuery->join('meetings', 'attendances.meeting_id', '=', 'meetings.id');
        $attendanceQuery->join('classes', 'students.class_id', '=', 'classes.id'); // Join untuk mendapatkan nama kelas
        $attendanceQuery->join('subjects', 'meetings.subject_id', '=', 'subjects.id'); // Join untuk mendapatkan nama mata pelajaran

        // Filter berdasarkan subject_id (yang berasal dari filterJurusan)
        $attendanceQuery->where('meetings.subject_id', $subject->id);

        // Filter berdasarkan kelas jika filterKelas dipilih
        if ($this->filterKelas) {
            $class = $this->kelasListData->where('name', $this->filterKelas)->first();
            if (!$class) {
                return collect(); // Jika kelas tidak ditemukan, kembalikan kosong
            }
            $attendanceQuery->where('classes.id', $class->id);
        }

        // Filter berdasarkan pertemuan spesifik jika filterPertemuan dipilih
        if ($this->filterPertemuan) {
            $attendanceQuery->where('attendances.meeting_id', $this->filterPertemuan);
        }

        // Pilih kolom yang diperlukan dan kelompokkan berdasarkan siswa, kelas, dan mata pelajaran
        // HANYA MENGGUNAKAN 'students.name' karena 'students.nama' tidak ditemukan
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
            ->groupBy('students.id', 'classes.id', 'subjects.id') // Kelompokkan berdasarkan ID untuk memastikan baris unik
            ->get();

        // Petakan hasil query ke format yang diinginkan untuk ditampilkan di tabel
        return $rekapData->map(function ($item) {
            return [
                'nama' => $item->student_name, // Menggunakan student_name yang sudah diperbaiki
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

    /**
     * Metode yang dipanggil ketika filterJurusan berubah.
     * Ini mereset filterKelas dan filterPertemuan untuk menghindari data yang tidak konsisten.
     */
    public function updatedFilterJurusan(): void
    {
        $this->filterKelas = null;
        $this->filterPertemuan = null;
    }

    /**
     * Metode yang dipanggil ketika filterKelas berubah.
     * Ini mereset filterPertemuan untuk menghindari data yang tidak konsisten.
     */
    public function updatedFilterKelas(): void
    {
        $this->filterPertemuan = null;
    }

    /**
     * Logika untuk mengunduh PDF.
     * Menggunakan Barryvdh\DomPDF.
     */
    public function downloadPdf()
    {
        $data = [
            'rekap' => $this->rekapSiswaGabung,
            'filterJurusan' => $this->filterJurusan,
            'filterKelas' => $this->filterKelas,
        ];

        // Muat view untuk pembuatan PDF
        $pdf = Pdf::loadView('filament.pages.rekap-presensi-pdf', $data);

        // Stream PDF untuk diunduh
        return response()->streamDownload(
            fn () => print($pdf->stream()),
            'rekap-presensi-' . ($this->filterJurusan ?? 'all') . '-' . ($this->filterKelas ?? 'semua-kelas') . '.pdf'
        );
    }
}
