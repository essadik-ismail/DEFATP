<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Plan de situation is stored in the locations table (article_id, mat, x, y, etc.),
     * not on articles. Remove plan_situation column from articles if it exists.
     */
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            if (Schema::hasColumn('articles', 'plan_situation')) {
                $table->dropColumn('plan_situation');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            if (!Schema::hasColumn('articles', 'plan_situation')) {
                $table->text('plan_situation')->nullable()->after('particuliere');
            }
        });
    }
};
