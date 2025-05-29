<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Example seed data
        Booking::create([
            'customer_id' => 1,
            'service_id' => 1,
            'user_id' => 1,        // user who created the booking (e.g. admin)
            'cleaner_id' => 2,     // assigned cleaner
            'cleaning_date' => Carbon::now()->addDays(2),
            'duration' => 2,       // 2 hours
            'price' => 150.00,
            'status' => Booking::STATUS_PENDING,
            'special_instructions' => 'Please focus on the kitchen area.',
            'admin_notes' => null,
            'booking_token' => Str::upper(Str::random(10)),
        ]);

        Booking::create([
            'customer_id' => 2,
            'service_id' => 2,
            'user_id' => 1,
            'cleaner_id' => 3,
            'cleaning_date' => Carbon::now()->addDays(5),
            'duration' => 3,
            'price' => 200.00,
            'status' => Booking::STATUS_CONFIRMED,
            'special_instructions' => 'Use eco-friendly products.',
            'admin_notes' => 'Customer prefers morning appointments.',
            'booking_token' => Str::upper(Str::random(10)),
        ]);

        Booking::create([
            'customer_id' => 3,
            'service_id' => 1,
            'user_id' => 2,
            'cleaner_id' => 2,
            'cleaning_date' => Carbon::now()->addDays(1),
            'duration' => 1,
            'price' => 75.00,
            'status' => Booking::STATUS_CANCELLED,
            'special_instructions' => null,
            'admin_notes' => 'Customer cancelled due to schedule conflict.',
            'booking_token' => Str::upper(Str::random(10)),
        ]);
    }
}
