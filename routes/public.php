<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\PublicBookingController;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::post('/', [PageController::class, 'store'])->name('public.bookings.store');
Route::get('/services', [PageController::class, 'services'])->name('services');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');

Route::get('/booking', [PublicBookingController::class, 'create'])->name('public.bookings.create');
Route::post('/booking', [PublicBookingController::class, 'store'])->name('public.bookings.store.alt');

Route::post('/book', [BookingController::class, 'store'])->name('bookings.store');
Route::get('/booking/success', function() {
    return view('bookings.success');
})->name('bookings.success');
