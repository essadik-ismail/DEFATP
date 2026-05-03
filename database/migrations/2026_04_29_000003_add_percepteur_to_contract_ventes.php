<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contract_ventes', function (Blueprint $table) {
            $table->string('percepteur')->nullable()->after('bois_chauffage_volume_st');
        });
    }

    public function down(): void
    {
        Schema::table('contract_ventes', function (Blueprint $table) {
            $table->dropColumn('percepteur');
        });
    }
};
