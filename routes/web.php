<?php

use App\Http\Controllers\GameController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LocationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('welcome'))->name('welcome');


Route::middleware('guest')->group(function () {
    Route::get('/login', fn() => view('login'))->name('login');

    // Google OAuth
    Route::prefix('auth/google')->name('auth.google.')->group(function () {
        Route::get('/redirect', [GoogleAuthController::class, 'redirect'])->name('redirect');
        Route::get('/callback', [GoogleAuthController::class, 'callback'])->name('callback');
    });
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');
    Route::get('/profile', fn() => view('profile'))->name('profile');
    Route::get('/logout', fn() => Auth::logout() ?: redirect('/'))->name('logout');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', fn() => view('admin'))->name('dashboard');

    Route::get('/users', [UserController::class, 'index'])->name('users');

    Route::prefix('games')->name('games.')->group(function () {
        Route::get('/', [GameController::class, 'index'])->name('index');
        Route::get('/delete', [GameController::class, 'delete'])->name('delete');
    });

    Route::prefix('locations')->name('locations.')->group(function() {
        Route::get('/', [LocationController::class, 'index'])->name('index');
    });
});

Route::get('/game/{game}', [GameController::class, 'play'])->name('game.play');
Route::get('/game/{game}/detail', [GameController::class, 'show'])->name('game.detail');
Route::get('game/{game}/edit', [GameController::class, 'edit'])->middleware('auth', 'admin')->name('game.edit');