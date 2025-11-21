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
        Schema::create('odf_etaps', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('lieu')->nullable();
            $table->text('participant')->nullable();
            $table->text('description')->nullable();
            $table->text('resultat')->nullable();
            $table->string('fichierjoin')->nullable();
            $table->foreignId('odf_id')->nullable()->constrained('odfs')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('odf_etaps');
    }
};
