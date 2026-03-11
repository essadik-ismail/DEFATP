<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Allow type and date_adjudication to be null (fields removed from contract de vente form).
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE contract_ventes MODIFY date_adjudication DATE NULL');

        if (Schema::hasColumn('contract_ventes', 'type')) {
            DB::statement('ALTER TABLE contract_ventes MODIFY type VARCHAR(255) NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE contract_ventes MODIFY date_adjudication DATE NOT NULL');

        if (Schema::hasColumn('contract_ventes', 'type')) {
            DB::statement('ALTER TABLE contract_ventes MODIFY type VARCHAR(255) NOT NULL');
        }
    }
};
