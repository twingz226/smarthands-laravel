<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Job;
use App\Models\Rating;
use Illuminate\Http\Request;

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
            'email' => 'required|email|unique:employees,email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'hire_date' => 'required|date',
        ]);

        Employee::create($validated);

        return redirect()->route('employees.index')
            ->with('success', 'Employee created successfully.');
    }

    // Show single employee
    public function show(Employee $employee)
    {
        $recentJobs = Job::where('employee_id', $employee->id)
            ->with(['customer', 'service'])
            ->latest()
            ->limit(5)
            ->get();
            
        $ratings = Rating::where('employee_id', $employee->id)
            ->with(['customer', 'job'])
            ->latest()
            ->paginate(5);
            
        return view('admin.employees.show', compact('employee', 'recentJobs', 'ratings'));
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
            'email' => 'required|email|unique:employees,email,'.$employee->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'hire_date' => 'required|date',
        ]);

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

        $availableEmployees = Employee::whereDoesntHave('jobs', function($query) {
                $query->whereIn('status', ['assigned', 'in_progress']);
            })
            ->orWhereHas('jobs', function($query) {
                $query->whereIn('status', ['assigned', 'in_progress']);
            }, '<', 3) // Employees with less than 3 active jobs
            ->orderBy('name')
            ->get();

        return view('admin.employees.assignments', compact('activeJobs', 'availableEmployees'));
    }
}