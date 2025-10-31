<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers');
            $table->foreignId('service_id')->constrained('services');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->foreignId('cleaner_id')->nullable()->constrained('users');
            $table->dateTime('cleaning_date');
            $table->integer('duration')->comment('In hours');
            $table->decimal('price', 8, 2);
            $table->string('status')->default('pending')->index();
            $table->text('special_instructions')->nullable();
            $table->text('admin_notes')->nullable();
            $table->string('booking_token')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['cleaning_date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};