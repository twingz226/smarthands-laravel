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
            // Check if columns don't exist before adding them
            if (!Schema::hasColumn('bookings', 'duration')) {
                $table->integer('duration')->comment('In hours')->after('cleaning_date');
            }
            
            if (!Schema::hasColumn('bookings', 'price')) {
                $table->decimal('price', 8, 2)->after('duration');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['duration', 'price']);
        });
    }
};
