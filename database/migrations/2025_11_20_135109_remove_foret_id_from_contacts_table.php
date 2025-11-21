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
        Schema::table('contacts', function (Blueprint $table) {
            if (Schema::hasColumn('contacts', 'foret_id')) {
                $table->dropForeign(['foret_id']);
                $table->dropColumn('foret_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            if (!Schema::hasColumn('contacts', 'foret_id')) {
                $table->foreignId('foret_id')->nullable()->after('situation_administrative_id')->constrained('forets')->onDelete('cascade');
            }
        });
    }
};
