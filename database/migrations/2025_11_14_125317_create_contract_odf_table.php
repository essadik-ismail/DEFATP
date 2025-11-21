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
        Schema::create('contract_odf', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('lieu')->nullable();
            
            $table->string('signature1_nom')->nullable();
            $table->string('signature2_nom')->nullable();

            $table->string('signature1_type')->nullable();
            $table->string('signature2_type')->nullable();
            
            $table->string('fichier_join')->nullable();

            $table->foreignId('odf_id')->nullable()->constrained('odfs')->onDelete('set null');
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
        Schema::dropIfExists('contract_odf');
    }
};
