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
        Schema::table('bookings', function (Blueprint $table) {
            // Track customer-initiated reschedules count
            $table->integer('customer_reschedule_count')->default(0)->after('reschedule_reason');
            // Track who performed the reschedule (customer vs admin)
            $table->unsignedBigInteger('rescheduled_by')->nullable()->after('customer_reschedule_count');
            // Track if this was an admin-initiated reschedule
            $table->boolean('is_admin_reschedule')->default(false)->after('rescheduled_by');
            
            // Add foreign key for rescheduled_by if it references users table
            $table->foreign('rescheduled_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['rescheduled_by']);
            $table->dropColumn(['customer_reschedule_count', 'rescheduled_by', 'is_admin_reschedule']);
        });
    }
};