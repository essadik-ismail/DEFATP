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
            // Add missing fields from ERD
            $table->string('type')->nullable()->after('nombre_tranche');
            $table->string('numeraAO')->nullable()->after('type');
            $table->string('Current_state')->nullable()->after('numeraAO');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contract_ventes', function (Blueprint $table) {
            $table->dropColumn(['type', 'numeraAO', 'Current_state']);
        });
    }
};

