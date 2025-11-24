<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            // Update any null values to false
            DB::table('articles')->whereNull('invendu')->update(['invendu' => false]);
            
            // Ensure the column has default(false) and is not nullable
            $table->boolean('invendu')->default(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            // Revert to nullable if needed
            $table->boolean('invendu')->nullable()->default(false)->change();
        });
    }
};
