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
        // Create new article_province pivot table
        if (!Schema::hasTable('article_province')) {
            Schema::create('article_province', function (Blueprint $table) {
                $table->id();
                $table->foreignId('article_id')->constrained('articles')->onDelete('cascade');
                $table->foreignId('province_id')->constrained('provinces')->onDelete('cascade');
                $table->timestamps();
                
                // Unique constraint to prevent duplicate entries
                $table->unique(['article_id', 'province_id']);
            });
        }

        // Drop old article_situation_administrative table
        if (Schema::hasTable('article_situation_administrative')) {
            Schema::dropIfExists('article_situation_administrative');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate old table
        if (!Schema::hasTable('article_situation_administrative')) {
            Schema::create('article_situation_administrative', function (Blueprint $table) {
                $table->id();
                $table->foreignId('article_id')->constrained('articles')->onDelete('cascade');
                $table->foreignId('situation_administrative_id')->constrained('situation_administratives')->onDelete('cascade');
                $table->timestamps();
                $table->unique(['article_id', 'situation_administrative_id']);
            });
        }

        // Drop new table
        if (Schema::hasTable('article_province')) {
            Schema::dropIfExists('article_province');
        }
    }
};
