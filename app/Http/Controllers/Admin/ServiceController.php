<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    // Display all services
    public function index()
    {
        $services = Service::latest()->paginate(10);
        return view('admin.services.index', compact('services'));
    }

    // Show create service form
    public function create()
    {
        return view('admin.services.create');
    }

    // Store new service
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'pricing_type' => 'required|in:sqm,duration',
            'price' => 'required|numeric|min:0',
            'duration_minutes' => [
                'nullable',
                'required_if:pricing_type,duration',
                'integer',
                'min:1'
            ],
        ]);

        if ($request->pricing_type === 'sqm') {
            $validated['duration_minutes'] = null;
        }

        Service::create($validated);

        return redirect()->route('services.index')
            ->with('success', 'Service created successfully.');
    }

    // Show single service
    public function show(Service $service)
    {
        return view('admin.services.show', compact('service'));
    }

    // Show edit form
    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    // Update service
    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'pricing_type' => 'required|in:sqm,duration',
            'price' => 'required|numeric|min:0',
            'duration_minutes' => [
                'nullable',
                'required_if:pricing_type,duration',
                'integer',
                'min:1'
            ],
        ]);

        $service->update($validated);

        return redirect()->route('services.index')
            ->with('success', 'Service updated successfully.');
    }

    // Delete service
    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('services.index')
            ->with('success', 'Service deleted successfully.');
    }
}
