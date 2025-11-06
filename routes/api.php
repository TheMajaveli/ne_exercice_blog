<?php

use App\Http\Controllers\Api\PostController;
use Illuminate\Support\Facades\Route;

// Routes publiques pour les posts (index et show)
Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{post}', [PostController::class, 'show']);

// Routes protégées par Sanctum pour créer, modifier et supprimer des posts
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/posts', [PostController::class, 'store']);
    Route::put('/posts/{post}', [PostController::class, 'update']);
    Route::delete('/posts/{post}', [PostController::class, 'destroy']);
});

