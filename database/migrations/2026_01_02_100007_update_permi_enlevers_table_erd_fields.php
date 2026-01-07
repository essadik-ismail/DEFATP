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
        Schema::table('permi_enlevers', function (Blueprint $table) {
            // Add num field from ERD
            $table->string('num')->nullable()->after('id');
            
            // Index
            $table->index('num');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permi_enlevers', function (Blueprint $table) {
            $table->dropIndex(['num']);
            $table->dropColumn('num');
        });
    }
};

