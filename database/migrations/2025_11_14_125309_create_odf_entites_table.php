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
        Schema::create('odf_entites', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('localisation_id')->nullable()->constrained('localisations')->onDelete('set null');
            $table->foreignId('situation_administrative_id')->nullable()->constrained('situation_administratives')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('odf_entites');
    }
};
