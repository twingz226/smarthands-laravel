<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\Employee;
use Illuminate\Http\Request;

class JobController extends Controller
{
    // Job dispatch view - for initial assignments
    public function dispatch()
    {
        $pendingJobs = Job::where('status', 'pending')
            ->with(['customer', 'service'])
            ->latest()
            ->get();
            
        $availableCleaners = Employee::whereDoesntHave('jobs', function($query) {
            $query->whereIn('status', ['assigned', 'in_progress']);
        })->get();
        
        return view('admin.jobs.dispatch', compact('pendingJobs', 'availableCleaners'));
    }

    // Assign job to cleaner (initial assignment)
    public function assign(Request $request, Job $job)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
        ]);
        
        $job->update([
            'employee_id' => $request->employee_id,
            'status' => 'assigned',
            'assigned_at' => now(),
        ]);
        
        return redirect()->route('jobs.dispatch')
            ->with('success', 'Job assigned successfully.');
    }

    // Active assignments view - for managing assigned jobs
    public function assignments()
    {
        $activeJobs = Job::with(['customer', 'service', 'employee'])
            ->whereIn('status', ['assigned', 'in_progress'])
            ->latest()
            ->get();
            
        $availableEmployees = Employee::whereDoesntHave('jobs', function($query) {
            $query->whereIn('status', ['assigned', 'in_progress']);
        })->get();
        
        return view('admin.employees.assignments', compact('activeJobs', 'availableEmployees'));
    }

    // Reassign job to different cleaner
    public function reassign(Request $request, Job $job)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
        ]);
        
        $job->update([
            'employee_id' => $request->employee_id,
            // Status remains the same (assigned or in_progress)
            'reassigned_at' => now(),
        ]);
        
        return back()->with('success', 'Job reassigned successfully.');
    }

    // Job tracking view
    public function tracking()
    {
        $jobs = Job::with(['customer', 'service', 'employee'])
            ->whereIn('status', ['assigned', 'in_progress', 'completed'])
            ->latest()
            ->get();
            
        return view('admin.jobs.tracking', compact('jobs'));
    }

    // Update job status
    public function updateStatus(Request $request, Job $job)
    {
        $request->validate([
            'status' => 'required|in:pending,assigned,in_progress,completed',
        ]);
        
        $updates = ['status' => $request->status];
        
        if ($request->status === 'completed') {
            $updates['completed_at'] = now();
        } elseif ($request->status === 'in_progress') {
            $updates['started_at'] = now();
        }
        
        $job->update($updates);
        
        return back()->with('success', 'Job status updated successfully.');
    }

    // Mark job as complete
    public function complete(Job $job)
    {
        $job->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
        
        return back()->with('success', 'Job marked as completed.');
    }
}