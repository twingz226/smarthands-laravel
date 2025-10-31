<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyBookingController extends Controller
{
    /**
     * Display the user's bookings page.
     */
    public function index()
    {
        // Ensure the user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to view your bookings.');
        }

        // Eager load bookings and related service for the authenticated user
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'User not authenticated.');
        }
        if ($user instanceof \App\Models\User) {
            $user->load(['bookings.service']);
        }
        return view('pages.my_bookings', compact('user'));
    }
}
