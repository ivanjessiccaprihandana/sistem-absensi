<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendances extends Model
{
    use HasFactory;

    const STATUS = [
        'Hadir' => 'Hadir',
        'Izin' => 'Izin',
        'Alpa' => 'Alpa'
    ];


    protected $fillable = [
        'meeting_id',
        'student_id',
        'status',
        'meeting_number'
    ];

            // Meeting.php// Attendance.php
    public function student() {
        return $this->belongsTo(Student::class);
    }

    public function meeting() {
        return $this->belongsTo(Meetings::class);
    }
    


}
