<?php

namespace App\Events;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookingCreated
{
    use Dispatchable, SerializesModels;

    public $booking;
    public $customer;
    public $actor; // User who performed the action (admin or customer user)

    /**
     * Create a new event instance.
     *
     * @param Booking $booking
     * @param Customer|null $customer
     */
    public function __construct(Booking $booking, ?Customer $customer = null, ?User $actor = null)
    {
        $this->booking = $booking;
        $this->customer = $customer;
        $this->actor = $actor; // can be null
    }
}
