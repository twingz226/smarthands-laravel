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
        Schema::table('ratings', function (Blueprint $table) {
            $table->foreignId('job_id')->after('id')->constrained('jobs')->onDelete('cascade');
            $table->foreignId('customer_id')->after('job_id')->constrained('customers')->onDelete('cascade');
            $table->text('comments')->nullable()->after('rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ratings', function (Blueprint $table) {
            $table->dropForeign(['job_id']);
            $table->dropForeign(['customer_id']);
            $table->dropColumn(['job_id', 'customer_id', 'comments']);
        });
    }
}; 