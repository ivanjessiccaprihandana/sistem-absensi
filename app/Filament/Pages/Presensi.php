<?php

namespace App\Filament\Pages;

use App\Models\Meetings;
use App\Models\Students;
use App\Models\Attendances;
use App\Models\Classes;
use App\Models\Subject;
use Filament\Pages\Page;
use Illuminate\Support\Collection;
use Filament\Notifications\Notification;

// Kelas ini merupakan halaman (page) di Filament untuk mengelola presensi siswa.
class Presensi extends Page
{
    // Properti statis untuk menentukan ikon navigasi di sidebar Filament.
    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    
    // Properti statis yang menunjuk ke view Blade yang akan dirender untuk halaman ini.
    protected static string $view = 'filament.pages.presensi';
    
    // Properti statis untuk menentukan judul halaman.
    protected static ?string $title = 'Presensi Siswa';

    // Properti publik untuk menyimpan ID kelas yang dipilih oleh pengguna.
    public ?int $selectedClass = null;
    
    // Properti publik untuk menyimpan ID pertemuan yang dipilih oleh pengguna.
    public ?int $selectedMeeting = null;
    
    // Properti publik untuk menyimpan ID mata pelajaran yang dipilih oleh pengguna.
    public ?int $selectedSubject = null;
    
    // Array publik untuk menampung data formulir, khususnya status presensi.
    public array $data = [];
    
    // Koleksi publik untuk menyimpan data siswa yang terkait dengan kelas yang dipilih.
    public Collection $students;
    
    // Koleksi publik untuk menyimpan semua data kelas.
    public Collection $classes;
    
    // Koleksi publik untuk menyimpan data pertemuan.
    public Collection $meetings;
    
    // Koleksi publik untuk menyimpan semua data mata pelajaran.
    public Collection $subjects;

    // Metode 'mount' adalah lifecycle hook Filament yang dijalankan saat halaman diinisialisasi.
    public function mount(): void
    {
        // Mengambil semua data kelas, mata pelajaran, dan pertemuan saat halaman pertama kali dimuat.
        $this->classes = $this->getClassesProperty();
        $this->subjects = $this->getSubjectsProperty();
        $this->meetings = $this->getMeetingsProperty();
        
        // Mengatur nilai default untuk dropdown, memilih item pertama jika ada.
        $this->selectedClass = $this->classes->first()?->id;
        $this->selectedSubject = $this->subjects->first()?->id;
        
        // Mengatur pertemuan terpilih menjadi null secara default.
        $this->selectedMeeting = null;
        
        // Mengambil siswa berdasarkan kelas yang sudah dipilih.
        $this->students = $this->getStudentsProperty();
        
        // Menginisialisasi array presensi untuk siswa.
        $this->initializePresences();
    }

    // Metode ini dipanggil secara otomatis oleh Livewire/Filament ketika $selectedClass berubah.
    public function updatedSelectedClass(): void
    {
        // Mereset pertemuan yang dipilih karena daftar pertemuan akan berubah.
        $this->selectedMeeting = null;
        
        // Memuat ulang daftar siswa, pertemuan, dan menginisialisasi presensi.
        $this->students = $this->getStudentsProperty();
        $this->meetings = $this->getMeetingsProperty();
        $this->initializePresences();
    }

    // Metode ini dipanggil secara otomatis ketika $selectedSubject berubah.
    public function updatedSelectedSubject(): void
    {
        // Mereset pertemuan yang dipilih karena daftar pertemuan akan berubah.
        $this->selectedMeeting = null;
        
        // Memuat ulang daftar pertemuan, siswa, dan menginisialisasi presensi.
        $this->meetings = $this->getMeetingsProperty();
        $this->students = $this->getStudentsProperty();
        $this->initializePresences();
    }

    // Metode ini dipanggil secara otomatis ketika $selectedMeeting berubah.
    public function updatedSelectedMeeting(): void
    {
        // Mengabaikan eksekusi jika tidak ada pertemuan yang dipilih.
        if (!$this->selectedMeeting) {
            return;
        }

        // Mengulang setiap siswa untuk memeriksa apakah sudah ada data presensi.
        foreach ($this->students as $student) {
            // Mencari data presensi berdasarkan ID pertemuan dan ID siswa.
            $attendance = Attendances::where('meeting_id', $this->selectedMeeting)
                ->where('student_id', $student->id)
                ->first();

            if ($attendance) {
                // Mengisi status presensi dari database, tetapi hanya jika belum ada input manual.
                // Operator '??=' (null coalescing assignment) digunakan untuk memastikan tidak menimpa nilai yang sudah ada.
                $this->data['presences'][$student->id] ??= $attendance->status;
            }
        }
    }
    
    // Metode privat untuk menginisialisasi array presensi siswa.
    private function initializePresences(): void
    {
        // Mengosongkan array presensi.
        $this->data['presences'] = [];

        // Mengulang setiap siswa.
        foreach ($this->students as $student) {
            // Mencari data presensi yang sudah ada untuk siswa dan pertemuan yang dipilih.
            $attendance = Attendances::where('meeting_id', $this->selectedMeeting)
                ->where('student_id', $student->id)
                ->first();

            // Mengisi status presensi dengan nilai dari database atau null jika tidak ada.
            $this->data['presences'][$student->id] = $attendance?->status ?? null;
        }
    }

    // Computed property untuk mengambil semua data kelas.
    public function getClassesProperty()
    {
        return Classes::all();
    }

    // Computed property untuk mengambil semua data mata pelajaran.
    public function getSubjectsProperty()
    {
        return Subject::all();
    }

    // Computed property untuk mengambil data siswa berdasarkan kelas yang dipilih.
    public function getStudentsProperty()
    {
        // Jika tidak ada kelas yang dipilih, kembalikan koleksi kosong.
        if (!$this->selectedClass) {
            return collect();
        }
        // Mengambil siswa yang memiliki class_id yang sesuai.
        return Students::where('class_id', $this->selectedClass)->get();
    }

    // Computed property untuk mengambil data pertemuan yang difilter.
    public function getMeetingsProperty(): Collection
    {
        // Memulai query dengan eager loading relasi 'class' dan 'subject'.
        $query = Meetings::with('class', 'subject');

        // Menambahkan filter berdasarkan kelas jika sudah dipilih.
        if ($this->selectedClass) {
            $query->where('class_id', $this->selectedClass);
        }
        // Menambahkan filter berdasarkan mata pelajaran jika sudah dipilih.
        if ($this->selectedSubject) {
            $query->where('subject_id', $this->selectedSubject);
        }

        // Mengeksekusi query dan mengembalikan hasilnya.
        return $query->get();
    }

    // Metode ini dipanggil saat formulir presensi dikirim (submit).
    public function submit(): void
    {
        // Mengambil daftar ID siswa yang valid dari kelas yang dipilih.
        $validStudentIds = $this->students->pluck('id')->toArray();

        // Validasi: Memeriksa apakah pertemuan sudah dipilih. Jika belum, tampilkan notifikasi.
        if (!$this->selectedMeeting) {
            Notification::make()
                ->title('Silakan pilih pertemuan terlebih dahulu')
                ->danger()
                ->send();
            return;
        }

        // Validasi: Memeriksa apakah ada data presensi yang diisi. Jika belum, tampilkan notifikasi.
        if (empty($this->data['presences'])) {
            Notification::make()
                ->title('Presensi belum diisi')
                ->danger()
                ->send();
            return;
        }

        // Mengulang setiap data presensi yang dikirim dari formulir.
        foreach ($this->data['presences'] as $studentId => $status) {
            // Memeriksa apakah ID siswa valid (ada dalam daftar siswa yang seharusnya).
            if (!in_array($studentId, $validStudentIds)) {
                continue;
            }

            // Memeriksa apakah status presensi sudah diisi. Jika kosong, lewati siswa ini.
            if (is_null($status) || $status === '') {
                continue;
            }

            // Menggunakan metode updateOrCreate() untuk menyimpan atau memperbarui data presensi.
            // Baris ini akan mencari data dengan 'meeting_id' dan 'student_id' yang sama.
            // Jika ada, akan diupdate. Jika tidak ada, akan dibuat entri baru.
            Attendances::updateOrCreate(
                [
                    'meeting_id' => $this->selectedMeeting,
                    'student_id' => $studentId,
                ],
                [
                    'status' => $status,
                    'meeting_number' => Meetings::find($this->selectedMeeting)?->meeting_number,
                ]
            );
        }

        // Menampilkan notifikasi sukses setelah semua data tersimpan.
        Notification::make()
            ->title('Presensi berhasil disimpan')
            ->success()
            ->send();

        // Memuat ulang daftar siswa untuk memastikan tampilan tetap konsisten.
        $this->students = $this->getStudentsProperty();
    }
}