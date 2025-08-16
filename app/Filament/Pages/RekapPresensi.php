<?php

namespace App\Filament\Pages;

use App\Models\Classes;
use App\Models\Students;
use App\Models\Attendances;
use App\Models\Meetings;
use App\Models\Subject;
use Filament\Pages\Page;
use Illuminate\Support\Collection;
use Barryvdh\DomPDF\Facade\Pdf; // Mengimpor facade PDF dari package Barryvdh/Laravel-DomPDF
use Illuminate\Http\Request; // Mengimpor kelas Request untuk menangani permintaan HTTP

// Kelas ini mendefinisikan halaman "Rekap Presensi" di panel admin Filament.
// Halaman ini digunakan untuk menampilkan dan mengunduh laporan rekapitulasi presensi siswa.
class RekapPresensi extends Page
{
    // Properti statis untuk menentukan ikon navigasi di sidebar Filament.
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    
    // Properti statis yang menunjuk ke view Blade yang akan dirender untuk halaman ini.
    protected static string $view = 'filament.pages.rekap-presensi';
    
    // Properti statis untuk menentukan judul halaman yang akan ditampilkan di UI Filament.
    protected static ?string $title = 'Rekap Presensi';

    // Properti publik untuk menyimpan nilai filter jurusan yang dipilih oleh pengguna.
    public $filterJurusan = null;
    
    // Properti publik untuk menyimpan nilai filter kelas yang dipilih oleh pengguna.
    public $filterKelas = null;
    
    // Properti publik untuk menyimpan nilai filter pertemuan yang dipilih oleh pengguna.
    public $filterPertemuan = null;

    // Koleksi publik untuk menyimpan opsi jurusan yang akan ditampilkan di dropdown filter.
    public Collection $jurusanOptions;
    
    // Koleksi publik untuk menyimpan semua data kelas dari database.
    public Collection $kelasListData;
    
    // Koleksi publik untuk menyimpan semua data mata pelajaran dari database.
    public Collection $subjectListData;

    // Metode 'mount' adalah lifecycle hook Filament yang dijalankan saat halaman diinisialisasi.
    public function mount(): void
    {
        // Memuat semua data mata pelajaran dan kelas dari database saat halaman dimuat.
        $this->subjectListData = Subject::all();
        $this->kelasListData = Classes::all();

        // Mengambil nama-nama jurusan unik dari data mata pelajaran untuk opsi filter.
        // `pluck('nama')` mengambil kolom 'nama', `unique()` menghilangkan duplikat,
        // `filter()` menghapus nilai kosong, dan `values()` mereset kunci array.
        $this->jurusanOptions = $this->subjectListData->pluck('nama')->unique()->filter()->values();

        // Mengatur nilai default untuk filter jurusan ke opsi pertama jika ada.
        $this->filterJurusan = $this->jurusanOptions->first();
        // Mengatur filter kelas dan pertemuan menjadi null secara default.
        $this->filterKelas = null;
        $this->filterPertemuan = null;
    }

    // Computed property untuk mendapatkan daftar kelas yang relevan berdasarkan filter jurusan.
    public function getKelasListProperty(): Collection
    {
        // Jika filter jurusan belum dipilih, kembalikan koleksi kosong.
        if (!$this->filterJurusan) {
            return collect();
        }

        // Mengambil ID mata pelajaran yang sesuai dengan nama jurusan yang difilter.
        $subjectIds = $this->subjectListData->where('nama', $this->filterJurusan)->pluck('id');

        // Jika tidak ada ID mata pelajaran yang ditemukan, kembalikan koleksi kosong.
        if ($subjectIds->isEmpty()) {
            return collect();
        }

        // Mengambil ID kelas yang memiliki pertemuan terkait dengan mata pelajaran yang difilter.
        // `distinct()` memastikan setiap ID kelas hanya muncul sekali.
        $classIdsWithMeetings = Meetings::whereIn('subject_id', $subjectIds)
                                         ->distinct()
                                         ->pluck('class_id');

        // Jika tidak ada ID kelas dengan pertemuan yang ditemukan, kembalikan koleksi kosong.
        if ($classIdsWithMeetings->isEmpty()) {
            return collect();
        }

        // Mengembalikan data kelas yang ID-nya ada dalam daftar ID kelas dengan pertemuan.
        return $this->kelasListData->whereIn('id', $classIdsWithMeetings->toArray());
    }

    // Computed property untuk mendapatkan daftar pertemuan yang relevan berdasarkan filter jurusan dan kelas.
    public function getMeetingListProperty(): Collection
    {
        // Jika filter jurusan atau kelas belum dipilih, kembalikan koleksi kosong.
        if (!$this->filterJurusan || !$this->filterKelas) {
            return collect();
        }

        // Mencari ID mata pelajaran berdasarkan nama jurusan yang difilter.
        $subjectId = $this->subjectListData->where('nama', $this->filterJurusan)->first()->id ?? null;
        // Mencari ID kelas berdasarkan nama kelas yang difilter.
        $classId = $this->kelasListData->where('name', $this->filterKelas)->first()->id ?? null;

        // Jika ID mata pelajaran atau ID kelas tidak ditemukan, kembalikan koleksi kosong.
        if (!$subjectId || !$classId) {
            return collect();
        }

        // Mengambil pertemuan yang sesuai dengan ID kelas dan ID mata pelajaran yang difilter.
        return Meetings::where('class_id', $classId)
                        ->where('subject_id', $subjectId)
                        ->get();
    }

    // Computed property untuk mendapatkan data rekapitulasi presensi siswa.
    public function getRekapSiswaGabungProperty(): Collection
    {
        // Jika filter jurusan belum dipilih, kembalikan koleksi kosong.
        if (!$this->filterJurusan) {
            return collect();
        }

        // Mencari objek mata pelajaran berdasarkan nama jurusan yang difilter.
        $subject = $this->subjectListData->where('nama', $this->filterJurusan)->first();
        // Jika objek mata pelajaran tidak ditemukan, kembalikan koleksi kosong.
        if (!$subject) {
            return collect();
        }

        // Memulai query pada tabel attendances.
        $attendanceQuery = Attendances::query();

        // Melakukan JOIN dengan tabel lain untuk mendapatkan informasi lengkap (siswa, pertemuan, kelas, mata pelajaran).
        $attendanceQuery->join('students', 'attendances.student_id', '=', 'students.id');
        $attendanceQuery->join('meetings', 'attendances.meeting_id', '=', 'meetings.id');
        $attendanceQuery->join('classes', 'students.class_id', '=', 'classes.id');
        $attendanceQuery->join('subjects', 'meetings.subject_id', '=', 'subjects.id');

        // Menerapkan filter berdasarkan ID mata pelajaran.
        $attendanceQuery->where('meetings.subject_id', $subject->id);

        // Menerapkan filter berdasarkan kelas jika filterKelas dipilih.
        if ($this->filterKelas) {
            $class = $this->kelasListData->where('name', $this->filterKelas)->first();
            if (!$class) {
                return collect();
            }
            $attendanceQuery->where('classes.id', $class->id);
        }

        // Menerapkan filter berdasarkan pertemuan jika filterPertemuan dipilih.
        if ($this->filterPertemuan) {
            $attendanceQuery->where('attendances.meeting_id', $this->filterPertemuan);
        }

        // Memilih kolom yang dibutuhkan dan menghitung status presensi (Hadir, Izin, Sakit, Alpa).
        // `selectRaw()` memungkinkan penulisan ekspresi SQL mentah.
        // `COUNT(CASE WHEN ... THEN 1 END)` digunakan untuk menghitung status tertentu.
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
            // Mengelompokkan hasil berdasarkan siswa, kelas, dan mata pelajaran untuk agregasi.
            ->groupBy('students.id', 'classes.id', 'subjects.id')
            ->get();

        // Memetakan hasil query ke format array yang lebih mudah digunakan di view.
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

    // Metode ini dipanggil secara otomatis ketika $filterJurusan berubah.
    public function updatedFilterJurusan(): void
    {
        // Mereset filter kelas dan pertemuan karena pilihan jurusan baru mungkin tidak relevan.
        $this->filterKelas = null;
        $this->filterPertemuan = null;
    }

    // Metode ini dipanggil secara otomatis ketika $filterKelas berubah.
    public function updatedFilterKelas(): void
    {
        // Mereset filter pertemuan karena pilihan kelas baru mungkin tidak relevan.
        $this->filterPertemuan = null;
    }

    // Metode ini menangani permintaan unduhan PDF.
    // Parameter Request $request digunakan untuk mengakses query string dari URL.
    public function download(Request $request)
    {
        // Mengambil nilai filter dari query string URL.
        $filterJurusan = $request->query('filterJurusan');
        $filterKelas = $request->query('filterKelas');
        $filterPertemuan = $request->query('filterPertemuan');

        // Memuat semua data Subject dan Classes sekali saja untuk efisiensi,
        // agar tidak perlu query berulang di dalam logika rekap.
        $subjectListData = Subject::all();
        $kelasListData = Classes::all();

        // --- Logika untuk mendapatkan data Rekap Siswa Gabung (mirip dengan computed property di atas) ---
        $rekap = collect(); // Inisialisasi koleksi rekap sebagai kosong.

        // Memastikan filterJurusan dipilih sebelum melanjutkan proses rekap.
        if ($filterJurusan) {
            $subject = $subjectListData->where('nama', $filterJurusan)->first();

            if ($subject) {
                $attendanceQuery = Attendances::query();

                // Melakukan JOIN tabel yang diperlukan untuk mendapatkan data lengkap.
                $attendanceQuery->join('students', 'attendances.student_id', '=', 'students.id');
                $attendanceQuery->join('meetings', 'attendances.meeting_id', '=', 'meetings.id');
                $attendanceQuery->join('classes', 'students.class_id', '=', 'classes.id');
                $attendanceQuery->join('subjects', 'meetings.subject_id', '=', 'subjects.id');

                // Menerapkan filter berdasarkan ID mata pelajaran.
                $attendanceQuery->where('meetings.subject_id', $subject->id);

                // Menerapkan filter kelas jika filterKelas ada.
                if ($filterKelas) {
                    $class = $kelasListData->where('name', $filterKelas)->first();
                    if ($class) {
                        $attendanceQuery->where('classes.id', $class->id);
                    }
                }

                // Menerapkan filter pertemuan jika filterPertemuan ada.
                if ($filterPertemuan) {
                    $attendanceQuery->where('attendances.meeting_id', $filterPertemuan);
                }

                // Mengambil data rekapitulasi dengan agregasi status presensi.
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

                // Memetakan data rekapitulasi ke format yang diinginkan untuk PDF.
                $rekap = $rekapData->map(function ($item) {
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
        }
        // --- Akhir Logika Rekap Siswa Gabung ---

        // --- Logika untuk menentukan namaPertemuan yang akan ditampilkan di PDF ---
        $namaPertemuan = 'Semua Pertemuan'; // Nilai default jika tidak ada filter pertemuan.
        if ($filterPertemuan) {
            // Mencari objek pertemuan berdasarkan ID yang difilter.
            $selectedMeeting = Meetings::find($filterPertemuan);
            // Memeriksa apakah pertemuan ditemukan dan memiliki 'meeting_number'.
            if ($selectedMeeting && !is_null($selectedMeeting->meeting_number)) {
                $namaPertemuan = "Pertemuan Ke-" . $selectedMeeting->meeting_number;
            } else if ($selectedMeeting) {
                // Fallback jika 'meeting_number' tidak ada atau null, tapi objek pertemuan ditemukan.
                $namaPertemuan = "Pertemuan ke- (data tidak tersedia)";
            }
        }

        // Memuat view Blade khusus untuk PDF ('filament.pages.rekap-presensi-pdf')
        // dengan data rekap, filter jurusan, filter kelas, dan nama pertemuan.
        $pdf = Pdf::loadView('filament.pages.rekap-presensi-pdf', compact('rekap', 'filterJurusan', 'filterKelas', 'namaPertemuan'))
                  // Mengatur ukuran kertas menjadi A4 dalam orientasi landscape.
                  ->setPaper('a4', 'landscape');

        // Mengembalikan PDF sebagai unduhan ke browser pengguna.
        // Nama file PDF akan dinamis berdasarkan filter yang digunakan.
        return $pdf->download('rekap-presensi-siswa-' . ($filterJurusan ?? 'all') . '-' . ($filterKelas ?? 'all') . '-' . ($namaPertemuan ?? 'all-meetings') . '.pdf');
    }
}