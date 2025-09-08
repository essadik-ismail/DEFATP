<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Check if an index exists on a table
     */
    private function indexExists(string $table, string $index): bool
    {
        try {
            $indexes = DB::select("SHOW INDEX FROM {$table} WHERE Key_name = ?", [$index]);
            return count($indexes) > 0;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Activity logs table indexes (additional indexes for better performance)
        Schema::table('activity_logs', function (Blueprint $table) {
            if (!$this->indexExists('activity_logs', 'activity_logs_action_created_idx')) {
                $table->index(['action', 'created_at'], 'activity_logs_action_created_idx');
            }
            if (!$this->indexExists('activity_logs', 'activity_logs_model_type_created_idx')) {
                $table->index(['model_type', 'created_at'], 'activity_logs_model_type_created_idx');
            }
            if (!$this->indexExists('activity_logs', 'activity_logs_ip_address_idx')) {
                $table->index(['ip_address'], 'activity_logs_ip_address_idx');
            }
            if (!$this->indexExists('activity_logs', 'activity_logs_method_idx')) {
                $table->index(['method'], 'activity_logs_method_idx');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Activity logs table indexes
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropIndex('activity_logs_action_created_idx');
            $table->dropIndex('activity_logs_model_type_created_idx');
            $table->dropIndex('activity_logs_ip_address_idx');
            $table->dropIndex('activity_logs_method_idx');
        });
    }
};
