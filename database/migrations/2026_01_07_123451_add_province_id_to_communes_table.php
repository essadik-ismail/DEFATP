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
        Schema::table('communes', function (Blueprint $table) {
            $table->foreignId('province_id')->nullable()->after('nom')->constrained('provinces')->onDelete('cascade');
        });

        DB::table('provinces')
            ->select('id', 'commune_id')
            ->whereNotNull('commune_id')
            ->orderBy('id')
            ->get()
            ->each(function ($province): void {
                DB::table('communes')
                    ->where('id', $province->commune_id)
                    ->update(['province_id' => $province->id]);
            });

        Schema::table('communes', function (Blueprint $table) {
            $table->foreignId('province_id')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('communes', function (Blueprint $table) {
            $table->dropForeign(['province_id']);
            $table->dropColumn('province_id');
        });
    }
};
