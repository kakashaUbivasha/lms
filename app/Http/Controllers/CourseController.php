<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckCourseRequest;
use App\Http\Requests\CourseRequest;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Services\CourseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CourseController extends Controller
{
    public function index(){
        $courses = Cache::remember('courses_list', 3600, function () {
            return Course::all();
        });
        return CourseResource::collection($courses);
    }
    public function show($id){
        $course = Course::findOrFail($id);
        return new CourseResource($course);
    }
    public function store(CourseRequest $request){
        $user = auth()->user();
        $data = $request->validated();
        if ($this->courseExistsForTeacher($user->id, $data['title'])) {
            return response()->json(['message' => 'У вас уже есть такой курс'], 400);
        }
        $course = Course::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'teacher_id' => $user->id,
        ]);
        return new CourseResource($course);
    }
    public function update(CourseRequest $request, $id){
        $user = auth()->user();
        $data = $request->validated();
        if ($this->courseExistsForTeacher($user->id, $data['title'])) {
            return response()->json(['message' => 'У вас уже есть такой курс'], 400);
        }
        $course = Course::findOrFail($id);
        $course->update($data);
        return new CourseResource($course);
    }
    public function destroy($id){
        $course = Course::findOrFail($id);
        $course->delete();
        return response()->json(['message'=>'Удалено успешно'], 204);
    }
    public function enroll(CheckCourseRequest $request)
    {
        $request->validated();
        $course = Course::findOrFail($request->course_id);
        if(!$course->students()->count() <= 30){
            return response(['message' => 'Вы уже записаны на этот курс'], 409);
        }
        $user = auth()->user();
        if($course->students()->where('user_id', $user->id)->exists()){
            return response()->json(['message' => 'Вы уже записаны на этот курс'], 409);
        }
        $course->students()->attach($user->id);
        return response()->json(['message' => 'Вы успешно записались на курс']);
    }
    public function unlink(CheckCourseRequest $request)
    {
        $request->validated();
        $course = Course::findOrFail($request->course_id);
        $user = auth()->user();
        if(!$course->students()->where('user_id', $user->id)->exists()){
            return response()->json(['message' => 'Вы не записаны на этот курс'], 409);
        }
        $course->students()->detach($user->id);
        return response()->json(['message' => 'Вы успешно отписались от курса']);
    }

    public function courseProgress($id, CourseService $courseService){
            $user = auth()->user();
        try {
            $data = $courseService->courseProgress($id, $user);
            return response()->json(['data' => $data]);
        }catch (\Exception $exception){
            return response()->json(['message' => $exception->getMessage()], 400);
        }
    }

    private function courseExistsForTeacher($teacherId, $title)
    {
        return Course::where('teacher_id', $teacherId)
            ->where('title', $title)
            ->exists();
    }
}
