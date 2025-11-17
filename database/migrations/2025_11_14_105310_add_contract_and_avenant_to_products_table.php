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
        Schema::table('products', function (Blueprint $table) {
            // Make article_id nullable to support contracts and avenants
            $table->foreignId('article_id')->nullable()->change();
            
            // Add contract_id and avenant_id
            $table->foreignId('contract_id')->nullable()->after('article_id')->constrained('contacts')->onDelete('cascade');
            $table->foreignId('avenant_id')->nullable()->after('contract_id')->constrained('avenants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['contract_id']);
            $table->dropForeign(['avenant_id']);
            $table->dropColumn(['contract_id', 'avenant_id']);
            $table->foreignId('article_id')->nullable(false)->change();
        });
    }
};
