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
        Schema::create('odf_diagnostic', function (Blueprint $table) {
            $table->id();
            $table->enum('type', [
                'associations',
                'coopératives',
                'titulaires_amodiations',
                'nouabs des collectivités ethniques',
                'autre'
            ])->nullable();
            $table->string('nom')->nullable();
            $table->string('activité')->nullable();
            $table->string('présidente')->nullable();
            $table->integer('nombre_de_membres')->nullable();
            $table->foreignId('odf_id')->nullable()->constrained('odfs')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('odf_diagnostic');
    }
};

