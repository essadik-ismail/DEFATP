<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            // Dashboard
            'dashboard.view',
            
            // Articles
            'articles.view',
            'articles.create',
            'articles.edit',
            'articles.delete',
            'articles.export',
            'articles.import',
            'articles.print',
            
            // Settings - Essences
            'essences.view',
            'essences.create',
            'essences.edit',
            'essences.delete',
            'essences.export',
            'essences.import',
            
            // Settings - Forets
            'forets.view',
            'forets.create',
            'forets.edit',
            'forets.delete',
            'forets.export',
            'forets.import',
            
            // Settings - Nature de Coupes
            'nature-de-coupes.view',
            'nature-de-coupes.create',
            'nature-de-coupes.edit',
            'nature-de-coupes.delete',
            'nature-de-coupes.export',
            'nature-de-coupes.import',
            
            // Settings - Situation Administratives
            'situation-administratives.view',
            'situation-administratives.create',
            'situation-administratives.edit',
            'situation-administratives.delete',
            'situation-administratives.export',
            'situation-administratives.import',
            
            // Settings - Localisations
            'localisations.view',
            'localisations.create',
            'localisations.edit',
            'localisations.delete',
            'localisations.export',
            'localisations.import',
            
            // Settings - Exploitants
            'exploitants.view',
            'exploitants.create',
            'exploitants.edit',
            'exploitants.delete',
            'exploitants.export',
            'exploitants.import',
            'exploitants.print-card',
            
            // Users
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'users.assign-roles',
            
            // Roles & Permissions
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',
            'permissions.view',
            'permissions.assign',
            
            // Notifications
            'notifications.view',
            'notifications.create',
            'notifications.send',
            'notifications.manage',
            
            // Activity Logs
            'activity-logs.view',
            'activity-logs.export',
            
            // System
            'system.backup',
            'system.maintenance',
            'system.settings',
            
            // Profile
            'profile.view',
            'profile.edit',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $managerRole = Role::firstOrCreate(['name' => 'manager']);
        $operatorRole = Role::firstOrCreate(['name' => 'operator']);
        $userRole = Role::firstOrCreate(['name' => 'user']);
        $viewerRole = Role::firstOrCreate(['name' => 'viewer']);

        // Assign all permissions to admin
        $adminRole->givePermissionTo(Permission::all());

        // Assign permissions to manager
        $managerRole->givePermissionTo([
            'dashboard.view',
            'articles.view',
            'articles.create',
            'articles.edit',
            'articles.export',
            'articles.import',
            'articles.print',
            'exploitants.view',
            'exploitants.create',
            'exploitants.edit',
            'exploitants.export',
            'exploitants.print-card',
            'forets.view',
            'forets.create',
            'forets.edit',
            'forets.export',
            'users.view',
            'users.create',
            'users.edit',
            'notifications.view',
            'notifications.send',
            'activity-logs.view',
            'profile.view',
            'profile.edit',
        ]);

        // Assign permissions to operator
        $operatorRole->givePermissionTo([
            'dashboard.view',
            'articles.view',
            'articles.create',
            'articles.edit',
            'articles.export',
            'articles.print',
            'exploitants.view',
            'exploitants.create',
            'exploitants.edit',
            'exploitants.print-card',
            'forets.view',
            'forets.create',
            'forets.edit',
            'notifications.view',
            'profile.view',
            'profile.edit',
        ]);

        // Assign basic permissions to user
        $userRole->givePermissionTo([
            'dashboard.view',
            'articles.view',
            'articles.create',
            'articles.edit',
            'articles.print',
            'exploitants.view',
            'exploitants.print-card',
            'notifications.view',
            'profile.view',
            'profile.edit',
        ]);

        // Assign read-only permissions to viewer
        $viewerRole->givePermissionTo([
            'dashboard.view',
            'articles.view',
            'exploitants.view',
            'forets.view',
            'notifications.view',
            'profile.view',
            'profile.edit',
        ]);
    }
}