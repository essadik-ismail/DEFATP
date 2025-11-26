<?php

namespace Database\Seeders;

use App\Models\OdfEntite;
use Illuminate\Database\Seeder;

class OdfEntiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $entites = [
            'Associations',
            'Coopératives',
            'Titulaires d\'amodiations',
            'Nouabs des collectivités ethniques',
            'Autre organisation',
        ];

        foreach ($entites as $name) {
            OdfEntite::firstOrCreate(['name' => $name]);
        }
    }
}


