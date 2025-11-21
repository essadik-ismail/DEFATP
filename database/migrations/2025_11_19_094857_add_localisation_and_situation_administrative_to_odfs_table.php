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
        Schema::table('odfs', function (Blueprint $table) {
            $table->foreignId('localisation_id')->nullable()->after('odf_entite_id')->constrained('localisations')->onDelete('set null');
            $table->foreignId('situation_administrative_id')->nullable()->after('localisation_id')->constrained('situation_administratives')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('odfs', function (Blueprint $table) {
            $table->dropForeign(['localisation_id']);
            $table->dropForeign(['situation_administrative_id']);
            $table->dropColumn(['localisation_id', 'situation_administrative_id']);
        });
    }
};
