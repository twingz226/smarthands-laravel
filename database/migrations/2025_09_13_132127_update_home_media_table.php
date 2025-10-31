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
        if (Schema::hasTable('home_media')) {
            Schema::table('home_media', function (Blueprint $table) {
                // Add columns only if they don't already exist
                if (!Schema::hasColumn('home_media', 'title')) {
                    $table->string('title')->nullable()->after('id');
                }
                if (!Schema::hasColumn('home_media', 'description')) {
                    $table->text('description')->nullable()->after('title');
                }
                if (!Schema::hasColumn('home_media', 'media_type')) {
                    $table->string('media_type')->after('description');
                }
                if (!Schema::hasColumn('home_media', 'media_path')) {
                    $table->string('media_path')->after('media_type');
                }
                if (!Schema::hasColumn('home_media', 'poster_image')) {
                    $table->string('poster_image')->nullable()->after('media_path');
                }
                if (!Schema::hasColumn('home_media', 'section')) {
                    $table->string('section')->default('hero')->after('poster_image');
                }
                if (!Schema::hasColumn('home_media', 'display_order')) {
                    $table->unsignedInteger('display_order')->default(0)->after('section');
                }
                if (!Schema::hasColumn('home_media', 'is_active')) {
                    $table->boolean('is_active')->default(true)->after('display_order');
                }

                // Skip index creation - it's already defined in the create migration
                // The index is created in 2025_09_12_130927_create_home_media_table.php
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('home_media')) {
            Schema::table('home_media', function (Blueprint $table) {
                if (Schema::hasColumn('home_media', 'is_active')) {
                    $table->dropColumn('is_active');
                }
                if (Schema::hasColumn('home_media', 'display_order')) {
                    $table->dropColumn('display_order');
                }
                if (Schema::hasColumn('home_media', 'section')) {
                    $table->dropColumn('section');
                }
                if (Schema::hasColumn('home_media', 'poster_image')) {
                    $table->dropColumn('poster_image');
                }
                if (Schema::hasColumn('home_media', 'media_path')) {
                    $table->dropColumn('media_path');
                }
                if (Schema::hasColumn('home_media', 'media_type')) {
                    $table->dropColumn('media_type');
                }
                if (Schema::hasColumn('home_media', 'description')) {
                    $table->dropColumn('description');
                }
                if (Schema::hasColumn('home_media', 'title')) {
                    $table->dropColumn('title');
                }
            });
        }
    }
};
