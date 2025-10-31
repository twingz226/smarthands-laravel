<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Show the admin profile
     */
    public function show()
    {
        $user = Auth::user();
        return view('admin.profile.show', compact('user'));
    }

    /**
     * Show the form for editing the admin profile
     */
    public function edit()
    {
        $user = Auth::user();
        return view('admin.profile.edit', compact('user'));
    }

    /**
     * Update the admin profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ], [
            'current_password.required_with' => 'Current password is required when setting a new password.',
            'new_password.confirmed' => 'Password confirmation does not match.',
            'profile_photo.image' => 'The file must be an image.',
            'profile_photo.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',
            'profile_photo.max' => 'The image may not be greater than 2MB.',
        ]);

        // Update basic information
        $user->name = $request->name;
        // Note: email, phone, and address are no longer editable via this form.

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($user->profile_photo && Storage::disk('public')->exists('profile-photos/' . $user->profile_photo)) {
                Storage::disk('public')->delete('profile-photos/' . $user->profile_photo);
            }

            // Store new photo
            $photo = $request->file('profile_photo');
            $filename = 'admin_' . $user->id . '_' . time() . '.' . $photo->getClientOriginalExtension();
            $photo->storeAs('profile-photos', $filename, 'public');
            $user->profile_photo = $filename;
        }

        // Update password if provided
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return redirect()->route('admin.profile.show')
            ->with('success', 'Profile updated successfully!');
    }
} 