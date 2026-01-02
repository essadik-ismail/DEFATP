<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing permissions cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define all resources and their actions
        $resources = [
            // Main Resources
            'dashboard' => ['view'],
            'articles' => ['view', 'create', 'update', 'delete', 'export', 'import'],
            'contracts' => ['view', 'create', 'update', 'delete', 'export', 'import'],
            'avenants' => ['view', 'create', 'update', 'delete'],
            'exploitants' => ['view', 'create', 'update', 'delete', 'export', 'import'],
            'coperatives' => ['view', 'create', 'update', 'delete', 'export', 'import'],
            
            // Entity Data Resources
            'essences' => ['view', 'create', 'update', 'delete', 'export', 'import'],
            'forets' => ['view', 'create', 'update', 'delete', 'export', 'import'],
            'situation-administratives' => ['view', 'create', 'update', 'delete', 'export', 'import'],
            'nature-de-coupes' => ['view', 'create', 'update', 'delete', 'export', 'import'],
            'especes' => ['view', 'create', 'update', 'delete'],
            'vocations' => ['view', 'create', 'update', 'delete'],
            
            // Entity Data Management
            'entity-data' => ['view', 'create', 'update', 'delete'],
            
            // Reports
            'reports' => ['view', 'export'],
            
            // User Management
            'users' => ['view', 'create', 'update', 'delete', 'export'],
            'roles' => ['view', 'create', 'update', 'delete'],
            'permissions' => ['view', 'create', 'update', 'delete'],
            
            // Activity Logs
            'activity-logs' => ['view', 'export'],
            
            // Excel/Import-Export
            'excel' => ['view', 'export', 'import'],
            
            // Settings
            'settings' => ['view'],
            
            // Notifications
            'notifications' => ['view', 'create', 'update', 'delete'],
        ];

        // Create permissions for each resource and action
        foreach ($resources as $resource => $actions) {
            foreach ($actions as $action) {
                $permissionName = "{$resource}.{$action}";
                
                Permission::firstOrCreate(
                    ['name' => $permissionName],
                    ['guard_name' => 'web']
                );
            }
        }

        $this->command->info('Permissions créées avec succès!');
    }
}
