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
        Schema::table('articles', function (Blueprint $table) {
            // Change lot column from integer to string if it exists
            if (Schema::hasColumn('articles', 'lot')) {
                $table->string('lot')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            // Revert back to integer (though this shouldn't be needed)
            if (Schema::hasColumn('articles', 'lot')) {
                $table->integer('lot')->nullable()->change();
            }
        });
    }
};
