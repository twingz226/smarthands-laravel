<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Job;
use App\Models\Service;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // Display all customers
    public function index()
    {
        $customers = Customer::latest()->paginate(10);
        return view('admin.customers.index', compact('customers'));
    }

    // Show create customer form
    public function create()
    {
        return view('admin.customers.create'); // Fixed view name
    }

    // Store new customer
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'contact' => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        Customer::create($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Customer created successfully.');
    }

    // Show single customer
    public function show(Customer $customer)
    {
        $jobs = Job::where('customer_id', $customer->id)
            ->with(['service', 'employee'])
            ->latest()
            ->paginate(5);

        return view('admin.customers.show', compact('customer', 'jobs'));
    }

    // Show edit form
    public function edit(Customer $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    // Update customer
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,'.$customer->id,
            'contact' => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        $customer->update($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    // Delete customer
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')
            ->with('success', 'Customer deleted successfully.');
    }

    // Customer portal view
    public function portal()
    {
        // Get the next upcoming booking for the authenticated customer
        $nextBooking = auth()->user()->bookings()
            ->where('cleaning_date', '>=', now())
            ->orderBy('cleaning_date')
            ->first();

        // Get all bookings (for the bookings tab)
        $bookings = auth()->user()->bookings()
            ->orderBy('cleaning_date', 'desc')
            ->get();

        // Get recent activities
        $recentActivities = auth()->user()->activities()
            ->latest()
            ->take(5)
            ->get();

        // Prepare calendar events
        $calendarEvents = auth()->user()->bookings()
            ->select('cleaning_date as start', 'service_id')
            ->with('service')
            ->get()
            ->map(function ($booking) {
                return [
                    'title' => $booking->service->name,
                    'start' => $booking->cleaning_date, // Fixed to use cleaning_date
                    'color' => '#3490dc',
                ];
            });

        // Fetch available services
        $services = Service::all();

        return view('admin.customers.portal', compact(
            'nextBooking',
            'bookings',
            'recentActivities',
            'calendarEvents',
            'services'
        ));
    }
}
