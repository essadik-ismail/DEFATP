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
        if (!Schema::hasTable('carnets') || Schema::hasColumn('carnets', 'type')) {
            return;
        }

        Schema::table('carnets', function (Blueprint $table) {
            $table->enum('type', [
                "Bois de for\u{00EA}t priv\u{00E9}e",
                "Bois de for\u{00EA}t domaniale",
            ])->default("Bois de for\u{00EA}t domaniale");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('carnets') || !Schema::hasColumn('carnets', 'type')) {
            return;
        }

        Schema::table('carnets', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
