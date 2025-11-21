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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('lieu')->nullable();
            $table->string('objet');
            $table->date('date');
            $table->text('participants')->nullable();
            $table->text('description')->nullable();
            $table->string('fichier_joint')->nullable();
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
        Schema::dropIfExists('activities');
    }
};
