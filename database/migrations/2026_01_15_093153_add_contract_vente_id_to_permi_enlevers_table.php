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
        Schema::table('permi_enlevers', function (Blueprint $table) {
            $table->foreignId('contract_vente_id')->nullable()->after('permis_id')->constrained('contract_ventes')->onDelete('cascade');
            $table->index('contract_vente_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permi_enlevers', function (Blueprint $table) {
            $table->dropForeign(['contract_vente_id']);
            $table->dropIndex(['contract_vente_id']);
            $table->dropColumn('contract_vente_id');
        });
    }
};
