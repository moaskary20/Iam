<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class FilamentAuthController extends Controller
{
    public function login(Request $request)
    {
        if ($request->isMethod('GET')) {
            // إعادة توجيه لصفحة تسجيل الدخول الأصلية
            return redirect('/admin/login');
        }

        // معالجة POST
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/admin');
        }

        return back()->withInput($request->only('email', 'remember'))
            ->withErrors([
                'email' => 'بيانات الدخول المقدمة لا تطابق سجلاتنا.',
            ]);
    }
    
    public function showLogin()
    {
        return view('filament-login');
    }
}
