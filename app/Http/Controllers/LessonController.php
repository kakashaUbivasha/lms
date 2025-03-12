<?php

namespace App\Http\Controllers;

use App\Http\Requests\LessonRequest;
use App\Http\Requests\UpdateLessonRequest;
use App\Http\Resources\LessonResource;
use App\Models\Course;
use App\Models\Lesson;
use App\Services\LessonService;

class LessonController extends Controller
{
    public function index($id)
    {
        $lessons = Lesson::where('course_id', $id)->get();
        return LessonResource::collection($lessons);
    }
    public function show($course_id, $lesson_id)
    {
        $course = Course::findOrFail($course_id);
        $lesson = Lesson::findOrFail($lesson_id);

        if($lesson->course_id != $course->id){
            return response()->json(['message'=>'Урок не принадлежит этому курсу'], 404);
        }
        return new LessonResource($lesson);
    }
    public function store($id, LessonRequest $request, LessonService $lessonService)
    {
        $course = Course::findOrFail($id);
        $data = $request->validated();
        try{
            $result = $lessonService->createLesson($data, $course);
            return new LessonResource($result);
        }catch (\Exception $e){
            return response()->json(['error'=>$e->getMessage()], $e->getCode());
        }
    }
    public function update($course_id, $lesson_id, UpdateLessonRequest $request, LessonService $lessonService)
    {
        $course = Course::findOrFail($course_id);
        $lesson = Lesson::findOrFail($lesson_id);
        $data = $request->validated();
        try {
            $result = $lessonService->updateLesson($data, $course, $lesson);
            return  new LessonResource($result);
        }catch (\Exception $e){
            return response()->json(['error'=>$e->getMessage()], $e->getCode());
        }
    }
    public function destroy($course_id, $lesson_id){
        $course = Course::findOrFail($course_id);
        $lesson = Lesson::findOrFail($lesson_id);
        $lesson->delete();
        return response()->json('Успешно удаленно', 204);
    }
}
