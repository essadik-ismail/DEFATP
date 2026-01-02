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
        Schema::create('contract_ventes', function (Blueprint $table) {
            $table->id();
            $table->date('date_adjudication');
            $table->decimal('prix_vente', 15, 2)->nullable();
            $table->decimal('prix_de_retrait', 15, 2)->nullable();
            $table->foreignId('article_id')->nullable()->constrained('articles')->onDelete('set null');
            $table->foreignId('exploitant_id')->nullable()->constrained('exploitants')->onDelete('set null');
            $table->date('date_de_decheance')->nullable();
            $table->string('id_decheance')->nullable();
            $table->date('date_de_resiliation')->nullable();
            $table->boolean('is_resiliation')->default(false);
            $table->integer('nombre_tranche')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('article_id');
            $table->index('exploitant_id');
            $table->index('date_adjudication');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_ventes');
    }
};
