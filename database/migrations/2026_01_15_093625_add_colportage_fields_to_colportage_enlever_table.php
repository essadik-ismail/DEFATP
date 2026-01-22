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
        Schema::table('colportage_enlever', function (Blueprint $table) {
            // Add columns for Permis de Colportage main data
            $table->foreignId('article_id')->nullable()->after('id')->constrained('articles')->onDelete('cascade');
            $table->date('date_debut')->nullable()->after('article_id');
            $table->date('date_fin')->nullable()->after('date_debut');
            $table->string('vehicule_immatriculation')->nullable()->after('date_fin');
            $table->string('chauffeur_nom')->nullable()->after('vehicule_immatriculation');
            $table->string('chauffeur_cin')->nullable()->after('chauffeur_nom');
            $table->string('destination')->nullable()->after('chauffeur_cin');
            $table->string('numero_permis')->nullable()->after('destination');
            
            // Make existing columns nullable since they might not always be used
            $table->foreignId('id_permis_enlever')->nullable()->change();
            $table->foreignId('product_id')->nullable()->change();
            $table->foreignId('id_essence')->nullable()->change();
            
            // Indexes
            $table->index('article_id');
            $table->index('numero_permis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('colportage_enlever', function (Blueprint $table) {
            $table->dropForeign(['article_id']);
            $table->dropIndex(['article_id']);
            $table->dropIndex(['numero_permis']);
            $table->dropColumn([
                'article_id',
                'date_debut',
                'date_fin',
                'vehicule_immatriculation',
                'chauffeur_nom',
                'chauffeur_cin',
                'destination',
                'numero_permis',
            ]);
        });
    }
};
