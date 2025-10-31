<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class LoginController extends Controller
{
    // Show login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Handle login form submission
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Get user by email
        $user = User::where('email', $request->email)->first();

        // Check if user exists
        if (!$user) {
            // Log failed login attempt
            Log::warning('Failed login attempt: User not found', [
                'email' => $request->email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            throw ValidationException::withMessages([
                'email' => 'The provided email does not exist in our records.',
            ]);
        }

        // Check if account is locked
        if ($user->isLocked()) {
            $lockoutTime = $user->lockout_time ? $user->lockout_time->diffForHumans() : 'permanently';
            throw ValidationException::withMessages([
                'email' => "Account is locked. Please try again {$lockoutTime} or contact support.",
            ]);
        }

        // Check password
        if (!Hash::check($request->password, $user->password)) {
            // Increment failed login attempts
            $user->increment('failed_login_attempts');
            $failedAttempts = $user->failed_login_attempts + 1;

            // Log failed login attempt
            Log::warning('Failed login attempt: Invalid password', [
                'email' => $request->email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'failed_attempts' => $failedAttempts
            ]);

            // Lock account after 5 failed attempts
            if ($failedAttempts >= 5) {
                $user->update([
                    'is_locked' => true,
                    'lockout_time' => now()->addMinutes(15) // 15 minute lockout
                ]);

                Log::alert('Account locked due to multiple failed login attempts', [
                    'email' => $request->email,
                    'ip' => $request->ip(),
                    'failed_attempts' => $failedAttempts
                ]);
            }

            throw ValidationException::withMessages([
                'password' => 'The provided password is incorrect.',
            ]);
        }

        // Reset failed attempts on successful login
        $user->update([
            'failed_login_attempts' => 0,
            'is_locked' => false,
            'lockout_time' => null,
            'last_login_at' => now(),
            'last_login_ip' => $request->ip()
        ]);

        // If credentials are correct, attempt login
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            // Log successful login
            Log::info('Successful login', [
                'email' => $request->email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            if ($user->isAdmin()) {
                return redirect()->intended('/admin/dashboard');
            } elseif ($user->isCustomer()) {
                return redirect()->intended('/');
            }
            return redirect()->intended('/');
        }

        // Fallback error (shouldn't normally reach here)
        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ])->onlyInput('email');
    }

    // Logout function
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}