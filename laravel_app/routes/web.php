<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TopController;
use App\Http\Controllers\TourController;
use App\Http\Controllers\PostController;


Route::get('/', function () {
    return view('welcome');
});

Route::prefix('auth')->group(function () {
    Route::get('/register', [AuthController::class, 'registerForm'])->name('register.form');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login.form');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});


Route::middleware('auth')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/update', [AuthController::class, 'updateForm'])->name('profile.edit');
        Route::put('/update', [AuthController::class, 'update'])->name('profile.update');
    });
    Route::prefix('top')->group(function () {
        Route::get('/', [TopController::class, 'top'])->name('index');
    });
    Route::prefix('tour')->group(function () {
        Route::get('/', [TourController::class, 'tourForm'])->name('tour.form');
        Route::post('/', [TourController::class, 'tourStore'])->name('tour.store');
        Route::get('/select', [TourController::class, 'tourSelect'])->name('tour.select');
    });
    Route::prefix('posts')->group(function () {
        Route::get('/', [PostController::class, 'posts'])->name('post.index');
    });
    Route::prefix('post')->group(function () {
        Route::get('/', [PostController::class, 'postFrom'])->name('post.form');
        Route::post('/', [PostController::class, 'postStore'])->name('post.store');
    });
});




