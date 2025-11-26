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
        Schema::create('constitutions', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->string('lieu')->nullable();
            $table->text('participant')->nullable();
            $table->date('date_depot_odf')->nullable();
            $table->string('fichier_joint_depot_odf')->nullable();
            $table->string('lieu_depot_odf')->nullable();
            $table->date('date_reçu_définitive')->nullable();
            $table->string('fichier_joint_reçu_définitive')->nullable();
            $table->string('lieu_reçu_définitive')->nullable();
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
        Schema::dropIfExists('constitutions');
    }
};

