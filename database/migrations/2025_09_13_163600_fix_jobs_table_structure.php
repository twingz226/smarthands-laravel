<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FixJobsTableStructure extends Migration
{
    public function up()
    {
        // Check if the jobs table exists
        if (Schema::hasTable('jobs')) {
            // Rename the existing jobs table to avoid conflicts
            Schema::rename('jobs', 'jobs_old');
        }

        // Create the new jobs table with the correct structure
        Schema::create('jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('queue')->index();
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('jobs');
        
        // If we had an old table, rename it back
        if (Schema::hasTable('jobs_old')) {
            Schema::rename('jobs_old', 'jobs');
        }
    }
}
