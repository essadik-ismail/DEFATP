<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Foret;
use App\Models\Essence;
use App\Models\Localisation;
use App\Models\SituationAdministrative;
use App\Models\Exploitant;
use App\Models\NatureDeCoupe;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing data for relationships
        $forets = Foret::all();
        $essences = Essence::all();
        $localisations = Localisation::all();
        $situations = SituationAdministrative::all();
        $exploitants = Exploitant::all();
        $natureCoupes = NatureDeCoupe::all();

        if ($forets->isEmpty() || $essences->isEmpty() || $localisations->isEmpty() || 
            $situations->isEmpty() || $exploitants->isEmpty() || $natureCoupes->isEmpty()) {
            return; // Don't create articles if required data doesn't exist
        }

        $articles = [
            [
                'annee' => 2025,
                'numero' => 'ART001',
                'date_adjudication' => '2025-08-15',
                'parcelle' => 1,
                'foret_id' => $forets->first()->id,
                'essence_id' => $essences->first()->id,
                'localisation_id' => $localisations->first()->id,
                'situation_administrative_id' => $situations->first()->id,
                'exploitant_id' => $exploitants->first()->id,
                'nature_de_coupe_id' => $natureCoupes->first()->id,
                'bo_m3' => 150,
                'bi_m3' => 50,
                'bf_st' => 25,
                'prix_de_retrait' => 2500.00,
                'prix_vente' => 3200.00,
                'type' => 'adjudication',
                'observations' => 'Article de qualité exceptionnelle',
                'is_validated' => true,
            ],
            [
                'annee' => 2025,
                'numero' => 'ART002',
                'date_adjudication' => '2025-08-20',
                'parcelle' => 2,
                'foret_id' => $forets->first()->id,
                'essence_id' => $essences->first()->id,
                'localisation_id' => $localisations->first()->id,
                'situation_administrative_id' => $situations->first()->id,
                'exploitant_id' => $exploitants->first()->id,
                'nature_de_coupe_id' => $natureCoupes->first()->id,
                'bo_m3' => 200,
                'bi_m3' => 75,
                'bf_st' => 30,
                'prix_de_retrait' => 3000.00,
                'prix_vente' => 4000.00,
                'type' => 'appel_doffre',
                'observations' => 'Article standard',
                'is_validated' => false,
            ],
            [
                'annee' => 2025,
                'numero' => 'ART003',
                'date_adjudication' => '2025-08-25',
                'parcelle' => 3,
                'foret_id' => $forets->first()->id,
                'essence_id' => $essences->first()->id,
                'localisation_id' => $localisations->first()->id,
                'situation_administrative_id' => $situations->first()->id,
                'exploitant_id' => $exploitants->first()->id,
                'nature_de_coupe_id' => $natureCoupes->first()->id,
                'bo_m3' => 180,
                'bi_m3' => 60,
                'bf_st' => 28,
                'prix_de_retrait' => 2800.00,
                'prix_vente' => 3600.00,
                'type' => 'adjudication',
                'observations' => 'Article de bonne qualité',
                'is_validated' => true,
            ],
        ];

        foreach ($articles as $articleData) {
            Article::create($articleData);
        }
    }
}
