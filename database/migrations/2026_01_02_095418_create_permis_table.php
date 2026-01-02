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
        Schema::create('permis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->nullable()->constrained('payments')->onDelete('cascade');
            $table->string('num_assurance')->nullable();
            $table->string('percepteur')->nullable();
            $table->boolean('cloture')->default(false);
            $table->string('num_quittance')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('payment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permis');
    }
};
