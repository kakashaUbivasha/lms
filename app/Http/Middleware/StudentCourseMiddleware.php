<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class StudentCourseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $courseId = $request->route('course');
        $user = Auth::user();
        $hasCourse = $user->student_courses()->where('course_id', $courseId)->exists();
        if (!$hasCourse) {
            return response()->json(['message' => 'Course not found'], 403);
        }
        return $next($request);
    }
}
