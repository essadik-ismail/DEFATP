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
        Schema::create('pv_installations', function (Blueprint $table) {
            $table->id();
            $table->string('pvn')->nullable();
            $table->date('date')->nullable();
            $table->text('participants')->nullable();
            $table->string('exploitant')->nullable();
            $table->text('reserve')->nullable();
            $table->string('mo')->nullable();
            $table->string('charbonniére')->nullable();
            $table->string('mise_en_charge')->nullable();
            $table->string('ravalement_souches')->nullable();
            $table->string('remarient')->nullable();
            $table->string('mise_en_defens')->nullable();
            $table->string('invitation_caporal')->nullable();
            $table->foreignId('contract_vente_id')->nullable()->constrained('contract_ventes')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('contract_vente_id');
            $table->index('date');
            $table->index('pvn');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pv_installations');
    }
};
