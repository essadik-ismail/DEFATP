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
        Schema::create('coperatives', function (Blueprint $table) {
            $table->id();
            $table->string('nom')->nullable(); // Nom complet de la coopérative
            $table->integer('nombre_membres')->default(0); // Nombre de membres
            $table->integer('nombre_coperatives')->default(0); // Nombre de coopératives
            $table->boolean('is_deleted')->default(false); // Soft delete flag
            $table->timestamps();
            $table->softDeletes();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coperatives');
    }
};
