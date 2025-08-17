<?php

namespace App\Observers;

use App\Models\Booking;
use App\Models\Job;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class BookingObserver
{
    /**
     * Handle the Booking "updated" event.
     */
    public function updated(Booking $booking): void
    {
        Log::info('BookingObserver: Booking updated', [
            'booking_id' => $booking->id,
            'status_changed' => $booking->wasChanged('status'),
            'old_status' => $booking->getOriginal('status'),
            'new_status' => $booking->status
        ]);

        // If the booking was just confirmed, create a job
        if ($booking->wasChanged('status') && 
            $booking->status === Booking::STATUS_CONFIRMED) {
            
            Log::info('BookingObserver: Creating job for confirmed booking', [
                'booking_id' => $booking->id
            ]);

            try {
                DB::beginTransaction();

                // Create a new job
                $job = Job::create([
                    'customer_id' => $booking->customer_id,
                    'service_id' => $booking->service_id,
                    'scheduled_date' => $booking->cleaning_date,
                    'status' => Job::STATUS_PENDING,
                    'address' => $booking->customer->address,
                    'special_instructions' => $booking->special_instructions
                ]);

                Log::info('BookingObserver: Job created successfully', [
                    'job_id' => $job->id,
                    'booking_id' => $booking->id
                ]);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('BookingObserver: Failed to create job', [
                    'booking_id' => $booking->id,
                    'error' => $e->getMessage()
                ]);
                throw $e;
            }
        }
    }
} 