<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SessionAdjudication;

class SessionAdjudicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sessionAdjudications = [
            [
                'type' => 'appel_doffre',
                'date' => '2024-01-15',
                'description' => 'Appel d\'offres pour l\'exploitation forestière - Session 2024-01',
            ],
            [
                'type' => 'adjudication',
                'date' => '2024-01-20',
                'description' => 'Adjudication des lots de bois - Mamora',
            ],
            [
                'type' => 'appel_doffre',
                'date' => '2024-02-10',
                'description' => 'Appel d\'offres pour coupe sanitaire - Benslimane',
            ],
            [
                'type' => 'adjudication',
                'date' => '2024-02-15',
                'description' => 'Adjudication des parcelles - Sidi Bettache',
            ],
            [
                'type' => 'appel_doffre',
                'date' => '2024-03-05',
                'description' => 'Appel d\'offres pour régénération - Témara',
            ],
            [
                'type' => 'adjudication',
                'date' => '2024-03-12',
                'description' => 'Adjudication des essences - Larache',
            ],
            [
                'type' => 'appel_doffre',
                'date' => '2024-04-01',
                'description' => 'Appel d\'offres pour éclaircie - Chefchaouen',
            ],
            [
                'type' => 'adjudication',
                'date' => '2024-04-08',
                'description' => 'Adjudication des coupes - Tétouan',
            ],
            [
                'type' => 'appel_doffre',
                'date' => '2024-05-01',
                'description' => 'Appel d\'offres pour maintenance - Tanger',
            ],
            [
                'type' => 'adjudication',
                'date' => '2024-05-10',
                'description' => 'Adjudication des lots - Fès',
            ],
            [
                'type' => 'appel_doffre',
                'date' => '2024-06-01',
                'description' => 'Appel d\'offres pour conversion - Meknès',
            ],
            [
                'type' => 'adjudication',
                'date' => '2024-06-15',
                'description' => 'Adjudication des parcelles - Marrakech',
            ],
            [
                'type' => 'appel_doffre',
                'date' => '2024-07-01',
                'description' => 'Appel d\'offres pour réhabilitation - Essaouira',
            ],
            [
                'type' => 'adjudication',
                'date' => '2024-07-20',
                'description' => 'Adjudication des coupes - Agadir',
            ],
            [
                'type' => 'appel_doffre',
                'date' => '2024-08-01',
                'description' => 'Appel d\'offres pour protection - Tiznit',
            ]
        ];

        foreach ($sessionAdjudications as $sessionAdjudication) {
            SessionAdjudication::create([
                'type' => $sessionAdjudication['type'],
                'date' => $sessionAdjudication['date'],
                // 'description' => $sessionAdjudication['description'],
                'is_deleted' => false,
            ]);
        }
    }
}
