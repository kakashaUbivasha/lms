<?php

namespace App\Services;

use App\Http\Resources\LessonResource;
use App\Models\Lesson;

class LessonService
{
    public function createLesson($data, $course)
    {
        $lessonOrder = Lesson::where('course_id', $course->id)->where('order', $data['order'])->exists();
        if($lessonOrder){
            throw new  \Exception('Курс с таким порядком уже есть', 409);
        }
        $lesson = Lesson::create([
            'course_id' => $course->id,
            'title' => $data['title'],
            'content' => $data['content'],
            'video' => $data['video'] ?? null,
            'order' => $data['order']
        ]);
        return $lesson;
    }

    public function updateLesson($data, $course, $lesson){
        $lessonOrder = Lesson::where('course_id', $course->id)->where('order', $data['order'])->exists();
        if($lessonOrder){
            throw new  \Exception('Курс с таким порядком уже есть', 409);
        }
        $lesson->update($data);
        return $lesson;
    }
}
