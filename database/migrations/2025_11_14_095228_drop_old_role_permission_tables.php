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
        // Get table names from config (Spatie tables)
        $tableNames = config('permission.table_names', [
            'permissions' => 'permissions',
            'roles' => 'roles',
            'model_has_permissions' => 'model_has_permissions',
            'model_has_roles' => 'model_has_roles',
            'role_has_permissions' => 'role_has_permissions',
        ]);

        // Drop tables in correct order (respecting foreign key constraints)
        // Drop pivot tables first
        $this->dropTableIfExists($tableNames['role_has_permissions'] ?? 'role_has_permissions');
        $this->dropTableIfExists($tableNames['model_has_roles'] ?? 'model_has_roles');
        $this->dropTableIfExists($tableNames['model_has_permissions'] ?? 'model_has_permissions');
        
        // Drop main tables
        $this->dropTableIfExists($tableNames['roles'] ?? 'roles');
        $this->dropTableIfExists($tableNames['permissions'] ?? 'permissions');

        // Drop any custom role/permission tables that might exist
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
