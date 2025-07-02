<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Attendances;
use App\Models\Classes;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Meetings;

class AttendancePage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.attendance-page';

    public $students = [];
    public $attendance = []; // ['student_id' => 'status']
    public $rekap = [];
    public $classes;
    
    public $meetings;
    public $subjects;
    public $selectedMeeting = null;
    public $selectedSubject = null;
    public $selectedClass = null;
  

    public function mount()
{
    $this->classes = Classes::orderBy('name')->get();
    $this->meetings = Meetings::orderBy('meeting_number')->get();
    $this->subjects = Subject::orderBy('nama')->get();

    $this->students = [];
    $this->attendance = [];
}


    public function updatedSelectedMeeting()
    {
        $this->loadAttendance();
    }

    public function updatedSelectedSubject()
    {
        $this->loadAttendance();
    }

    private function loadAttendance()
    {
        if ($this->selectedMeeting && $this->selectedSubject && $this->selectedClass) {
            $meeting = Meetings::where('meeting_number', $this->selectedMeeting)
                ->where('subject_id', $this->selectedSubject)
                ->where('class_id', $this->selectedClass)
                ->first();
    
            if (!$meeting) {
                $this->students = [];
                $this->attendance = [];
                return;
            }
    
            // Ambil siswa berdasarkan kelas yang dipilih
            $this->students = Student::where('class_id', $this->selectedClass)->get();
    
            foreach ($this->students as $student) {
                $existing = Attendances::where('student_id', $student->id)
                    ->where('meeting_id', $meeting->id)
                    ->first();
    
                $this->attendance[$student->id] = $existing?->status ?? 'hadir';
            }
        }
    }
    
    

    public function save()
    {
        if (!$this->selectedMeeting || !$this->selectedSubject) {
            $this->notify('danger', 'Pilih pertemuan dan mata pelajaran terlebih dahulu.');
            return;
        }

        $meeting = Meetings::where('meeting_number', $this->selectedMeeting)
            ->where('subject_id', $this->selectedSubject)
            ->first();

        if (!$meeting) {
            $this->notify('danger', 'Pertemuan tidak ditemukan.');
            return;
        }

        foreach ($this->attendance as $studentId => $status) {
            Attendances::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'meeting_id' => $meeting->id,
                ],
                [
                    'status' => $status,
                ]
            );
        }

        $this->notify('success', 'Presensi berhasil disimpan untuk pertemuan ke-' . $this->selectedMeeting);
    }
        public function updatedSelectedClass()
    {
        $this->loadAttendance();
    }
}
