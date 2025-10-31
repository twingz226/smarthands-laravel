<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Job;
use App\Mail\JobStarted;
use Illuminate\Support\Facades\Mail;

class TestJobStartedEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:job-started-email {job_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the job started email functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $jobId = $this->argument('job_id');
        
        if ($jobId) {
            $job = Job::with(['customer', 'service', 'employees'])->find($jobId);
            if (!$job) {
                $this->error("Job with ID {$jobId} not found.");
                return 1;
            }
        } else {
            // Get the first job with employees assigned and status 'assigned'
            $job = Job::with(['customer', 'service', 'employees'])
                ->whereHas('employees')
                ->where('status', 'assigned')
                ->first();
                
            if (!$job) {
                $this->error("No jobs with assigned employees and 'assigned' status found.");
                return 1;
            }
        }

        $this->info("Testing job started email for Job #{$job->id}");
        $this->info("Customer: {$job->customer->name} ({$job->customer->email})");
        $this->info("Service: {$job->service->name}");
        $this->info("Current Status: {$job->status}");
        $this->info("Assigned Cleaners: " . $job->employees->pluck('name')->implode(', '));

        // Temporarily set started_at for testing
        $job->started_at = now();

        try {
            Mail::to($job->customer->email)->send(new JobStarted($job));
            $this->info("✅ Job started email sent successfully to {$job->customer->email}");
        } catch (\Exception $e) {
            $this->error("❌ Failed to send job started email: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
} 