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
        Schema::create('dranefs', function (Blueprint $table) {
            $table->id();
            $table->string('dranef');
            $table->text('adresse')->nullable();
            $table->string('tel')->nullable();
            $table->string('fax')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dranefs');
    }
};
