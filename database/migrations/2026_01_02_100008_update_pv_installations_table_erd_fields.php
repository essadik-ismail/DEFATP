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
        // Rename mo to emo if it exists
        if (Schema::hasColumn('pv_installations', 'mo') && !Schema::hasColumn('pv_installations', 'emo')) {
            Schema::table('pv_installations', function (Blueprint $table) {
                $table->renameColumn('mo', 'emo');
            });
        } elseif (!Schema::hasColumn('pv_installations', 'emo')) {
            Schema::table('pv_installations', function (Blueprint $table) {
                $table->string('emo')->nullable()->after('reserve');
            });
        }
        
        // charbonniére should be charbonnière (with è)
        // This is a minor spelling difference, keeping as is
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('pv_installations', 'emo') && !Schema::hasColumn('pv_installations', 'mo')) {
            Schema::table('pv_installations', function (Blueprint $table) {
                $table->renameColumn('emo', 'mo');
            });
        }
    }
};

