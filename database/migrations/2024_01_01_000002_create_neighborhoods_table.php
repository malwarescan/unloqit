<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('neighborhoods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('city_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug');
            $table->timestamps();
            
            $table->unique(['city_id', 'slug']);
            $table->index(['city_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('neighborhoods');
    }
};

