<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contract_ventes', function (Blueprint $table) {
            // Adjudication letter tracking
            $table->timestamp('letter_generated_at')->nullable()->after('Current_state');
            $table->string('letter_signed_file', 255)->nullable()->after('letter_generated_at');
            $table->timestamp('letter_signed_at')->nullable()->after('letter_signed_file');
            // Contract end/expiry date (derived from date_adjudication + duree)
            $table->date('date_expiration')->nullable()->after('date_limite_tranche');
        });

        Schema::table('payments', function (Blueprint $table) {
            // Type distinguishes caution vs taxes vs tranche
            if (!Schema::hasColumn('payments', 'type')) {
                $table->enum('type', ['caution', 'taxe', 'tranche', 'autre'])
                    ->default('autre')
                    ->after('nom')
                    ->index();
            }
        });
    }

    public function down(): void
    {
        Schema::table('contract_ventes', function (Blueprint $table) {
            $table->dropColumn(['letter_generated_at', 'letter_signed_file', 'letter_signed_at', 'date_expiration']);
        });
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'type')) {
                $table->dropColumn('type');
            }
        });
    }
};
