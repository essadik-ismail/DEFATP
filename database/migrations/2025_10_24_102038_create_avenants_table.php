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
        Schema::create('avenants', function (Blueprint $table) {
            $table->id();
            $table->integer('annee');
            $table->foreignId('coperative_id')->nullable()->constrained('exploitants')->onDelete('set null'); // Assuming coperative refers to exploitants
            $table->date('date');
            $table->decimal('superficie', 10, 2)->nullable();
            $table->decimal('gardiennage', 10, 2)->nullable();
            $table->decimal('prevention_incendies', 10, 2)->nullable();
            $table->decimal('elagage', 10, 2)->nullable();
            $table->decimal('eclaircie', 10, 2)->nullable();
            $table->decimal('rajeunissement_romarin', 10, 2)->nullable();
            $table->decimal('valeurs_des_produits', 10, 2)->nullable();
            $table->decimal('valeur_des_prestations', 10, 2)->nullable();
            $table->decimal('redevances', 10, 2)->nullable();
            $table->decimal('taxes', 10, 2)->nullable();
            $table->decimal('total_avenant', 10, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avenants');
    }
};