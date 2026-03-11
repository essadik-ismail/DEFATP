<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adapt carnets table from old (de, a) to (num, status, soft deletes) if needed.
     */
    public function up(): void
    {
        if (!Schema::hasTable('carnets')) {
            return;
        }

        if (Schema::hasColumn('carnets', 'de')) {
            Schema::table('carnets', function (Blueprint $table) {
                $table->dropColumn(['de', 'a']);
            });
        }

        if (!Schema::hasColumn('carnets', 'num')) {
            Schema::table('carnets', function (Blueprint $table) {
                $table->unsignedInteger('num')->default(0)->after('serie');
            });
        }

        if (!Schema::hasColumn('carnets', 'status')) {
            Schema::table('carnets', function (Blueprint $table) {
                $table->string('status', 20)->default('disponible')->after('num');
            });
        }

        if (!Schema::hasColumn('carnets', 'deleted_at')) {
            Schema::table('carnets', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optional: restore de, a and drop num, status, deleted_at if needed
    }
};
