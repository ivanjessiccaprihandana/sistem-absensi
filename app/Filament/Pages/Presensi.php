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
        $this->initializePresences();
    }

    public function updatedSelectedClass(): void
    {
        $this->selectedMeeting = null;
        $this->students = $this->getStudentsProperty();
        $this->meetings = $this->getMeetingsProperty();
        $this->initializePresences();
    }

    public function updatedSelectedSubject(): void
    {
        $this->selectedMeeting = null;
        $this->meetings = $this->getMeetingsProperty();
        $this->students = $this->getStudentsProperty();
        $this->initializePresences();
    }

        public function updatedSelectedMeeting(): void
        {
            // Jangan reset presences jika user sedang input manual
            if (!$this->selectedMeeting) {
                return;
            }

            foreach ($this->students as $student) {
                $attendance = Attendances::where('meeting_id', $this->selectedMeeting)
                    ->where('student_id', $student->id)
                    ->first();

                if ($attendance) {
                    // hanya set jika belum pernah diisi (jangan override input manual user)
                    $this->data['presences'][$student->id] ??= $attendance->status;
                }
            }
        }
    private function initializePresences(): void
    {
        $this->data['presences'] = [];

        foreach ($this->students as $student) {
            $attendance = Attendances::where('meeting_id', $this->selectedMeeting)
                ->where('student_id', $student->id)
                ->first();

            $this->data['presences'][$student->id] = $attendance?->status ?? null;
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

    public function getMeetingsProperty(): Collection
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

        if (!$this->selectedMeeting) {
            Notification::make()
                ->title('Silakan pilih pertemuan terlebih dahulu')
                ->danger()
                ->send();
            return;
        }

        if (empty($this->data['presences'])) {
            Notification::make()
                ->title('Presensi belum diisi')
                ->danger()
                ->send();
            return;
        }

        foreach ($this->data['presences'] as $studentId => $status) {
            if (!in_array($studentId, $validStudentIds)) {
                continue;
            }

            // Jika status belum diisi, skip siswa ini
            if (is_null($status) || $status === '') {
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
