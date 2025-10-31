<?php

namespace App\Events;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookingCancelled
{
    use Dispatchable, SerializesModels;

    public $booking;
    public $customer;
    public $reason;
    public $actor; // User who performed the action (admin or customer user)

    /**
     * Create a new event instance.
     *
     * @param Booking $booking
     * @param Customer $customer
     * @param string|null $reason
     */
    public function __construct(Booking $booking, Customer $customer, $reason = null, ?User $actor = null)
    {
        $this->booking = $booking;
        $this->customer = $customer;
        $this->reason = $reason;
        $this->actor = $actor; // can be null
    }
}
