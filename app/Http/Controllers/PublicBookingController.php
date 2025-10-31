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
        return view('public.bookings.create', compact('services'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        
        try {
            Log::debug('Received booking request:', $request->all());
            Log::info('Booking token received: ' . $request->input('booking_token'));
            Log::info('Service ID received: ' . $request->input('service_id'));
            Log::info('Cleaning Date received: ' . $request->input('cleaning_date'));
            Log::debug('Request data before validation:', $request->all());
            
            // Validate the request data
            $validated = $request->validate([
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
            Log::debug('Validated cleaning_date and cleaning_time:', [
                'cleaning_date' => $validated['cleaning_date'],
                'cleaning_time' => $validated['cleaning_time']
            ]);

            // Parse the cleaning date and time in the application timezone
            $cleaningDateTime = Carbon::parse(
                $validated['cleaning_date'] . ' ' . $validated['cleaning_time'], 
                config('app.timezone')
            );

            // Reject if the selected date is administratively disabled
            try {
                $disabled = DisabledDate::query()->active()->whereDate('date', $cleaningDateTime->toDateString())->exists();
            } catch (\Throwable $e) {
                $disabled = false; // table may not exist yet
            }
            if ($disabled) {
                return response()->json(['success' => false, 'errors' => ['cleaning_date' => 'This date is unavailable for booking. Please choose another date.']], 422);
            }

            // Convert to UTC for storage
            $cleaningDateTimeUTC = $cleaningDateTime->setTimezone('UTC');

            // Check daily booking limit
            if (!Booking::hasAvailableSlots($cleaningDateTimeUTC)) {
                Log::warning('Daily booking limit reached for ' . $cleaningDateTimeUTC->toDateString());
                return response()->json(['success' => false, 'errors' => ['cleaning_date' => 'Daily booking limit reached for this date.']], 422);
            }

            // Check hourly booking limit
            if (!Booking::hasAvailableHourlySlot($cleaningDateTimeUTC, $validated['service_id'])) {
                Log::warning('Hourly booking limit reached for ' . $cleaningDateTimeUTC->toDateTimeString());
                return response()->json(['success' => false, 'errors' => ['cleaning_time' => 'Hourly booking limit reached for this time slot.']], 422);
            }

            Log::debug('Validated booking data:', $validated);

            // Get the service
            $service = Service::findOrFail($validated['service_id']);
            Log::debug('Service found:', $service->toArray());

            // Create or update customer
            $customer = Customer::updateOrCreate(
                ['email' => strtolower($validated['email'])],
                [
                    'name' => $validated['name'],
                    'contact' => $validated['contact'],
                    'registered_date' => now()
                ]
            );

            // Dispatch NewCustomerRegistered event if this is a new customer
            if ($customer->wasRecentlyCreated) {
                event(new NewCustomerRegistered($customer));
            }

            Log::debug('Customer found/updated:', $customer->toArray());
            
            // Assign user_id if authenticated
            $userId = Auth::check() ? Auth::id() : null;
            Log::debug('User ID for booking:', ['user_id' => $userId]);

            // Prevent double booking by checking for existing booking_token
            $booking = Booking::where('booking_token', $validated['booking_token'])->first();
            Log::debug('Booking token check result:', ['booking_token' => $validated['booking_token'], 'found' => (bool)$booking]);
            if ($booking) {
                Log::info('Duplicate booking prevented: booking_token already exists.');
            } else {
                $booking = new Booking([
                    'user_id' => $userId,
                    'service_id' => $validated['service_id'],
                    'cleaning_date' => $cleaningDateTimeUTC, // Store in UTC
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
                    'customer_id' => $customer->id // Add this line
                ]);
                $booking->save(); // Save the new booking to the database
                
                // BookingCreated event is dispatched centrally in Booking::boot() on created
                // to avoid duplicate notifications
            }

            // Dispatch job to send emails (if configured)
            if (config('mail.enabled')) {
                SendBookingEmails::dispatch($booking, $customer);
            }

            DB::commit();

            if ($request->ajax()) {
                return response()->json(['success' => true, 'redirect_url' => route('bookings.success')]);
            } else {
                return redirect()->route('bookings.success');
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error("Booking validation failed: " . json_encode($request->all()) . ". Errors: " . json_encode($e->errors()));
            Log::debug('Validation errors:', $e->errors());
            if ($request->ajax()) {
                return response()->json(['success' => false, 'errors' => $e->errors()], 422);
            } else {
                return back()->withErrors($e->errors())->withInput();
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Booking failed: " . $e->getMessage());
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Booking failed. Please try again.'], 500);
            } else {
                return back()->with('error', 'Booking failed. Please try again.')->withInput();
            }
        }
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

        $cleaningDateTime = Carbon::parse($validated['cleaning_date'] . ' ' . ($validated['cleaning_time'] ?? '00:00'));

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

        if (!Booking::hasAvailableHourlySlot($cleaningDateTime, $validated['service_id'])) {
            return response()->json(['available' => false, 'message' => 'Hourly booking limit reached for this time slot.']);
        }

        return response()->json(['available' => true, 'message' => 'Date and time available!']);
    }
}