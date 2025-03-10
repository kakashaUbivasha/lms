<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function course(){
        return $this->belongsTo(Course::class);
    }
    public function quizzes(){
        return $this->hasMany(Quiz::class);
    }
    public function progress()
    {
    return $this->hasMany(Progress::class);
    }
}
