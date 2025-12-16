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
        // Common closure to create a PDFC step table
        $createStepTable = function (string $tableName) {
            Schema::create($tableName, function (Blueprint $table) {
                $table->id();
                $table->foreignId('pdfc_id')->constrained('pdfcs')->onDelete('cascade');
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
                $table->string('titre')->nullable();
                $table->text('description')->nullable();
                $table->string('document')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        };

        // Etape 1: Diagnostic Commune
        $createStepTable('etape1_diagnostic_communes');

        // Etape 2: Diagnostic Situation Forestière
        $createStepTable('etape2_diagnostic_situation_forestieres');

        // Etape 3: Analyse Usagers Forêts
        $createStepTable('etape3_analyse_usagers_forets');

        // Etape 4: Analyse Degré Acceptation
        $createStepTable('etape4_analyse_degre_acceptations');

        // Etape 5: Analyse Programmes Antérieurs
        $createStepTable('etape5_analyse_programmes_anterieurs');

        // Etape 6: Élaboration Projet / Programme
        $createStepTable('etape6_elaboration_projet_programmes');

        // Etape 7: Concertation Population
        $createStepTable('etape7_concertation_populations');

        // Etape 8: Validation DPANEF
        $createStepTable('etape8_validation_dpanefs');

        // Etape 9: Validation Finale Population
        $createStepTable('etape9_validation_finale_populations');

        // Etape 10: Finalisation PCFC
        $createStepTable('etape10_finalisation_pcfcs');

        // Etape 11: Validation Conseil Communal
        $createStepTable('etape11_validation_conseil_communaux');

        // Etape 12: Mise En Oeuvre PCFC
        $createStepTable('etape12_mise_en_oeuvre_pcfcs');

        // Etape 13: Suivi Mise En Oeuvre
        $createStepTable('etape13_suivi_mise_en_oeuvres');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etape13_suivi_mise_en_oeuvres');
        Schema::dropIfExists('etape12_mise_en_oeuvre_pcfcs');
        Schema::dropIfExists('etape11_validation_conseil_communaux');
        Schema::dropIfExists('etape10_finalisation_pcfcs');
        Schema::dropIfExists('etape9_validation_finale_populations');
        Schema::dropIfExists('etape8_validation_dpanefs');
        Schema::dropIfExists('etape7_concertation_populations');
        Schema::dropIfExists('etape6_elaboration_projet_programmes');
        Schema::dropIfExists('etape5_analyse_programmes_anterieurs');
        Schema::dropIfExists('etape4_analyse_degre_acceptations');
        Schema::dropIfExists('etape3_analyse_usagers_forets');
        Schema::dropIfExists('etape2_diagnostic_situation_forestieres');
        Schema::dropIfExists('etape1_diagnostic_communes');
    }
};


