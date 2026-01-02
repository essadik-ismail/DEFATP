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
        // Drop old financial tables
        Schema::dropIfExists('province_annual_shares');
        Schema::dropIfExists('regional_budgets');
        Schema::dropIfExists('monthly_revenues');
        Schema::dropIfExists('national_summaries');

        // Create new NationalSummary table based on the image structure
        Schema::create('national_summaries', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->integer('month')->nullable();
            $table->decimal('budget_general_frais_adjudication', 15, 2)->default(0);
            $table->decimal('budget_general_taj', 15, 2)->default(0);
            $table->decimal('budget_general_taxe_reconnaissance', 15, 2)->default(0);
            $table->decimal('budget_general_total', 15, 2)->default(0);
            $table->decimal('part_etat', 15, 2)->default(0);
            $table->decimal('cas_fnf_total', 15, 2)->default(0);
            $table->decimal('cas_chasse_peche_total', 15, 2)->default(0);
            $table->decimal('communes_bois_tanin', 15, 2)->default(0);
            $table->decimal('communes_liege', 15, 2)->default(0);
            $table->decimal('communes_pam_produits_divers', 15, 2)->default(0);
            $table->decimal('communes_redevances_parcours', 15, 2)->default(0);
            $table->decimal('communes_occupations_temporaires', 15, 2)->default(0);
            $table->decimal('communes_autres_taxes', 15, 2)->default(0);
            $table->decimal('communes_total', 15, 2)->default(0);
            $table->decimal('provinces_liege', 15, 2)->default(0);
            $table->decimal('provinces_bois_tanin', 15, 2)->default(0);
            $table->decimal('provinces_Alfa', 15, 2)->default(0);
            $table->decimal('provinces_pam_produits_divers', 15, 2)->default(0);
            $table->decimal('provinces_interets_retard', 15, 2)->default(0);
            $table->decimal('provinces_total', 15, 2)->default(0);
            $table->decimal('total_general', 15, 2)->default(0);
            $table->foreignId('situation_administrative_id')->nullable()->constrained('situation_administratives')->onDelete('set null');
            $table->timestamps();
            
            // Indexes
            $table->index(['year', 'month']);
            $table->index('situation_administrative_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop new table
        Schema::dropIfExists('national_summaries');

        // Recreate old tables (simplified structure for rollback)
        Schema::create('province_annual_shares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('situation_administrative_id')->nullable()->constrained('situation_administratives')->onDelete('set null');
            $table->integer('year');
            $table->decimal('llege', 15, 2)->default(0);
            $table->decimal('bols_charbon_tanin', 15, 2)->default(0);
            $table->decimal('alfa', 15, 2)->default(0);
            $table->decimal('produits_divers', 15, 2)->default(0);
            $table->decimal('interets_retard', 15, 2)->default(0);
            $table->decimal('total_province', 15, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('regional_budgets', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->foreignId('situation_administrative_id')->nullable()->constrained('situation_administratives')->onDelete('set null');
            $table->decimal('taxe_adjudication_1_6', 15, 2)->default(0);
            $table->decimal('taxe_reconnaissance_interets', 15, 2)->default(0);
            $table->decimal('ta_saisie_caution', 15, 2)->default(0);
            $table->decimal('budget_fmf', 15, 2)->default(0);
            $table->decimal('remboursement_drs', 15, 2)->default(0);
            $table->decimal('remboursement_fmf_autres', 15, 2)->default(0);
            $table->decimal('taxe_fmf_20', 15, 2)->default(0);
            $table->decimal('taxe_mise_en_charge', 15, 2)->default(0);
            $table->decimal('chasse_peche', 15, 2)->default(0);
            $table->decimal('taxe_12_bois_importes', 15, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('monthly_revenues', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->integer('month');
            $table->decimal('llege', 15, 2)->default(0);
            $table->decimal('bols_charbon_tanin', 15, 2)->default(0);
            $table->decimal('alfa', 15, 2)->default(0);
            $table->decimal('produits_divers', 15, 2)->default(0);
            $table->decimal('interets_retard', 15, 2)->default(0);
            $table->decimal('total_part_province', 15, 2)->default(0);
            $table->foreignId('situation_administrative_id')->nullable()->constrained('situation_administratives')->onDelete('set null');
            $table->timestamps();
        });

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
            $table->unique('year');
            $table->index('year');
        });
    }
};
