<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Service;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Mail\BookingConfirmation;
use App\Mail\NewBookingAlert;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
            
            // Validate the request data
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'contact' => 'required|string|max:20',
                'address' => 'required|string|max:500',
                'service_id' => 'required|exists:services,id',
                'cleaning_date' => 'required|date|after:now',
                'booking_token' => 'required|string'
            ]);

            Log::debug('Validated booking data:', $validated);

            // Parse the date in PHT timezone
            $cleaningDate = Carbon::parse($validated['cleaning_date'], 'Asia/Manila');
            
            // Get the service
            $service = Service::findOrFail($validated['service_id']);
            Log::debug('Service found:', $service->toArray());

            // Create or update customer
            $customer = Customer::updateOrCreate(
                ['email' => strtolower($validated['email'])],
                [
                    'name' => $validated['name'],
                    'contact' => $validated['contact'],
                    'address' => $validated['address'],
                    'registered_date' => now()
                ]
            );

            Log::debug('Customer found/updated:', $customer->toArray());

            // Create booking
            $booking = Booking::create([
                'customer_id' => $customer->id,
                'service_id' => $validated['service_id'],
                'cleaning_date' => $cleaningDate,
                'status' => 'pending',
                'booking_token' => $validated['booking_token'],
                'special_instructions' => $request->input('special_instructions'),
            ]);

            Log::debug('Booking created:', $booking->toArray());

            // Send emails (if configured)
            if (config('mail.enabled')) {
                Mail::to(config('mail.admin_email'))->send(new NewBookingAlert($booking));
                Mail::to($customer->email)->send(new BookingConfirmation($booking));
            }

            DB::commit();

            return redirect()->route('bookings.success')
                ->with('success', 'Booking submitted successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error("Booking validation failed: " . json_encode($request->all()));
            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Booking failed: " . $e->getMessage());
            return back()->with('error', 'Booking failed. Please try again.')->withInput();
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
}