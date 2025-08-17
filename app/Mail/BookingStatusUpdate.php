<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Booking;

class BookingStatusUpdate extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;

    /**
     * Create a new message instance.
     *
     * @param Booking $booking
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $status = ucfirst($this->booking->status);
        return $this->markdown('emails.bookings.status_update')
                    ->subject("Booking #{$this->booking->id} {$status}")
                    ->with([
                        'booking' => $this->booking,
                    ]);
    }
} 