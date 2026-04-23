<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alerts', function (Blueprint $table) {
            $table->id();
            // Alert type (domain-specific constants defined in Alert model)
            $table->string('type', 80)->index();
            // Target entity (polymorphic-style, no FK constraint for flexibility)
            $table->string('entity_type', 80)->nullable()->index();
            $table->unsignedBigInteger('entity_id')->nullable()->index();
            // Content
            $table->string('title', 200);
            $table->text('message')->nullable();
            // Severity: info | warning | critical
            $table->enum('severity', ['info', 'warning', 'critical'])->default('warning')->index();
            // Lifecycle: active | archived
            $table->enum('status', ['active', 'archived'])->default('active')->index();
            $table->timestamp('archived_at')->nullable();
            $table->string('archived_reason', 255)->nullable();
            // Optional: user who should act / who archived
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->unsignedBigInteger('archived_by')->nullable();
            // Extra structured data (deadline, metadata)
            $table->json('data')->nullable();
            $table->timestamps();

            $table->index(['entity_type', 'entity_id']);
            $table->index(['status', 'severity']);
            $table->index(['type', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alerts');
    }
};
