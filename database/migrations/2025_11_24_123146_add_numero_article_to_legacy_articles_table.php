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
        Schema::table('legacy_articles', function (Blueprint $table) {
            $table->string('numero_article', 50)->nullable()->after('dref')->comment('Numéro d\'article');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('legacy_articles', function (Blueprint $table) {
            $table->dropColumn('numero_article');
        });
    }
};
