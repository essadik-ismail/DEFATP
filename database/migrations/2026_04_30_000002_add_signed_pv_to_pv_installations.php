<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pv_installations', function (Blueprint $table) {
            $table->string('fichier_pv_signe')->nullable()->after('invitation_caporal');
            $table->timestamp('pv_signed_at')->nullable()->after('fichier_pv_signe');
        });
    }

    public function down(): void
    {
        Schema::table('pv_installations', function (Blueprint $table) {
            $table->dropColumn(['fichier_pv_signe', 'pv_signed_at']);
        });
    }
};
