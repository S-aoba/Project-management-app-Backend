<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

// Project CRUD
// Read: All Project, Detail Project, User Projects

Route::apiResource('/project', ProjectController::class)->middleware(['auth:sanctum']);
Route::middleware(['auth:sanctum'])->get('/user/{user_id}/projects', [UserController::class, 'fetchUserProject']);