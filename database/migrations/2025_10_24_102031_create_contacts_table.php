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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->integer('annee');
            $table->string('contarct'); // Note: keeping original typo as per schema
            $table->foreignId('localisation_id')->constrained('localisations')->onDelete('cascade');
            $table->foreignId('situation_administrative_id')->constrained('situation_administratives')->onDelete('cascade');
            $table->foreignId('espece_id')->constrained('especes')->onDelete('cascade');

            $table->string('superficie')->nullable();
            $table->string('gardiennage')->nullable();
            $table->string('elagage')->nullable();
            $table->string('eclaircie')->nullable();
            $table->string('rajeunissement_romarin')->nullable();
            $table->string('valeurs_des_produits')->nullable();
            $table->string('valeur_des_prestations')->nullable();
            $table->string('redevances')->nullable();
            $table->string('taxes')->nullable();
            $table->string('total_avenant')->nullable();

            $table->string('bo_m3')->nullable();
            $table->string('bi_m3')->nullable();
            $table->string('bf_st')->nullable();
            $table->string('tanin_t')->nullable();
            $table->string('fleur_acacia_t')->nullable();
            $table->string('caroube_t')->nullable();
            $table->string('romarin_t')->nullable();
            $table->string('ps_t')->nullable();
            $table->string('liége_st')->nullable();
            $table->string('charbon_bois_ox')->nullable();

            // $table->string('attribute13')->nullable();
            // $table->string('attribute14')->nullable();
            // $table->string('attribute15')->nullable();
            // $table->string('attribute16')->nullable();
            // $table->string('attribute17')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};