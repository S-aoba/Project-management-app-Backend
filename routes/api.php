<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectUserController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});


// Project
Route::apiResource('/project', ProjectController::class)->middleware(['auth:sanctum']);
Route::middleware(['auth:sanctum'])->get('/user/{user_id}/projects', [UserController::class, 'fetchUserProject']);

// ProjectUser
Route::apiResource('/project.user', ProjectUserController::class)->middleware([
    'auth:sanctum'
]);

// Task
Route::apiResource('/task', TaskController::class)->middleware(['auth:sanctum']);