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
        Schema::table('articles', function (Blueprint $table) {
            // Remove product columns from articles table
            $columnsToDrop = [];
            
            if (Schema::hasColumn('articles', 'bo_m3')) {
                $columnsToDrop[] = 'bo_m3';
            }
            if (Schema::hasColumn('articles', 'bi_m3')) {
                $columnsToDrop[] = 'bi_m3';
            }
            if (Schema::hasColumn('articles', 'bf_st')) {
                $columnsToDrop[] = 'bf_st';
            }
            if (Schema::hasColumn('articles', 'tanin_t')) {
                $columnsToDrop[] = 'tanin_t';
            }
            if (Schema::hasColumn('articles', 'fleur_acacia_t')) {
                $columnsToDrop[] = 'fleur_acacia_t';
            }
            if (Schema::hasColumn('articles', 'caroube_t')) {
                $columnsToDrop[] = 'caroube_t';
            }
            if (Schema::hasColumn('articles', 'romarin_t')) {
                $columnsToDrop[] = 'romarin_t';
            }
            if (Schema::hasColumn('articles', 'liége_st')) {
                $columnsToDrop[] = 'liége_st';
            }
            if (Schema::hasColumn('articles', 'charbon_bois_ox')) {
                $columnsToDrop[] = 'charbon_bois_ox';
            }
            
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            // Re-add product columns
            $table->integer('bo_m3')->nullable()->after('superficie');
            $table->integer('bi_m3')->nullable()->after('bo_m3');
            $table->integer('bf_st')->nullable()->after('bi_m3');
            $table->integer('tanin_t')->nullable()->after('bf_st');
            $table->integer('fleur_acacia_t')->nullable()->after('tanin_t');
            $table->integer('caroube_t')->nullable()->after('fleur_acacia_t');
            $table->integer('romarin_t')->nullable()->after('caroube_t');
            $table->integer('liége_st')->nullable()->after('ps_t');
            $table->integer('charbon_bois_ox')->nullable()->after('liége_st');
        });
    }
};
