<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/tasks', [TaskController::class,'index']);
    Route::get('/tasks/{id}', [TaskController::class,'show']);
    Route::post('/tasks', [TaskController::class,'store']);
    Route::put('/tasks/{id}', [TaskController::class,'update']);
    Route::delete('/tasks/{id}', [TaskController::class,'destroy']);
    Route::get('/tasks/status/{status}', [TaskController::class,'filterByStatus']);

    Route::post('/user/logout', [AuthController::class,'logout']);
});



 Route::post('/user/register', [AuthController::class,'register']);
 Route::post('/user/login', [AuthController::class,'login']);
 Route::post('/user/forgot-password', [AuthController::class,'forgotPassword']);
