<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use App\Mail\BookingStatusUpdate;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['user', 'service'])
            ->orderByRaw("CASE 
                WHEN status = 'pending' THEN 1
                WHEN status = 'confirmed' THEN 2
                WHEN status = 'rescheduled' THEN 3
                WHEN status = 'completed' THEN 4
                WHEN status = 'cancelled' THEN 5
                ELSE 6
            END")
            ->orderBy('cleaning_date', 'asc')
            ->get()
            ->map(function ($booking) {
                // Convert to application timezone for display
                $booking->cleaning_date = $booking->cleaning_date->timezone(config('app.timezone'));
                return $booking;
            });

        Log::debug('Bookings loaded:', [
            'count' => $bookings->count(),
            'timezone' => config('app.timezone'),
            'first_booking_time' => $bookings->first()?->cleaning_date
        ]);

        // Define what constitutes 'fully booked' - e.g., 3 or more bookings on a single day
        $fullyBookedThreshold = 3; // consider moving to config/settings if needed

        // Centralized calculation (excludes cancelled automatically)
        $fullyBookedDates = Booking::getFullyBookedDatesWithCounts($fullyBookedThreshold);

        return view('admin.bookings.index', compact('bookings', 'fullyBookedDates'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        $services = Service::orderBy('name')->get();
        return view('admin.bookings.create', compact('users', 'services'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'service_id' => 'required|exists:services,id',
                'cleaning_date' => 'required|date|after:now',
                'special_instructions' => 'nullable|string',
                'admin_notes' => 'nullable|string',
            ]);

            // Parse with application timezone then convert to UTC
            $cleaningDate = Carbon::parse($validated['cleaning_date'], config('app.timezone'))
                ->setTimezone('UTC');

            $booking = Booking::create([
                'user_id' => $validated['user_id'],
                'service_id' => $validated['service_id'],
                'cleaning_date' => $cleaningDate,
                'status' => 'pending',
                'special_instructions' => $validated['special_instructions'] ?? null,
                'admin_notes' => $validated['admin_notes'] ?? null,
            ]);

            DB::commit();

            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'redirect_url' => route('booking.success')
                ], 200);
            }

            // Normal form submit fallback
            return redirect()->route('booking.success')
                ->with('success', 'Booking created successfully.');

        } catch (ValidationException $e) {
            DB::rollBack();
            Log::error('Admin booking validation failed: ' + $e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors()
                ], 422);
            }

            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Admin booking creation failed: ' + $e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An unexpected error occurred: ' + $e->getMessage()
                ], 500); // Use 500 for general server errors
            }

            return back()->with('error', 'Booking creation failed: ' + $e->getMessage())->withInput();
        }
    }

    public function show(Booking $booking)
    {
        // Convert to application timezone for display
        $booking->cleaning_date = $booking->cleaning_date->timezone(config('app.timezone'));
        
        return view('admin.bookings.show', [
            'booking' => $booking->load(['user', 'service', 'cleaner'])
        ]);
    }

    public function edit(Booking $booking)
    {
        // Format for datetime-local input
        $formattedDate = $booking->cleaning_date
            ->timezone(config('app.timezone'))
            ->format('Y-m-d\TH:i');
            
        $users = User::orderBy('name')->get();
        $services = Service::orderBy('name')->get();
        
        return view('admin.bookings.edit', compact('booking', 'users', 'services', 'formattedDate'));
    }

    public function update(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'cleaning_date' => 'required|date|after:now',
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'special_instructions' => 'nullable|string',
            'admin_notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Convert to proper timezone before storage
            $cleaningDate = Carbon::parse($validated['cleaning_date'], config('app.timezone'))
                ->setTimezone('UTC');

            $oldStatus = $booking->status;
            
            $booking->update([
                'user_id' => $validated['user_id'],
                'service_id' => $validated['service_id'],
                'cleaning_date' => $cleaningDate,
                'status' => $validated['status'],
                'special_instructions' => $validated['special_instructions'] ?? null,
                'admin_notes' => $validated['admin_notes'] ?? null,
            ]);

            // If booking status changes to cancelled, update associated job status
            if ($oldStatus !== 'cancelled' && $validated['status'] === 'cancelled') {
                if ($booking->job) {
                    $booking->job->update(['status' => \App\Models\Job::STATUS_CANCELLED]);
                }
            }

            // Send email if status has changed
            if ($oldStatus !== $validated['status'] && config('mail.enabled')) {
                Mail::to($booking->user->email)
                        ->queue(new BookingStatusUpdate($booking));
            }

            DB::commit();

            return redirect()->route('admin.bookings.show', $booking)
                ->with('success', 'Booking updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking update failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to update booking: ' . $e->getMessage());
        }
    }

    public function reschedule(Booking $booking)
    {
        // Ensure the booking is not soft-deleted
        if ($booking->trashed()) {
            return back()->with('error', 'Cannot reschedule a deleted booking.');
        }

        return view('admin.bookings.reschedule', compact('booking'));
    }

    public function fullyBookedDates()
    {
        // Use application timezone day buckets to compute counts accurately
        $fullyBookedThreshold = 3;
        $start = now()->timezone(config('app.timezone'))->startOfDay();
        $end = now()->timezone(config('app.timezone'))->addDays(60)->endOfDay();
        $fullyBookedDates = Booking::getFullyBookedDatesWithCountsByAppTimezone($start, $end, $fullyBookedThreshold);

        Log::debug('Fully Booked Dates Data:', ['fullyBookedDates' => $fullyBookedDates->toArray()]);
        return view('admin.bookings.fully.booked.dates', compact('fullyBookedDates'));
    }

    public function updateReschedule(Request $request, Booking $booking)
    {
        $request->validate([
            'new_cleaning_date' => 'required|date|after_or_equal:today',
            'new_cleaning_time' => 'required|date_format:H:i',
        ]);

        DB::beginTransaction();

        try {
            // Combine date and time in the application timezone
            $newCleaningDateTimeAppTz = Carbon::parse(
                $request->input('new_cleaning_date') . ' ' . $request->input('new_cleaning_time'),
                config('app.timezone')
            );

            // Prevent rescheduling to past datetime
            if ($newCleaningDateTimeAppTz->isPast()) {
                return back()->with('error', 'Cannot reschedule to a past date and time.')->withInput();
            }

            // Update booking
            // Assign in app timezone; the Booking mutator will convert it to UTC for storage
            $booking->cleaning_date = $newCleaningDateTimeAppTz;
            $booking->status = Booking::STATUS_RESCHEDULED;
            // Set rescheduled_at if column exists
            if (Schema::hasColumn('bookings', 'rescheduled_at')) {
                $booking->rescheduled_at = now('UTC');
            }
            $booking->save();

            // If there's an associated job, update its scheduled date and status
            if ($booking->job) {
                $job = $booking->job;
                // Jobs are stored in UTC; convert explicitly
                $job->scheduled_date = $newCleaningDateTimeAppTz->copy()->setTimezone('UTC');
                $job->status = \App\Models\Job::STATUS_PENDING; // Or a specific 'rescheduled' status if available
                $job->save();
            }

            // Log activity
            try {
                activity()
                    ->performedOn($booking)
                    ->causedBy(Auth::user())
                    ->withProperties([
                        'old_date' => $booking->getOriginal('cleaning_date'),
                        'new_date' => $booking->cleaning_date,
                        'actor_id' => Auth::id(),
                    ])
                    ->log('Booking rescheduled by admin');
            } catch (\Throwable $logEx) {
                Log::warning('Activity log failed during booking reschedule', [
                    'booking_id' => $booking->id,
                    'error' => $logEx->getMessage(),
                ]);
            }

            // Send email notification
            if (config('mail_settings.enabled')) {
                Mail::to($booking->customer->email)->queue(new BookingStatusUpdate($booking));
            }

            DB::commit();

            Log::info('Booking rescheduled successfully', [
                'booking_id' => $booking->id,
                'new_cleaning_date_app_tz' => $newCleaningDateTimeAppTz->toDateTimeString(),
                'new_cleaning_date_utc' => $newCleaningDateTimeAppTz->copy()->setTimezone('UTC')->toDateTimeString(),
            ]);

            return redirect()->route('bookings.index')->with('success', 'Booking rescheduled successfully.');
        } catch (ValidationException $e) {
            DB::rollBack();
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking reschedule failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to reschedule booking: ' . $e->getMessage());
        }
    }

    public function confirm(Booking $booking)
    {
        
        try {
            DB::beginTransaction();

            $originalStatus = $booking->status;
            $booking->update(['status' => Booking::STATUS_CONFIRMED]);

            if (config('mail_settings.enabled') && $originalStatus !== $booking->status) {
                $formattedDate = $booking->cleaning_date
                    ->timezone(config('app.timezone'))
                    ->format('M j, Y g:i A');
                    
                Mail::to($booking->customer->email)
                        ->queue(new BookingStatusUpdate($booking));
            }

            DB::commit();

            return back()->with('success', 
                'Booking confirmed for ' . 
                $booking->cleaning_date->timezone(config('app.timezone'))->format('M j, Y g:i A'));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking confirmation failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to confirm booking. Please try again.');
        }
    }

    public function cancel(Booking $booking)
    {
        try {
            DB::beginTransaction();
            
            $originalStatus = $booking->status;
            $booking->update(['status' => Booking::STATUS_CANCELLED]);

            // Update associated job status to cancelled
            if ($booking->job) {
                $booking->job->update(['status' => \App\Models\Job::STATUS_CANCELLED]);
            }
            
            if (config('mail_settings.enabled') && $originalStatus !== $booking->status) {
                $formattedDate = $booking->cleaning_date
                    ->timezone(config('app.timezone'))
                    ->format('M j, Y g:i A');
                    
                Mail::to($booking->customer->email)
                        ->queue(new BookingStatusUpdate($booking));
            }

            DB::commit();
            
            $formattedDate = $booking->cleaning_date
                ->timezone(config('app.timezone'))
                ->format('M j, Y g:i A');
                
            return back()->with('success', "Booking for {$formattedDate} has been cancelled");
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking cancellation failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to cancel booking. Please try again.');
        }
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return redirect()->route('admin.bookings.index')
            ->with('success', 'Booking deleted successfully.');
    }


}
