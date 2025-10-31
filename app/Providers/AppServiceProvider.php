<?php

namespace App\Providers;

use App\Models\Rating;
use App\Models\Booking;
use App\Models\Job;
use App\Observers\CustomerActivityObserver;
use App\Observers\BookingObserver;
use App\Observers\JobObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS on production
        if(config('app.env') !== 'local') {
            URL::forceScheme('https');
        }

        // Handle ngrok https URLs
        if(isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
            URL::forceScheme('https');
        }

        // Custom binding for booking_token
        Route::bind('token', function ($value) {
            return \App\Models\Booking::where('booking_token', $value)->firstOrFail();
        });

        Booking::observe([CustomerActivityObserver::class, BookingObserver::class]);
        Job::observe([CustomerActivityObserver::class, JobObserver::class]);
        Rating::observe(CustomerActivityObserver::class);
    }
}
