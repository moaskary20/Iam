<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', function () {
        $user = Auth::user();
        return view('profile', compact('user'));
    })->name('profile');
});
