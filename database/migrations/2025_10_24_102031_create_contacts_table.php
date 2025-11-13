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
            $table->integer('contarct'); // Note: keeping original typo as per schema
            $table->foreignId('localisation_id')->constrained('localisations')->onDelete('cascade');
            $table->foreignId('situation_administrative_id')->constrained('situation_administratives')->onDelete('cascade');
            $table->foreignId('foret_id')->constrained()->onDelete('cascade');
            $table->foreignId('coperative_id')->constrained('coperatives')->onDelete('cascade');

            $table->decimal('superficie', 10, 2);
            $table->string('gardiennage')->nullable();
            $table->string('prevention_contre_les_incendies')->nullable();
            $table->string('elagage')->nullable();
            $table->string('eclaircie')->nullable();
            $table->string('rajeunissement_romarin')->nullable();
            $table->string('autre')->nullable();

            $table->integer('bo_m3')->nullable();
            $table->integer('bi_m3')->nullable();
            $table->integer('bf_st')->nullable();
            $table->integer('tanin_t')->nullable();
            $table->integer('laurier_sauce')->nullable();
            $table->integer('myrte')->nullable();
            $table->integer('callune')->nullable();
            $table->integer('thym')->nullable();
            $table->integer('bruyetre')->nullable();
            $table->integer('lichen')->nullable();
            $table->integer('tanin')->nullable();
            $table->integer('romarin')->nullable();
            $table->integer('liege_male')->nullable();
            $table->integer('liege_de_reproduction')->nullable();
            $table->integer('sauge')->nullable();
            $table->integer('lavande')->nullable();
            $table->integer('armoise')->nullable();
            $table->integer('origan')->nullable();
            $table->integer('alfa')->nullable();
            $table->integer('lentisque')->nullable();
            $table->integer('ciste')->nullable();
            $table->integer('fleur_acacia_t')->nullable();

            $table->string('valeurs_des_produits');
            $table->string('valeur_des_prestations');
            $table->string('redevances');
            $table->string('taxes');
            $table->string('total_avenant');

            $table->boolean('resiliation')->default(false);
            $table->date('date_resiliation')->nullable();
            
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