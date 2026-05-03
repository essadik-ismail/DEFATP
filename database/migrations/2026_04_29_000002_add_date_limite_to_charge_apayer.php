<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('charge_apayer', function (Blueprint $table) {
            $table->date('date_limite')->nullable()->after('date_echeance');
        });
    }

    public function down(): void
    {
        Schema::table('charge_apayer', function (Blueprint $table) {
            $table->dropColumn('date_limite');
        });
    }
};
