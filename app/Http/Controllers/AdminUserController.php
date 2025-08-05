<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function showCreateForm()
    {
        return view('admin.create-user');
    }

    public function createUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);

        try {
            // إنشاء المستخدم
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'email_verified_at' => now(),
            ]);

            // الحصول على دور admin
            $adminRole = Role::where('name', 'admin')->first();
            
            if ($adminRole) {
                $user->assignRole($adminRole);
            }

            // إنشاء محفظة للمستخدم
            $user->wallet()->create([
                'balance' => 0
            ]);

            return redirect()->back()->with('success', 'تم إنشاء المستخدم بنجاح!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'فشل في إنشاء المستخدم: ' . $e->getMessage());
        }
    }
}
