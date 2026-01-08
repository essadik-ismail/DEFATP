<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('communes', function (Blueprint $table) {
            // Add province_id column (nullable first for data migration)
            $table->foreignId('province_id')->nullable()->after('nom')->constrained('provinces')->onDelete('cascade');
        });

        // Migrate data: For each province, set its commune's province_id
        // Since provinces have commune_id, we need to set the commune's province_id to the province's id
        DB::statement('
            UPDATE communes c
            INNER JOIN provinces p ON p.commune_id = c.id
            SET c.province_id = p.id
        ');

        // Make province_id not nullable after data migration
        Schema::table('communes', function (Blueprint $table) {
            $table->foreignId('province_id')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('communes', function (Blueprint $table) {
            $table->dropForeign(['province_id']);
            $table->dropColumn('province_id');
        });
    }
};
