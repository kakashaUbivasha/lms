<?php

namespace App\Services;

use App\Models\Course;

class CourseService
{
    public function courseProgress($id, $user)
    {
        $course = Course::findOrFail($id);
        if(!$course->students()->find($user->id)){
            throw new \Exception('Вы не записаны на этот курс', 409);
        }
        $progress = $course->progress()
            ->where('user_id', $user->id)
            ->where('is_completed', true)
            ->count();
        $result = ($progress/$course->lessons()->count())*100 . '%';
        return $result;
    }
}
