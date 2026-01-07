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
        Schema::table('dpanefs', function (Blueprint $table) {
            // Add code field
            if (!Schema::hasColumn('dpanefs', 'code')) {
                $table->string('code')->unique()->after('id');
            }
            
            // Add dranef_code field
            if (!Schema::hasColumn('dpanefs', 'dranef_code')) {
                $table->string('dranef_code')->nullable()->after('code');
            }
            
            // Keep dranef_id for backward compatibility, but we'll use dranef_code for relationships
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dpanefs', function (Blueprint $table) {
            if (Schema::hasColumn('dpanefs', 'code')) {
                $table->dropColumn('code');
            }
            if (Schema::hasColumn('dpanefs', 'dranef_code')) {
                $table->dropColumn('dranef_code');
            }
        });
    }
};
