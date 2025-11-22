<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('provider_availability', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_id')->constrained()->onDelete('cascade');
            $table->boolean('is_online')->default(false);
            $table->decimal('current_lat', 10, 8)->nullable();
            $table->decimal('current_lng', 11, 8)->nullable();
            $table->timestamp('last_seen_at')->nullable();
            $table->integer('max_jobs_per_hour')->default(2);
            $table->integer('active_jobs_count')->default(0);
            $table->timestamps();
            
            $table->index('provider_id');
            $table->index('is_online');
            $table->index(['is_online', 'last_seen_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('provider_availability');
    }
};

