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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('nom')->nullable();
            $table->date('date_decheace')->nullable();
            $table->date('date_payment')->nullable();
            $table->boolean('is_paye')->default(false);
            $table->string('fichier_join')->nullable();
            $table->string('num_quittace')->nullable();
            $table->integer('order')->nullable();
            $table->foreignId('contract_vente_id')->nullable()->constrained('contract_ventes')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('contract_vente_id');
            $table->index('date_payment');
            $table->index('is_paye');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
