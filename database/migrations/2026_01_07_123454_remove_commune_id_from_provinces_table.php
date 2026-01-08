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
        Schema::table('provinces', function (Blueprint $table) {
            // Drop foreign key constraint first
            $table->dropForeign(['commune_id']);
            // Then drop the column
            $table->dropColumn('commune_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('provinces', function (Blueprint $table) {
            // Re-add commune_id column
            $table->foreignId('commune_id')->after('nom')->constrained('communes')->onDelete('cascade');
        });

        // Migrate data back: For each commune, set its province's commune_id
        DB::statement('
            UPDATE provinces p
            INNER JOIN communes c ON c.province_id = p.id
            SET p.commune_id = c.id
        ');
    }
};
