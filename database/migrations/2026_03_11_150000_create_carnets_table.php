<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Carnet = un numéro par ligne (série "De-À", num = entier). Statut: disponible, epuise, perdu, utilise.
     */
    public function up(): void
    {
        Schema::create('carnets', function (Blueprint $table) {
            $table->id();
            $table->string('serie'); // affichage "De-À", ex. "1-100"
            $table->unsignedInteger('num'); // numéro dans la série (1, 2, … 100)
            $table->string('status', 20)->default('disponible'); // disponible, epuise, perdu, utilise
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['serie', 'num']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carnets');
    }
};
