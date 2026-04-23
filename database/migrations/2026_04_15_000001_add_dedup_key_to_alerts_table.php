<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds a dedup_key column to the alerts table.
 *
 * Why: Some alert types generate multiple distinct alerts for the same
 * (type, entity) pair — e.g. EXPIRATION_CONTRAT at 90 / 60 / 30 days,
 * or RETARD_TRANCHE per individual tranche order number.
 * The existing unique scope (type + entity_type + entity_id + status)
 * is too coarse for those cases.
 *
 * The dedup_key is an optional free-form string that further qualifies
 * the alert within its (type, entity) space. Examples:
 *   - "expiration_90d"  / "expiration_60d"  / "expiration_30d"
 *   - "tranche_3"
 *   - "resiliation_no_taxes"
 *   - "resiliation_expired_unpaid"
 *
 * NULL means "one alert of this type per entity" (original behaviour).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('alerts', function (Blueprint $table) {
            // Placed after entity_id for logical grouping
            $table->string('dedup_key', 120)->nullable()->after('entity_id');

            // Composite index used by AlertService::raise() dedup query
            $table->index(
                ['type', 'entity_type', 'entity_id', 'status', 'dedup_key'],
                'alerts_dedup_idx'
            );
        });
    }

    public function down(): void
    {
        Schema::table('alerts', function (Blueprint $table) {
            $table->dropIndex('alerts_dedup_idx');
            $table->dropColumn('dedup_key');
        });
    }
};
