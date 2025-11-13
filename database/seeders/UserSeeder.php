<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or update users
        $admin = User::firstOrCreate(
            ['ppr' => '12345'],
            [
                'name' => 'Administrateur',
                'password' => Hash::make('password'),
            ]
        );

        $user1 = User::firstOrCreate(
            ['ppr' => '12345678'],
            [
                'name' => 'Jean Dupont',
                'password' => Hash::make('password'),
            ]
        );

        $user2 = User::firstOrCreate(
            ['ppr' => '87654321'],
            [
                'name' => 'Marie Martin',
                'password' => Hash::make('password'),
            ]
        );

        // Assign roles to users
        $adminRole = Role::where('name', 'Super Admin')->first();
        $managerRole = Role::where('name', 'Manager')->first();
        $operatorRole = Role::where('name', 'Operator')->first();

        if ($adminRole && !$admin->hasRole($adminRole)) {
            $admin->assignRole($adminRole);
        }

        if ($managerRole && !$user1->hasRole($managerRole)) {
            $user1->assignRole($managerRole);
        }

        if ($operatorRole && !$user2->hasRole($operatorRole)) {
            $user2->assignRole($operatorRole);
        }
    }
}
