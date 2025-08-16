<?php

namespace App\Models;

use App\Models\Students;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Classes extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function students() {
        return $this->hasMany(Students::class);
    }

    public function meetings() {
        return $this->hasMany(Meetings::class);
    }
}