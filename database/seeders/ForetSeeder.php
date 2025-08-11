<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Foret;

class ForetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $forets = [
            [
                'foret' => 'Forêt de la Mamora',
                // 'province' => 'Kénitra',
                'lat' => 34.2333,
                'log' => -6.5833,
            ],
            [
                'foret' => 'Forêt de Benslimane',
                // 'province' => 'Benslimane',
                'lat' => 33.6167,
                'log' => -7.1167,
            ],
            [
                'foret' => 'Forêt de Sidi Bettache',
                'province' => 'Settat',
                'lat' => 33.0000,
                'log' => -7.6167,
            ],
            [
                'foret' => 'Forêt de Témara',
                'province' => 'Rabat-Salé-Kénitra',
                'lat' => 33.9333,
                'log' => -6.9167,
            ],
            [
                'foret' => 'Forêt de Larache',
                'province' => 'Larache',
                'lat' => 35.1833,
                'log' => -6.1500,
            ],
            [
                'foret' => 'Forêt de Chefchaouen',
                'province' => 'Chefchaouen',
                'lat' => 35.1667,
                'log' => -5.2667,
            ],
            [
                'foret' => 'Forêt de Tétouan',
                'province' => 'Tétouan',
                'lat' => 35.5667,
                'log' => -5.3667,
            ],
            [
                'foret' => 'Forêt de Tanger',
                'province' => 'Tanger-Assilah',
                'lat' => 35.7667,
                'log' => -5.8000,
            ],
            [
                'foret' => 'Forêt de Fès',
                'province' => 'Fès-Meknès',
                'lat' => 34.0333,
                'log' => -5.0000,
            ],
            [
                'foret' => 'Forêt de Meknès',
                'province' => 'Fès-Meknès',
                'lat' => 33.8833,
                'log' => -5.5500,
            ],
            [
                'foret' => 'Forêt de Marrakech',
                'province' => 'Marrakech-Safi',
                'lat' => 31.6333,
                'log' => -7.9833,
            ],
            [
                'foret' => 'Forêt d\'Essaouira',
                'province' => 'Marrakech-Safi',
                'lat' => 31.5167,
                'log' => -9.7667,
            ],
            [
                'foret' => 'Forêt d\'Agadir',
                'province' => 'Souss-Massa',
                'lat' => 30.4167,
                'log' => -9.5833,
            ],
            [
                'foret' => 'Forêt de Tiznit',
                'province' => 'Souss-Massa',
                'lat' => 29.7167,
                'log' => -9.7167,
            ],
            [
                'foret' => 'Forêt de Guelmim',
                'province' => 'Guelmim-Oued Noun',
                'lat' => 28.9833,
                'log' => -10.0667,
            ]
        ];

        foreach ($forets as $foret) {
            Foret::create([
                'foret' => $foret['foret'],
                // 'province' => $foret['province'],
                'lat' => $foret['lat'],
                'log' => $foret['log'],
                // 'is_deleted' => false,
            ]);
        }
    }
}
