<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('/projects', ProjectController::class)->only([
    'store',
    'show',
    'update',
    'destroy', 
    ])->middleware(['auth:sanctum']);
    
Route::apiResource('/tasks', TaskController::class)->only([
    'store',
    'update',
    'destroy'
    ])->middleware(['auth:sanctum']);
    
Route::middleware(['auth:sanctum'])->get('/user/projects', [UserController::class, 'fetchUserProject']);
    