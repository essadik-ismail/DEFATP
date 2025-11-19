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
        Schema::create('activity_journals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('Objet');
            $table->date('Date');
            $table->string('Lieu')->nullable();
            $table->text('Participants')->nullable();
            $table->text('Description')->nullable();
            $table->text('Recommandations')->nullable();
            $table->text('Conclusion')->nullable();
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['user_id', 'created_at']);
            $table->index('Date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_journals');
    }
};
