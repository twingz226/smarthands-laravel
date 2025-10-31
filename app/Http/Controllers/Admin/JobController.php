<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Mail\CleanerAssigned;
use App\Mail\JobStarted;
use App\Mail\BookingStatusUpdate;
use Carbon\Carbon;

class JobController extends Controller
{
    public function index()
    {
        $jobs = Job::with(['customer', 'service', 'employees'])
            ->orderBy('scheduled_date', 'asc')
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
        $validated = $request->validate([
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'exists:employees,id',
        ]);
        
        // If validation fails, Laravel will automatically redirect back with errors
        // and old input, including the job_id we added to the form
        
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

            // Reload the job with relationships for email
            $job->load(['customer', 'service', 'employees']);

            // Send email notification to customer
            try {
                Mail::to($job->customer->email)->queue(new CleanerAssigned($job));
                Log::info('Cleaner assignment email queued successfully', [
                    'job_id' => $job->id,
                    'customer_email' => $job->customer->email
                ]);
            } catch (\Exception $emailException) {
                Log::error('Failed to queue cleaner assignment email: ' . $emailException->getMessage(), [
                    'job_id' => $job->id,
                    'customer_email' => $job->customer->email
                ]);
                // Don't fail the entire operation if email fails
            }

            Log::info('Job assigned successfully', [
                'job_id' => $job->id,
                'employee_ids' => $request->employee_ids
            ]);

            DB::commit();
            return back()->with('success', 'Job assigned successfully and customer notified.');
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

            // Reload the job with relationships for email
            $job->load(['customer', 'service', 'employees']);

            // Send email notification to customer about reassignment
            try {
                Mail::to($job->customer->email)->queue(new CleanerAssigned($job));
                Log::info('Cleaner reassignment email sent successfully', [
                    'job_id' => $job->id,
                    'customer_email' => $job->customer->email
                ]);
            } catch (\Exception $emailException) {
                Log::error('Failed to send cleaner reassignment email: ' . $emailException->getMessage(), [
                    'job_id' => $job->id,
                    'customer_email' => $job->customer->email
                ]);
                // Don't fail the entire operation if email fails
            }
            
            Log::info('Job reassigned successfully', [
                'job_id' => $job->id,
                'old_employee_ids' => $job->employees()->pluck('employee_id'),
                'new_employee_ids' => $request->employee_ids
            ]);
            
            DB::commit();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Job reassigned successfully and customer notified.'
                ]);
            }
            
            return back()->with('success', 'Job reassigned successfully and customer notified.');
            
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
                Job::STATUS_COMPLETED,
                Job::STATUS_CANCELLED
            ])
            ->orderByRaw("CASE 
                WHEN status = 'pending' THEN 1
                WHEN status = 'assigned' THEN 2
                WHEN status = 'in_progress' THEN 3
                WHEN status = 'completed' THEN 4
                WHEN status = 'cancelled' THEN 5
                ELSE 6
            END")
            ->orderBy('scheduled_date', 'asc')
            ->get();

        $availableEmployees = Employee::whereDoesntHave('jobs', function($query) {
            $query->whereIn('status', [Job::STATUS_ASSIGNED, Job::STATUS_IN_PROGRESS]);
        })->get();

        return view('admin.jobs.tracking', compact('jobs', 'availableEmployees'));
    }

    public function reschedule(Job $job)
    {
        return view('admin.jobs.reschedule', compact('job'));
    }

    public function cancel(Request $request, Job $job)
    {
        try {
            DB::beginTransaction();
            
            // Update job status
            $job->update(['status' => Job::STATUS_CANCELLED]);
            
            // Reload the job with relationships for email
            $job->load(['customer', 'service', 'employees']);
            
            // Send cancellation email to customer
            try {
                $cancellationReason = $request->input('cancellation_reason', 'No reason provided.');
                Mail::to($job->customer->email)->queue(new \App\Mail\JobCancelled($job, $cancellationReason));
                
                Log::info('Job cancellation email sent successfully', [
                    'job_id' => $job->id,
                    'customer_email' => $job->customer->email
                ]);
            } catch (\Exception $emailException) {
                Log::error('Failed to send job cancellation email: ' . $emailException->getMessage(), [
                    'job_id' => $job->id,
                    'customer_email' => $job->customer->email ?? 'No customer email'
                ]);
                // Don't fail the entire operation if email fails
            }
            
            DB::commit();
            return back()->with('success', 'Job cancelled successfully and customer has been notified.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to cancel job: ' . $e->getMessage());
            return back()->with('error', 'Failed to cancel job. Please try again.');
        }
    }

    public function updateReschedule(Request $request, Job $job)
    {
        $request->validate([
            'new_cleaning_date' => 'required|date|after_or_equal:today',
            'new_cleaning_time' => 'required|date_format:H:i',
        ]);

        try {
            // Combine date and time using app timezone, then convert to UTC for storage
            $scheduledDateTime = Carbon::parse(
                $request->input('new_cleaning_date') . ' ' . $request->input('new_cleaning_time'),
                config('app.timezone')
            )->setTimezone('UTC');

            if ($scheduledDateTime->isPast()) {
                return back()->with('error', 'Cannot reschedule to a past date and time.')->withInput();
            }

            // Update both job and related booking
            DB::beginTransaction();

            $job->update([
                'scheduled_date' => $scheduledDateTime,
                'status' => Job::STATUS_PENDING, // Set to pending (or a specific rescheduled status if available)
            ]);

            // Update the related booking's cleaning_date if it exists
            if ($job->booking) {
                $job->booking->update([
                    'cleaning_date' => $scheduledDateTime,
                    'status' => \App\Models\Booking::STATUS_RESCHEDULED,
                ]);
            }

            // Send reschedule email notification to customer
            if ($job->booking && $job->customer) {
                try {
                    Mail::to($job->customer->email)->queue(new BookingStatusUpdate($job->booking));
                    Log::info('Job reschedule email sent successfully', [
                        'job_id' => $job->id,
                        'booking_id' => $job->booking->id,
                        'customer_email' => $job->customer->email
                    ]);
                } catch (\Exception $emailException) {
                    Log::error('Failed to send job reschedule email: ' . $emailException->getMessage(), [
                        'job_id' => $job->id,
                        'booking_id' => $job->booking->id,
                        'customer_email' => $job->customer->email ?? 'No customer email'
                    ]);
                    // Don't fail the entire operation if email fails
                }
            }

            DB::commit();

            return redirect()->route('jobs.tracking')->with('success', 'Job rescheduled successfully and customer has been notified.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to reschedule job: ' . $e->getMessage());
            return back()->with('error', 'Failed to reschedule job. Please try again.');
        }
    }

    // Update job status
    public function updateStatus(Request $request, Job $job)
    {
        $request->validate([
            'status' => 'required|in:pending,assigned,in_progress,completed',
        ]);
        
        try {
            DB::beginTransaction();

            $oldStatus = $job->status;

            if ($request->status === Job::STATUS_COMPLETED) {
                $job->markAsCompleted();
            } else {
                $updates = ['status' => $request->status];
                
                if ($request->status === Job::STATUS_IN_PROGRESS) {
                    $updates['started_at'] = now();
                }
                
                $job->update($updates);
            }

            // Send email notification when job is started
            if ($request->status === Job::STATUS_IN_PROGRESS && $oldStatus !== Job::STATUS_IN_PROGRESS) {
                // Reload the job with relationships for email
                $job->load(['customer', 'service', 'employees']);

                try {
                    Mail::to($job->customer->email)->queue(new JobStarted($job));
                    Log::info('Job started email sent successfully', [
                        'job_id' => $job->id,
                        'customer_email' => $job->customer->email
                    ]);
                } catch (\Exception $emailException) {
                    Log::error('Failed to send job started email: ' . $emailException->getMessage(), [
                        'job_id' => $job->id,
                        'customer_email' => $job->customer->email
                    ]);
                    // Don't fail the entire operation if email fails
                }
            }

            Log::info('Job status updated', [
                'job_id' => $job->id,
                'old_status' => $oldStatus,
                'new_status' => $request->status
            ]);

            DB::commit();
            
            $message = 'Job status updated successfully.';
            if ($request->status === Job::STATUS_IN_PROGRESS && $oldStatus !== Job::STATUS_IN_PROGRESS) {
                $message = 'Job started successfully and customer notified.';
            }
            
            return back()->with('success', $message);
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

            // Reload the job with relationships for email
            $job->load(['customer', 'service', 'employees']);

            // Send email notification to customer
            try {
                Mail::to($job->customer->email)->queue(new CleanerAssigned($job));
                Log::info('Cleaner assignment email queued via tracking update', [
                    'job_id' => $job->id,
                    'customer_email' => $job->customer->email
                ]);
            } catch (\Exception $emailException) {
                Log::error('Failed to send cleaner assignment email via tracking update: ' . $emailException->getMessage(), [
                    'job_id' => $job->id,
                    'customer_email' => $job->customer->email
                ]);
                // Don't fail the entire operation if email fails
            }

            Log::info('Job tracking updated', [
                'job_id' => $job->id,
                'employee_ids' => $request->employee_ids
            ]);

            DB::commit();
            return back()->with('success', 'Job tracking updated successfully and customer notified.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update job tracking: ' . $e->getMessage());
            return back()->with('error', 'Failed to update job tracking. Please try again.');
        }
    }
}