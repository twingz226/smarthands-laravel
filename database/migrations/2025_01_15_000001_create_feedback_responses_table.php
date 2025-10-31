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
        Schema::create('feedback_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('feedback_id')->constrained('customer_feedback')->onDelete('cascade');
            $table->enum('response_type', ['acknowledgment', 'resolution', 'follow_up']);
            $table->text('response_text');
            $table->foreignId('responded_by')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('is_internal_note')->default(false);
            $table->timestamps();

            // Indexes
            $table->index(['feedback_id', 'created_at']);
            $table->index('response_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback_responses');
    }
}; 