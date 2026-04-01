<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = DB::getDriverName();

        Schema::table('contacts', function (Blueprint $table) use ($driver) {
            // Change product columns from string to integer to match original migration.
            $productColumns = [
                'bo_m3',
                'bi_m3',
                'bf_st',
                'tanin_t',
                'fleur_acacia_t',
                'caroube_t',
                'romarin_t',
                'ps_t',
                "li\u{00E9}ge_st",
                'charbon_bois_ox',
            ];

            foreach ($productColumns as $column) {
                if (!Schema::hasColumn('contacts', $column)) {
                    continue;
                }

                if ($driver === 'sqlite') {
                    DB::statement("UPDATE contacts SET {$column} = NULL WHERE {$column} IS NOT NULL AND (TRIM({$column}) = '' OR TRIM({$column}) GLOB '*[^0-9]*')");
                    DB::statement("UPDATE contacts SET {$column} = CAST({$column} AS INTEGER) WHERE {$column} IS NOT NULL AND TRIM({$column}) != '' AND TRIM({$column}) NOT GLOB '*[^0-9]*'");
                } else {
                    DB::statement("UPDATE contacts SET {$column} = NULL WHERE {$column} IS NOT NULL AND ({$column} = '' OR {$column} NOT REGEXP '^[0-9]+$')");
                    DB::statement("UPDATE contacts SET {$column} = CAST({$column} AS UNSIGNED) WHERE {$column} IS NOT NULL AND {$column} REGEXP '^[0-9]+$'");
                }

                $table->integer($column)->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $productColumns = [
                'bo_m3',
                'bi_m3',
                'bf_st',
                'tanin_t',
                'fleur_acacia_t',
                'caroube_t',
                'romarin_t',
                'ps_t',
                "li\u{00E9}ge_st",
                'charbon_bois_ox',
            ];

            foreach ($productColumns as $column) {
                if (Schema::hasColumn('contacts', $column)) {
                    $table->string($column)->nullable()->change();
                }
            }
        });
    }
};
