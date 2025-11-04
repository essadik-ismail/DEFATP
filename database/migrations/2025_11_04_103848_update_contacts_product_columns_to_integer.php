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
        Schema::table('contacts', function (Blueprint $table) {
            // Change product columns from string to integer to match original migration
            $productColumns = [
                'bo_m3', 'bi_m3', 'bf_st', 'tanin_t', 'fleur_acacia_t', 
                'caroube_t', 'romarin_t', 'ps_t', 'liége_st', 'charbon_bois_ox'
            ];
            
            foreach ($productColumns as $column) {
                if (Schema::hasColumn('contacts', $column)) {
                    // First, set NULL for any non-numeric values
                    DB::statement("UPDATE contacts SET {$column} = NULL WHERE {$column} IS NOT NULL AND ({$column} = '' OR {$column} NOT REGEXP '^[0-9]+$')");
                    
                    // Then convert valid numeric strings to integers
                    DB::statement("UPDATE contacts SET {$column} = CAST({$column} AS UNSIGNED) WHERE {$column} IS NOT NULL AND {$column} REGEXP '^[0-9]+$'");
                    
                    // Change column type to integer
                    $table->integer($column)->nullable()->change();
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            // Change back to string if needed
            $productColumns = [
                'bo_m3', 'bi_m3', 'bf_st', 'tanin_t', 'fleur_acacia_t', 
                'caroube_t', 'romarin_t', 'ps_t', 'liége_st', 'charbon_bois_ox'
            ];
            
            foreach ($productColumns as $column) {
                if (Schema::hasColumn('contacts', $column)) {
                    $table->string($column)->nullable()->change();
                }
            }
        });
    }
};