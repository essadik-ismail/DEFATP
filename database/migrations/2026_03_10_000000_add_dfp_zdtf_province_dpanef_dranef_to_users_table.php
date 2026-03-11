<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add DFP, ZDTF, Province, DPANEF, DRANEF references to users table.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('dranef_id')->nullable()->after('role');
            $table->unsignedBigInteger('dpanef_id')->nullable()->after('dranef_id');
            $table->unsignedBigInteger('zdtf_id')->nullable()->after('dpanef_id');
            $table->unsignedBigInteger('dfp_id')->nullable()->after('zdtf_id');
            $table->unsignedBigInteger('province_id')->nullable()->after('dfp_id');

            $table->foreign('dranef_id')->references('id')->on('dranefs')->onDelete('set null');
            $table->foreign('dpanef_id')->references('id')->on('dpanefs')->onDelete('set null');
            $table->foreign('zdtf_id')->references('id')->on('zdtfs')->onDelete('set null');
            $table->foreign('dfp_id')->references('id')->on('dfps')->onDelete('set null');
            $table->foreign('province_id')->references('id')->on('provinces')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['dranef_id']);
            $table->dropForeign(['dpanef_id']);
            $table->dropForeign(['zdtf_id']);
            $table->dropForeign(['dfp_id']);
            $table->dropForeign(['province_id']);

            $table->dropColumn(['dranef_id', 'dpanef_id', 'zdtf_id', 'dfp_id', 'province_id']);
        });
    }
};
