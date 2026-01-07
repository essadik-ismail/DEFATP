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
        // The existing contract_product table links to 'contacts'
        // According to ERD, we need a separate table for contract_ventes
        // Create contract_vente_product table for contract_ventes
        if (!Schema::hasTable('contract_vente_product')) {
            Schema::create('contract_vente_product', function (Blueprint $table) {
                $table->id();
                $table->foreignId('contract_id')->constrained('contract_ventes')->onDelete('cascade');
                $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
                $table->decimal('quantity', 15, 2)->nullable();
                $table->timestamps();
                
                // Unique constraint to prevent duplicate entries
                $table->unique(['contract_id', 'product_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('contract_vente_product')) {
            Schema::dropIfExists('contract_vente_product');
        }
    }
};

