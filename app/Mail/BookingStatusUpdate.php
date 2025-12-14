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
    public $rescheduleReason;

    /**
     * Create a new message instance.
     *
     * @param Booking $booking
     * @param string|null $rescheduleReason
     */
    public function __construct(Booking $booking, $rescheduleReason = null)
    {
        $this->booking = $booking;
        $this->rescheduleReason = $rescheduleReason;
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
                    ->subject("Booking {$status}")
                    ->with([
                        'booking' => $this->booking,
                        'rescheduleReason' => $this->rescheduleReason,
                    ]);
    }
} 