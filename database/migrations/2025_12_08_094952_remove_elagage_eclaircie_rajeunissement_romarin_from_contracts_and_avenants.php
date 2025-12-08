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
        // Remove columns from contacts (contracts) table
        if (Schema::hasTable('contacts')) {
            Schema::table('contacts', function (Blueprint $table) {
                if (Schema::hasColumn('contacts', 'elagage')) {
                    $table->dropColumn('elagage');
                }
                if (Schema::hasColumn('contacts', 'eclaircie')) {
                    $table->dropColumn('eclaircie');
                }
                if (Schema::hasColumn('contacts', 'rajeunissement_romarin')) {
                    $table->dropColumn('rajeunissement_romarin');
                }
            });
        }

        // Remove columns from avenants table
        if (Schema::hasTable('avenants')) {
            Schema::table('avenants', function (Blueprint $table) {
                if (Schema::hasColumn('avenants', 'elagage')) {
                    $table->dropColumn('elagage');
                }
                if (Schema::hasColumn('avenants', 'eclaircie')) {
                    $table->dropColumn('eclaircie');
                }
                if (Schema::hasColumn('avenants', 'rajeunissement_romarin')) {
                    $table->dropColumn('rajeunissement_romarin');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-add columns to contacts (contracts) table
        if (Schema::hasTable('contacts')) {
            Schema::table('contacts', function (Blueprint $table) {
                $table->string('elagage')->nullable();
                $table->string('eclaircie')->nullable();
                $table->string('rajeunissement_romarin')->nullable();
            });
        }

        // Re-add columns to avenants table
        if (Schema::hasTable('avenants')) {
            Schema::table('avenants', function (Blueprint $table) {
                $table->decimal('elagage', 10, 2)->nullable();
                $table->decimal('eclaircie', 10, 2)->nullable();
                $table->decimal('rajeunissement_romarin', 10, 2)->nullable();
            });
        }
    }
};
