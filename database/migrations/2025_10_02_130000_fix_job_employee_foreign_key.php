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

        // Drop the existing foreign key constraint that points to jobs_old
        Schema::table('job_employee', function (Blueprint $table) {
            $table->dropForeign(['job_id']);
        });

        // Add the correct foreign key constraint that points to jobs
        Schema::table('job_employee', function (Blueprint $table) {
            $table->foreign('job_id')
                ->references('id')
                ->on('jobs')
                ->onDelete('cascade');
        });

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Drop the corrected foreign key
        Schema::table('job_employee', function (Blueprint $table) {
            $table->dropForeign(['job_id']);
        });

        // Add back the old foreign key (pointing to jobs_old)
        Schema::table('job_employee', function (Blueprint $table) {
            $table->foreign('job_id')
                ->references('id')
                ->on('jobs_old')
                ->onDelete('cascade');
        });

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};
