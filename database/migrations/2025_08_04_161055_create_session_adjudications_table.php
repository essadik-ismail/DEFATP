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
        Schema::create('session_adjudications', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['appel_doffre', 'adjudication']);
            $table->date('date')->default(now()); 
            $table->string('numero')->nullable(); 
            $table->string('nature_juridique')->nullable(); 

            $table->string('adjudicatire')->nullable(); 

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
        Schema::dropIfExists('session_adjudications');
    }
};
