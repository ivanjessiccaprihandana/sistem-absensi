<?php

namespace App\Http\Controllers;

use App\Models\Meetings;
use App\Models\Students;
use App\Models\Classes; // Pastikan model Classes diimport
use App\Models\Attendances; // Pastikan model Attendances diimport
use App\Models\Subject; // Pastikan model Subject diimport
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class RekapPresensiController extends Controller
{
    /**
     * Mengunduh rekap presensi siswa dalam format PDF.
     * Menerima filter dari request (query parameters).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function download(Request $request)
    {
        // Mengambil nilai filter dari request
        $filterJurusan = $request->query('filterJurusan');
        $filterKelas = $request->query('filterKelas');
        $filterPertemuan = $request->query('filterPertemuan');

        // Muat semua data Subject dan Classes sekali saja untuk efisiensi
        $subjectListData = Subject::all();
        $kelasListData = Classes::all();

        // --- Logika untuk mendapatkan data Rekap Siswa Gabung (mirip dengan Livewire component) ---
        $rekap = collect(); // Inisialisasi koleksi rekap

        // Pastikan filterJurusan dipilih sebelum melanjutkan
        if ($filterJurusan) {
            $subject = $subjectListData->where('nama', $filterJurusan)->first();

            if ($subject) {
                $attendanceQuery = Attendances::query();

                $attendanceQuery->join('students', 'attendances.student_id', '=', 'students.id');
                $attendanceQuery->join('meetings', 'attendances.meeting_id', '=', 'meetings.id');
                $attendanceQuery->join('classes', 'students.class_id', '=', 'classes.id');
                $attendanceQuery->join('subjects', 'meetings.subject_id', '=', 'subjects.id');

                $attendanceQuery->where('meetings.subject_id', $subject->id);

                if ($filterKelas) {
                    $class = $kelasListData->where('name', $filterKelas)->first();
                    if ($class) {
                        $attendanceQuery->where('classes.id', $class->id);
                    }
                }

                if ($filterPertemuan) {
                    $attendanceQuery->where('attendances.meeting_id', $filterPertemuan);
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

        // --- Logika untuk menentukan namaPertemuan ---
        $namaPertemuan = 'Semua Pertemuan'; // Default value
        if ($filterPertemuan) {
            $selectedMeeting = Meetings::find($filterPertemuan);
            // Menggunakan 'meeting_number' sebagai asumsi nama kolom yang benar
            // Anda HARUS mengganti 'meeting_number' dengan nama kolom yang sebenarnya di tabel 'meetings' Anda
            if ($selectedMeeting && property_exists($selectedMeeting, 'meeting_number')) {
                $namaPertemuan = "Pertemuan ke-" . $selectedMeeting->meeting_number;
            } else if ($selectedMeeting) {
                // Fallback jika 'meeting_number' tidak ada atau null, tapi objek pertemuan ditemukan
                $namaPertemuan = "Pertemuan ke- (data tidak tersedia)";
            }
        }
        // --- Akhir Logika namaPertemuan ---

        // LOGGING UNTUK DEBUGGING: Lihat data yang akan diteruskan ke PDF
        Log::info('PDF Data:', [
            'rekap' => $rekap->toArray(),
            'filterJurusan' => $filterJurusan,
            'filterKelas' => $filterKelas,
            'namaPertemuan' => $namaPertemuan
        ]);

        // Memuat view PDF dengan data yang sudah diolah
        $pdf = Pdf::loadView('filament.pages.rekap-presensi-pdf', compact('rekap', 'filterJurusan', 'filterKelas', 'namaPertemuan'))
                  ->setPaper('a4', 'landscape');

        // Mengembalikan PDF sebagai unduhan
        return $pdf->download('rekap-presensi-siswa-' . ($filterJurusan ?? 'all') . '-' . ($filterKelas ?? 'all') . '-' . ($namaPertemuan ?? 'all-meetings') . '.pdf');
    }
}
