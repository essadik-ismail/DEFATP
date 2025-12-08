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
            LocalisationSeeder::class,
            EssenceSeeder::class,
            ForetSeeder::class,
            NatureDeCoupeSeeder::class,
            SituationAdministrativeSeeder::class,
            OdfEntiteSeeder::class,
            ExploitantSeeder::class,
            ProductSeeder::class,
            PrestationSeeder::class,
            ArticleSeeder::class,
            VocationSeeder::class,
            CoperativeSeeder::class,
            ContractSeeder::class,
            AvenantSeeder::class,
            LegacyArticlesSeeder::class,
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
