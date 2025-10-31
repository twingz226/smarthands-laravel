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
        Schema::table('home_media', function (Blueprint $table) {
            if (!Schema::hasColumn('home_media', 'price')) {
                $table->string('price')->nullable()->after('description');
            }
            if (!Schema::hasColumn('home_media', 'service_type')) {
                $table->string('service_type')->nullable()->after('price');
            }
            if (!Schema::hasColumn('home_media', 'service_id')) {
                $table->unsignedInteger('service_id')->nullable()->after('service_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('home_media', function (Blueprint $table) {
            if (Schema::hasColumn('home_media', 'service_id')) {
                $table->dropColumn('service_id');
            }
            if (Schema::hasColumn('home_media', 'service_type')) {
                $table->dropColumn('service_type');
            }
            if (Schema::hasColumn('home_media', 'price')) {
                $table->dropColumn('price');
            }
        });
    }
};
