<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\ProgressController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['middleware'=>'auth:sanctum'], function (){
   Route::post('/logout', [AuthController::class, 'logout']);
   Route::post('/courses/enroll', [CourseController::class, 'enroll']);
   Route::delete('/courses/unlink', [CourseController::class, 'unlink']);
   Route::post('/progress/{lesson}', [ProgressController::class, 'update']);
   Route::get('/progress/{course}', [ProgressController::class, 'show']);
});
Route::group(['middleware'=>['auth:sanctum', 'role:teacher']], function (){
    Route::post('/courses', [CourseController::class, 'store']);
    Route::put('/courses/{course}', [CourseController::class, 'update']);
    Route::delete('/courses/{course}', [CourseController::class, 'destroy']);
});
Route::group(['middleware'=>['auth:sanctum', 'course.owner']], function (){
    Route::post('/courses/{course}/lessons', [LessonController::class, 'store']);
    Route::put('/courses/{course}/lessons/{lesson}', [LessonController::class, 'update']);
    Route::delete('/courses/{course}/lessons/{lesson}', [LessonController::class, 'destroy']);
});
Route::group(['middleware'=>['auth:sanctum', 'course.access']], function (){
   Route::get('/courses/{course}/lessons', [\App\Http\Controllers\LessonController::class, 'index']);
   Route::get('/courses/{course}/lessons/{lesson}', [\App\Http\Controllers\LessonController::class, 'show']);
});
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/courses', [CourseController::class, 'index']);
Route::get('/courses/{id}', [CourseController::class, 'show']);
