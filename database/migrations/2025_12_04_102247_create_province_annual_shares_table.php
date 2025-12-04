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
        Schema::create('province_annual_shares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('situation_administrative_id')->constrained('situation_administratives')->onDelete('cascade');
            $table->integer('year');
            $table->decimal('llege', 15, 2)->default(0);
            $table->decimal('bols_charbon_tanin', 15, 2)->default(0);
            $table->decimal('alfa', 15, 2)->default(0);
            $table->decimal('produits_divers', 15, 2)->default(0);
            $table->decimal('interets_retard', 15, 2)->default(0);
            $table->decimal('total_province', 15, 2)->default(0);
            $table->timestamps();
            
            // Indexes
            $table->index(['situation_administrative_id', 'year']);
            $table->index('year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('province_annual_shares');
    }
};
