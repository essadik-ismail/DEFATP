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
        Schema::table('articles', function (Blueprint $table) {
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
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            if (Schema::hasColumn('articles', 'date_echeance_taxe_refection_chemins')) {
                $table->dropColumn('date_echeance_taxe_refection_chemins');
            }
            if (Schema::hasColumn('articles', 'date_echeance_service_rendu_anef')) {
                $table->dropColumn('date_echeance_service_rendu_anef');
            }
            if (Schema::hasColumn('articles', 'mise_en_charge_destination')) {
                $table->dropColumn('mise_en_charge_destination');
            }
            if (Schema::hasColumn('articles', 'mise_en_charge_volume')) {
                $table->dropColumn('mise_en_charge_volume');
            }
            if (Schema::hasColumn('articles', 'date_echeance_mise_en_charge')) {
                $table->dropColumn('date_echeance_mise_en_charge');
            }
        });
    }
};
