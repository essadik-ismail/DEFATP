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
            if (!Schema::hasColumn('colportage_enlever', 'transport_nuit')) {
                $table->boolean('transport_nuit')->default(false)->after('destination');
            }
            if (!Schema::hasColumn('colportage_enlever', 'distance_km')) {
                $table->decimal('distance_km', 10, 2)->nullable()->after('transport_nuit');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('colportage_enlever', function (Blueprint $table) {
            $table->dropColumn(['transport_nuit', 'distance_km']);
        });
    }
};
