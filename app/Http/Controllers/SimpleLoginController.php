<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class SimpleLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('simple-login');
    }
    
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        $credentials = $request->only('email', 'password');
        
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // ✅ توجيه مباشر بعد تسجيل الدخول بدون شرط الصلاحيات
            return redirect()->intended('/admin');
        }
        
        return back()->withErrors([
            'email' => 'بيانات تسجيل الدخول غير صحيحة',
        ]);
    }
    
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login-simple');
    }
}
