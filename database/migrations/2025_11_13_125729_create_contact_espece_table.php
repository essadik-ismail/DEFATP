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
        Schema::create('contact_espece', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->constrained('contacts')->onDelete('cascade');
            $table->foreignId('espece_id')->constrained('especes')->onDelete('cascade');
            $table->timestamps();
            
            // Ensure unique combination of contact and espece
            $table->unique(['contact_id', 'espece_id'], 'contact_espece_unique');
            
            // Indexes for better performance
            $table->index('contact_id');
            $table->index('espece_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_espece');
    }
};
