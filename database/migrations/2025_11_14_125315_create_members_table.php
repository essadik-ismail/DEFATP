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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->enum('type_membre', ['présidente', 'vice_présidente', 'trésorière', 'membre'])->nullable();
            $table->string('nom');
            $table->string('n_cin')->nullable();
            $table->string('tel')->nullable();
            $table->string('email')->nullable();
            $table->enum('type_odf', ['Association', 'Coopérative', 'Entreprise', 'Élu', 'Citoyen'])->nullable();
            $table->string('type_odf_domaine_activite')->nullable();
            $table->integer('type_odf_nombre_de_membres')->nullable();
            $table->foreignId('odf_id')->nullable()->constrained('odfs')->onDelete('set null');
            $table->text('commentaire')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};