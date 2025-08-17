<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('job_employee', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained()->onDelete('cascade');
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->timestamp('assigned_at')->nullable();
            $table->timestamps();

            // Prevent duplicate assignments
            $table->unique(['job_id', 'employee_id']);
        });

        // Copy existing job-employee relationships to the pivot table
        DB::table('jobs')
            ->whereNotNull('employee_id')
            ->get()
            ->each(function ($job) {
                DB::table('job_employee')->insert([
                    'job_id' => $job->id,
                    'employee_id' => $job->employee_id,
                    'assigned_at' => $job->assigned_at ?? now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            });

        // Remove the employee_id column from jobs table
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
            $table->dropColumn('employee_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back the employee_id column to jobs table
        Schema::table('jobs', function (Blueprint $table) {
            $table->foreignId('employee_id')->nullable()->constrained();
        });

        // Copy the first employee assignment back to the jobs table
        DB::table('job_employee')
            ->orderBy('assigned_at')
            ->get()
            ->groupBy('job_id')
            ->each(function ($assignments, $jobId) {
                $firstAssignment = $assignments->first();
                DB::table('jobs')
                    ->where('id', $jobId)
                    ->update([
                        'employee_id' => $firstAssignment->employee_id,
                        'assigned_at' => $firstAssignment->assigned_at,
                    ]);
            });

        Schema::dropIfExists('job_employee');
    }
}; 