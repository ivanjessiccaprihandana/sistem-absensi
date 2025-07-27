<?php

namespace App\Filament\Pages;

use Filament\Forms;
use App\Models\Student;
use App\Models\Meetings;
use App\Models\Students;
use Filament\Pages\Page;
use App\Models\Attendances;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;

class Presensi extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static string $view = 'filament.pages.presensi';
    protected static ?string $title = 'Presensi Siswa';

    public ?int $selectedMeeting = null;
    public array $data = [];

    public function mount(): void
    {
        $this->selectedMeeting = Meetings::latest()->first()?->id;
    }
public function updatedSelectedMeeting(): void
{
    $this->data = [];

    $students = $this->students;

    foreach ($students as $student) {
        $attendance = Attendances::where('meeting_id', $this->selectedMeeting)
            ->where('student_id', $student->id)
            ->first();

        if ($attendance) {
            $this->data['presences'][$student->id] = $attendance->status;
        }
    }
}

public function submit(): void
{
    $validStudentIds = $this->students->pluck('id')->toArray();

    foreach ($this->data['presences'] ?? [] as $studentId => $status) {
        // Hanya simpan jika siswa benar-benar ada di kelas
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
}
public function getStudentsProperty()
{
    if (!$this->selectedMeeting) {
        return collect();
    }

    $meeting = Meetings::find($this->selectedMeeting);

    if (!$meeting) {
        return collect();
    }

    return Students::where('class_id', $meeting->class_id)->get();
}

    public function getMeetingsProperty()
    {
        return Meetings::with('class', 'subject')->get();
    }
}
