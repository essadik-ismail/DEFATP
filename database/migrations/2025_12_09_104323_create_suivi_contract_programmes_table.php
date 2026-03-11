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
        Schema::dropIfExists('suivi_contract_programmes');
        
        Schema::create('suivi_contract_programmes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('foret_id')->nullable()->constrained('forets')->onDelete('set null');
            // Simple nullable reference; no foreign key because partenariats table does not exist
            $table->unsignedBigInteger('partenariat_id')->nullable();
            $table->string('CT')->nullable();
            $table->string('DPF')->nullable();
            $table->string('Parcelle')->nullable();
            $table->string('Projet_CP')->nullable();
            $table->integer('Année')->nullable();
            $table->decimal('Superficie_prévue_CP_ha', 15, 2)->nullable();
            $table->decimal('Montant_prévu_CP_dh', 15, 2)->nullable();
            $table->decimal('Superficie_engagée_ha', 15, 2)->nullable();
            $table->decimal('Montant_engagé_dh', 15, 2)->nullable();
            $table->decimal('Superficie_payée_ha', 15, 2)->nullable();
            $table->decimal('Montant_payé_dh', 15, 2)->nullable();
            $table->decimal('Superficie_non_payée', 15, 2)->nullable();
            $table->text('Motif_du_Non_paiement')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('foret_id');
            $table->index('partenariat_id');
            $table->index('Année');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suivi_contract_programmes');
    }
};
