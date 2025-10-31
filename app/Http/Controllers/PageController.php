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
use Illuminate\Support\Facades\Auth;
use App\Models\ContactInfo;
use App\Models\HomeMedia;
use App\Models\DisabledDate;
use App\Models\Rating;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function home()
    {
        $services = Service::all();
        $contactInfo = ContactInfo::first();

        // Fetch dynamic hero media (video/image). If columns not migrated yet, gracefully fallback
        $heroMedia = null;
        try {
            $heroMedia = HomeMedia::query()
                ->active()
                ->section(HomeMedia::SECTION_HERO)
                ->orderBy('display_order')
                ->orderByDesc('id')
                ->first();
        } catch (\Throwable $e) {
            // Likely columns missing (e.g., is_active/section not migrated yet)
            Log::warning('Hero media query failed, falling back to static asset: ' . $e->getMessage());
            $heroMedia = null;
        }

        // Fetch dynamic services media
        $servicesMedia = [];
        try {
            $servicesMedia = HomeMedia::query()
                ->active()
                ->section(HomeMedia::SECTION_SERVICES)
                ->orderBy('display_order')
                ->get();
        } catch (\Throwable $e) {
            Log::warning('Services media query failed, falling back to static services: ' . $e->getMessage());
            $servicesMedia = collect();
        }

        $ratingSummary = [
            'average' => null,
            'count' => 0,
        ];
        $serviceRatings = collect();

        try {
            $ratingSummary['average'] = round((float) Rating::avg('rating'), 1) ?: null;
            $ratingSummary['count'] = Rating::count();

            if ($ratingSummary['count'] > 0) {
                // Create one card per rating submission (job-level)
                $ratingsWithRelations = Rating::with(['customer', 'job.service'])
                    ->whereHas('job')
                    ->whereHas('job.service')
                    ->whereHas('customer')
                    ->orderByDesc('created_at')
                    ->get();

                $serviceRatings = $ratingsWithRelations
                    ->groupBy('job_id')
                    ->map(function ($ratings) {
                        $firstRating = $ratings->first();
                        $job = $firstRating->job;
                        $service = $job?->service;
                        $customer = $firstRating->customer;

                        if (!$job || !$service || !$customer) {
                            return null;
                        }

                        $averageRating = round($ratings->avg('rating'), 1);
                        $ratingCount = $ratings->count();

                        $mostRecentRating = $ratings->sortByDesc('created_at')->first();
                        $latestCommentRating = $ratings->filter(function ($rating) {
                                return filled($rating->comments);
                            })
                            ->sortByDesc('created_at')
                            ->first();

                        return [
                            'job_id' => $job->id,
                            'customer_id' => $customer->id,
                            'customer_name' => $customer->name,
                            'service_id' => $service->id,
                            'service_name' => $service->name,
                            'service_description' => $service->description,
                            'average_rating' => $averageRating,
                            'rating_count' => $ratingCount,
                            'customer_comment' => $latestCommentRating ? Str::limit(strip_tags($latestCommentRating->comments), 150) : null,
                            'latest_rating_date' => $mostRecentRating?->created_at,
                        ];
                    })
                    ->filter()
                    ->sortByDesc('latest_rating_date')
                    ->values();
            }
        } catch (\Throwable $e) {
            Log::warning('Ratings query failed for homepage: ' . $e->getMessage());
            $serviceRatings = collect();
            $ratingSummary = [
                'average' => null,
                'count' => 0,
            ];
        }

        return view('pages.home', compact(
            'services',
            'contactInfo',
            'heroMedia',
            'servicesMedia',
            'ratingSummary',
            'serviceRatings'
        )); 
    }

    public function services()
    {
        $services = Service::all();
        $user = Auth::check() ? Auth::user() : null;
        $contactInfo = ContactInfo::first();
        return view('pages.services', compact('services', 'user', 'contactInfo'));
    }

    public function about()
    {
        $contactInfo = ContactInfo::first();
        $user = Auth::check() ? Auth::user() : null;
        return view('pages.about', compact('contactInfo', 'user'));
    }

    public function contact()
    {
        $contactInfo = ContactInfo::first();
        $user = Auth::check() ? Auth::user() : null;
        return view('pages.contact', compact('contactInfo', 'user'));
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
                Mail::to(config('mail.admin_email'))->queue(new NewBookingAlert($booking));
                Mail::to($customer->email)->queue(new BookingConfirmation($booking));
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

        } catch (ValidationException $e) {
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

    public function fullyBookedDates(Request $request)
    {
        $start = now()->startOfDay();
        $end = now()->addDays(30)->endOfDay();
        $fullyBookedDates = [];
        $fullyBookedTimes = [];

        // Admin-configured disabled dates (always include)
        $disabledDates = [];
        try {
            $disabledDates = DisabledDate::query()
                ->active()
                ->orderBy('date')
                ->pluck('date')
                ->map(function ($d) { return \Carbon\Carbon::parse($d)->toDateString(); })
                ->toArray();
        } catch (\Throwable $e) {
            // table may not exist yet during first deploy; fail gracefully
            $disabledDates = [];
        }

        // If ?context=home is passed, return fully booked dates (using threshold)
        // and also return fully booked time slots for dates that are not fully booked
        if ($request->query('context') === 'home') {
            for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
                $dateString = $date->toDateString();
                if (!Booking::hasAvailableSlots($dateString)) {
                    $fullyBookedDates[] = $dateString;
                } else {
                    // Provide time slot availability info for dates that are not fully booked
                    $availableSlots = Booking::getAvailableSlots($dateString);
                    $fullyBookedTimes[$dateString] = array_keys(array_filter($availableSlots, function($slot) { return !$slot; }));
                }
            }
            // Merge disabled dates
            $fullyBookedDates = array_values(array_unique(array_merge($fullyBookedDates, $disabledDates)));
            return response()->json([
                'fullyBookedDates' => $fullyBookedDates,
                'fullyBookedTimes' => $fullyBookedTimes
            ]);
        }
        
        // If ?context=reschedule is passed, return both dates and times
        if ($request->query('context') === 'reschedule') {
            for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
                $dateString = $date->toDateString();
                $availableSlots = Booking::getAvailableSlots($dateString);
                if (empty($availableSlots)) {
                    $fullyBookedDates[] = $dateString;
                } else {
                    $fullyBookedTimes[$dateString] = array_keys(array_filter($availableSlots, function($slot) { return !$slot; }));
                }
            }
            // Merge disabled dates for reschedule as well
            $fullyBookedDates = array_values(array_unique(array_merge($fullyBookedDates, $disabledDates)));
            return response()->json([
                'fullyBookedDates' => $fullyBookedDates,
                'fullyBookedTimes' => $fullyBookedTimes
            ]);
        }

        // Default: return both fully booked dates and times (for other contexts)
        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $dateString = $date->toDateString();
            $availableSlots = Booking::getAvailableSlots($dateString);
            if (empty($availableSlots)) {
                $fullyBookedDates[] = $dateString;
            } else {
                $fullyBookedTimes[$dateString] = array_keys(array_filter($availableSlots, function($slot) { return !$slot; }));
            }
        }
        // Merge disabled dates
        $fullyBookedDates = array_values(array_unique(array_merge($fullyBookedDates, $disabledDates)));
        return response()->json([
            'fullyBookedDates' => $fullyBookedDates,
            'fullyBookedTimes' => $fullyBookedTimes
        ]);
    }
}