<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('article_nature_de_coupe')) {
            Schema::create('article_nature_de_coupe', function (Blueprint $table) {
                $table->id();
                $table->foreignId('article_id')->constrained('articles')->onDelete('cascade');
                $table->foreignId('nature_de_coupe_id')->constrained('nature_de_coupes')->onDelete('cascade');
                $table->timestamps();
                
                // Unique constraint to prevent duplicate entries
                $table->unique(['article_id', 'nature_de_coupe_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_nature_de_coupe');
    }
};

