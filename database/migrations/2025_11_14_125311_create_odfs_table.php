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
        Schema::create('odfs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('odf_entite_id')->nullable()->constrained('odf_entites')->onDelete('set null');
            $table->boolean('constitution')->nullable();
            $table->text('commentaire')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('odfs');
    }
};
