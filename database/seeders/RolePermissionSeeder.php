<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // User management
            'view users',
            'create users',
            'edit users',
            'delete users',
            
            // Activity Logs
            'view activity logs',
            'export activity logs',

            // Article management
            'view articles',
            'create articles',
            'edit articles',
            'delete articles',
            'export articles',
            'import articles',
            
            // Exploitant management
            'view exploitants',
            'create exploitants',
            'edit exploitants',
            'delete exploitants',
            'export exploitants',
            
            // Forest management
            'view forets',
            'create forets',
            'edit forets',
            'delete forets',
            
            // Essence management
            'view essences',
            'create essences',
            'edit essences',
            'delete essences',
            
            // Localisation management
            'view localisations',
            'create localisations',
            'edit localisations',
            'delete localisations',
            
            // Situation Administrative management
            'view situation_administratives',
            'create situation_administratives',
            'edit situation_administratives',
            'delete situation_administratives',
            
            // Nature de Coupes management
            'view nature_de_coupes',
            'create nature_de_coupes',
            'edit nature_de_coupes',
            'delete nature_de_coupes',
            
            // Reports
            'view reports',
            'export reports',
            
            // Settings
            'view settings',
            'edit settings',
            'manage settings',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $superAdmin = Role::create(['name' => 'Super Admin']);
        $superAdmin->givePermissionTo(Permission::all());

        $admin = Role::create(['name' => 'Admin']);
        $admin->givePermissionTo([
            'view users', 'create users', 'edit users',
            'view activity logs', 'export activity logs',
            'view articles', 'create articles', 'edit articles', 'delete articles',
            'view exploitants', 'create exploitants', 'edit exploitants', 'delete exploitants',
            'view forets', 'create forets', 'edit forets', 'delete forets',
            'view essences', 'create essences', 'edit essences', 'delete essences',
            'view localisations', 'create localisations', 'edit localisations', 'delete localisations',
            'view situation_administratives', 'create situation_administratives', 'edit situation_administratives', 'delete situation_administratives',
            'view nature_de_coupes', 'create nature_de_coupes', 'edit nature_de_coupes', 'delete nature_de_coupes',
            'view reports', 'export reports',
            'view settings', 'edit settings'
        ]);

        $manager = Role::create(['name' => 'Manager']);
        $manager->givePermissionTo([
            'view users',
            'view activity logs',
            'view articles', 'create articles', 'edit articles', 'export articles', 'import articles',
            'view exploitants', 'create exploitants', 'edit exploitants', 'export exploitants',
            'view forets', 'create forets', 'edit forets',
            'view essences', 'create essences', 'edit essences',
            'view localisations', 'create localisations', 'edit localisations',
            'view reports', 'export reports',
            'view settings'
        ]);

        $operator = Role::create(['name' => 'Operator']);
        $operator->givePermissionTo([
            'view articles', 'create articles', 'edit articles',
            'view exploitants', 'view forets', 'view essences', 'view localisations',
            'view reports',
        ]);

        $viewer = Role::create(['name' => 'Viewer']);
        $viewer->givePermissionTo([
            'view users',
            'view activity logs',
            'view articles', 'export articles',
            'view exploitants', 'export exploitants',
            'view forets', 'view essences', 'view localisations',
            'view reports', 'export reports',
            'view settings'
        ]);
    }
}
