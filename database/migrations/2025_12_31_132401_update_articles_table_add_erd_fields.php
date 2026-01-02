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
            $table->decimal('taxe_refection_chemins', 10, 2)->nullable()->after('fourniture_mise_charge');
            $table->decimal('service_rendu_anef', 10, 2)->nullable()->after('taxe_refection_chemins');
            $table->decimal('bois_chauffage_volume', 10, 2)->nullable()->after('service_rendu_anef');
            $table->string('bois_chauffage_destination')->nullable()->after('bois_chauffage_volume');
            $table->date('date_payement_service_anef')->nullable()->after('bois_chauffage_destination');
            $table->date('date_livaison_mise_en_charge_bf')->nullable()->after('date_payement_service_anef');
            $table->foreignId('zdtf_id')->nullable()->after('date_livaison_mise_en_charge_bf')->constrained('zdtfs')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropForeign(['wdtf_id']);
            $table->dropColumn([
                'taxe_refection_chemins',
                'service_rendu_anef',
                'bois_chauffage_volume',
                'bois_chauffage_destination',
                'date_payement_service_anef',
                'date_livaison_mise_en_charge_bf',
                'zdtf_id'
            ]);
        });
    }
};
