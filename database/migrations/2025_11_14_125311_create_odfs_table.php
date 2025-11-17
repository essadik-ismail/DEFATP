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
            $table->string('présidente')->nullable();
            $table->string('vice_présidente')->nullable();
            $table->string('trésorière')->nullable();
            $table->text('reçu_du_dépôt')->nullable();
            $table->text('constitution')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
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
