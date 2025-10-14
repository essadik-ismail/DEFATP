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
            $table->date('date_adjudication')->default(now());
            $table->integer('annee')->default(now()->year);
            $table->string('numero')->nullable(); 

            $table->boolean('invendu')->default(false);

            $table->decimal('prix_de_retrait', 10, 2)->nullable();
            

            $table->integer('lot')->nullable();
            $table->integer('parcelle')->nullable();
            $table->string('superficie')->nullable();

            $table->integer('bo_m3')->nullable();
            $table->integer('bi_m3')->nullable();
            $table->integer('bf_st')->nullable();
            $table->integer('tanin_t')->nullable();
            $table->integer('fleur_acacia_t')->nullable();
            $table->integer('caroube_t')->nullable();
            $table->integer('romarin_t')->nullable();
            $table->integer('ps_t')->nullable();
            $table->integer('liége_st')->nullable();
            $table->integer('charbon_bois_ox')->nullable();

            $table->decimal('prix_vente', 10, 2)->nullable();
            $table->decimal('fourniture_mise_charge', 10, 2)->nullable();

            $table->string('lat')->nullable();
            $table->string('log')->nullable();

            $table->date('date_dr')->nullable(); 
            $table->text('observations')->nullable();
            $table->text('charges_du_lot')->nullable();

            $table->enum('type', ['appel_doffre', 'adjudication', 'marche_negocié']);
            $table->string('numero_adjudication')->nullable(); 
            $table->string('nature_juridique')->nullable(); 

            $table->boolean('dc')->default(false);
            $table->boolean('rc')->default(false); 
            $table->date('date_de_resiliation')->nullable();
            $table->date('date_de_decheance')->nullable();

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
