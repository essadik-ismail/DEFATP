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
        if (!Schema::hasTable('depot_article')) {
            Schema::create('depot_article', function (Blueprint $table) {
                $table->id();
                $table->foreignId('id_article')->constrained('articles')->onDelete('cascade');
                $table->foreignId('id_depot')->constrained('depot')->onDelete('cascade');
                $table->timestamps();
                
                // Unique constraint to prevent duplicate entries
                $table->unique(['id_article', 'id_depot']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('depot_article');
    }
};

