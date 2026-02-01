<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create employee availability table
        Schema::create('employee_availability', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime');
            $table->string('status', 20)->default('unavailable'); // unavailable, busy, assigned
            $table->text('reason')->nullable(); // Reason for unavailability (e.g., job assignment)
            $table->foreignId('job_id')->nullable()->constrained()->onDelete('cascade'); // Link to job if unavailable due to assignment
            $table->timestamps();

            // Indexes for performance
            $table->index(['employee_id', 'start_datetime', 'end_datetime'], 'emp_availability_time_idx');
            $table->index(['start_datetime', 'end_datetime'], 'time_range_idx');
        });

        // Add availability status to employees table for quick reference
        Schema::table('employees', function (Blueprint $table) {
            $table->string('availability_status', 20)->default('available')->after('phone'); // available, unavailable, busy
            $table->text('availability_notes')->nullable()->after('availability_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['availability_status', 'availability_notes']);
        });
        
        Schema::dropIfExists('employee_availability');
    }
};
