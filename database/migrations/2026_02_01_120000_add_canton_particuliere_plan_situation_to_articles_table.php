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
            if (!Schema::hasColumn('articles', 'canton')) {
                $table->string('canton')->nullable()->after('nature_juridique');
            }
            if (!Schema::hasColumn('articles', 'particuliere')) {
                $table->text('particuliere')->nullable()->after('canton');
            }
            // if (!Schema::hasColumn('articles', 'plan_situation')) {
            //     $table->text('plan_situation')->nullable()->after('particuliere');
            // }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            if (Schema::hasColumn('articles', 'canton')) {
                $table->dropColumn('canton');
            }
            if (Schema::hasColumn('articles', 'particuliere')) {
                $table->dropColumn('particuliere');
            }
            if (Schema::hasColumn('articles', 'plan_situation')) {
                $table->dropColumn('plan_situation');
            }
        });
    }
};
