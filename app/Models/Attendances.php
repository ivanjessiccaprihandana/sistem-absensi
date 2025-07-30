<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendances extends Model
{
    use HasFactory;

    const STATUS = [
        'Hadir' => 'Hadir',
        'Izin'  => 'Izin',
        'Alpa'  => 'Alpa',
    ];

    protected $fillable = [
        'meeting_id',
        'student_id',
        'status',
        'meeting_number',
    ];

    /**
     * Relasi ke model Student
     */
    public function student()
    {
        return $this->belongsTo(Students::class);
    }

    /**
     * Relasi ke model Meeting
     */
    public function meeting()
    {
        return $this->belongsTo(Meetings::class);
    }
}
