<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Students;
use App\Models\Attendances;

class AttendanceRecapPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.attendance-recap-page';

    public $students;
    public $rekap = [];

    public function mount()
    {
        $this->students = Students::all();

        foreach ($this->students as $student) {
            $hadir = Attendances::where('student_id', $student->id)->where('status', 'hadir')->count();
            $izin = Attendances::where('student_id', $student->id)->where('status', 'izin')->count();
            $alpa = Attendances::where('student_id', $student->id)->where('status', 'alpa')->count();
            $total = $hadir + $izin + $alpa;

            $this->rekap[] = [
                'name' => $student->name,
                'hadir' => $hadir,
                'izin' => $izin,
                'alpa' => $alpa,
                'total' => $total,
            ];
        }
    }
}
