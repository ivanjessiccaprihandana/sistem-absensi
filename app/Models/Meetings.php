<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meetings extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'class_id',
        'meeting_date',
        'meeting_number'
    ];
        // Meeting.php
    public function subject() {
        return $this->belongsTo(Subject::class);
    }

    public function class() {
        return $this->belongsTo(Classes::class);
    }

    public function attendances() {
        return $this->hasMany(Attendances::class, 'meeting_id'); 
    }
    

}
