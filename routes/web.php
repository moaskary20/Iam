<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// ✅ عرض صفحة تسجيل الدخول (GET)
Route::get('/login', [AuthController::class, 'showLoginForm'])
    ->name('login')
    ->middleware('guest');

// ✅ معالجة تسجيل الدخول (POST)
Route::post('/login', [AuthController::class, 'login'])
    ->name('login.post')
    ->middleware('guest');

// ✅ تسجيل الخروج
Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// ✅ الصفحة الرئيسية بعد تسجيل الدخول
Route::get('/', function () {
    return redirect('/admin'); // تحويل مباشر للوحة التحكم
})->middleware('auth');
