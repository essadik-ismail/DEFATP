<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\NatureDeCoupe;

class NatureDeCoupeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $natureDeCoupes = [
            'Coupe de régénération',
            'Coupe d\'amélioration',
            'Coupe sanitaire',
            'Coupe d\'éclaircie',
            'Coupe de conversion',
            'Coupe de réhabilitation',
            'Coupe de défrichement',
            'Coupe de nettoiement',
            'Coupe de formation',
            'Coupe de transformation',
            'Coupe de rajeunissement',
            'Coupe de sélection',
            'Coupe de jardinage',
            'Coupe de taillis',
            'Coupe de futaie',
            'Coupe de protection',
            'Coupe de conservation',
            'Coupe de développement',
            'Coupe de maintenance',
            'Coupe de restauration'
        ];

        foreach ($natureDeCoupes as $natureDeCoupe) {
            NatureDeCoupe::create([
                'nature_de_coupe' => $natureDeCoupe,
                'is_deleted' => false,
            ]);
        }
    }
}
