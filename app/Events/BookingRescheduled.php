<?php

namespace App\Events;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookingRescheduled
{
    use Dispatchable, SerializesModels;

    public $booking;
    public $customer;
    public $oldDate;
    public $newDate;
    public $actor; // User who performed the action (admin or customer user)

    /**
     * Create a new event instance.
     *
     * @param Booking $booking
     * @param Customer $customer
     * @param string $oldDate
     * @param string $newDate
     */
    public function __construct(Booking $booking, Customer $customer, $oldDate, $newDate, ?User $actor = null)
    {
        $this->booking = $booking;
        $this->customer = $customer;
        $this->oldDate = $oldDate;
        $this->newDate = $newDate;
        $this->actor = $actor; // can be null
    }
}
