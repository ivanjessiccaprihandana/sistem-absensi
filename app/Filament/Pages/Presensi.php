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

class Presensi extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static string $view = 'filament.pages.presensi';
    protected static ?string $title = 'Presensi Siswa';

    public ?int $selectedClass = null;
    public ?int $selectedMeeting = null;
    public ?int $selectedSubject = null;
    public array $data = [];
    public Collection $students;
    public Collection $classes;
    public Collection $meetings;
    public Collection $subjects;

    public function mount(): void
    {
        $this->classes = $this->getClassesProperty();
        $this->subjects = $this->getSubjectsProperty();
        $this->meetings = $this->getMeetingsProperty();
        $this->selectedClass = $this->classes->first()?->id;
        $this->selectedSubject = $this->subjects->first()?->id;
        $this->selectedMeeting = null;
        $this->students = $this->getStudentsProperty();
    }

    public function updatedSelectedClass(): void
    {
        $this->selectedMeeting = null;
        $this->students = $this->getStudentsProperty();
        $this->meetings = $this->getMeetingsProperty();
        $this->reset('data');
    }

    public function updatedSelectedSubject(): void
    {
        $this->selectedMeeting = null;
        $this->meetings = $this->getMeetingsProperty();
        $this->students = $this->getStudentsProperty();
        $this->reset('data');
    }

    public function updatedSelectedMeeting(): void
    {
        $this->reset('data');
        foreach ($this->students as $student) {
            $attendance = Attendances::where('meeting_id', $this->selectedMeeting)
                ->where('student_id', $student->id)
                ->first();

            if ($attendance) {
                $this->data['presences'][$student->id] = $attendance->status;
            }
        }
    }

    public function getClassesProperty()
    {
        return Classes::all();
    }

    public function getSubjectsProperty()
    {
        return Subject::all();
    }

    public function getStudentsProperty()
    {
        if (!$this->selectedClass) {
            return collect();
        }
        return Students::where('class_id', $this->selectedClass)->get();
    }

    public function getMeetingsProperty(): \Illuminate\Support\Collection
    {
        $query = Meetings::with('class', 'subject');
        if ($this->selectedClass) {
            $query->where('class_id', $this->selectedClass);
        }
        if ($this->selectedSubject) {
            $query->where('subject_id', $this->selectedSubject);
        }
        return $query->get();
    }

    public function submit(): void
    {
        $validStudentIds = $this->students->pluck('id')->toArray();

        // Jika pertemuan belum dipilih, jangan simpan presensi
        if (!$this->selectedMeeting) {
            Notification::make()
                ->title('Silakan pilih pertemuan terlebih dahulu')
                ->danger()
                ->send();
            return;
        }

        foreach ($this->data['presences'] ?? [] as $studentId => $status) {
            if (!in_array($studentId, $validStudentIds)) {
                continue;
            }

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

        Notification::make()
            ->title('Presensi berhasil disimpan')
            ->success()
            ->send();

        $this->students = $this->getStudentsProperty();
    }
}
