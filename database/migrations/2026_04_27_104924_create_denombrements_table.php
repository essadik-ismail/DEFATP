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
        Schema::create('denombrements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_vente_id')->constrained('contract_ventes')->cascadeOnDelete();
            $table->date('date_denombrement');
            $table->string('agent_responsable')->nullable();
            $table->decimal('volume_denombre', 10, 3)->nullable();
            $table->text('observations')->nullable();
            $table->string('fichier_pv')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('denombrements');
    }
};
