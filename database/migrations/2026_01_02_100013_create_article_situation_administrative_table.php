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
                $table->foreignId('article_id')
                    ->constrained('articles')
                    ->onDelete('cascade');

                // Use a shorter, explicit foreign key name to avoid MySQL length limits
                $table->unsignedBigInteger('situation_administrative_id');
                $table->foreign('situation_administrative_id', 'art_sitadm_sitadm_id_fk')
                    ->references('id')
                    ->on('situation_administratives')
                    ->onDelete('cascade');

                $table->timestamps();
                
                // Unique constraint to prevent duplicate entries (with explicit short index name)
                $table->unique(
                    ['article_id', 'situation_administrative_id'],
                    'art_sitadm_article_sitadm_unique'
                );
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

