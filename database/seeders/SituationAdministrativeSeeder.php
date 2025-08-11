<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SituationAdministrative;

class SituationAdministrativeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SituationAdministrative::create([
            'commune' => "rabat",
            'province' => "sale",
        ]);
    }
}
