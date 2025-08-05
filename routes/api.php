<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// API لإنشاء مستخدم Admin جديد
Route::post('/create-admin-user', function (Request $request) {
    // تحقق من المفتاح السري للأمان
    if ($request->header('X-Admin-Secret') !== config('app.admin_secret', 'default-secret-key')) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    $request->validate([
        'email' => 'required|email|unique:users',
        'password' => 'required|min:8',
        'name' => 'required|string|max:255',
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
        
        if (!$adminRole) {
            return response()->json(['error' => 'Admin role not found'], 404);
        }

        // تعيين دور Admin
        $user->assignRole($adminRole);

        // إنشاء محفظة للمستخدم
        $user->wallet()->create([
            'balance' => 0
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Admin user created successfully',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => 'admin',
                'wallet_balance' => 0
            ]
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Failed to create user: ' . $e->getMessage()
        ], 500);
    }
});
