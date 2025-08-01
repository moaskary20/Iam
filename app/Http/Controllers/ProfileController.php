<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('profile-edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:6|confirmed',
            'profile_photo' => 'nullable|image|max:2048',
            'id_image_path' => 'nullable|image|max:4096',
        ]);

        if ($request->hasFile('profile_photo')) {
            $profilePhotoPath = $request->file('profile_photo')->store('users', 'public');
            $user->profile_photo = $profilePhotoPath;
        }
        if ($request->hasFile('id_image_path')) {
            $idImagePath = $request->file('id_image_path')->store('users', 'public');
            $user->id_image_path = $idImagePath;
        }

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        unset($data['password']);
        unset($data['password_confirmation']);
        $user->fill($data);
        $user->save();
        return redirect()->route('profile')->with('success', 'تم تحديث البيانات بنجاح');
    }
}
