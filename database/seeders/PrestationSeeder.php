<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Prestation;

class PrestationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prestations = [
            'Elagage',
            'Eclaircie',
            'Rajeunissement Romarin',
        ];

        foreach ($prestations as $prestationName) {
            Prestation::firstOrCreate(
                ['name' => $prestationName]
            );
        }
    }
}

