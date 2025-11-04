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
        Schema::table('contacts', function (Blueprint $table) {
            // Add foreign key columns if they don't exist
            if (!Schema::hasColumn('contacts', 'situation_administrative_id')) {
                $table->foreignId('situation_administrative_id')->nullable()->constrained('situation_administratives')->onDelete('cascade')->after('localisation_id');
            }
            
            if (!Schema::hasColumn('contacts', 'espece_id')) {
                $table->foreignId('espece_id')->nullable()->constrained('especes')->onDelete('cascade')->after('situation_administrative_id');
            }

            // Add the named columns if they don't exist (mapping to replace attributes if needed)
            if (!Schema::hasColumn('contacts', 'superficie')) {
                $table->string('superficie')->nullable()->after('espece_id');
            }
            
            if (!Schema::hasColumn('contacts', 'gardiennage')) {
                $table->string('gardiennage')->nullable();
            }
            
            if (!Schema::hasColumn('contacts', 'elagage')) {
                $table->string('elagage')->nullable();
            }
            
            if (!Schema::hasColumn('contacts', 'eclaircie')) {
                $table->string('eclaircie')->nullable();
            }
            
            if (!Schema::hasColumn('contacts', 'rajeunissement_romarin')) {
                $table->string('rajeunissement_romarin')->nullable();
            }
            
            if (!Schema::hasColumn('contacts', 'valeurs_des_produits')) {
                $table->string('valeurs_des_produits')->nullable();
            }
            
            if (!Schema::hasColumn('contacts', 'valeur_des_prestations')) {
                $table->string('valeur_des_prestations')->nullable();
            }
            
            if (!Schema::hasColumn('contacts', 'redevances')) {
                $table->string('redevances')->nullable();
            }
            
            if (!Schema::hasColumn('contacts', 'taxes')) {
                $table->string('taxes')->nullable();
            }
            
            if (!Schema::hasColumn('contacts', 'total_avenant')) {
                $table->string('total_avenant')->nullable();
            }
            
            if (!Schema::hasColumn('contacts', 'bo_m3')) {
                $table->string('bo_m3')->nullable();
            }
            
            if (!Schema::hasColumn('contacts', 'bi_m3')) {
                $table->string('bi_m3')->nullable();
            }
            
            if (!Schema::hasColumn('contacts', 'bf_st')) {
                $table->string('bf_st')->nullable();
            }
            
            if (!Schema::hasColumn('contacts', 'tanin_t')) {
                $table->string('tanin_t')->nullable();
            }
            
            if (!Schema::hasColumn('contacts', 'fleur_acacia_t')) {
                $table->string('fleur_acacia_t')->nullable();
            }
            
            if (!Schema::hasColumn('contacts', 'caroube_t')) {
                $table->string('caroube_t')->nullable();
            }
            
            if (!Schema::hasColumn('contacts', 'romarin_t')) {
                $table->string('romarin_t')->nullable();
            }
            
            if (!Schema::hasColumn('contacts', 'ps_t')) {
                $table->string('ps_t')->nullable();
            }
            
            if (!Schema::hasColumn('contacts', 'liége_st')) {
                $table->string('liége_st')->nullable();
            }
            
            if (!Schema::hasColumn('contacts', 'charbon_bois_ox')) {
                $table->string('charbon_bois_ox')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            // Drop foreign keys first
            if (Schema::hasColumn('contacts', 'situation_administrative_id')) {
                $table->dropForeign(['situation_administrative_id']);
                $table->dropColumn('situation_administrative_id');
            }
            
            if (Schema::hasColumn('contacts', 'espece_id')) {
                $table->dropForeign(['espece_id']);
                $table->dropColumn('espece_id');
            }
            
            // Drop other columns
            $columnsToDrop = [
                'superficie', 'gardiennage', 'elagage', 'eclaircie', 'rajeunissement_romarin',
                'valeurs_des_produits', 'valeur_des_prestations', 'redevances', 'taxes', 'total_avenant',
                'bo_m3', 'bi_m3', 'bf_st', 'tanin_t', 'fleur_acacia_t', 'caroube_t', 'romarin_t',
                'ps_t', 'liége_st', 'charbon_bois_ox'
            ];
            
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('contacts', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};