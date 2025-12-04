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
        Schema::create('monthly_revenues', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->integer('month'); // 1-12
            $table->decimal('llege', 15, 2)->default(0);
            $table->decimal('bols_charbon_tanin', 15, 2)->default(0);
            $table->decimal('alfa', 15, 2)->default(0);
            $table->decimal('produits_divers', 15, 2)->default(0);
            $table->decimal('interets_retard', 15, 2)->default(0);
            $table->decimal('total_part_province', 15, 2)->default(0);
            $table->foreignId('situation_administrative_id')->constrained('situation_administratives')->onDelete('cascade');
            $table->timestamps();
            
            // Indexes
            $table->index(['situation_administrative_id', 'year', 'month']);
            $table->index(['year', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_revenues');
    }
};
