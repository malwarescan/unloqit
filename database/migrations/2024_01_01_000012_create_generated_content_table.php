<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('generated_content', function (Blueprint $table) {
            $table->id();
            $table->string('content_type')->comment('city, service, neighborhood, guide, faq');
            $table->foreignId('city_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('service_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('neighborhood_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('template_id')->nullable()->constrained('content_templates')->onDelete('set null');
            $table->text('content');
            $table->text('meta_description')->nullable();
            $table->string('title')->nullable();
            $table->boolean('is_auto_generated')->default(true);
            $table->boolean('is_published')->default(true);
            $table->timestamp('generated_at')->nullable();
            $table->timestamps();
            
            $table->index(['content_type', 'is_published']);
            $table->index(['city_id', 'service_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('generated_content');
    }
};

