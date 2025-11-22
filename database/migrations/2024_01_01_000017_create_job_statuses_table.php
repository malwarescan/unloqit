<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['created', 'broadcast', 'claimed', 'en_route', 'arrived', 'in_progress', 'completed', 'cancelled']);
            $table->text('notes')->nullable();
            $table->foreignId('updated_by_provider_id')->nullable()->constrained('providers')->onDelete('set null');
            $table->timestamps();
            
            $table->index('job_id');
            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_statuses');
    }
};

