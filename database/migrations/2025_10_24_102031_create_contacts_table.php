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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->integer('annee');
            $table->string('contarct'); // Note: keeping original typo as per schema
            $table->foreignId('localisation_id')->constrained('localisations')->onDelete('cascade');
            $table->string('attribute2')->nullable();
            $table->string('attribute4')->nullable();
            $table->string('attribute5')->nullable();
            $table->string('attribute6')->nullable();
            $table->string('attribute7')->nullable();
            $table->string('attribute8')->nullable();
            $table->string('attribute9')->nullable();
            $table->string('attribute10')->nullable();
            $table->string('attribute11')->nullable();
            $table->string('attribute12')->nullable();
            $table->string('attribute13')->nullable();
            $table->string('attribute14')->nullable();
            $table->string('attribute15')->nullable();
            $table->string('attribute16')->nullable();
            $table->string('attribute17')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};