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
        if (!Schema::hasTable('article_essence')) {
            Schema::create('article_essence', function (Blueprint $table) {
                $table->id();
                $table->foreignId('article_id')->constrained('articles')->onDelete('cascade');
                $table->foreignId('essence_id')->constrained('essences')->onDelete('cascade');
                $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
                $table->decimal('quantity', 15, 2)->nullable();
                $table->timestamps();
                
                // Unique constraint to prevent duplicate entries
                $table->unique(['article_id', 'essence_id', 'product_id'], 'article_essence_unique');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_essence');
    }
};

