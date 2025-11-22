<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('providers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->text('bio')->nullable();
            $table->string('license_number')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_active')->default(true);
            $table->decimal('rating', 3, 2)->default(0)->comment('Average rating out of 5');
            $table->integer('total_jobs')->default(0);
            $table->json('service_areas')->nullable()->comment('Array of city IDs');
            $table->json('service_types')->nullable()->comment('Array of service IDs');
            $table->json('availability')->nullable()->comment('Availability schedule');
            $table->string('response_time')->nullable()->comment('Average response time');
            $table->timestamps();
            
            $table->index('is_active');
            $table->index('is_verified');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('providers');
    }
};

