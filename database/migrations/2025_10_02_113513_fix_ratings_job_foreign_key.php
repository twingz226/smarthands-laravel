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
        // Drop the existing foreign key constraint that references jobs_old
        Schema::table('ratings', function (Blueprint $table) {
            $table->dropForeign(['job_id']);
        });

        // Add the correct foreign key constraint that references jobs
        Schema::table('ratings', function (Blueprint $table) {
            $table->foreign('job_id')
                ->references('id')
                ->on('jobs')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to jobs_old if needed (though this shouldn't be used)
        Schema::table('ratings', function (Blueprint $table) {
            $table->dropForeign(['job_id']);
        });

        Schema::table('ratings', function (Blueprint $table) {
            $table->foreign('job_id')
                ->references('id')
                ->on('jobs_old')
                ->onDelete('cascade');
        });
    }
};
