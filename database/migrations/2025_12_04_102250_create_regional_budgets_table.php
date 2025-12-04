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
        Schema::create('regional_budgets', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->foreignId('situation_administrative_id')->constrained('situation_administratives')->onDelete('cascade');
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
            
            // Indexes
            $table->index(['situation_administrative_id', 'year']);
            $table->index('year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regional_budgets');
    }
};
