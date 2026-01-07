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
        if (!Schema::hasTable('article_situation_administrative')) {
            Schema::create('article_situation_administrative', function (Blueprint $table) {
                $table->id();
                $table->foreignId('article_id')->constrained('articles')->onDelete('cascade');
                $table->foreignId('situation_administrative_id')->constrained('situation_administratives')->onDelete('cascade');
                $table->timestamps();
                
                // Unique constraint to prevent duplicate entries
                $table->unique(['article_id', 'situation_administrative_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_situation_administrative');
    }
};

