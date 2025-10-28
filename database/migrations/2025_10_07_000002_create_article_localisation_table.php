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
        Schema::create('article_localisation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained('articles')->onDelete('cascade');
            $table->foreignId('localisation_id')->constrained('localisations')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['article_id', 'localisation_id'], 'al_unique');
        });

        // Backfill existing single selections into pivot
        if (Schema::hasColumn('articles', 'localisation_id')) {
            DB::statement('INSERT INTO article_localisation (article_id, localisation_id, created_at, updated_at)
                SELECT id AS article_id, localisation_id, NOW(), NOW() FROM articles WHERE localisation_id IS NOT NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_localisation');
    }
};


