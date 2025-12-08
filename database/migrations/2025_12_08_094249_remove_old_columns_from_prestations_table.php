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
        if (Schema::hasTable('prestations')) {
            Schema::table('prestations', function (Blueprint $table) {
                // Drop foreign keys first
                if (Schema::hasColumn('prestations', 'contract_id')) {
                    $table->dropForeign(['contract_id']);
                }
                if (Schema::hasColumn('prestations', 'avenant_id')) {
                    $table->dropForeign(['avenant_id']);
                }
                
                // Drop columns
                if (Schema::hasColumn('prestations', 'quantity')) {
                    $table->dropColumn('quantity');
                }
                if (Schema::hasColumn('prestations', 'contract_id')) {
                    $table->dropColumn('contract_id');
                }
                if (Schema::hasColumn('prestations', 'avenant_id')) {
                    $table->dropColumn('avenant_id');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('prestations')) {
            Schema::table('prestations', function (Blueprint $table) {
                $table->integer('quantity')->default(1);
                $table->foreignId('contract_id')->nullable()->constrained('contacts')->onDelete('cascade');
                $table->foreignId('avenant_id')->nullable()->constrained('avenants')->onDelete('cascade');
            });
        }
    }
};
