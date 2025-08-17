<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PageController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\PublicRatingController;
use Illuminate\Support\Facades\Mail;

// Authentication routes
Auth::routes();

// Logout route
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// Load route groups
require __DIR__ . '/public.php';
require __DIR__ . '/admin.php';

Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/check-slots', [PageController::class, 'checkSlots'])->name('check.slots');

// Customer Portal Routes
Route::middleware(['auth'])->group(function () {
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::post('/', 'App\Http\Controllers\BookingController@store')->name('store');
        Route::put('/{booking}/reschedule', 'App\Http\Controllers\BookingController@reschedule')->name('reschedule');
        Route::put('/{booking}/cancel', 'App\Http\Controllers\BookingController@cancel')->name('cancel');
    });
});

// Customer Routes
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    // Job Rating Routes
    Route::get('/jobs/{job}/rate', [JobController::class, 'showRatingForm'])->name('jobs.rate');
    Route::post('/jobs/{job}/rate', [JobController::class, 'submitRating'])->name('jobs.submit-rating');
});

// Public Rating Routes
Route::get('/rate/{token}', [PublicRatingController::class, 'showForm'])->name('public.rating.form');
Route::post('/rate/{token}', [PublicRatingController::class, 'submitRating'])->name('public.rating.submit');

