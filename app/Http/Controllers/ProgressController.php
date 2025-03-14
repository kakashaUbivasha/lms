<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckCourseRequest;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Progress;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    public function update(CheckCourseRequest $request, $id)
    {
        $lesson = Lesson::where('id', $id)
            ->where('course_id', $request->course_id)
            ->firstOrFail();

        $progress = Progress::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'course_id' => $request->course_id,
                'lesson_id' => $lesson->id,
            ],
            [
                'is_completed' => $request->is_completed??false,
            ]
        );

        return response()->json($progress);
    }
    public function show($id)
    {
        $course = Course::findOrFail($id);
        $user = auth()->user();

        $progress = Progress::where('user_id', $user->id)
            ->whereHas('lesson', function ($query) use ($id) {
                $query->where('course_id', $id);
            })->get();

        return response()->json($progress);
    }
}
