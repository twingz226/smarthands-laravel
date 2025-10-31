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
        // Rename the existing notifications table
        if (Schema::hasTable('notifications')) {
            Schema::rename('notifications', 'legacy_notifications');
        }

        // Create the new notifications table with Laravel's standard structure
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
        
        // Restore the original table if it was renamed
        if (Schema::hasTable('legacy_notifications')) {
            Schema::rename('legacy_notifications', 'notifications');
        }
    }
};
