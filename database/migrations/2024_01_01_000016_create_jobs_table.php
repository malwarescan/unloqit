<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('provider_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('city_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->foreignId('neighborhood_id')->nullable()->constrained()->onDelete('set null');
            
            // Customer info (stored even if customer account deleted)
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email')->nullable();
            
            // Job details
            $table->text('description')->nullable();
            $table->enum('urgency', ['low', 'normal', 'high', 'emergency'])->default('normal');
            $table->enum('status', ['created', 'broadcast', 'claimed', 'en_route', 'arrived', 'in_progress', 'completed', 'cancelled'])->default('created');
            
            // Location
            $table->string('address')->nullable();
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lng', 11, 8)->nullable();
            
            // Pricing
            $table->decimal('quoted_price', 10, 2)->nullable();
            $table->decimal('final_price', 10, 2)->nullable();
            $table->string('payment_status')->default('pending')->comment('pending, paid, refunded');
            $table->string('stripe_payment_intent_id')->nullable();
            
            // Timing
            $table->timestamp('requested_at')->useCurrent();
            $table->timestamp('claimed_at')->nullable();
            $table->timestamp('en_route_at')->nullable();
            $table->timestamp('arrived_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->integer('estimated_minutes')->nullable();
            
            $table->timestamps();
            
            $table->index('status');
            $table->index('city_id');
            $table->index('service_id');
            $table->index('provider_id');
            $table->index('created_at');
            $table->index(['city_id', 'service_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};

