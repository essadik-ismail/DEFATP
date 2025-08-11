<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Administrateur',
            'ppr' => '12345',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'Jean Dupont',
            'ppr' => '12345678',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'Marie Martin',
            'ppr' => '87654321',
            'password' => Hash::make('password'),
        ]);
    }
}
