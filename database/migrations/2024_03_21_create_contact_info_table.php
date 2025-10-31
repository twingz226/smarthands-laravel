<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('contact_info', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('phone');
            $table->string('address');
            $table->string('service_area');
            $table->string('business_hours');
            $table->string('facebook_url')->nullable();
            $table->text('google_business_url')->nullable();
            $table->text('about_content')->nullable();
            $table->text('mission')->nullable();
            $table->text('vision')->nullable();
            $table->text('services_offered')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('contact_info');
    }
}; 