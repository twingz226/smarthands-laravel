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
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Fix customer_feedback foreign key if the table exists
        if (Schema::hasTable('customer_feedback')) {
            Schema::table('customer_feedback', function (Blueprint $table) {
                // Drop the old foreign key pointing to jobs_old
                $table->dropForeign(['job_id']);
            });

            Schema::table('customer_feedback', function (Blueprint $table) {
                // Add the correct foreign key pointing to jobs
                $table->foreign('job_id')
                    ->references('id')
                    ->on('jobs')
                    ->onDelete('cascade');
            });
        }

        // Drop the old jobs_old table that is no longer needed
        Schema::dropIfExists('jobs_old');

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We won't recreate jobs_old as it was a temporary table
        // during migration and should not be restored
    }
};
