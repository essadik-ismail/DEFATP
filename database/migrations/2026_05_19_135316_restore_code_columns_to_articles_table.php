<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            if (!Schema::hasColumn('articles', 'dranef_code')) {
                $table->string('dranef_code')->nullable()->after('invendu');
                $table->foreign('dranef_code')->references('code')->on('dranefs')->onDelete('set null');
            }
            if (!Schema::hasColumn('articles', 'dpanef_code')) {
                $table->string('dpanef_code')->nullable()->after('dranef_code');
                $table->foreign('dpanef_code')->references('code')->on('dpanefs')->onDelete('set null');
            }
            if (!Schema::hasColumn('articles', 'zdtf_code')) {
                $table->string('zdtf_code')->nullable()->after('dpanef_code');
                $table->foreign('zdtf_code')->references('code')->on('zdtfs')->onDelete('set null');
            }
            if (!Schema::hasColumn('articles', 'dfp_code')) {
                $table->string('dfp_code')->nullable()->after('zdtf_code');
                $table->foreign('dfp_code')->references('code')->on('dfps')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            if (Schema::hasColumn('articles', 'dfp_code')) {
                $table->dropForeign(['dfp_code']);
                $table->dropColumn('dfp_code');
            }
            if (Schema::hasColumn('articles', 'zdtf_code')) {
                $table->dropForeign(['zdtf_code']);
                $table->dropColumn('zdtf_code');
            }
            if (Schema::hasColumn('articles', 'dpanef_code')) {
                $table->dropForeign(['dpanef_code']);
                $table->dropColumn('dpanef_code');
            }
            if (Schema::hasColumn('articles', 'dranef_code')) {
                $table->dropForeign(['dranef_code']);
                $table->dropColumn('dranef_code');
            }
        });
    }
};
