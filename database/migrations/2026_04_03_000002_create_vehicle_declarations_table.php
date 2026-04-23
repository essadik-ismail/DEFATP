<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicle_declarations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contract_vente_id')->index();
            $table->string('immatriculation', 80);
            $table->string('marque', 100)->nullable();
            $table->decimal('capacite', 10, 2)->nullable();
            // Unit for capacity: m3 | stere | sacs | tonnes | autre
            $table->enum('capacite_unite', ['m3', 'stere', 'sacs', 'tonnes', 'autre'])->default('m3');
            $table->string('chauffeur_nom', 150)->nullable();
            $table->string('chauffeur_cin', 50)->nullable();
            $table->date('date_declaration')->nullable();
            $table->unsignedBigInteger('declared_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('contract_vente_id')
                ->references('id')->on('contract_ventes')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_declarations');
    }
};
