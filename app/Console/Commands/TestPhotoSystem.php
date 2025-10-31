<?php

namespace App\Console\Commands;

use App\Models\Employee;
use Illuminate\Console\Command;

class TestPhotoSystem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:photo-system';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the photo identification system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Photo Identification System...');
        $this->newLine();

        // Test employee photo status
        $employees = Employee::all();
        
        if ($employees->isEmpty()) {
            $this->warn('No employees found. Please create some employees first.');
            return;
        }

        $this->info('Employee Photo Status Report:');
        $this->newLine();

        $headers = ['ID', 'Name', 'Has Photos', 'Approved', 'Expired', 'Consent Given'];
        $rows = [];

        foreach ($employees as $employee) {
            $rows[] = [
                $employee->id,
                $employee->name,
                $employee->hasPhotos() ? 'Yes' : 'No',
                $employee->hasApprovedPhotos() ? 'Yes' : 'No',
                $employee->photosExpired() ? 'Yes' : 'No',
                $employee->photo_consent_given ? 'Yes' : 'No'
            ];
        }

        $this->table($headers, $rows);

        // Test scopes
        $this->newLine();
        $this->info('Testing Employee Scopes:');
        
        $withApprovedPhotos = Employee::withApprovedPhotos()->count();
        $needsPhotoUpdate = Employee::needsPhotoUpdate()->count();
        
        $this->line("Employees with approved photos: {$withApprovedPhotos}");
        $this->line("Employees needing photo updates: {$needsPhotoUpdate}");

        // Test photo URLs
        $this->newLine();
        $this->info('Testing Photo URLs:');
        
        $employeeWithPhotos = $employees->first(function($emp) {
            return $emp->hasPhotos();
        });

        if ($employeeWithPhotos) {
            $this->line("Employee: {$employeeWithPhotos->name}");
            if ($employeeWithPhotos->profile_photo_url) {
                $this->line("Profile Photo URL: {$employeeWithPhotos->profile_photo_url}");
            }
            if ($employeeWithPhotos->id_badge_photo_url) {
                $this->line("ID Badge Photo URL: {$employeeWithPhotos->id_badge_photo_url}");
            }
            if ($employeeWithPhotos->uniform_photo_url) {
                $this->line("Uniform Photo URL: {$employeeWithPhotos->uniform_photo_url}");
            }
            if ($employeeWithPhotos->getPrimaryPhotoUrl()) {
                $this->line("Primary Photo URL: {$employeeWithPhotos->getPrimaryPhotoUrl()}");
            }
        } else {
            $this->warn('No employees with photos found.');
        }

        $this->newLine();
        $this->info('Photo System Test Completed!');
        
        $this->newLine();
        $this->info('Next Steps:');
        $this->line('1. Upload photos for employees via admin panel');
        $this->line('2. Approve photos for customer display');
        $this->line('3. Test email notifications with photos');
        $this->line('4. Monitor photo expiration dates');
    }
} 