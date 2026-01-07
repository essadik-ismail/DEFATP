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
        Schema::table('dranefs', function (Blueprint $table) {
            if (!Schema::hasColumn('dranefs', 'code')) {
                $table->string('code')->unique()->after('id');
            }
            if (!Schema::hasColumn('dranefs', 'Abréviation')) {
                $table->string('Abréviation')->nullable()->after('dranef');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dranefs', function (Blueprint $table) {
            if (Schema::hasColumn('dranefs', 'code')) {
                $table->dropColumn('code');
            }
            if (Schema::hasColumn('dranefs', 'Abréviation')) {
                $table->dropColumn('Abréviation');
            }
        });
    }
};
