<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FixJobsTableStructureFinal extends Migration
{
    public function up()
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Drop the jobs table if it exists
        Schema::dropIfExists('jobs');

        // Recreate the jobs table with the correct structure
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers');
            $table->foreignId('service_id')->constrained('services');
            $table->dateTime('scheduled_date');
            $table->enum('status', ['pending', 'assigned', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->string('address');
            $table->text('special_instructions')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('reassigned_at')->nullable();
            $table->string('rating_token')->nullable();
            $table->foreignId('booking_id')->nullable()->constrained('bookings');
            $table->timestamps();
            
            $table->index(['status', 'scheduled_date']);
        });
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function down()
    {
        // This is a one-way migration to fix the table structure
        // Rollback would require manual intervention
    }
}
