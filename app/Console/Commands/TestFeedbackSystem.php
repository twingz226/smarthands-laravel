<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CustomerFeedback;
use App\Models\Job;
use App\Models\Customer;
use App\Models\Employee;

class TestFeedbackSystem extends Command
{
    protected $signature = 'test:feedback-system';
    protected $description = 'Test the customer feedback system with sample data';

    public function handle()
    {
        $this->info('Testing Customer Feedback System...');

        // Check if we have the required data
        $job = Job::with(['customer', 'employees'])->first();
        if (!$job) {
            $this->error('No jobs found. Please create some jobs first.');
            return 1;
        }

        $this->info("Using Job #{$job->id} for testing");

        // Create sample feedback
        try {
            $feedback = CustomerFeedback::create([
                'job_id' => $job->id,
                'customer_id' => $job->customer_id,
                'employee_id' => $job->employees->first()?->id,
                'overall_rating' => 5,
                'cleanliness_rating' => 5,
                'professionalism_rating' => 4,
                'punctuality_rating' => 5,
                'communication_rating' => 4,
                'value_rating' => 5,
                'comments' => 'Excellent service! The cleaners were very professional and thorough. Highly recommend!',
                'is_anonymous' => false,
                'feedback_type' => CustomerFeedback::TYPE_POST_SERVICE,
                'status' => CustomerFeedback::STATUS_PENDING
            ]);

            $this->info("✅ Sample feedback created successfully!");
            $this->info("Feedback ID: {$feedback->id}");
            $this->info("Overall Rating: {$feedback->overall_rating}/5");
            $this->info("Status: {$feedback->status}");

            // Test feedback methods
            $this->info("\nTesting feedback methods:");
            $this->info("Average Rating: " . $feedback->getAverageRating());
            $this->info("Is Positive: " . ($feedback->isPositive() ? 'Yes' : 'No'));
            $this->info("Customer Display Name: " . $feedback->getCustomerDisplayName());

            return 0;

        } catch (\Exception $e) {
            $this->error("❌ Failed to create sample feedback: " . $e->getMessage());
            return 1;
        }
    }
} 