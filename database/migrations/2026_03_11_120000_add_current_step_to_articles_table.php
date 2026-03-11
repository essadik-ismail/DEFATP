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
            if (!Schema::hasColumn('articles', 'current_step')) {
                // Track the workflow step for the article (cahier_affiche, contrat_vente, etc.)
                $table->string('current_step', 50)->nullable()->after('coordonnee_y');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            if (Schema::hasColumn('articles', 'current_step')) {
                $table->dropColumn('current_step');
            }
        });
    }
};

