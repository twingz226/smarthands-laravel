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
            // Add price column if it doesn't exist
            if (!Schema::hasColumn('bookings', 'price')) {
                // Try to add after cleaning_date if duration doesn't exist
                if (Schema::hasColumn('bookings', 'duration')) {
                    $table->decimal('price', 8, 2)->nullable()->after('duration');
                } elseif (Schema::hasColumn('bookings', 'cleaning_date')) {
                    $table->decimal('price', 8, 2)->nullable()->after('cleaning_date');
                } else {
                    $table->decimal('price', 8, 2)->nullable();
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'price')) {
                $table->dropColumn('price');
            }
        });
    }
};
