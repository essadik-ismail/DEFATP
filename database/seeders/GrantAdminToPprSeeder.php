<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class GrantAdminToPprSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ppr = '12345';

        $user = User::withoutGlobalScope('not_deleted')
            ->where('ppr', $ppr)
            ->first();

        if (!$user) {
            $this->command?->warn("No user found with PPR {$ppr}.");
            return;
        }

        // Ensure admin role exists
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Ensure core permissions exist and are granted to admin
        $permissionNames = [
            'users.view', 'users.create', 'users.edit', 'users.delete',
            'activity-logs.view', 'activity-logs.export',
        ];

        foreach ($permissionNames as $permName) {
            $perm = Permission::findOrCreate($permName);
            if (!$adminRole->hasPermissionTo($perm)) {
                $adminRole->givePermissionTo($perm);
            }
        }

        // Assign admin role to user
        if (!$user->hasRole('admin')) {
            $user->assignRole($adminRole);
        }

        $this->command?->info("User with PPR {$ppr} has been granted the 'admin' role.");
    }
}


