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
        Schema::table('avenants', function (Blueprint $table) {
            // Remove product columns from avenants table
            $columnsToDrop = [];
            
            // Product columns to remove
            $productColumns = [
                'bo_m3',
                'bi_m3',
                'bf_st',
                'tanin_t',
                'laurier_sauce',
                'myrte',
                'callune',
                'thym',
                'bruyetre',
                'lichen',
                'tanin',
                'romarin',
                'liege_male',
                'liege_de_reproduction',
                'sauge',
                'lavande',
                'armoise',
                'origan',
                'alfa',
                'lentisque',
                'ciste',
                'fleur_acacia_t',
            ];
            
            foreach ($productColumns as $column) {
                if (Schema::hasColumn('avenants', $column)) {
                    $columnsToDrop[] = $column;
                }
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
        Schema::table('avenants', function (Blueprint $table) {
            // Re-add product columns
            $table->integer('bo_m3')->nullable()->after('rajeunissement_romarin');
            $table->integer('bi_m3')->nullable()->after('bo_m3');
            $table->integer('bf_st')->nullable()->after('bi_m3');
            $table->integer('tanin_t')->nullable()->after('bf_st');
            $table->integer('laurier_sauce')->nullable()->after('tanin_t');
            $table->integer('myrte')->nullable()->after('laurier_sauce');
            $table->integer('callune')->nullable()->after('myrte');
            $table->integer('thym')->nullable()->after('callune');
            $table->integer('bruyetre')->nullable()->after('thym');
            $table->integer('lichen')->nullable()->after('bruyetre');
            $table->integer('tanin')->nullable()->after('lichen');
            $table->integer('romarin')->nullable()->after('tanin');
            $table->integer('liege_male')->nullable()->after('romarin');
            $table->integer('liege_de_reproduction')->nullable()->after('liege_male');
            $table->integer('sauge')->nullable()->after('liege_de_reproduction');
            $table->integer('lavande')->nullable()->after('sauge');
            $table->integer('armoise')->nullable()->after('lavande');
            $table->integer('origan')->nullable()->after('armoise');
            $table->integer('alfa')->nullable()->after('origan');
            $table->integer('lentisque')->nullable()->after('alfa');
            $table->integer('ciste')->nullable()->after('lentisque');
            $table->integer('fleur_acacia_t')->nullable()->after('ciste');
        });
    }
};
