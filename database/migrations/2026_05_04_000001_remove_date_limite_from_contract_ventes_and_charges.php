<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contract_ventes', function (Blueprint $table) {
            $table->dropColumn(['date_limite_tranche', 'date_limite_taxes']);
        });

        Schema::table('charge_apayer', function (Blueprint $table) {
            $table->dropColumn('date_limite');
        });
    }

    public function down(): void
    {
        Schema::table('contract_ventes', function (Blueprint $table) {
            $table->date('date_limite_tranche')->nullable()->after('nombre_tranche');
            $table->date('date_limite_taxes')->nullable()->after('date_limite_tranche');
        });

        Schema::table('charge_apayer', function (Blueprint $table) {
            $table->date('date_limite')->nullable()->after('date_echeance');
        });
    }
};
