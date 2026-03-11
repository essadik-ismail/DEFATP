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
        Schema::create('groupe_cession', function (Blueprint $table) {
            $table->id();

            // Reference to the related DRANEF entity
            $table->unsignedBigInteger('dranef_id');

            $table->string('mode_cession')->nullable();
            $table->string('Exercice')->nullable();
            $table->string('numAO')->nullable();
            $table->date('dateAO')->nullable();
            $table->date('DateAdj')->nullable();
            $table->string('Statut')->nullable();

            $table->timestamps();

            // Foreign key constraint (adjust target table/column if needed)
            $table->foreign('dranef_id')->references('id')->on('dranefs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groupe_cession');
    }
};

