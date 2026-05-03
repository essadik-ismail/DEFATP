<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('permi_enlevers', function (Blueprint $table) {
            $table->string('fichier_permis_signe')->nullable()->after('volume');
            $table->timestamp('signed_at')->nullable()->after('fichier_permis_signe');
        });
    }

    public function down(): void
    {
        Schema::table('permi_enlevers', function (Blueprint $table) {
            $table->dropColumn(['fichier_permis_signe', 'signed_at']);
        });
    }
};
