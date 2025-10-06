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
                $table->foreignId('article_id')->constrained()->onDelete('cascade');
                $table->unsignedBigInteger('situation_administrative_id');
                $table->foreign('situation_administrative_id', 'asi_sai_fk')
                    ->references('id')
                    ->on('situation_administratives')
                    ->onDelete('cascade');
                $table->timestamps();
                
                // Ensure unique combinations
                $table->unique(['article_id', 'situation_administrative_id']);
            });
        } else {
            // Table exists (likely from a failed prior run); add missing columns and constraints.
            Schema::table('article_situation_administrative', function (Blueprint $table) {
                if (!Schema::hasColumn('article_situation_administrative', 'article_id')) {
                    $table->foreignId('article_id')->after('id')->constrained()->onDelete('cascade');
                }
                if (!Schema::hasColumn('article_situation_administrative', 'situation_administrative_id')) {
                    $table->unsignedBigInteger('situation_administrative_id')->after('article_id');
                    $table->foreign('situation_administrative_id', 'asi_sai_fk')
                        ->references('id')
                        ->on('situation_administratives')
                        ->onDelete('cascade');
                }
                // Add unique index if missing
                $table->unique(['article_id', 'situation_administrative_id'], 'asi_unique');
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
