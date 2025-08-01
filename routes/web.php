

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/profile', function () {
    $user = Auth::user();
    return view('profile', compact('user'));
})->middleware(['auth', 'verified'])->name('profile');

Route::get('/profile/edit', [ProfileController::class, 'edit'])->middleware('auth')->name('profile.edit');
Route::post('/profile/update', [ProfileController::class, 'update'])->middleware('auth')->name('profile.update');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/wallet', function () {
    return view('wallet');
})->middleware(['auth', 'verified'])->name('wallet');

Route::get('/market', function () {
    return view('market');
})->middleware(['auth', 'verified'])->name('market');


