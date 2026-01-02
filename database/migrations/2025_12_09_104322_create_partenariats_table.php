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
        Schema::create('partenariats', function (Blueprint $table) {
            $table->id();
            $table->string('nom_association')->nullable();
            $table->integer('nombre_adherents_association')->nullable();
            $table->date('date_creation_association')->nullable();
            $table->decimal('superficie', 15, 2)->nullable();
            $table->string('nom_périmètre')->nullable();
            $table->foreignId('essence_id')->nullable()->constrained('essences')->onDelete('set null');
            $table->text('object_cmd')->nullable();
            $table->string('num_contract')->nullable();
            $table->date('date_signature_contract')->nullable();
            $table->string('num_avenant')->nullable();
            $table->integer('nombre_avenant')->nullable();
            $table->date('date_signature_avenant')->nullable();
            $table->decimal('Superficie_Contrat_avenant', 15, 2)->nullable();
            $table->date('Date_PV_etat_des_lieux')->nullable();
            $table->decimal('Superficie_ha', 15, 2)->nullable();
            $table->decimal('Taux_de_réussite', 5, 2)->nullable();
            $table->string('Etat_de_la_clôture')->nullable();
            $table->text('PV_évaluation')->nullable();
            $table->text('observations')->nullable();
            $table->string('Etat_peuplement')->nullable();
            $table->text('Contraintes')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('essence_id');
            $table->index('num_contract');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partenariats');
    }
};
