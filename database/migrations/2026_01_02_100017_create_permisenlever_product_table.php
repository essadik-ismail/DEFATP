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
        if (!Schema::hasTable('permisenlever_product')) {
            Schema::create('permisenlever_product', function (Blueprint $table) {
                $table->id();
                $table->foreignId('permis_id')->constrained('permi_enlevers')->onDelete('cascade');
                $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
                $table->foreignId('id_essence')->constrained('essences')->onDelete('cascade');
                $table->decimal('quantity', 15, 2)->nullable();
                $table->timestamps();
                
                // Unique constraint to prevent duplicate entries
                $table->unique(['permis_id', 'product_id', 'id_essence'], 'permisenlever_product_unique');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permisenlever_product');
    }
};

