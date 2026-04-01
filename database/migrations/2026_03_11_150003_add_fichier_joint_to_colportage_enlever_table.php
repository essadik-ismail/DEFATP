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
        Schema::table('colportage_enlever', function (Blueprint $table) {
            $table->string('fichier_joint')->nullable()->after('carnet_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('colportage_enlever', function (Blueprint $table) {
            $table->dropColumn('fichier_joint');
        });
    }
};

