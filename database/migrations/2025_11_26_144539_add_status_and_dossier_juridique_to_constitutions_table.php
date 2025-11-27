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
        Schema::table('constitutions', function (Blueprint $table) {
            $table->string('status')->nullable()->after('participant');
            $table->string('dossier_juridique')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('constitutions', function (Blueprint $table) {
            $table->dropColumn(['status', 'dossier_juridique']);
        });
    }
};
