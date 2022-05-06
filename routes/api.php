<?php

use App\Http\Controllers\Api\{
    AuthController,
    CourseController,
    LessonController,
    ModuleController,
    ReplySupportController,
    SupportController
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/auth', [AuthController::class, 'auth']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/user', [AuthController::class, 'user'])->middleware('auth:sanctum');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/courses', [CourseController::class, 'index']);
    Route::get('/courses/{id}', [CourseController::class, 'show']);
    
    Route::get('/courses/{id}/modules', [ModuleController::class, 'index']);
    
    Route::get('/modules/{id}/lessons', [LessonController::class, 'index']);
    Route::get('/lessons/{id}', [LessonController::class, 'show']);
    
    Route::get('/supports', [SupportController::class, 'index']);
    Route::get('/user-supports', [SupportController::class, 'userSupports']);
    Route::post('/supports', [SupportController::class, 'store']);
    
    Route::post('/replies', [ReplySupportController::class, 'createReply']);
});

Route::get('/', function() {
    return response()->json([
        'sucess' => true
    ]);
});
