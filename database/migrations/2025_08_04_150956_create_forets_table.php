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
        Schema::create('forets', function (Blueprint $table) {
            $table->id();
            $table->string('foret');
            $table->string('lat')->nullable();
            $table->string('log')->nullable();
            $table->foreignId('localisation_id')->nullable()->constrained()->onDelete('cascade');
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forets');
    }
};
