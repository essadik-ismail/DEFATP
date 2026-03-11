<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Remove columns that are not in the latest ERD diagrams.
     */
    public function up(): void
    {
        // Clean up articles table
        Schema::table('articles', function (Blueprint $table) {
            // Foreign key code columns
            if (Schema::hasColumn('articles', 'dfp_code')) {
                $table->dropForeign(['dfp_code']);
                $table->dropColumn('dfp_code');
            }
            if (Schema::hasColumn('articles', 'zdtf_code')) {
                $table->dropForeign(['zdtf_code']);
                $table->dropColumn('zdtf_code');
            }
            if (Schema::hasColumn('articles', 'dpanef_code')) {
                $table->dropForeign(['dpanef_code']);
                $table->dropColumn('dpanef_code');
            }
            if (Schema::hasColumn('articles', 'dranef_code')) {
                $table->dropForeign(['dranef_code']);
                $table->dropColumn('dranef_code');
            }
        });

        // Clean up contract_ventes table
        Schema::table('contract_ventes', function (Blueprint $table) {

        });
    }

    /**
     * Reverse the migrations.
     * Re-create removed columns with their previous definitions.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            if (!Schema::hasColumn('articles', 'dranef_code')) {
                $table->string('dranef_code')->nullable()->after('invandu');
                $table->foreign('dranef_code')->references('code')->on('dranefs')->onDelete('set null');
            }
            if (!Schema::hasColumn('articles', 'dpanef_code')) {
                $table->string('dpanef_code')->nullable()->after('dranef_code');
                $table->foreign('dpanef_code')->references('code')->on('dpanefs')->onDelete('set null');
            }
            if (!Schema::hasColumn('articles', 'zdtf_code')) {
                $table->string('zdtf_code')->nullable()->after('dpanef_code');
                $table->foreign('zdtf_code')->references('code')->on('zdtfs')->onDelete('set null');
            }
            if (!Schema::hasColumn('articles', 'dfp_code')) {
                $table->string('dfp_code')->nullable()->after('zdtf_code');
                $table->foreign('dfp_code')->references('code')->on('dfps')->onDelete('set null');
            }

            if (!Schema::hasColumn('articles', 'nature_juridique')) {
                $table->string('nature_juridique')->nullable()->after('dfp_code');
            }
            if (!Schema::hasColumn('articles', 'canton')) {
                $table->string('canton')->nullable()->after('nature_juridique');
            }
            if (!Schema::hasColumn('articles', 'particuliere')) {
                $table->text('particuliere')->nullable()->after('canton');
            }
            if (!Schema::hasColumn('articles', 'date_echeance_taxe_refection_chemins')) {
                $table->date('date_echeance_taxe_refection_chemins')->nullable()->after('taxe_refection_chemins');
            }
            if (!Schema::hasColumn('articles', 'date_echeance_service_rendu_anef')) {
                $table->date('date_echeance_service_rendu_anef')->nullable()->after('service_rendu_anef');
            }
            if (!Schema::hasColumn('articles', 'mise_en_charge_destination')) {
                $table->string('mise_en_charge_destination')->nullable()->after('fourniture_mise_charge');
            }
            if (!Schema::hasColumn('articles', 'mise_en_charge_volume')) {
                $table->decimal('mise_en_charge_volume', 15, 2)->nullable()->after('mise_en_charge_destination');
            }
            if (!Schema::hasColumn('articles', 'date_echeance_mise_en_charge')) {
                $table->date('date_echeance_mise_en_charge')->nullable()->after('mise_en_charge_volume');
            }
            if (!Schema::hasColumn('articles', 'limite_nord')) {
                $table->string('limite_nord')->nullable()->after('particuliere');
            }
            if (!Schema::hasColumn('articles', 'limite_sud')) {
                $table->string('limite_sud')->nullable()->after('limite_nord');
            }
            if (!Schema::hasColumn('articles', 'limite_est')) {
                $table->string('limite_est')->nullable()->after('limite_sud');
            }
            if (!Schema::hasColumn('articles', 'limite_ouest')) {
                $table->string('limite_ouest')->nullable()->after('limite_est');
            }
            if (!Schema::hasColumn('articles', 'coordonnee_x')) {
                $table->decimal('coordonnee_x', 15, 6)->nullable()->after('limite_ouest');
            }
            if (!Schema::hasColumn('articles', 'coordonnee_y')) {
                $table->decimal('coordonnee_y', 15, 6)->nullable()->after('coordonnee_x');
            }
        });

        Schema::table('contract_ventes', function (Blueprint $table) {
            if (!Schema::hasColumn('contract_ventes', 'date_adjudication')) {
                $table->date('date_adjudication')->nullable()->after('id');
            }
            if (!Schema::hasColumn('contract_ventes', 'id_decheance')) {
                $table->string('id_decheance')->nullable()->after('date_de_decheance');
            }
            if (!Schema::hasColumn('contract_ventes', 'type')) {
                $table->string('type')->nullable()->after('nombre_tranche');
            }
            if (!Schema::hasColumn('contract_ventes', 'numeraAO')) {
                $table->string('numeraAO')->nullable()->after('type');
            }
            if (!Schema::hasColumn('contract_ventes', 'duree_decheache')) {
                $table->string('duree_decheache')->nullable()->after('date_de_decheance');
            }
            if (!Schema::hasColumn('contract_ventes', 'date_limite_tranche')) {
                $table->date('date_limite_tranche')->nullable()->after('nombre_tranche');
            }
            if (!Schema::hasColumn('contract_ventes', 'date_limite_taxes')) {
                $table->date('date_limite_taxes')->nullable()->after('date_limite_tranche');
            }
        });
    }
};

