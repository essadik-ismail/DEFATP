<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Limites du lot (Nord, Sud, Est, Ouest) and Coordonnées du centre (X, Y).
     */
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            if (!Schema::hasColumn('articles', 'limite_nord')) {
                $table->string('limite_nord')->nullable()->after('particuliere');
            }
            if (!Schema::hasColumn('articles', 'limite_sud')) {
                $table->string('limite_sud')->nullable()->after('limite_nord');
            }
            if (!Schema::hasColumn('articles', 'limite_est')) {
                $table->string('limite_est')->nullable()->after('limite_sud');
            }
            if (!Schema::hasColumn('articles', 'limite_ouest')) {
                $table->string('limite_ouest')->nullable()->after('limite_est');
            }
            if (!Schema::hasColumn('articles', 'coordonnee_x')) {
                $table->decimal('coordonnee_x', 15, 6)->nullable()->after('limite_ouest');
            }
            if (!Schema::hasColumn('articles', 'coordonnee_y')) {
                $table->decimal('coordonnee_y', 15, 6)->nullable()->after('coordonnee_x');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            if (Schema::hasColumn('articles', 'limite_nord')) {
                $table->dropColumn('limite_nord');
            }
            if (Schema::hasColumn('articles', 'limite_sud')) {
                $table->dropColumn('limite_sud');
            }
            if (Schema::hasColumn('articles', 'limite_est')) {
                $table->dropColumn('limite_est');
            }
            if (Schema::hasColumn('articles', 'limite_ouest')) {
                $table->dropColumn('limite_ouest');
            }
            if (Schema::hasColumn('articles', 'coordonnee_x')) {
                $table->dropColumn('coordonnee_x');
            }
            if (Schema::hasColumn('articles', 'coordonnee_y')) {
                $table->dropColumn('coordonnee_y');
            }
        });
    }
};
