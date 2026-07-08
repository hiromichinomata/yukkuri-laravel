<?php

use App\Http\Controllers\Api\AuthTokenController;
use App\Http\Controllers\Api\PostController;
use Illuminate\Support\Facades\Route;

Route::post('/tokens', [AuthTokenController::class, 'store']);

Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{id}', [PostController::class, 'show'])->whereNumber('id');

Route::middleware('auth:sanctum')->group(function () {
    Route::delete('/tokens', [AuthTokenController::class, 'destroy']);
    Route::post('/posts', [PostController::class, 'store']);
    Route::put('/posts/{post}', [PostController::class, 'update']);
    Route::patch('/posts/{post}', [PostController::class, 'update']);
    Route::delete('/posts/{post}', [PostController::class, 'destroy']);
});
