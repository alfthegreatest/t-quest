<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GameController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/game/{game}', [GameController::class, 'show']);


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

Route::get('/profile', function () {
    return view('profile');
})->middleware('auth')->name('profile');


Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/logout', function() {
    Auth::logout();
    return redirect('/');
})->name('logout');


Route::get('/admin', function () {
    return view('admin');
})->middleware('admin')->name('admin.dashboard');

Route::get('/admin/users', [UserController::class, 'index'])->middleware('admin')->name('admin.users');
Route::get('/admin/games', [GameController::class, 'index'])->middleware('admin')->name('admin.games');
Route::get('/admin/games/delete', [GameController::class, 'delete'])->middleware('admin')->name('admin.games.delete');


// Route to redirect to Google's OAuth page
Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])
    ->name('auth.google.redirect');

// Route to handle the callback from Google
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])
    ->name('auth.google.callback');