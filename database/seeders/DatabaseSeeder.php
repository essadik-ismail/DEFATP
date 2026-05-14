<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RbacSeeder::class,
            UserSeeder::class,
            EssenceSeeder::class,
            NatureDeCoupeSeeder::class,
            DranefSeeder::class,
            DpanefSeeder::class,
            ZdtfSeeder::class,
            DfpSeeder::class,
            ForetSeeder::class,
            CantonSeeder::class,
            ParcelleSeeder::class,
            ProductSeeder::class,            
            ProvinceSeeder::class,
            CommuneSeeder::class,
            ModeExploitationSeeder::class,
        ]);
    }

    /**
     * Seed the authentication database.
     */
    public function runAuth(): void
    {
        $this->call([
            AuthUserSeeder::class,
        ]);
    }
}
