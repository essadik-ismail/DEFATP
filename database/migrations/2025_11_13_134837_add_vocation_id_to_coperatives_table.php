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
        Schema::table('coperatives', function (Blueprint $table) {
            $table->foreignId('vocation_id')->nullable()->after('nom')->constrained('vocations')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coperatives', function (Blueprint $table) {
            $table->dropForeign(['vocation_id']);
            $table->dropColumn('vocation_id');
        });
    }
};
