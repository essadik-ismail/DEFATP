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
        Schema::table('phases', function (Blueprint $table) {
            $table->string('nom')->nullable()->after('num');
            $table->date('date_de_début')->nullable()->after('date');
            $table->date('date_de_fin')->nullable()->after('date_de_début');
            $table->enum('etat', ['en_cours', 'terminée', 'validée'])->default('en_cours')->after('échéance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('phases', function (Blueprint $table) {
            $table->dropColumn(['nom', 'date_de_début', 'date_de_fin', 'etat']);
        });
    }
};
