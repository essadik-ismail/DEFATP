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
        Schema::table('forets', function (Blueprint $table) {
            $table->string('nature_juridique')->nullable()->after('log');
            $table->foreignId('dpanef_id')->nullable()->after('nature_juridique')->constrained('dpanefs')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('forets', function (Blueprint $table) {
            $table->dropForeign(['dpanef_id']);
            $table->dropColumn(['nature_juridique', 'dpanef_id']);
        });
    }
};
