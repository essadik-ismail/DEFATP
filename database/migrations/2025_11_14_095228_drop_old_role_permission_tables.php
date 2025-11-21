<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Drop all role and permission related tables to prepare for Spatie Laravel Permission.
     */
    public function up(): void
    {
        // Only drop old custom role/permission tables that might exist
        // Do NOT drop Spatie permission tables as they are created by create_permission_tables migration
        $this->dropTableIfExists('user_roles');
        $this->dropTableIfExists('role_user');
        $this->dropTableIfExists('user_permissions');
        $this->dropTableIfExists('permission_user');
        $this->dropTableIfExists('users_roles');
        $this->dropTableIfExists('users_permissions');
        
        // Clear Spatie permission cache
        app('cache')
            ->store(config('permission.cache.store') != 'default' ? config('permission.cache.store') : null)
            ->forget(config('permission.cache.key'));
    }

    /**
     * Reverse the migrations.
     * This would recreate the tables, but we'll leave it empty
     * as the Spatie migration will handle recreation.
     */
    public function down(): void
    {
        // Tables will be recreated by running the Spatie permission tables migration
        // php artisan migrate --path=database/migrations/2025_09_12_094159_create_permission_tables.php
    }

    /**
     * Helper method to safely drop a table if it exists.
     */
    private function dropTableIfExists(string $tableName): void
    {
        if (Schema::hasTable($tableName)) {
            Schema::dropIfExists($tableName);
        }
    }
};
