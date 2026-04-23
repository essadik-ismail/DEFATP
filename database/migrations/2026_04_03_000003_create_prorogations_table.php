<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prorogations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contract_vente_id')->index();
            // Duration in months
            $table->unsignedInteger('duration_months');
            // Status: pending | approved | rejected
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->index();
            $table->text('motif')->nullable();
            $table->text('decision_note')->nullable();
            // Audit: original expiry date before this prorogation was applied
            $table->date('original_expiry_date')->nullable();
            $table->date('new_expiry_date')->nullable();
            $table->unsignedBigInteger('requested_by')->nullable();
            $table->unsignedBigInteger('decided_by')->nullable();
            $table->timestamp('decided_at')->nullable();
            $table->timestamps();

            $table->foreign('contract_vente_id')
                ->references('id')->on('contract_ventes')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prorogations');
    }
};
