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
                'duration' => 'required|integer|min:1',
                'price' => 'required|numeric|min:0',
                'special_instructions' => 'nullable|string  ',
                'admin_notes' => 'nullable|string',
            ]);

            $booking = Booking::create([
                'customer_id' => $validated['customer_id'],
                'service_id' => $validated['service_id'],
                'cleaning_date' => $validated['cleaning_date'],
                'duration' => $validated['duration'],
                'price' => $validated['price'],
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
            'duration' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'special_instructions' => 'nullable|string',
            'admin_notes' => 'nullable|string',
        ]);

        $booking->update($validated);

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Booking updated successfully.');
    }

    public function confirm(Booking $booking)
    {
        $booking->update(['status' => 'confirmed']);
        return back()->with('success', 'Booking confirmed successfully.');
    }

    public function cancel(Booking $booking)
    {
        $booking->update(['status' => 'cancelled']);
        return back()->with('success', 'Booking cancelled successfully.');
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return redirect()->route('bookings.index')
            ->with('success', 'Booking deleted successfully.');
    }
}