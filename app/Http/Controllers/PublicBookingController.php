<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Service;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Mail\BookingConfirmation;
use App\Mail\NewBookingAlert;
use Illuminate\Support\Facades\Mail;
use App\Jobs\SendBookingEmails;
use App\Events\BookingCreated;
use App\Events\NewCustomerRegistered;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Event;
use App\Models\DisabledDate;

class PublicBookingController extends Controller
{
    public function create()
    {
        $services = Service::all();
        return view('bookings.create', compact('services'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        
        try {
            Log::debug('Received booking request:', $request->all());
            
            $validated = $this->validateBookingRequest($request);
            
            // Parse and convert to UTC
            $cleaningDateTime = Carbon::parse(
                $validated['cleaning_date'] . ' ' . $validated['cleaning_time'], 
                config('app.timezone')
            );
            $cleaningDateTimeUTC = $cleaningDateTime->copy()->setTimezone('UTC');

            // Availability checks
            $availabilityError = $this->checkBookingAvailability($cleaningDateTime, $cleaningDateTimeUTC);
            if ($availabilityError) {
                return $availabilityError;
            }

            // Get the service
            $service = Service::findOrFail($validated['service_id']);

            // Customer creation/update
            $customer = $this->getOrCreateCustomer($validated);

            // Assign user_id if authenticated
            $userId = Auth::check() ? Auth::id() : null;

            // Prevent double booking and create booking
            $booking = Booking::where('booking_token', $validated['booking_token'])->first();
            if ($booking) {
                Log::info('Duplicate booking prevented: booking_token already exists.');
            } else {
                $booking = $this->createNewBooking($validated, $customer, $cleaningDateTimeUTC, $userId, $service);
            }

            // Dispatch emails
            if (config('mail.enabled')) {
                SendBookingEmails::dispatch($booking, $customer);
            }

            DB::commit();

            return $request->ajax() 
                ? response()->json(['success' => true, 'redirect_url' => route('bookings.success')])
                : redirect()->route('bookings.success');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error("Booking validation failed. Errors: " . json_encode($e->errors()));
            return $request->ajax()
                ? response()->json(['success' => false, 'errors' => $e->errors()], 422)
                : back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Booking failed: " . $e->getMessage());
            return $request->ajax()
                ? response()->json(['success' => false, 'message' => 'Booking failed. Please try again.'], 500)
                : back()->with('error', 'Booking failed. Please try again.')->withInput();
        }
    }

    private function validateBookingRequest(Request $request)
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'contact' => 'required|string|max:20',
            'block' => 'nullable|string|max:255',
            'lot' => 'nullable|string|max:255',
            'street' => 'required|string|max:500',
            'subdivision' => 'nullable|string|max:255',
            'barangay' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'zip_code' => 'required|string|max:20',
            'service_id' => 'required|exists:services,id',
            'cleaning_date' => 'required|date|after_or_equal:today',
            'cleaning_time' => 'required|date_format:H:i',
            'client_timezone_offset' => 'required|integer',
            'booking_token' => 'required|string'
        ]);
    }

    private function checkBookingAvailability($cleaningDateTime, $cleaningDateTimeUTC)
    {
        // 1. Check administratively disabled dates
        try {
            $disabled = DisabledDate::query()->active()->whereDate('date', $cleaningDateTime->toDateString())->exists();
        } catch (\Throwable $e) {
            $disabled = false;
        }
        if ($disabled) {
            return response()->json(['success' => false, 'errors' => ['cleaning_date' => 'This date is unavailable for booking.']], 422);
        }

        // 2. Check daily booking limit
        if (!Booking::hasAvailableSlots($cleaningDateTimeUTC)) {
            Log::warning('Daily booking limit reached for ' . $cleaningDateTimeUTC->toDateString());
            return response()->json(['success' => false, 'errors' => ['cleaning_date' => 'Daily booking limit reached for this date.']], 422);
        }

        // 3. Check cleaner availability for the selected time slot
        $availableSlots = Booking::getAvailableSlotsWithCleanerCount($cleaningDateTimeUTC->toDateString());
        $timeSlot = $cleaningDateTimeUTC->format('H:i');
        
        if (isset($availableSlots[$timeSlot]) && !$availableSlots[$timeSlot]['available']) {
            Log::warning('Insufficient cleaners available for ' . $timeSlot);
            return response()->json(['success' => false, 'errors' => ['cleaning_time' => 'Insufficient cleaners available for this time slot.']], 422);
        }

        return null;
    }

    private function getOrCreateCustomer(array $validated)
    {
        return Customer::updateOrCreate(
            ['email' => strtolower($validated['email'])],
            [
                'name' => $validated['name'],
                'contact' => $validated['contact'],
                'registered_date' => now()
            ]
        );
    }

    private function createNewBooking(array $validated, $customer, $cleaningDateTimeUTC, $userId, $service)
    {
        $booking = new Booking([
            'user_id' => $userId,
            'service_id' => $validated['service_id'],
            'cleaning_date' => $cleaningDateTimeUTC,
            'status' => 'pending',
            'customer_name' => $validated['name'],
            'customer_email' => $validated['email'],
            'customer_contact' => $validated['contact'],
            'customer_address' => implode(', ', array_filter([
                $validated['block'] ? 'Block: ' . $validated['block'] : null,
                $validated['lot'] ? 'Lot: ' . $validated['lot'] : null,
                $validated['street'] ? 'Street: ' . $validated['street'] : null,
                $validated['subdivision'] ? 'Subdivision: ' . $validated['subdivision'] : null,
                $validated['barangay'] ? 'Barangay: ' . $validated['barangay'] : null,
                $validated['city'] ? 'City: ' . $validated['city'] : null,
                $validated['zip_code'] ? 'Zip: ' . $validated['zip_code'] : null,
            ])),
            'booking_token' => $validated['booking_token'],
            'customer_id' => $customer->id
        ]);
        
        $booking->save();

        if ($userId) {
            \App\Models\ActivityLog::create([
                'user_id' => $userId,
                'description' => 'Created new booking for ' . $service->name
            ]);
        }

        return $booking;
    }

    private function calculatePrice($service, $data)
    {
        return 0;
    }

    private function calculateDuration($service)
    {
        return 0;
    }

    public function checkAvailability(Request $request)
    {
        $validated = $request->validate([
            'cleaning_date' => 'required|date',
            'cleaning_time' => 'nullable|date_format:H:i', // Make time nullable for daily checks
            'service_id' => 'required|exists:services,id',
            'check_daily_limit' => 'boolean' // New parameter
        ]);

        $cleaningDateTime = Carbon::parse($validated['cleaning_date'] . ' ' . ($validated['cleaning_time'] ?? '00:00'), config('app.timezone'));
        $cleaningDateTimeUTC = $cleaningDateTime->copy()->setTimezone('UTC');

        // If check_daily_limit is true, only check daily slots
        if ($request->input('check_daily_limit')) {
            if (!Booking::hasAvailableSlots($cleaningDateTime)) {
                return response()->json(['available' => false, 'message' => 'Daily booking limit reached for this date.']);
            }
            return response()->json(['available' => true, 'message' => 'Date available!']);
        }

        // Otherwise, check both daily and hourly slots (for full availability check)
        if (!Booking::hasAvailableSlots($cleaningDateTime)) {
            return response()->json(['available' => false, 'message' => 'Daily booking limit reached for this date.']);
        }

        if (!Booking::hasAvailableHourlySlot($cleaningDateTimeUTC, $validated['service_id'])) {
            return response()->json(['available' => false, 'message' => 'Hourly booking limit reached for this time slot.']);
        }

        return response()->json(['available' => true, 'message' => 'Date and time available!']);
    }

    /**
     * Check real-time availability for a specific time slot
     */
    public function checkTimeSlotAvailability(Request $request)
    {
        $validated = $request->validate([
            'cleaning_date' => 'required|date',
            'cleaning_time' => 'required|date_format:H:i',
            'service_id' => 'required|exists:services,id'
        ]);

        // Get detailed cleaner availability information
        $availableSlots = Booking::getAvailableSlotsWithCleanerCount($validated['cleaning_date']);
        $timeSlot = $validated['cleaning_time'];
        
        if (isset($availableSlots[$timeSlot])) {
            $slotInfo = $availableSlots[$timeSlot];
            if (!$slotInfo['available']) {
                $message = 'Time slot unavailable.';
                $reason = 'unavailable';
                
                // Provide specific messages based on the reason
                switch ($slotInfo['reason']) {
                    case 'temporary_booking':
                        $message = 'Time slot temporarily unavailable - waiting for cleaner assignment.';
                        $reason = 'temporary_booking';
                        break;
                    case 'insufficient_assignments':
                        $message = 'Time slot unavailable - more bookings than cleaner assignments.';
                        $reason = 'insufficient_assignments';
                        break;
                    case 'insufficient_cleaners':
                        $message = 'Time slot unavailable - insufficient cleaners available.';
                        $reason = 'insufficient_cleaners';
                        break;
                    default:
                        $message = 'Time slot unavailable.';
                        $reason = 'unavailable';
                }
                
                return response()->json([
                    'available' => false,
                    'message' => $message,
                    'reason' => $reason,
                    'available_cleaners' => $slotInfo['available_cleaners'],
                    'assigned_cleaners' => $slotInfo['assigned_cleaners'] ?? 0,
                    'booking_count' => $slotInfo['booking_count'] ?? 0
                ]);
            }
        }

        return response()->json([
            'available' => true,
            'message' => 'Time slot available!',
            'reason' => 'available'
        ]);
    }
}