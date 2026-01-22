<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Fix typo: Rename 'invandu' to 'invendu' in articles table
     */
    public function up(): void
    {
        // Check if the incorrectly named column exists
        if (Schema::hasColumn('articles', 'invandu')) {
            // Check if the correctly named column already exists
            if (!Schema::hasColumn('articles', 'invendu')) {
                Schema::table('articles', function (Blueprint $table) {
                    $table->renameColumn('invandu', 'invendu');
                });
            } else {
                // Both columns exist - copy data and drop old one
                DB::statement('UPDATE articles SET invendu = invandu WHERE invandu IS NOT NULL');
                Schema::table('articles', function (Blueprint $table) {
                    $table->dropColumn('invandu');
                });
            }
        } elseif (!Schema::hasColumn('articles', 'invendu')) {
            // Neither column exists - create the correct one
            Schema::table('articles', function (Blueprint $table) {
                $table->boolean('invendu')->default(false)->after('date_livaison_mise_en_charge_bf');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('articles', 'invendu')) {
            Schema::table('articles', function (Blueprint $table) {
                $table->renameColumn('invendu', 'invandu');
            });
        }
    }
};
