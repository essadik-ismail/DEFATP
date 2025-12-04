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
        Schema::table('situation_administratives', function (Blueprint $table) {
            if (!Schema::hasColumn('situation_administratives', 'region')) {
                $table->string('region')->nullable()->after('province');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('situation_administratives', function (Blueprint $table) {
            if (Schema::hasColumn('situation_administratives', 'region')) {
                $table->dropColumn('region');
            }
        });
    }
};


