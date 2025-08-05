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
            
            // التحقق من أن المستخدم يمكنه الوصول للوحة التحكم
            $user = Auth::user();
            
            if ($user && $user->canAccessPanel(app(\Filament\Panel::class))) {
                // توجيه مباشر للوحة التحكم
                return redirect()->intended('/admin');
            } else {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'ليس لديك صلاحية للوصول للوحة التحكم',
                ]);
            }
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
