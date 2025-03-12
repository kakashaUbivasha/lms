<?php

namespace App\Http\Middleware;

use App\Models\Course;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckCourseOwnership
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $courseId = $request->route('course');
        $course = Course::findOrFail($courseId);
        if($course->teacher_id !== Auth::id()  && !Auth::user()->isAdmin()){
            return response()->json(['message' => 'Доступ запрещен'], 403);
        }
        return $next($request);
    }
}
