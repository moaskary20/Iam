<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // ✅ عرض صفحة تسجيل الدخول
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // ✅ التحقق من بيانات تسجيل الدخول
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $request->session()->regenerate();

            // تحويل للمكان المقصود أو لوحة التحكم
            return redirect()->intended('/admin');
        }

        return back()->withErrors([
            'email' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة.',
        ])->withInput();
    }

    // ✅ تسجيل الخروج
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
