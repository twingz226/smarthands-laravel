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
use App\Models\ContactInfo;
use Illuminate\Validation\ValidationException;

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
        $contactInfo = ContactInfo::first();
        return view('pages.about', compact('contactInfo'));
    }

    public function contact()
    {
        $contactInfo = ContactInfo::first();
        return view('pages.contact', compact('contactInfo'));
    }

    /**
     * Store a new booking request.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'contact' => 'required|string|max:20',
                'address' => 'required|string|max:500',
                'service_id' => 'required|exists:services,id',
                'cleaning_date' => 'required|date|after:now',
                'special_instructions' => 'nullable|string',
                'booking_token' => 'required|string'
            ]);

            // Check if the selected date has available slots
            if (!Booking::hasAvailableSlots($validated['cleaning_date'])) {
                throw ValidationException::withMessages([
                    'cleaning_date' => ['Selected date is fully booked. Please choose another date.']
                ]);
            }

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
            $service = Service::findOrFail($validated['service_id']);
            $booking = Booking::create([
                'customer_id' => $customer->id,
                'service_id' => $validated['service_id'],
                'cleaning_date' => $validated['cleaning_date'],
                'status' => 'pending',
                'booking_token' => $validated['booking_token'],
                'special_instructions' => $validated['special_instructions'] ?? null,
            ]);

            Log::debug('Booking created:', $booking->toArray());

            // Send emails (if configured)
            if (config('mail.enabled')) {
                Mail::to(config('mail.admin_email'))->send(new NewBookingAlert($booking));
                Mail::to($customer->email)->send(new BookingConfirmation($booking));
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

    public function checkSlots(Request $request)
    {
        $date = $request->query('date');
        return response()->json([
            'available' => Booking::hasAvailableSlots($date)
        ]);
    }
}