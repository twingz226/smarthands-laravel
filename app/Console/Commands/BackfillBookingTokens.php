<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use Illuminate\Support\Str;

class BackfillBookingTokens extends Command
{
    protected $signature = 'bookings:backfill-tokens';
    protected $description = 'Backfill missing booking_token values for existing bookings';

    public function handle()
    {
        $count = 0;
        Booking::whereNull('booking_token')->orWhere('booking_token', '')->chunk(100, function ($bookings) use (&$count) {
            foreach ($bookings as $booking) {
                $booking->booking_token = Str::random(32);
                $booking->save();
                $count++;
            }
        });

        $this->info("Backfilled $count bookings with missing tokens.");
    }
} 