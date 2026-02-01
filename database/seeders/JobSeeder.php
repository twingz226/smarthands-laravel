<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Job;
use App\Models\Service;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample customers first
        $customers = [
            [
                'name' => 'Juan Dela Cruz',
                'email' => 'juan.delacruz@email.com',
                'contact' => '0912-345-6789',
                'address' => '123 Main Street, Quezon City, Metro Manila'
            ],
            [
                'name' => 'Maria Santos',
                'email' => 'maria.santos@email.com',
                'contact' => '0913-456-7890',
                'address' => '456 Oak Avenue, Makati City, Metro Manila'
            ],
            [
                'name' => 'Roberto Reyes',
                'email' => 'roberto.reyes@email.com',
                'contact' => '0914-567-8901',
                'address' => '789 Pine Road, Mandaluyong City, Metro Manila'
            ],
            [
                'name' => 'Elena Garcia',
                'email' => 'elena.garcia@email.com',
                'contact' => '0915-678-9012',
                'address' => '321 Elm Street, Pasig City, Metro Manila'
            ],
            [
                'name' => 'Antonio Martinez',
                'email' => 'antonio.martinez@email.com',
                'contact' => '0916-789-0123',
                'address' => '654 Maple Drive, Taguig City, Metro Manila'
            ]
        ];

        $createdCustomers = [];
        foreach ($customers as $customerData) {
            $customer = Customer::firstOrCreate(
                ['email' => $customerData['email']],
                $customerData
            );
            $createdCustomers[] = $customer;
        }

        // Get services and employees
        $services = Service::all();
        $employees = Employee::all();

        if ($services->isEmpty() || $employees->isEmpty()) {
            $this->command->warn('Services or Employees not found. Please run ServicesTableSeeder and EmployeeSeeder first.');
            return;
        }

        // Create sample jobs for today
        $today = Carbon::today();
        $jobs = [
            // Assigned & In Progress Jobs
            [
                'customer' => $createdCustomers[0],
                'service' => $services->first(),
                'status' => Job::STATUS_ASSIGNED,
                'scheduled_time' => '08:00',
                'address' => '123 Main Street, Quezon City, Metro Manila',
                'special_instructions' => 'Please focus on kitchen and bathrooms',
                'employees' => [$employees[0], $employees[1]]
            ],
            [
                'customer' => $createdCustomers[1],
                'service' => $services->skip(1)->first(),
                'status' => Job::STATUS_IN_PROGRESS,
                'scheduled_time' => '09:30',
                'address' => '456 Oak Avenue, Makati City, Metro Manila',
                'special_instructions' => 'Client has pets, please be careful',
                'employees' => [$employees[2]]
            ],
            [
                'customer' => $createdCustomers[2],
                'service' => $services->skip(2)->first(),
                'status' => Job::STATUS_ASSIGNED,
                'scheduled_time' => '11:00',
                'address' => '789 Pine Road, Mandaluyong City, Metro Manila',
                'special_instructions' => 'Move-out cleaning, property is empty',
                'employees' => [$employees[3], $employees[4]]
            ],

            // Pending Jobs
            [
                'customer' => $createdCustomers[3],
                'service' => $services->skip(3)->first(),
                'status' => Job::STATUS_PENDING,
                'scheduled_time' => '13:00',
                'address' => '321 Elm Street, Pasig City, Metro Manila',
                'special_instructions' => 'Post-renovation cleaning needed',
                'employees' => []
            ],
            [
                'customer' => $createdCustomers[4],
                'service' => $services->skip(4)->first(),
                'status' => Job::STATUS_PENDING,
                'scheduled_time' => '14:30',
                'address' => '654 Maple Drive, Taguig City, Metro Manila',
                'special_instructions' => 'Regular maintenance cleaning',
                'employees' => []
            ],

            // Completed Jobs
            [
                'customer' => $createdCustomers[0],
                'service' => $services->first(),
                'status' => Job::STATUS_COMPLETED,
                'scheduled_time' => '06:00',
                'address' => '123 Main Street, Quezon City, Metro Manila',
                'special_instructions' => 'Early morning cleaning requested',
                'employees' => [$employees[0]],
                'completed_at' => $today->copy()->setTime('08', '30', '00')
            ],
            [
                'customer' => $createdCustomers[1],
                'service' => $services->skip(1)->first(),
                'status' => Job::STATUS_COMPLETED,
                'scheduled_time' => '07:00',
                'address' => '456 Oak Avenue, Makati City, Metro Manila',
                'special_instructions' => 'Deep cleaning service',
                'employees' => [$employees[1], $employees[2]],
                'completed_at' => $today->copy()->setTime('10', '00', '00')
            ]
        ];

        foreach ($jobs as $jobData) {
            // Create the job
            $job = Job::create([
                'customer_id' => $jobData['customer']->id,
                'service_id' => $jobData['service']->id,
                'scheduled_date' => $today->copy()->setTimeFromTimeString($jobData['scheduled_time']),
                'status' => $jobData['status'],
                'address' => $jobData['address'],
                'special_instructions' => $jobData['special_instructions'],
                'rating_token' => Job::generateRatingToken(),
                'started_at' => $jobData['status'] === Job::STATUS_IN_PROGRESS ? $today->copy()->setTimeFromTimeString($jobData['scheduled_time'])->addMinutes(30) : null,
                'completed_at' => $jobData['completed_at'] ?? null,
                'assigned_at' => in_array($jobData['status'], [Job::STATUS_ASSIGNED, Job::STATUS_IN_PROGRESS, Job::STATUS_COMPLETED]) ? $today->copy()->setTimeFromTimeString($jobData['scheduled_time'])->subHours(2) : null,
            ]);

            // Assign employees if any
            if (!empty($jobData['employees'])) {
                foreach ($jobData['employees'] as $employee) {
                    $job->employees()->attach($employee->id, [
                        'assigned_at' => $today->copy()->setTimeFromTimeString($jobData['scheduled_time'])->subHours(2)
                    ]);
                }
            }
        }

        $this->command->info('Job seeder completed successfully!');
        $this->command->info('Created jobs for daily schedule:');
        $this->command->info('- Assigned & In Progress Jobs: 3');
        $this->command->info('- Pending Jobs: 2');
        $this->command->info('- Completed Jobs: 2');
    }
}
