<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LikeController;
use App\Http\Middleware\EnsureAuthenticated;
use Illuminate\Support\Facades\Route;

// Register our custom authentication middleware
Route::aliasMiddleware('ensure.auth', EnsureAuthenticated::class);

// Public Routes - Still accessible when not logged in
// Homepage
Route::get('/', [PostController::class, 'index'])->name('home');

// Profile Routes - Protected
Route::middleware(['auth', 'ensure.auth'])->group(function () {
    // Post Routes
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('/posts/search', [PostController::class, 'search'])->name('posts.search');
    Route::get('/posts/{slug}', [PostController::class, 'show'])->name('posts.show');
    Route::get('/posts/create/new', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{slug}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{slug}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{slug}', [PostController::class, 'destroy'])->name('posts.destroy');
    
    // Comment Routes
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::post('/comments/{comment}/reply', [CommentController::class, 'reply'])->name('comments.reply');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    
    // Like Routes
    Route::post('/posts/{post}/like', [LikeController::class, 'toggleLike'])->name('posts.like');
    
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
