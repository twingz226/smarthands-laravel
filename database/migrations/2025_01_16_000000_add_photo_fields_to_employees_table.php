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
        Schema::table('employees', function (Blueprint $table) {
            $table->string('profile_photo')->nullable()->after('hire_date');
            $table->string('id_badge_photo')->nullable()->after('profile_photo');
            $table->string('uniform_photo')->nullable()->after('id_badge_photo');
            $table->timestamp('photo_approved_at')->nullable()->after('uniform_photo');
            $table->timestamp('photo_expires_at')->nullable()->after('photo_approved_at');
            $table->boolean('photo_consent_given')->default(false)->after('photo_expires_at');
            $table->timestamp('photo_consent_date')->nullable()->after('photo_consent_given');
            $table->text('photo_notes')->nullable()->after('photo_consent_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'profile_photo',
                'id_badge_photo', 
                'uniform_photo',
                'photo_approved_at',
                'photo_expires_at',
                'photo_consent_given',
                'photo_consent_date',
                'photo_notes'
            ]);
        });
    }
}; 