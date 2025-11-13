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
        Schema::create('avenants', function (Blueprint $table) {
            $table->id();
            $table->integer('annee')->default(now()->year);
            $table->string('avenant');
            $table->date('date');
            $table->foreignId('coperative_id')->nullable()->constrained('coperatives')->onDelete('set null');
            
            $table->decimal('superficie', 10, 2)->nullable();
            $table->decimal('gardiennage', 10, 2)->nullable();
            $table->decimal('prevention_incendies', 10, 2)->nullable();
            $table->decimal('elagage', 10, 2)->nullable();
            $table->decimal('eclaircie', 10, 2)->nullable();
            $table->decimal('rajeunissement_romarin', 10, 2)->nullable();

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

            $table->decimal('valeurs_des_produits', 10, 2);
            $table->decimal('valeur_des_prestations', 10, 2);
            $table->decimal('redevances', 10, 2);
            $table->decimal('taxes', 10, 2);
            $table->decimal('total_avenant', 10, 2);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avenants');
    }
};