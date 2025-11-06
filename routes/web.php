<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('posts.index');
});

Route::get('/dashboard', function () {
    return redirect()->route('posts.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Route publique pour la liste des posts
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');

// Routes protégées pour créer, modifier et supprimer des posts
Route::resource('posts', PostController::class)
    ->middleware(['auth'])
    ->except(['index', 'show']);

// Route publique pour afficher un post (doit être après le resource pour éviter les conflits)
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
