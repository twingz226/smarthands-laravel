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
    public function index(Request $request)
    {
        $showArchived = $request->query('archived', false);
        
        $customers = Customer::when($showArchived, function($query) {
                return $query->archived();
            }, function($query) {
                return $query->active();
            })
            ->latest()
            ->paginate(10);

        return view('admin.customers.index', compact('customers', 'showArchived'));
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

        // Add registered_date
        $validated['registered_date'] = now();

        Customer::create($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Customer created successfully.');
    }

    // Show single customer
    public function show(Customer $customer)
    {
        $jobs = Job::where('customer_id', $customer->id)
            ->with(['service', 'employees'])
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
}
