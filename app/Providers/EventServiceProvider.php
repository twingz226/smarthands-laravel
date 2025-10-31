<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\BookingCreated;
use App\Events\BookingCancelled;
use App\Events\BookingRescheduled;
use App\Events\NewCustomerRegistered;
use App\Listeners\SendBookingCreatedNotification;
use App\Listeners\SendBookingCancelledNotification;
use App\Listeners\SendBookingRescheduledNotification;
use App\Listeners\SendNewCustomerNotification;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        BookingCreated::class => [
            SendBookingCreatedNotification::class,
        ],
        BookingCancelled::class => [
            SendBookingCancelledNotification::class,
        ],
        BookingRescheduled::class => [
            SendBookingRescheduledNotification::class,
        ],
        NewCustomerRegistered::class => [
            SendNewCustomerNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
