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
        if (!Schema::hasTable('colportage_enlever')) {
            Schema::create('colportage_enlever', function (Blueprint $table) {
                $table->id();
                $table->foreignId('id_permis_enlever')->constrained('permi_enlevers')->onDelete('cascade');
                $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
                $table->foreignId('id_essence')->constrained('essences')->onDelete('cascade');
                $table->decimal('quantity', 15, 2)->nullable();
                $table->timestamps();
                
                // Unique constraint to prevent duplicate entries
                $table->unique(['id_permis_enlever', 'product_id', 'id_essence'], 'colportage_enlever_unique');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('colportage_enlever');
    }
};

