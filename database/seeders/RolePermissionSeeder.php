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
            // Articles
            'articles.view',
            'articles.create',
            'articles.edit',
            'articles.delete',
            'articles.export',
            'articles.import',
            
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
            
            // Users
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            
            // Profile
            'profile.view',
            'profile.edit',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        // Assign all permissions to admin
        $adminRole->givePermissionTo(Permission::all());

        // Assign basic permissions to user
        $userRole->givePermissionTo([
            'articles.view',
            'articles.create',
            'articles.edit',
            'profile.view',
            'profile.edit',
        ]);
    }
}