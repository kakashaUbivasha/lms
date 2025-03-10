<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function lesson(){
        return $this->belongsTo(Lesson::class);
    }
    public function questions(){
        return $this->hasMany(Question::class);
    }
    public function users(){
        return $this->belongsToMany(User::class)->withPivot('score', 'completed_at')->withTimestamps();
    }
}
