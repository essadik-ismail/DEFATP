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
        Schema::create('odf_modification', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->text('modification')->nullable();
            $table->text('actions')->nullable();
            $table->text('commentaire')->nullable();
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
        Schema::dropIfExists('odf_modification');
    }
};
