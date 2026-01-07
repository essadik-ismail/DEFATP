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
        Schema::create('charge_apayer', function (Blueprint $table) {
            $table->id();
            $table->string('nom')->nullable();
            $table->decimal('montant', 15, 2)->nullable();
            $table->date('date_echeance')->nullable();
            $table->foreignId('contrat_vente_id')->nullable()->constrained('contract_ventes')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('contrat_vente_id');
            $table->index('date_echeance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('charge_apayer');
    }
};

