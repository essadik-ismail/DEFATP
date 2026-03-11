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
            // Drop foreign keys for contract and avenant if they exist
            if (Schema::hasColumn('products', 'contract_id')) {
                $table->dropForeign(['contract_id']);
            }
            if (Schema::hasColumn('products', 'avenant_id')) {
                $table->dropForeign(['avenant_id']);
            }

            // Drop columns if they exist (article_id is now a simple reference)
            $columnsToDrop = [];
            if (Schema::hasColumn('products', 'article_id')) {
                $columnsToDrop[] = 'article_id';
            }
            if (Schema::hasColumn('products', 'contract_id')) {
                $columnsToDrop[] = 'contract_id';
            }
            if (Schema::hasColumn('products', 'avenant_id')) {
                $columnsToDrop[] = 'avenant_id';
            }
            if (Schema::hasColumn('products', 'quantity')) {
                $columnsToDrop[] = 'quantity';
            }
            if (Schema::hasColumn('products', 'is_deleted')) {
                $columnsToDrop[] = 'is_deleted';
            }
            
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Re-add columns
            $table->foreignId('article_id')->nullable()->after('name')->constrained('articles')->onDelete('cascade');
            $table->foreignId('contract_id')->nullable()->after('article_id')->constrained('contacts')->onDelete('cascade');
            $table->foreignId('avenant_id')->nullable()->after('contract_id')->constrained('avenants')->onDelete('cascade');
            $table->integer('quantity')->default(1)->after('name');
            $table->boolean('is_deleted')->default(false)->after('avenant_id');
        });
    }
};
