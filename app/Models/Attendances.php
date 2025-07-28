<?php

namespace App\Models;

use App\Models\Students;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        return $this->belongsTo(Students::class);
    }

    public function meeting() {
        return $this->belongsTo(Meetings::class);
    }
    


}
