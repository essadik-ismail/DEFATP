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
        Schema::table('national_summaries', function (Blueprint $table) {
            $table->decimal('lf_2009', 15, 2)->default(0)->after('cas_chasse_peche_total');
            $table->decimal('remboursement_drs', 15, 2)->default(0)->after('lf_2009');
            $table->decimal('remboursement_fnf_et_autres', 15, 2)->default(0)->after('remboursement_drs');
            $table->decimal('taxe_fnf_20_percent', 15, 2)->default(0)->after('remboursement_fnf_et_autres');
            $table->decimal('taxe_de_mise_en_charge', 15, 2)->default(0)->after('taxe_fnf_20_percent');
            $table->decimal('total_fnf', 15, 2)->default(0)->after('taxe_de_mise_en_charge');
            $table->decimal('chasse_et_peche', 15, 2)->default(0)->after('total_fnf');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('national_summaries', function (Blueprint $table) {
            $table->dropColumn([
                'lf_2009',
                'remboursement_drs',
                'remboursement_fnf_et_autres',
                'taxe_fnf_20_percent',
                'taxe_de_mise_en_charge',
                'total_fnf',
                'chasse_et_peche',
            ]);
        });
    }
};

