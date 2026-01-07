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
        if (!Schema::hasTable('article_parcelle')) {
            Schema::create('article_parcelle', function (Blueprint $table) {
                $table->id();
                $table->foreignId('idarticle')->constrained('articles')->onDelete('cascade');
                $table->foreignId('idparcelle')->constrained('parcelles')->onDelete('cascade');
                $table->timestamps();
                
                // Unique constraint to prevent duplicate entries
                $table->unique(['idarticle', 'idparcelle']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_parcelle');
    }
};

