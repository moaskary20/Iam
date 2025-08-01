<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FilamentRouteFix extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Register Filament login POST route manually
        Route::middleware(['web'])->group(function () {
            Route::post('/admin/login', function (Request $request) {
                $credentials = $request->validate([
                    'email' => 'required|email',
                    'password' => 'required|string',
                ]);

                if (Auth::attempt($credentials, $request->filled('remember'))) {
                    $request->session()->regenerate();
                    
                    // تسجيل الدخول بنجاح
                    return redirect()->intended('/admin');
                }

                // فشل تسجيل الدخول
                return back()->withInput($request->only('email', 'remember'))
                    ->withErrors([
                        'email' => 'بيانات الدخول المقدمة لا تطابق سجلاتنا.',
                    ]);
            })->name('filament.admin.auth.login');
        });
    }
}
