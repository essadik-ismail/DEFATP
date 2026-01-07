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
        if (!Schema::hasTable('contract_foret')) {
            Schema::create('contract_foret', function (Blueprint $table) {
                $table->id();
                $table->foreignId('foret_id')->constrained('forets')->onDelete('cascade');
                $table->foreignId('contract_id')->constrained('contract_ventes')->onDelete('cascade');
                $table->timestamps();
                
                // Unique constraint to prevent duplicate entries
                $table->unique(['foret_id', 'contract_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_foret');
    }
};

