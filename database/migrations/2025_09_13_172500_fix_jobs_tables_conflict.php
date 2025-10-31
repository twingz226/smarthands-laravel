<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FixJobsTablesConflict extends Migration
{
    public function up()
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // 1. Rename the queue jobs table to job_queue if it exists
        if (Schema::hasTable('jobs')) {
            Schema::rename('jobs', 'job_queue');
        }
        
        // 2. Recreate the service jobs table if it doesn't exist
        if (!Schema::hasTable('jobs')) {
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
                $table->timestamps();
                
                $table->index(['status', 'scheduled_date']);
            });
        }
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function down()
    {
        // This is a one-way migration to fix the conflict
        // Rollback would require manual intervention
    }
}
