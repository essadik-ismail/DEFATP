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
        Schema::create('permi_enlevers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permis_id')->nullable()->constrained('permis')->onDelete('cascade');
            $table->string('num_quittance')->nullable();
            $table->date('date')->nullable();
            $table->integer('num_tranche_paye')->nullable();
            $table->string('percepteur')->nullable();
            $table->decimal('volume', 15, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('permis_id');
            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permi_enlevers');
    }
};
