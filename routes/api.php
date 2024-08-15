<?php

use App\Http\Controllers\ProjectController;
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
Route::middleware(['auth:sanctum'])->post('/project/{project_id}/members', [ProjectController::class, 'inviteAsMember']);
Route::middleware(['auth:sanctum'])->delete('/project/{project_id}/member/{user_id}', [ProjectController::class, 'removeMember']);
Route::middleware(['auth:sanctum'])->put('/project/{project_id}/user/{user_id}/role', [ProjectController::class, 'changeOfRole']);

// Task
Route::apiResource('/task', TaskController::class)->middleware(['auth:sanctum']);