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
        // Create users
        $admin = User::create([
            'name' => 'Administrateur',
            'ppr' => '12345',
            'password' => Hash::make('password'),
        ]);

        $user1 = User::create([
            'name' => 'Jean Dupont',
            'ppr' => '12345678',
            'password' => Hash::make('password'),
        ]);

        $user2 = User::create([
            'name' => 'Marie Martin',
            'ppr' => '87654321',
            'password' => Hash::make('password'),
        ]);

        // Assign roles to users
        $adminRole = Role::where('name', 'Super Admin')->first();
        $managerRole = Role::where('name', 'Manager')->first();
        $operatorRole = Role::where('name', 'Operator')->first();

        if ($adminRole) {
            $admin->assignRole($adminRole);
        }

        if ($managerRole) {
            $user1->assignRole($managerRole);
        }

        if ($operatorRole) {
            $user2->assignRole($operatorRole);
        }
    }
}
