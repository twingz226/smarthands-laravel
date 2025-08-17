<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingStatusUpdate;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['customer', 'service', 'user'])
            ->latest()
            ->get();

        Log::debug('Bookings loaded:', [
            'count' => $bookings->count(),
            'bookings' => $bookings->toArray()
        ]);

        return view('admin.bookings.index', compact('bookings'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $services = Service::orderBy('name')->get();
        return view('admin.bookings.create', compact('customers', 'services'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'service_id' => 'required|exists:services,id',
                'cleaning_date' => 'required|date',
                'special_instructions' => 'nullable|string',
                'admin_notes' => 'nullable|string',
            ]);

            $booking = Booking::create([
                'customer_id' => $validated['customer_id'],
                'service_id' => $validated['service_id'],
                'cleaning_date' => $validated['cleaning_date'],
                'status' => 'pending',
                'special_instructions' => $validated['special_instructions'] ?? null,
                'admin_notes' => $validated['admin_notes'] ?? null,
                'user_id' => Auth::id(),
            ]);

            DB::commit();

            return redirect()->route('bookings.index')
                ->with('success', 'Booking created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Admin booking creation failed: ' . $e->getMessage());
            return back()->with('error', 'Booking creation failed: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Booking $booking)
    {
        return view('admin.bookings.show', [
            'booking' => $booking->load(['customer', 'service', 'user', 'cleaner'])
        ]);
    }

    public function edit(Booking $booking)
    {
        $customers = Customer::orderBy('name')->get();
        $services = Service::orderBy('name')->get();
        return view('admin.bookings.edit', compact('booking', 'customers', 'services'));
    }

    public function update(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'service_id' => 'required|exists:services,id',
            'cleaning_date' => 'required|date',
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'special_instructions' => 'nullable|string',
            'admin_notes' => 'nullable|string',
        ]);

        $oldStatus = $booking->status;
        $booking->update($validated);

        // Send email if status has changed
        if ($oldStatus !== $validated['status'] && config('mail.enabled')) {
            Mail::to($booking->customer->email)
                ->send(new BookingStatusUpdate($booking));
        }

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Booking updated successfully.');
    }

    public function confirm(Booking $booking)
    {
        try {
            DB::beginTransaction();

            Log::info('BookingController: Starting booking confirmation process', [
                'booking_id' => $booking->id,
                'old_status' => $booking->status,
                'customer_id' => $booking->customer_id,
                'service_id' => $booking->service_id,
                'cleaning_date' => $booking->cleaning_date
            ]);

            // Get the original status before update
            $originalStatus = $booking->status;

            $booking->update(['status' => Booking::STATUS_CONFIRMED]);

            // Reload the model to ensure we have fresh data
            $booking->refresh();

            Log::info('BookingController: Booking status updated', [
                'booking_id' => $booking->id,
                'original_status' => $originalStatus,
                'new_status' => $booking->status,
                'was_status_changed' => $originalStatus !== $booking->status,
                'is_confirmed' => $booking->isConfirmed()
            ]);
            
            if (config('mail.enabled')) {
                Mail::to($booking->customer->email)
                    ->send(new BookingStatusUpdate($booking));
            }

            DB::commit();

            Log::info('BookingController: Booking confirmation completed successfully', [
                'booking_id' => $booking->id,
                'final_status' => $booking->status
            ]);

            return back()->with('success', 'Booking confirmed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('BookingController: Failed to confirm booking', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Failed to confirm booking. Please try again.');
        }
    }

    public function cancel(Booking $booking)
    {
        try {
            DB::beginTransaction();
            
            $booking->update(['status' => Booking::STATUS_CANCELLED]);
            
            if (config('mail.enabled')) {
                Mail::to($booking->customer->email)
                    ->send(new BookingStatusUpdate($booking));
            }

            DB::commit();
            return back()->with('success', 'Booking cancelled successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking cancellation failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to cancel booking. Please try again.');
        }
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return redirect()->route('bookings.index')
            ->with('success', 'Booking deleted successfully.');
    }
}