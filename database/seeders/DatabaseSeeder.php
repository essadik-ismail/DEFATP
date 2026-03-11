<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            EssenceSeeder::class,
            ForetSeeder::class,
            NatureDeCoupeSeeder::class,
            DranefSeeder::class,
            DpanefSeeder::class,
            ZdtfSeeder::class,
            DfpSeeder::class,
            // SituationAdministrativeSeeder::class,
            // ExploitantSeeder::class,
            ProductSeeder::class,
            // PrestationSeeder::class,
            // VocationSeeder::class,
            // CoperativeSeeder::class,
            // Geographic seeders - must be in order: Province first, then Commune
            ProvinceSeeder::class,
            CommuneSeeder::class,
            // LegacyArticlesSeeder::class,
            // ContractSeeder::class,
            // AvenantSeeder::class,
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
