<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    /**
     * Display the customer's profile.
     */
    public function show(Request $request)
    {
        // You can pass user data to the view if needed
        return view('customer.profile');
    }

    /**
     * Update the customer's profile.
     */
    public function update(Request $request)
    {
        $user = $request->user();

        // Validate name and email
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()->toArray()], 422);
            }
            return back()->withErrors($validator->errors())->with('open_profile_modal', true);
        }

        $validated = $validator->validated();

        // Handle password change
        if ($request->filled('current_password') || $request->filled('new_password') || $request->filled('new_password_confirmation')) {
            $passwordValidator = Validator::make($request->all(), [
                'current_password' => 'required',
                'new_password' => 'required|min:8|confirmed',
            ], [
                'new_password.confirmed' => 'The new password confirmation does not match.',
            ]);

            if ($passwordValidator->fails()) {
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'errors' => $passwordValidator->errors()->toArray()], 422);
                }
                return back()->withErrors($passwordValidator->errors())->with('open_profile_modal', true);
            }

            // Check current password
            if (!Hash::check($request->current_password, $user->password)) {
                Log::warning('Password change failed for user ' . $user->id . ' (' . $user->email . '): current_password does not match stored password');
                $error = ['current_password' => ['Current password is incorrect. Please enter your login password.']];
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'errors' => $error], 422);
                }
                return back()->withErrors($error)->with('open_profile_modal', true);
            }

            $user->password = bcrypt($request->new_password);
        }

        $user->update($validated);
        $user->save();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Profile updated successfully.']);
        }
        return back()->with('success', 'Profile updated successfully.');
    }
}
