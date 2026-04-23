<?php

use App\Http\Controllers\PublicBookingController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PublicRatingController;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\Customer\JobController;
use App\Models\Job;
use Illuminate\Support\Facades\DB;



// Booking form POST route


// Booking success page
Route::get('/booking/success', function () {
    return view('bookings.success');
})->name('bookings.success');



// Home route
Route::get('/', function () {
    return view('home');
})->name('home');

// Contact Message Us form submission
Route::post('/contact/message', [\App\Http\Controllers\ContactMessageController::class, 'store'])->middleware('recaptcha')->name('contact.message.store');

// Authentication routes (excluding password reset routes to define them manually)
Auth::routes(['reset' => false, 'verify' => false]);

// Custom Password Reset Routes
Route::prefix('password')->name('password.')->group(function () {
    // Show the password reset request form
    Route::get('/reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])
        ->name('request')
        ->middleware('guest');
        
    // Handle password reset link email
    Route::post('/email', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])
        ->name('email')
        ->middleware('guest');
        
    // Show the password reset form (with token and email)
    Route::get('/reset/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])
        ->name('reset')
        ->middleware('guest')
        ->where('token', '.*');
        
    // Handle the password reset form submission
    Route::post('/reset', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])
        ->name('update')
        ->middleware('guest');
});

// Custom registration route with throttling
Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])
    ->middleware('guest')
    ->name('register');
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register'])
    ->middleware(['guest', 'throttle:5,1'])
    ->name('register.store');

// Phone Registration
Route::post('/register/phone', [App\Http\Controllers\Auth\RegisterController::class, 'registerWithPhone'])
    ->middleware(['guest', 'throttle:3,1'])
    ->name('register.phone');

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

// Admin Contact Messages management
Route::middleware(['web', 'auth', 'role:admin'])->prefix('admin/contact-messages')->name('admin.contact_messages.')->group(function () {
    Route::get('/', [\App\Http\Controllers\ContactMessageController::class, 'index'])->name('index');
    Route::get('/{id}', [\App\Http\Controllers\ContactMessageController::class, 'show'])->name('show');
    Route::delete('/{id}', [\App\Http\Controllers\ContactMessageController::class, 'destroy'])->name('destroy');
    Route::post('/mark-all-read', [\App\Http\Controllers\ContactMessageController::class, 'markAllRead'])->name('mark_all_read');
});


Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/check-slots', [PageController::class, 'checkSlots'])->name('check.slots');
Route::get('/fully-booked-dates', [PageController::class, 'fullyBookedDates'])->name('fully.booked.dates');

// Customer Portal Routes
Route::middleware(['auth'])->group(function () {
    // Customer booking management routes
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::post('/{booking:booking_token}/reschedule', [App\Http\Controllers\BookingController::class, 'reschedule'])
            ->name('reschedule')
            ->withTrashed();

        Route::post('/{booking:booking_token}/cancel', [App\Http\Controllers\BookingController::class, 'cancelBooking'])
            ->name('cancel')
            ->withTrashed();

        Route::patch('/{booking:booking_token}/confirm', [App\Http\Controllers\BookingController::class, 'confirmBooking'])
            ->name('confirm')
            ->withTrashed();
    });
});

// Customer Routes
Route::middleware(['auth'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/my-bookings', [App\Http\Controllers\MyBookingController::class, 'index'])->name('my_bookings');
    // Feedback & Ratings
    Route::get('/feedback', [App\Http\Controllers\Customer\FeedbackController::class, 'index'])->name('feedback');
    Route::get('/feedback/create/{booking}', [App\Http\Controllers\Customer\FeedbackController::class, 'create'])->name('feedback.create');
    Route::post('/feedback/store/{booking}', [App\Http\Controllers\Customer\FeedbackController::class, 'store'])->name('feedback.store');
    Route::get('/feedback/edit/{feedback}', [App\Http\Controllers\Customer\FeedbackController::class, 'edit'])->name('feedback.edit');
    Route::put('/feedback/update/{feedback}', [App\Http\Controllers\Customer\FeedbackController::class, 'update'])->name('feedback.update');
    Route::delete('/feedback/delete/{feedback}', [App\Http\Controllers\Customer\FeedbackController::class, 'destroy'])->name('feedback.delete');

    // Customer Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [App\Http\Controllers\NotificationController::class, 'index'])->name('index');
        Route::get('/unread-count', [App\Http\Controllers\NotificationController::class, 'unreadCount'])->name('unread_count');
        Route::post('/{notification}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('mark_all_read');
    });

    // Support/Contact
    Route::get('/support', function() {
        return view('customer.support');
    })->name('support');
    Route::get('/tickets', function() {
        return view('customer.tickets');
    })->name('tickets');

    // Profile
    Route::get('/profile', [App\Http\Controllers\Customer\ProfileController::class, 'show'])->name('profile');
    Route::post('/profile', [App\Http\Controllers\Customer\ProfileController::class, 'update'])->name('customer.profile.update');
    Route::get('/change-password', [App\Http\Controllers\Customer\ProfileController::class, 'changePasswordForm'])->name('change_password');
    Route::post('/change-password', [App\Http\Controllers\Customer\ProfileController::class, 'changePassword'])->name('change_password.submit');

    // Invoice Download (dummy route)
    Route::get('/invoice/download/{booking}', function($booking) {
        // Download logic here
        return back();
    })->name('invoice.download');

    // Job Rating Routes
    Route::get('/jobs/{job}/rate', [App\Http\Controllers\Admin\JobController::class, 'showRatingForm'])->name('jobs.rate');
    Route::post('/jobs/{job}/rate', [App\Http\Controllers\Admin\JobController::class, 'submitRating'])->name('jobs.submit-rating');
});

// Google OAuth routes removed to disable social login

// Public Rating Routes (use a unique parameter name to avoid global 'token' binding)
Route::get('/rate/{ratingToken}', [PublicRatingController::class, 'showForm'])->name('public.rating.form');
Route::post('/rate/{ratingToken}', [PublicRatingController::class, 'submitRating'])->name('public.rating.submit');

// Booking view route
Route::get('/booking/{booking}', function(\App\Models\Booking $booking) {
    return redirect()->route('bookings.show', $booking);
})->name('booking.view');

// Customer Feedback Routes
Route::get('/feedback/{token}', [FeedbackController::class, 'show'])->name('public.feedback.show');
Route::post('/feedback/{token}', [FeedbackController::class, 'store'])->name('feedback.store');

