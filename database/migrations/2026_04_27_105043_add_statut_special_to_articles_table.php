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
        Schema::table('articles', function (Blueprint $table) {
            $table->string('statut_special')->nullable()->after('current_step');
            $table->date('date_statut_special')->nullable()->after('statut_special');
            $table->text('motif_statut_special')->nullable()->after('date_statut_special');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn(['statut_special', 'date_statut_special', 'motif_statut_special']);
        });
    }
};
