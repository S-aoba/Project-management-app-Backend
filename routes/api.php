<?php

use App\Http\Controllers\InviteCodeController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectUserController;
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

Route::middleware(['auth:sanctum'])->patch('/tasks/{task}/assigned_user_id', [TaskController::class,'changeAssignedUserId']);
    
Route::middleware(['auth:sanctum'])->get('/user/projects', [UserController::class, 'fetchUserProject']);


Route::middleware(['auth:sanctum'])->post('/invite_code', [InviteCodeController::class, 'store']);
Route::middleware(['auth:sanctum'])->post('/invite_code/{invite_code}', [InviteCodeController::class, 'show']);

Route::middleware(['auth:sanctum'])->delete('/projects/{project}/users/{user}', [ProjectUserController::class, 'destroy']);

Route::middleware(['auth:sanctum'])->patch('/projects/{project}/users/{user}/role',[ProjectUserController::class, 'update']);