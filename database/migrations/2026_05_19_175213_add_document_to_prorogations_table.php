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
        Schema::table('prorogations', function (Blueprint $table) {
            $table->string('document')->nullable()->after('motif');
        });
    }

    public function down(): void
    {
        Schema::table('prorogations', function (Blueprint $table) {
            $table->dropColumn('document');
        });
    }
};
