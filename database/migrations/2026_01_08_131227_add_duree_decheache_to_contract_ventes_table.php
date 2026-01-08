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
            $table->string('duree_decheache')->nullable()->after('date_de_decheance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contract_ventes', function (Blueprint $table) {
            $table->dropColumn('duree_decheache');
        });
    }
};
