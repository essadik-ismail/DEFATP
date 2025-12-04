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
        Schema::create('national_summaries', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->decimal('budget_general_frais_adjudication', 15, 2)->default(0);
            $table->decimal('budget_general_ta', 15, 2)->default(0);
            $table->decimal('budget_general_taxe_reconnaissance', 15, 2)->default(0);
            $table->decimal('budget_general_total', 15, 2)->default(0);
            $table->decimal('part_etat', 15, 2)->default(0);
            $table->decimal('cas_fmf_total', 15, 2)->default(0);
            $table->decimal('cas_chasse_peche_total', 15, 2)->default(0);
            $table->decimal('communes_bois_tanin', 15, 2)->default(0);
            $table->decimal('communes_liege', 15, 2)->default(0);
            $table->decimal('communes_pam_produits_divers', 15, 2)->default(0);
            $table->decimal('communes_redevances_parcours', 15, 2)->default(0);
            $table->decimal('communes_occupations_temporaires', 15, 2)->default(0);
            $table->decimal('communes_autres_taxes', 15, 2)->default(0);
            $table->decimal('communes_total', 15, 2)->default(0);
            $table->decimal('provinces_bois_tanin', 15, 2)->default(0);
            $table->decimal('provinces_liege', 15, 2)->default(0);
            $table->decimal('provinces_pam_produits_divers', 15, 2)->default(0);
            $table->decimal('provinces_interets_retard', 15, 2)->default(0);
            $table->decimal('provinces_total', 15, 2)->default(0);
            $table->decimal('total_general', 15, 2)->default(0);
            $table->timestamps();
            
            // Indexes
            $table->unique('year');
            $table->index('year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('national_summaries');
    }
};
