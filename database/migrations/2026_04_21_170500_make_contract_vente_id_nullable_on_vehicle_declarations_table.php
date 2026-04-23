<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicle_declarations', function (Blueprint $table) {
            $table->dropForeign(['contract_vente_id']);
            $table->unsignedBigInteger('contract_vente_id')->nullable()->change();
            $table->foreign('contract_vente_id')
                ->references('id')
                ->on('contract_ventes')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('vehicle_declarations', function (Blueprint $table) {
            $table->dropForeign(['contract_vente_id']);
            $table->unsignedBigInteger('contract_vente_id')->nullable(false)->change();
            $table->foreign('contract_vente_id')
                ->references('id')
                ->on('contract_ventes')
                ->cascadeOnDelete();
        });
    }
};
