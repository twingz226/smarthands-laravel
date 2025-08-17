<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class JobController extends Controller
{
    public function index()
    {
        $jobs = Job::with(['customer', 'service', 'employees'])
            ->latest()
            ->paginate(10);
            
        return view('admin.jobs.index', compact('jobs'));
    }

    // Show job details
    public function show(Job $job)
    {
        $job->load(['customer', 'service', 'employees']);
        
        $availableEmployees = Employee::whereDoesntHave('jobs', function($query) {
            $query->whereIn('status', [Job::STATUS_ASSIGNED, Job::STATUS_IN_PROGRESS]);
        })->get();
        
        return view('admin.jobs.show', compact('job', 'availableEmployees'));
    }

    // Assign job to cleaners (initial assignment)
    public function assign(Request $request, Job $job)
    {
        $request->validate([
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'exists:employees,id',
        ]);
        
        try {
            DB::beginTransaction();

            $now = now();
            
            // Sync the employees with the job
            $job->employees()->sync(
                collect($request->employee_ids)->mapWithKeys(function ($id) use ($now) {
                    return [$id => ['assigned_at' => $now]];
                })
            );

            // Update job status
            $job->update([
                'status' => Job::STATUS_ASSIGNED,
                'assigned_at' => $now,
            ]);

            Log::info('Job assigned successfully', [
                'job_id' => $job->id,
                'employee_ids' => $request->employee_ids
            ]);

            DB::commit();
            return back()->with('success', 'Job assigned successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to assign job: ' . $e->getMessage());
            return back()->with('error', 'Failed to assign job. Please try again.');
        }
    }

    // Job assignments view
    public function assignments()
    {
        $activeJobs = Job::with(['employees', 'customer', 'service'])
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

    // Reassign job to different cleaners
    public function reassign(Request $request, Job $job)
    {
        $request->validate([
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'exists:employees,id',
        ]);
        
        try {
            DB::beginTransaction();
            
            $now = now();
            
            // Sync the new employees with the job
            $job->employees()->sync(
                collect($request->employee_ids)->mapWithKeys(function ($id) use ($now) {
                    return [$id => ['assigned_at' => $now]];
                })
            );
            
            $job->update([
                'reassigned_at' => $now,
            ]);
            
            Log::info('Job reassigned successfully', [
                'job_id' => $job->id,
                'old_employee_ids' => $job->employees()->pluck('employee_id'),
                'new_employee_ids' => $request->employee_ids
            ]);
            
            DB::commit();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Job reassigned successfully'
                ]);
            }
            
            return back()->with('success', 'Job reassigned successfully.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to reassign job: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to reassign job. Please try again.'
                ], 500);
            }
            
            return back()->with('error', 'Failed to reassign job. Please try again.');
        }
    }

    // Job tracking view
    public function tracking()
    {
        $jobs = Job::with(['customer', 'service', 'employees'])
            ->whereIn('status', [
                Job::STATUS_PENDING,
                Job::STATUS_ASSIGNED,
                Job::STATUS_IN_PROGRESS,
                Job::STATUS_COMPLETED
            ])
            ->latest()
            ->get();
            
        $availableEmployees = Employee::whereDoesntHave('jobs', function($query) {
                $query->whereIn('status', [Job::STATUS_ASSIGNED, Job::STATUS_IN_PROGRESS]);
            })
            ->orWhereHas('jobs', function($query) {
                $query->whereIn('status', [Job::STATUS_ASSIGNED, Job::STATUS_IN_PROGRESS]);
            }, '<', 3) // Employees with less than 3 active jobs
            ->orderBy('name')
            ->get();
            
        return view('admin.jobs.tracking', compact('jobs', 'availableEmployees'));
    }

    // Update job status
    public function updateStatus(Request $request, Job $job)
    {
        $request->validate([
            'status' => 'required|in:pending,assigned,in_progress,completed',
        ]);
        
        try {
            DB::beginTransaction();

            if ($request->status === Job::STATUS_COMPLETED) {
                $job->markAsCompleted();
            } else {
                $updates = ['status' => $request->status];
                
                if ($request->status === Job::STATUS_IN_PROGRESS) {
                    $updates['started_at'] = now();
                }
                
                $job->update($updates);
            }

            Log::info('Job status updated', [
                'job_id' => $job->id,
                'old_status' => $job->getOriginal('status'),
                'new_status' => $request->status
            ]);

            DB::commit();
            return back()->with('success', 'Job status updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update job status: ' . $e->getMessage());
            return back()->with('error', 'Failed to update job status. Please try again.');
        }
    }

    // Mark job as complete
    public function complete(Job $job)
    {
        try {
            DB::beginTransaction();

            $job->markAsCompleted();

            Log::info('Job marked as completed', [
                'job_id' => $job->id
            ]);

            DB::commit();
            return back()->with('success', 'Job marked as completed.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to complete job: ' . $e->getMessage());
            return back()->with('error', 'Failed to complete job. Please try again.');
        }
    }

    // Handle job tracking updates
    public function updateTracking(Request $request)
    {
        $request->validate([
            'job_id' => 'required|exists:jobs,id',
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'exists:employees,id',
        ]);

        try {
            DB::beginTransaction();

            $job = Job::findOrFail($request->job_id);
            $now = now();
            
            // Sync the employees with the job
            $job->employees()->sync(
                collect($request->employee_ids)->mapWithKeys(function ($id) use ($now) {
                    return [$id => ['assigned_at' => $now]];
                })
            );
            
            $job->update([
                'status' => Job::STATUS_ASSIGNED,
                'assigned_at' => $now,
            ]);

            Log::info('Job tracking updated', [
                'job_id' => $job->id,
                'employee_ids' => $request->employee_ids
            ]);

            DB::commit();
            return back()->with('success', 'Job tracking updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update job tracking: ' . $e->getMessage());
            return back()->with('error', 'Failed to update job tracking. Please try again.');
        }
    }
}