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
                'booking_token' => 'required|string'
            ]);

            Log::debug('Validated booking data:', $validated);

            // Get the service
            $service = Service::findOrFail($validated['service_id']);

            // Create or find customer
            $customer = Customer::firstOrCreate(
                ['email' => $validated['email']],
                [
                    'name' => $validated['name'],
                    'contact' => $validated['contact'],
                    'address' => $validated['address'],
                    'Registered_Date' => now()
                ]
            );

            Log::debug('Customer found/created:', $customer->toArray());

            // Create booking
            $booking = Booking::create([
                'customer_id' => $customer->id,
                'service_id' => $validated['service_id'],
                'cleaning_date' => $validated['cleaning_date'],
                'duration' => $this->calculateDuration($service),
                'price' => $this->calculatePrice($service, $validated),
                'status' => 'pending',
                'user_id' => null,
                'booking_token' => $validated['booking_token'],
            ]);

            dd($booking);

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