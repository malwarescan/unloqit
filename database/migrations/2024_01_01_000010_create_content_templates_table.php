<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_templates', function (Blueprint $table) {
            $table->id();
            $table->string('type')->comment('city, service, neighborhood, guide, faq');
            $table->string('name');
            $table->text('template')->comment('Template with variables like {city_name}, {service_name}, etc.');
            $table->json('variables')->nullable()->comment('Available variables for this template');
            $table->text('meta_description_template')->nullable();
            $table->text('title_template')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('priority')->default(0)->comment('Priority for template selection');
            $table->timestamps();
            
            $table->index(['type', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_templates');
    }
};

