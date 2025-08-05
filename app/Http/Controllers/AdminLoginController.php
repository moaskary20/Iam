<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        // يمكنك تخصيص الحارس (guard) إذا كان لديك حارس خاص بالأدمن
        if (Auth::attempt($credentials)) {
            return redirect()->intended('/admin/dashboard');
        }
        return back()->withErrors(['email' => 'بيانات الدخول غير صحيحة'])->withInput();
    }
}
