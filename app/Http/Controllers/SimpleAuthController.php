<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class SimpleAuthController extends Controller
{
    public function showLogin()
    {
        return view('simple-login');
    }

    public function quickLogin(Request $request)
    {
        $email = $request->input('email', 'mo.askary@gmail.com');
        $password = $request->input('password', 'password');

        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            return redirect('/admin');
        }

        return back()->with('error', 'خطأ في البيانات');
    }

    public function instantLogin()
    {
        $user = User::where('email', 'mo.askary@gmail.com')->first();
        
        if ($user) {
            Auth::login($user);
            return redirect('/admin');
        }

        return 'المستخدم غير موجود';
    }
}
