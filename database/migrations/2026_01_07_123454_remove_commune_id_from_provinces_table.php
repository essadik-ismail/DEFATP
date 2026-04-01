<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('provinces', function (Blueprint $table) {
            $table->dropForeign(['commune_id']);
            $table->dropColumn('commune_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('provinces', function (Blueprint $table) {
            $table->foreignId('commune_id')->after('nom')->constrained('communes')->onDelete('cascade');
        });

        DB::table('communes')
            ->select('id', 'province_id')
            ->whereNotNull('province_id')
            ->orderBy('id')
            ->get()
            ->each(function ($commune): void {
                DB::table('provinces')
                    ->where('id', $commune->province_id)
                    ->update(['commune_id' => $commune->id]);
            });
    }
};
