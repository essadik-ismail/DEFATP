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
        Schema::table('contract_ventes', function (Blueprint $table) {
            $table->decimal('bois_chauffage_volume_st', 10, 2)->nullable()->after('nombre_tranche');
        });
    }

    public function down(): void
    {
        Schema::table('contract_ventes', function (Blueprint $table) {
            $table->dropColumn('bois_chauffage_volume_st');
        });
    }
};
