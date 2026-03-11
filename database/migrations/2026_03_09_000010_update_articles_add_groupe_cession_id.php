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
            if (!Schema::hasColumn('articles', 'groupe_cession_id')) {
                // Place column near the end to avoid relying on specific preceding column names
                $table->unsignedBigInteger('groupe_cession_id')->nullable();

                $table->foreign('groupe_cession_id')
                    ->references('id')
                    ->on('groupe_cession')
                    ->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            if (Schema::hasColumn('articles', 'groupe_cession_id')) {
                $table->dropForeign(['groupe_cession_id']);
                $table->dropColumn('groupe_cession_id');
            }
        });
    }
};

