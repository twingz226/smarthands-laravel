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

            // Get the service
            $service = Service::findOrFail($validated['service_id']);
            Log::debug('Service found:', $service->toArray());

            // Create or find customer
            $customer = Customer::firstOrCreate(
                ['Email' => strtolower($validated['email'])],
                [
                    'Name' => $validated['name'],
                    'Contact' => $validated['contact'],
                    'Address' => $validated['address'],
                    'Registered_Date' => now(),
                    'Customer_Id' => 'CUST-' . strtoupper(substr(uniqid(), -6))
                ]
            );

            Log::debug('Customer found/created:', $customer->toArray());

            // Create booking
            $booking = Booking::create([
                'customer_id' => $customer->id,
                'service_id' => $validated['service_id'],
                'cleaning_date' => $validated['cleaning_date'],
                'duration' => $request->input('duration', $this->calculateDuration($service)),
                'price' => $request->input('price', $this->calculatePrice($service, $validated)),
                'status' => 'pending',
                'booking_token' => $validated['booking_token'],
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
        if (in_array($service->id, [1, 2])) {
            return 299 * 6; // 6 hour minimum
        } else {
            return 75 * 100; // Example 100 sqm default
        }
    }

    private function calculateDuration($service)
    {
        return in_array($service->id, [1, 2]) ? 6 : 8; // hours
    }
}