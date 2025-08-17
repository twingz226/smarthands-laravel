<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Service;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingStatusUpdate;

class BookingController extends Controller
{
    /**
     * Store a new booking.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'cleaning_date' => 'required|date|after:now',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            // Create the booking
            $booking = Booking::create([
                'user_id' => auth()->id(),
                'service_id' => $validated['service_id'],
                'cleaning_date' => $validated['cleaning_date'],
                'special_instructions' => $validated['notes'] ?? null,
                'status' => Booking::STATUS_PENDING
            ]);

            // Log the activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'description' => 'Created new booking for ' . $booking->service->name
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Booking created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking creation failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create booking. Please try again.');
        }
    }

    /**
     * Reschedule a booking.
     */
    public function reschedule(Request $request, Booking $booking)
    {
        // Validate user owns the booking
        if ($booking->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $validated = $request->validate([
            'cleaning_date' => 'required|date|after:now',
        ]);

        try {
            DB::beginTransaction();

            $oldDate = $booking->cleaning_date->format('F j, Y g:i A');
            $booking->update([
                'cleaning_date' => $validated['cleaning_date'],
                'status' => Booking::STATUS_PENDING // Reset to pending as it needs to be confirmed again
            ]);

            // Log the activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'description' => "Rescheduled booking from {$oldDate} to " . 
                    Carbon::parse($validated['cleaning_date'])->format('F j, Y g:i A')
            ]);

            // Send email notification for rescheduling
            if (config('mail.enabled')) {
                Mail::to($booking->customer->email)
                    ->send(new BookingStatusUpdate($booking));
            }

            DB::commit();

            return redirect()->back()->with('success', 'Booking rescheduled successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking reschedule failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to reschedule booking. Please try again.');
        }
    }

    /**
     * Cancel a booking.
     */
    public function cancel(Booking $booking)
    {
        // Validate user owns the booking
        if ($booking->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        try {
            DB::beginTransaction();

            $booking->update([
                'status' => Booking::STATUS_CANCELLED
            ]);

            // Log the activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'description' => 'Cancelled booking for ' . $booking->service->name . 
                    ' scheduled for ' . $booking->cleaning_date->format('F j, Y g:i A')
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Booking cancelled successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking cancellation failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to cancel booking. Please try again.');
        }
    }
} 