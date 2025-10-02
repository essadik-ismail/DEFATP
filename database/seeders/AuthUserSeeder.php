<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user for authentication database
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@defatp.com',
            'ppr' => 'ADMIN001',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_deleted' => false,
            'email_notifications' => true,
            'push_notifications' => true,
            'notification_types' => ['all'],
        ]);

        // Create test user
        User::create([
            'name' => 'Test User',
            'email' => 'test@defatp.com',
            'ppr' => 'TEST001',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_deleted' => false,
            'email_notifications' => true,
            'push_notifications' => false,
            'notification_types' => ['email'],
        ]);
    }
}
