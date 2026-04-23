<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recolements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contract_vente_id')->index();
            // PV de récolement submitted by provincial commission
            $table->date('date_pv')->nullable();
            $table->string('num_pv', 80)->nullable();
            $table->text('observations')->nullable();
            $table->string('fichier_pv', 255)->nullable();
            $table->unsignedBigInteger('submitted_by')->nullable();
            // Mainlevée issued by regional (DRANEF)
            $table->date('date_mainlevee')->nullable();
            $table->string('num_mainlevee', 80)->nullable();
            $table->string('fichier_mainlevee', 255)->nullable();
            $table->unsignedBigInteger('mainlevee_issued_by')->nullable();
            // Status: pending_pv | pv_submitted | mainlevee_issued | closed
            $table->enum('status', ['pending_pv', 'pv_submitted', 'mainlevee_issued', 'closed'])
                ->default('pending_pv')->index();
            $table->timestamps();

            $table->foreign('contract_vente_id')
                ->references('id')->on('contract_ventes')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recolements');
    }
};
