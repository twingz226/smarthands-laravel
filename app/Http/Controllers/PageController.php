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

class PageController extends Controller
{
    public function home()
    {
        $services = Service::all();
        return view('pages.home', compact('services')); 
    }

    public function services()
    {
        $services = Service::all();
        return view('pages.services', compact('services'));
    }

    public function about()
    {
        return view('pages.about');
    }
    public function contact()
    {
        return view('pages.contact');
    }

    /**
     * Store a new booking request.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        
        try {
            // Validate the request data
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'contact' => 'required|string|max:20',
                'address' => 'required|string|max:500',
                'service_id' => 'required|exists:services,id',
                'cleaning_date' => 'required|date|after:now',
                'booking_token' => 'required|string',
                'duration' => 'required|numeric|min:1',
                'price' => 'required|numeric|min:0'
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
                'duration' => $validated['duration'],
                'price' => $validated['price'],
                'status' => 'pending',
                'booking_token' => $validated['booking_token'],
            ]);

            Log::debug('Booking created:', $booking->toArray());

            // Send emails (if configured)
            if (config('mail.enabled')) {
                Mail::to(config('mail.admin_email'))->send(new NewBookingAlert($booking));
                Mail::to($customer->Email)->send(new BookingConfirmation($booking));
            }

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Booking submitted successfully!',
                    'booking' => $booking
                ]);
            }

            return redirect()->route('bookings.success')
                ->with('success', 'Booking submitted successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error("Booking validation failed: " . json_encode($request->all()));
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors()
                ], 422);
            }
            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Booking failed: " . $e->getMessage());
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking failed. Please try again.'
                ], 500);
            }
            return back()->with('error', 'Booking failed. Please try again.')->withInput();
        }
    }

    private function calculateDuration(Service $service)
    {
        // Add your duration calculation logic here
        return 2; // Default duration in hours
    }

    private function calculatePrice(Service $service, array $validated)
    {
        // Add your price calculation logic here
        return $service->base_price; // Return base price as default
    }
}