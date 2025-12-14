<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Service;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Mail\BookingStatusUpdate;
use Illuminate\Support\Str;
use App\Models\Job;
use App\Models\DisabledDate;

class BookingController extends Controller
{
    public function create()
    {
        $services = Service::all();
        return view('bookings.create', compact('services'));
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You need to be logged in to make a booking.');
        }

        $userId = Auth::id();
        if (!$userId) {
            Log::warning('Attempted booking without valid user ID.');
            return redirect()->route('login')->with('error', 'Authentication error. Please log in again.');
        }

        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'cleaning_date' => 'required|date|after_or_equal:today',
            'notes' => 'nullable|string|max:500',
            'client_timezone_offset' => 'required|integer',
        ]);

        try {
            DB::beginTransaction();

            $booking = new Booking([
                'user_id' => $userId,
                'service_id' => $validated['service_id'],
                'customer_name' => $request->user()->name, // Assuming authenticated user's name
                'customer_email' => $request->user()->email, // Assuming authenticated user's email
                'customer_contact' => $request->user()->phone, // Assuming authenticated user's phone
                'customer_address' => $request->user()->address, // Assuming authenticated user's address
            ]);

            $booking->cleaning_date = Carbon::parse($validated['cleaning_date'])
                                            ->addMinutes((int) $request->input('client_timezone_offset')) // Add offset to get UTC equivalent
                                            ->setTimezone(config('app.timezone')); // Convert to app's timezone for storage

            if ($booking->cleaning_date->isPast()) {
                return redirect()->back()->with('error', 'Cannot book a cleaning in the past.');
            }

            $booking->special_instructions = $validated['notes'] ?? null;
            $booking->status = Booking::STATUS_PENDING;
            $booking->booking_token = Str::random(32);
            $booking->save();

            $booking->load('service'); // Ensure service relationship is loaded
            ActivityLog::create([
                'user_id' => Auth::id(),
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

    public function cancelBooking(Request $request, $bookingToken)
     {
        try {
            $booking = Booking::where('booking_token', $bookingToken)->firstOrFail();
            $booking->load('user', 'service');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found.'
            ], 404);
        }

        // If the booking is already cancelled or soft-deleted, it cannot be cancelled again.
        if ($booking->status === Booking::STATUS_CANCELLED || $booking->trashed()) {
            return response()->json([
                'success' => false,
                'message' => 'This booking has already been cancelled.'
            ], 400);
        }

        if ($booking->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.'
            ], 403);
        }

        try {
            DB::beginTransaction();

            $booking->update([
                'status' => Booking::STATUS_CANCELLED
            ]);

            // If a job exists for this booking, cancel it as well
            if ($booking->job) {
                $booking->job->update(['status' => Job::STATUS_CANCELLED]);
            }

            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => 'Cancelled booking for ' . $booking->service->name . 
                    ' scheduled for ' . $booking->cleaning_date->format('F j, Y g:i A')
            ]);

            if (config('mail_settings.enabled', false) && $booking->user && $booking->user->email) {
                Mail::to($booking->user->email)
                    ->queue(new BookingStatusUpdate($booking));
            }

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Booking cancelled successfully!']);
            } else {
                return redirect()->route('customer.my_bookings')->with('success', 'Booking cancelled successfully!');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking cancellation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel booking. Please try again.'
            ], 500);
        }
    }

    public function reschedule(Request $request, $bookingToken)
    {

            try {
            $booking = Booking::where('booking_token', $bookingToken)->firstOrFail();
            $booking->load('user', 'service');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found.'
            ], 404);
        }


        // If the booking is soft-deleted, it cannot be rescheduled.
        if ($booking->trashed()) {
            return response()->json([
                'success' => false,
                'message' => 'This booking has been cancelled and cannot be rescheduled.'
            ], 400);
        }

        // Validate user owns the booking, or if it's a guest booking, allow rescheduling.
        // If the booking has a user_id, ensure it matches the authenticated user.
        if (!is_null($booking->user_id) && $booking->user_id !== Auth::id()) {
            // For API calls, return JSON; for web routes, redirect.
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action.'
                ], 403);
            } else {
                return redirect()->route('customer.my_bookings')->with('error', 'Unauthorized action.');
            }
        }

        try {
            $validated = $request->validate([
                'new_cleaning_date' => 'required|date_format:Y-m-d H:i|after_or_equal:today',
                'reason' => 'nullable|string|max:1000',
            ]);
            
            // Check if customer has reached reschedule limit (default 3)
            $rescheduleLimit = 3;
            if ($booking->hasReachedRescheduleLimit($rescheduleLimit)) {
                return response()->json([
                    'success' => false,
                    'message' => "You have reached the maximum number of reschedules ({$rescheduleLimit}) for this booking."
                ], 422);
            }
            
            // Debug logging
            Log::info('Reschedule request data:', [
                'all_request_data' => $request->all(),
                'validated_data' => $validated,
                'reason_value' => $request->input('reason'),
                'reason_in_validated' => $validated['reason'] ?? 'NOT_SET',
                'current_reschedule_count' => $booking->customer_reschedule_count,
                'reschedule_limit' => $rescheduleLimit,
                'remaining_reschedules' => $booking->getRemainingReschedules($rescheduleLimit)
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $oldCleaningDate = $booking->cleaning_date; // Store old date before update

            $newDateTime = Carbon::createFromFormat('Y-m-d H:i', $validated['new_cleaning_date'], config('app.timezone'));

            // Reject if the selected date is administratively disabled
            try {
                $isDisabled = DisabledDate::query()->active()->whereDate('date', $newDateTime->toDateString())->exists();
            } catch (\Throwable $e) {
                $isDisabled = false; // table may not exist yet
            }
            if ($isDisabled) {
                return response()->json([
                    'success' => false,
                    'message' => 'This date is unavailable for rescheduling. Please choose another date.'
                ], 422);
            }

            if ($newDateTime->isPast()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot reschedule to a past date and time.'
                ], 422);
            }

            // Log current booking state before update
            Log::info('Before customer reschedule update:', [
                'booking_id' => $booking->id,
                'current_status' => $booking->status,
                'current_reschedule_count' => $booking->customer_reschedule_count,
                'intended_status' => Booking::STATUS_PENDING,
                'new_date' => $newDateTime->toDateTimeString()
            ]);

            $booking->update([
                'cleaning_date' => $newDateTime,
                'rescheduled_at' => now(),
                'status' => Booking::STATUS_PENDING, // Set back to pending when customer reschedules
                'reschedule_reason' => $validated['reason'] ?? null,
                'customer_reschedule_count' => $booking->customer_reschedule_count + 1,
                'rescheduled_by' => Auth::id(),
                'is_admin_reschedule' => false, // This is a customer-initiated reschedule
            ]);

            // Log immediately after update
            Log::info('After customer reschedule update:', [
                'booking_id' => $booking->id,
                'updated_status' => $booking->status,
                'updated_reschedule_count' => $booking->customer_reschedule_count,
                'was_status_changed' => $booking->wasChanged('status'),
                'original_status' => $booking->getOriginal('status'),
                'fresh_status' => $booking->fresh()->status
            ]);

            // If there's an associated job, delete it because it needs to be reconfirmed through Online Bookings
            if ($booking->job) {
                $job = $booking->job;
                $jobId = $job->id;
                $job->delete(); // Delete the job tracking entry
                Log::info('Job tracking entry deleted due to customer reschedule:', [
                    'deleted_job_id' => $jobId,
                    'booking_id' => $booking->id,
                    'reason' => 'Customer rescheduled - needs reconfirmation through Online Bookings'
                ]);
            }
            
            // Debug logging after update
            Log::info('Booking updated with customer reschedule:', [
                'booking_id' => $booking->id,
                'reschedule_reason_saved' => $booking->fresh()->reschedule_reason,
                'rescheduled_at_saved' => $booking->fresh()->rescheduled_at,
                'status_saved' => $booking->fresh()->status,
                'status_intended' => Booking::STATUS_PENDING,
                'customer_reschedule_count' => $booking->fresh()->customer_reschedule_count,
                'is_admin_reschedule' => $booking->fresh()->is_admin_reschedule,
                'rescheduled_by' => $booking->fresh()->rescheduled_by,
                'job_deleted' => isset($jobId) ? true : false,
                'deleted_job_id' => $jobId ?? null,
            ]);

            // Log the activity
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => 'Rescheduled booking for ' . $booking->service->name .
                    ' from ' . $oldCleaningDate->format('F j, Y g:i A') . ' to ' . $booking->cleaning_date->format('F j, Y g:i A')
            ]);

            // Send email notification for rescheduled booking
            if (config('mail_settings.enabled', false) && $booking->user && $booking->user->email) {
                Mail::to($booking->user->email)
                    ->queue(new BookingStatusUpdate($booking));
            }

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Booking rescheduled successfully!']);
            } else {
                return redirect()->route('customer.my_bookings')->with('success', 'Booking rescheduled successfully!');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking reschedule failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to reschedule booking. Please try again.'
            ], 500);
        }
    }
}