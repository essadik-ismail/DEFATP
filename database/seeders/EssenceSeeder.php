<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Essence;

class EssenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $essences = [
            'Chêne-liège',
            'Chêne vert',
            'Pin maritime',
            'Pin d\'Alep',
            'Cèdre de l\'Atlas',
            'Thuya de Berbérie',
            'Arganier',
            'Eucalyptus',
            'Acacia',
            'Tamaris',
            'Caroubier',
            'Romarin',
            'Lavande',
            'Myrte',
            'Genévrier',
            'Pistachier',
            'Amandier sauvage',
            'Figuier de Barbarie',
            'Palmier nain',
            'Bambou'
        ];

        foreach ($essences as $essence) {
            Essence::create([
                'essence' => $essence,
                'is_deleted' => false,
            ]);
        }
    }
}
