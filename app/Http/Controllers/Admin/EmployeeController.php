<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Job;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class EmployeeController extends Controller
{
    // Display all employees
    public function index()
    {
        $employees = Employee::withCount(['jobs' => function($query) {
                $query->where('status', 'completed');
            }])
            ->withAvg('ratings', 'rating')
            ->latest()
            ->paginate(10);
            
        return view('admin.employees.index', compact('employees'));
    }

    // Show create employee form
    public function create()
    {
        return view('admin.employees.create');
    }

    // Store new employee
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'employee_no' => 'nullable|string|max:50|unique:employees,employee_no',
            'position' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'hire_date' => 'required|date',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'id_badge_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'uniform_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'photo_consent_given' => 'boolean'
        ]);

        try {
            $employee = Employee::create([
                'name' => $validated['name'],
                'employee_no' => $validated['employee_no'],
                'position' => $validated['position'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'hire_date' => $validated['hire_date'],
            ]);

            // Handle photo uploads
            $photoUpdates = [];

            if ($request->hasFile('profile_photo')) {
                $path = $request->file('profile_photo')->store('employees/photos', 'public');
                $photoUpdates['profile_photo'] = $path;
            }

            if ($request->hasFile('id_badge_photo')) {
                $path = $request->file('id_badge_photo')->store('employees/photos', 'public');
                $photoUpdates['id_badge_photo'] = $path;
            }

            if ($request->hasFile('uniform_photo')) {
                $path = $request->file('uniform_photo')->store('employees/photos', 'public');
                $photoUpdates['uniform_photo'] = $path;
            }

            // Update consent
            if ($request->has('photo_consent_given')) {
                $photoUpdates['photo_consent_given'] = $request->boolean('photo_consent_given');
                if ($request->boolean('photo_consent_given')) {
                    $photoUpdates['photo_consent_date'] = now();
                }
            }

            if (!empty($photoUpdates)) {
                $photoUpdates['photo_approved_at'] = now();
                $employee->update($photoUpdates);

                Log::info('Employee created with photos', [
                    'employee_id' => $employee->id,
                    'employee_name' => $employee->name,
                    'photos_uploaded' => array_keys($photoUpdates)
                ]);
            }

            return redirect()->route('employees.index')
                ->with('success', 'Employee created successfully.');

        } catch (\Exception $e) {
            Log::error('Failed to create employee: ' . $e->getMessage());
            return back()->with('error', 'Failed to create employee. Please try again.')
                        ->withInput();
        }
    }

    // Show single employee
    public function show(Employee $employee)
    {
        $recent_jobs = $employee->jobs()
            ->with(['customer', 'service'])
            ->latest()
            ->limit(5)
            ->get();
            
        $ratings_count = Rating::where('employee_id', $employee->id)->count();
            
        return view('admin.employees.show', compact('employee', 'recent_jobs', 'ratings_count'));
    }

    // Show edit form
    public function edit(Employee $employee)
    {
        return view('admin.employees.edit', compact('employee'));
    }

    // Update employee
    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'employee_no' => 'nullable|string|max:50|unique:employees,employee_no,' . $employee->id,
            'position' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'hire_date' => 'required|date',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'photo_consent_given' => 'nullable|boolean',
        ]);

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            if ($employee->profile_photo) {
                \Storage::delete($employee->profile_photo);
            }
            $path = $request->file('profile_photo')->store('employees/photos', 'public');
            $validated['profile_photo'] = $path;
        }

        // Handle consent
        if ($request->has('photo_consent_given')) {
            $validated['photo_consent_given'] = $request->boolean('photo_consent_given');
            if ($request->boolean('photo_consent_given')) {
                $validated['photo_consent_date'] = now();
            }
        }

        $employee->update($validated);

        return redirect()->route('employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    // Delete employee
    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')
            ->with('success', 'Employee deleted successfully.');
    }

    // Upload employee photos
    public function uploadPhotos(Request $request, Employee $employee)
    {
        $request->validate([
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'id_badge_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'uniform_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'photo_consent_given' => 'boolean'
        ]);

        try {
            $updates = [];

            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                if ($employee->profile_photo) {
                    Storage::delete($employee->profile_photo);
                }
                $path = $request->file('profile_photo')->store('employees/photos', 'public');
                $updates['profile_photo'] = $path;
            }

            // Handle ID badge photo upload
            if ($request->hasFile('id_badge_photo')) {
                if ($employee->id_badge_photo) {
                    Storage::delete($employee->id_badge_photo);
                }
                $path = $request->file('id_badge_photo')->store('employees/photos', 'public');
                $updates['id_badge_photo'] = $path;
            }

            // Handle uniform photo upload
            if ($request->hasFile('uniform_photo')) {
                if ($employee->uniform_photo) {
                    Storage::delete($employee->uniform_photo);
                }
                $path = $request->file('uniform_photo')->store('employees/photos', 'public');
                $updates['uniform_photo'] = $path;
            }

            // Update consent
            if ($request->has('photo_consent_given')) {
                $updates['photo_consent_given'] = $request->boolean('photo_consent_given');
                if ($request->boolean('photo_consent_given')) {
                    $updates['photo_consent_date'] = now();
                }
            }

            $hasPhoto = isset($updates['profile_photo']) || isset($updates['id_badge_photo']) || isset($updates['uniform_photo']);
            if ($hasPhoto) {
                $updates['photo_approved_at'] = now();
            }
            $employee->update($updates);

            Log::info('Employee photos uploaded', [
                'employee_id' => $employee->id,
                'employee_name' => $employee->name,
                'photos_uploaded' => array_keys($updates)
            ]);

            return back()->with('success', 'Photos uploaded successfully.');

        } catch (\Exception $e) {
            Log::error('Failed to upload employee photos: ' . $e->getMessage());
            return back()->with('error', 'Failed to upload photos. Please try again.');
        }
    }

    // Delete employee photo
    public function deletePhoto(Request $request, Employee $employee)
    {
        $request->validate([
            'photo_type' => 'required|in:profile_photo,id_badge_photo,uniform_photo'
        ]);

        try {
            $photoType = $request->photo_type;
            $photoPath = $employee->$photoType;

            if ($photoPath) {
                Storage::delete($photoPath);
                $employee->update([$photoType => null]);

                Log::info('Employee photo deleted', [
                    'employee_id' => $employee->id,
                    'employee_name' => $employee->name,
                    'photo_type' => $photoType
                ]);

                return back()->with('success', 'Photo deleted successfully.');
            }

            return back()->with('error', 'Photo not found.');

        } catch (\Exception $e) {
            Log::error('Failed to delete employee photo: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete photo. Please try again.');
        }
    }

    // Employee performance metrics
    public function performance()
    {
        $employees = Employee::withCount([
                'jobs as completed_jobs_count' => function($query) {
                    $query->where('status', 'completed')
                        ->whereBetween('completed_at', [now()->startOfMonth(), now()->endOfMonth()]);
                },
                'jobs as active_jobs_count' => function($query) {
                    $query->whereIn('status', ['assigned', 'in_progress']);
                }
            ])
            ->withAvg('ratings', 'rating')
            ->with(['jobs' => function($query) {
                $query->where('status', 'completed')
                    ->whereBetween('completed_at', [now()->startOfMonth(), now()->endOfMonth()])
                    ->with('service');
            }])
            ->orderBy('completed_jobs_count', 'desc')
            ->paginate(10);

        $performanceMetrics = [
            'average_rating' => Rating::avg('rating'),
            'total_completed' => Job::where('status', 'completed')
                ->whereBetween('completed_at', [now()->startOfMonth(), now()->endOfMonth()])
                ->count(),
            'top_performer' => Employee::withCount(['jobs' => function($query) {
                    $query->where('status', 'completed')
                        ->whereBetween('completed_at', [now()->startOfMonth(), now()->endOfMonth()]);
                }])
                ->orderBy('jobs_count', 'desc')
                ->first()
        ];

        return view('admin.employees.performance', compact('employees', 'performanceMetrics'));
    }

    // Job assignments view
    public function assignments()
    {
        $activeJobs = Job::with(['employee', 'customer', 'service'])
            ->whereIn('status', ['assigned', 'in_progress'])
            ->orderBy('scheduled_date')
            ->paginate(15);

        $availableEmployees = Employee::orderBy('name')->get();

        return view('admin.employees.assignments', compact('activeJobs', 'availableEmployees'));
    }
}