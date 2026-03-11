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
            if (!Schema::hasColumn('contract_ventes', 'is_decheance')) {
                $table->boolean('is_decheance')
                    ->default(false)
                    ->after('date_de_decheance');
            }

            if (!Schema::hasColumn('contract_ventes', 'duree')) {
                $table->string('duree')
                    ->nullable()
                    ->after('is_decheance');
            }

            if (!Schema::hasColumn('contract_ventes', 'is_prorogation')) {
                $table->boolean('is_prorogation')
                    ->default(false)
                    ->after('duree');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contract_ventes', function (Blueprint $table) {
            if (Schema::hasColumn('contract_ventes', 'is_decheance')) {
                $table->dropColumn('is_decheance');
            }

            if (Schema::hasColumn('contract_ventes', 'duree')) {
                $table->dropColumn('duree');
            }

            if (Schema::hasColumn('contract_ventes', 'is_prorogation')) {
                $table->dropColumn('is_prorogation');
            }
        });
    }
};

