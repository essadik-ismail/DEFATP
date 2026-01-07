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
        Schema::table('zdtfs', function (Blueprint $table) {
            // Add code field
            if (!Schema::hasColumn('zdtfs', 'code')) {
                $table->string('code')->unique()->after('id');
            }
            
            // Add zdtf field (keep sdtf for backward compatibility)
            if (!Schema::hasColumn('zdtfs', 'zdtf')) {
                if (Schema::hasColumn('zdtfs', 'sdtf')) {
                    // Copy data from sdtf to zdtf
                    $table->string('zdtf')->nullable()->after('code');
                } else {
                    $table->string('zdtf')->after('code');
                }
            }
            
            // Add dpanef_code field
            if (!Schema::hasColumn('zdtfs', 'dpanef_code')) {
                $table->string('dpanef_code')->nullable()->after('code');
            }
            
            // Keep dpanef_id and sdtf for backward compatibility
        });
        
        // Copy data from sdtf to zdtf if zdtf is null
        if (Schema::hasColumn('zdtfs', 'sdtf') && Schema::hasColumn('zdtfs', 'zdtf')) {
            \DB::statement('UPDATE zdtfs SET zdtf = sdtf WHERE zdtf IS NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('zdtfs', function (Blueprint $table) {
            if (Schema::hasColumn('zdtfs', 'code')) {
                $table->dropColumn('code');
            }
            if (Schema::hasColumn('zdtfs', 'zdtf')) {
                $table->dropColumn('zdtf');
            }
            if (Schema::hasColumn('zdtfs', 'dpanef_code')) {
                $table->dropColumn('dpanef_code');
            }
        });
    }
};
