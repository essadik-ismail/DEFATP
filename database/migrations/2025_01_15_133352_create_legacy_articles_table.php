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
        Schema::create('legacy_articles', function (Blueprint $table) {
            $table->id();
            $table->string('dref', 10)->nullable()->comment('Reference code (CC, MA, FB)');
            $table->string('foret', 100)->nullable()->comment('Forest name');
            $table->string('province', 100)->nullable()->comment('Province name');
            $table->string('date', 10)->nullable()->comment('Date in YYMMDD format');
            $table->string('essence', 50)->nullable()->comment('Tree species');
            $table->string('intervent', 10)->nullable()->comment('Intervention type (CCN, CBE, BED, ECL)');
            $table->decimal('surface', 10, 2)->nullable()->comment('Surface area');
            $table->decimal('bom3', 10, 2)->nullable()->comment('Volume measurement BOM3');
            $table->decimal('bim3', 10, 2)->nullable()->comment('Volume measurement BIM3');
            $table->decimal('bfst', 10, 2)->nullable()->comment('Volume measurement BFST');
            $table->decimal('lcst', 10, 2)->nullable()->comment('Volume measurement LCST');
            $table->decimal('ett', 10, 2)->nullable()->comment('Volume measurement ETT');
            $table->decimal('pst', 10, 2)->nullable()->comment('Volume measurement PST');
            $table->string('acheteur', 200)->nullable()->comment('Buyer name');
            $table->decimal('ppdh', 15, 2)->nullable()->comment('Price');
            $table->string('dr', 10)->nullable()->comment('Direction code (NL, RC)');
            $table->string('source_file', 50)->nullable()->comment('Source JSON file name');
            $table->timestamps();

            // Add indexes for better performance
            $table->index(['dref']);
            $table->index(['province']);
            $table->index(['date']);
            $table->index(['essence']);
            $table->index(['intervent']);
            $table->index(['source_file']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('legacy_articles');
    }
};
