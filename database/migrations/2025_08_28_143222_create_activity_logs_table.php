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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('action'); // login, logout, create, update, delete, view, etc.
            $table->string('model_type')->nullable(); // Article, User, Exploitant, etc.
            $table->unsignedBigInteger('model_id')->nullable(); // ID of the affected model
            $table->string('description'); // Human-readable description
            $table->json('properties')->nullable(); // Additional data (old values, new values, etc.)
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('url')->nullable();
            $table->string('method')->nullable(); // GET, POST, PUT, DELETE
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['user_id', 'created_at']);
            $table->index(['action', 'created_at']);
            $table->index(['model_type', 'model_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
