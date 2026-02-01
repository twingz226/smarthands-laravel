<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\PublicBookingController;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/services', [PageController::class, 'services'])->name('services');
Route::get('/about', [PageController::class, 'about'])->name('about');

// Public booking routes (no authentication required)
Route::get('/booking', [PublicBookingController::class, 'create'])->name('public.bookings.create');
Route::post('/booking', [PublicBookingController::class, 'store'])->name('public.bookings.store');
Route::post('/book', [PublicBookingController::class, 'store'])->name('bookings.store.public');

// Booking availability check routes
Route::get('/booking/check-availability', [PublicBookingController::class, 'checkAvailability'])->name('booking.check.availability');
Route::post('/booking/check-timeslot', [PublicBookingController::class, 'checkTimeSlotAvailability'])->name('booking.check.timeslot');

Route::get('/contact', [PageController::class, 'contact'])->name('contact');

// Legal/Policy pages
Route::get('/privacy-policy', function () {
    $contactInfo = \App\Models\ContactInfo::first();
    $user = \Illuminate\Support\Facades\Auth::check() ? \Illuminate\Support\Facades\Auth::user() : null;
    return view('pages.privacy', compact('contactInfo', 'user'));
})->name('privacy');

Route::get('/terms-and-conditions', function () {
    $contactInfo = \App\Models\ContactInfo::first();
    $user = \Illuminate\Support\Facades\Auth::check() ? \Illuminate\Support\Facades\Auth::user() : null;
    return view('pages.terms', compact('contactInfo', 'user'));
})->name('terms');

Route::get('/cookie-policy', function () {
    $contactInfo = \App\Models\ContactInfo::first();
    $user = \Illuminate\Support\Facades\Auth::check() ? \Illuminate\Support\Facades\Auth::user() : null;
    return view('pages.cookies', compact('contactInfo', 'user'));
})->name('cookies');


Route::get('/booking/success', function() {
    return view('bookings.success');
})->name('bookings.success');

// Public booking submission route
Route::post('/bookings', [App\Http\Controllers\PublicBookingController::class, 'store'])->name('public.bookings.store');
