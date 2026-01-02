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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->integer('annee')->default(now()->year);
            $table->string('numero')->nullable(); 

            $table->integer('lot')->nullable();
            $table->integer('parcelle')->nullable();
            $table->string('superficie')->nullable();

            $table->decimal('fourniture_mise_charge', 10, 2)->nullable();

            $table->string('lat')->nullable();
            $table->string('log')->nullable();

            $table->date('date_dr')->nullable(); 
            $table->text('observations')->nullable();
            $table->text('charges_du_lot')->nullable();

            $table->enum('type', ['appel_doffre', 'adjudication', 'marche_negocié']);
            $table->string('numero_adjudication')->nullable(); 

            $table->foreignId('exploitant_id')->nullable()->constrained()->onDelete('cascade');

            $table->boolean('is_validated')->default(false);
            $table->boolean('is_deleted')->default(false);
            
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
