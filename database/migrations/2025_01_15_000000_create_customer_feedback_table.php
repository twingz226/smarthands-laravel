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
        Schema::create('customer_feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('employee_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('overall_rating')->comment('1-5 stars');
            $table->integer('cleanliness_rating')->nullable()->comment('1-5 stars');
            $table->integer('professionalism_rating')->nullable()->comment('1-5 stars');
            $table->integer('punctuality_rating')->nullable()->comment('1-5 stars');
            $table->integer('communication_rating')->nullable()->comment('1-5 stars');
            $table->integer('value_rating')->nullable()->comment('1-5 stars');
            $table->text('comments')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->enum('feedback_type', ['immediate', 'post_service', 'follow_up'])->default('post_service');
            $table->enum('status', ['pending', 'reviewed', 'responded', 'resolved'])->default('pending');
            $table->timestamps();

            // Indexes for better performance
            $table->index(['job_id', 'customer_id']);
            $table->index(['employee_id', 'created_at']);
            $table->index(['feedback_type', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_feedback');
    }
}; 