<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('article_vehicle_declaration', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained('articles')->cascadeOnDelete();
            $table->foreignId('vehicle_declaration_id')->constrained('vehicle_declarations')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['article_id', 'vehicle_declaration_id'], 'art_veh_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('article_vehicle_declaration');
    }
};
