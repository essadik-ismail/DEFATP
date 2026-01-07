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
        // Update column names to match ERD (lat, log instead of x, y)
        if (Schema::hasColumn('locations', 'x') && !Schema::hasColumn('locations', 'lat')) {
            Schema::table('locations', function (Blueprint $table) {
                $table->renameColumn('x', 'lat');
            });
        }
        if (Schema::hasColumn('locations', 'y') && !Schema::hasColumn('locations', 'log')) {
            Schema::table('locations', function (Blueprint $table) {
                $table->renameColumn('y', 'log');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('locations', 'lat') && !Schema::hasColumn('locations', 'x')) {
            Schema::table('locations', function (Blueprint $table) {
                $table->renameColumn('lat', 'x');
            });
        }
        if (Schema::hasColumn('locations', 'log') && !Schema::hasColumn('locations', 'y')) {
            Schema::table('locations', function (Blueprint $table) {
                $table->renameColumn('log', 'y');
            });
        }
    }
};

