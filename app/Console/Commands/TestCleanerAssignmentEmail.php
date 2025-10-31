<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Job;
use App\Models\Employee;
use App\Mail\CleanerAssigned;
use Illuminate\Support\Facades\Mail;

class TestCleanerAssignmentEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:cleaner-assignment-email {job_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the cleaner assignment email functionality';

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
            // Get the first job with employees assigned
            $job = Job::with(['customer', 'service', 'employees'])
                ->whereHas('employees')
                ->first();
                
            if (!$job) {
                $this->error("No jobs with assigned employees found.");
                return 1;
            }
        }

        $this->info("Testing cleaner assignment email for Job #{$job->id}");
        $this->info("Customer: {$job->customer->name} ({$job->customer->email})");
        $this->info("Service: {$job->service->name}");
        $this->info("Assigned Cleaners: " . $job->employees->pluck('name')->implode(', '));

        try {
            Mail::to($job->customer->email)->send(new CleanerAssigned($job));
            $this->info("✅ Email sent successfully to {$job->customer->email}");
        } catch (\Exception $e) {
            $this->error("❌ Failed to send email: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
} 