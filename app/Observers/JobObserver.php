<?php

namespace App\Observers;

use App\Models\Job;
use App\Models\Booking;
use App\Mail\JobCompleted;
use App\Mail\BookingStatusUpdate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class JobObserver
{
    /**
     * Handle the Job "updated" event.
     */
    public function updated(Job $job): void
    {
        Log::info('JobObserver: Starting job updated event handler', [
            'job_id' => $job->id,
            'old_status' => $job->getOriginal('status'),
            'new_status' => $job->status,
            'is_dirty' => $job->isDirty('status'),
            'was_status_changed' => $job->wasChanged('status')
        ]);

        // Check if status was changed to completed
        if ($job->wasChanged('status') && $job->status === Job::STATUS_COMPLETED) {
            try {
                DB::beginTransaction();

                // Find the corresponding booking
                $booking = Booking::where('customer_id', $job->customer_id)
                    ->where('service_id', $job->service_id)
                    ->whereIn('status', [Booking::STATUS_CONFIRMED, Booking::STATUS_PENDING])
                    ->first();

                if ($booking) {
                    Log::info('JobObserver: Updating booking status to completed', [
                        'job_id' => $job->id,
                        'booking_id' => $booking->id,
                        'old_status' => $booking->status
                    ]);

                    $booking->update([
                        'status' => Booking::STATUS_COMPLETED
                    ]);

                    // Send email notification for completed booking
                    if (config('mail.enabled')) {
                        Mail::to($booking->customer->email)
                            ->send(new BookingStatusUpdate($booking));
                        
                        // Also send job completed email with rating link
                        Mail::to($booking->customer->email)
                            ->send(new JobCompleted($job));
                    }

                    Log::info('JobObserver: Booking status updated successfully', [
                        'job_id' => $job->id,
                        'booking_id' => $booking->id,
                        'new_status' => $booking->status
                    ]);
                } else {
                    Log::info('JobObserver: No corresponding booking found for the completed job', [
                        'job_id' => $job->id,
                        'customer_id' => $job->customer_id,
                        'service_id' => $job->service_id
                    ]);
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('JobObserver: Failed to update booking status', [
                    'job_id' => $job->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                throw $e;
            }
        }
    }
}