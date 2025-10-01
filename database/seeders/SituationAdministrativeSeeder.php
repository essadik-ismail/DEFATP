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
        $situations = [
            ['commune' => 'Mohammedia', 'province' => 'Casablanca-Settat', 'region' => 'Casablanca-Settat'],
            ['commune' => 'Casablanca', 'province' => 'Casablanca-Settat', 'region' => 'Casablanca-Settat'],
            ['commune' => 'Rabat', 'province' => 'Rabat-Salé-Kénitra', 'region' => 'Rabat-Salé-Kénitra'],
            ['commune' => 'Fès', 'province' => 'Fès-Meknès', 'region' => 'Fès-Meknès'],
            ['commune' => 'Meknès', 'province' => 'Fès-Meknès', 'region' => 'Fès-Meknès'],
            ['commune' => 'Marrakech', 'province' => 'Marrakech-Safi', 'region' => 'Marrakech-Safi'],
            ['commune' => 'Agadir', 'province' => 'Souss-Massa', 'region' => 'Souss-Massa'],
            ['commune' => 'Tanger', 'province' => 'Tanger-Tétouan-Al Hoceïma', 'region' => 'Tanger-Tétouan-Al Hoceïma'],
            ['commune' => 'Tétouan', 'province' => 'Tanger-Tétouan-Al Hoceïma', 'region' => 'Tanger-Tétouan-Al Hoceïma'],
            ['commune' => 'Al Hoceïma', 'province' => 'Tanger-Tétouan-Al Hoceïma', 'region' => 'Tanger-Tétouan-Al Hoceïma'],
            ['commune' => 'Oujda', 'province' => 'Oriental', 'region' => 'Oriental'],
            ['commune' => 'Nador', 'province' => 'Oriental', 'region' => 'Oriental'],
            ['commune' => 'Béni Mellal', 'province' => 'Béni Mellal-Khénifra', 'region' => 'Béni Mellal-Khénifra'],
            ['commune' => 'Khénifra', 'province' => 'Béni Mellal-Khénifra', 'region' => 'Béni Mellal-Khénifra'],
            ['commune' => 'Azilal', 'province' => 'Béni Mellal-Khénifra', 'region' => 'Béni Mellal-Khénifra'],
            ['commune' => 'Khouribga', 'province' => 'Béni Mellal-Khénifra', 'region' => 'Béni Mellal-Khénifra'],
            ['commune' => 'Safi', 'province' => 'Marrakech-Safi', 'region' => 'Marrakech-Safi'],
            ['commune' => 'Essaouira', 'province' => 'Marrakech-Safi', 'region' => 'Marrakech-Safi'],
            ['commune' => 'El Jadida', 'province' => 'Casablanca-Settat', 'region' => 'Casablanca-Settat'],
            ['commune' => 'Settat', 'province' => 'Casablanca-Settat', 'region' => 'Casablanca-Settat'],
            ['commune' => 'Benslimane', 'province' => 'Casablanca-Settat', 'region' => 'Casablanca-Settat'],
            ['commune' => 'Kénitra', 'province' => 'Rabat-Salé-Kénitra', 'region' => 'Rabat-Salé-Kénitra'],
            ['commune' => 'Salé', 'province' => 'Rabat-Salé-Kénitra', 'region' => 'Rabat-Salé-Kénitra'],
            ['commune' => 'Sidi Slimane', 'province' => 'Rabat-Salé-Kénitra', 'region' => 'Rabat-Salé-Kénitra'],
            ['commune' => 'Sidi Kacem', 'province' => 'Rabat-Salé-Kénitra', 'region' => 'Rabat-Salé-Kénitra'],
            ['commune' => 'Ifrane', 'province' => 'Fès-Meknès', 'region' => 'Fès-Meknès'],
            ['commune' => 'Sefrou', 'province' => 'Fès-Meknès', 'region' => 'Fès-Meknès'],
            ['commune' => 'Taza', 'province' => 'Fès-Meknès', 'region' => 'Fès-Meknès'],
            ['commune' => 'Taounate', 'province' => 'Fès-Meknès', 'region' => 'Fès-Meknès'],
            ['commune' => 'Boulemane', 'province' => 'Fès-Meknès', 'region' => 'Fès-Meknès'],
            ['commune' => 'Midelt', 'province' => 'Drâa-Tafilalet', 'region' => 'Drâa-Tafilalet'],
            ['commune' => 'Errachidia', 'province' => 'Drâa-Tafilalet', 'region' => 'Drâa-Tafilalet'],
            ['commune' => 'Ouarzazate', 'province' => 'Drâa-Tafilalet', 'region' => 'Drâa-Tafilalet'],
            ['commune' => 'Zagora', 'province' => 'Drâa-Tafilalet', 'region' => 'Drâa-Tafilalet'],
            ['commune' => 'Tinghir', 'province' => 'Drâa-Tafilalet', 'region' => 'Drâa-Tafilalet'],
            ['commune' => 'Taroudant', 'province' => 'Souss-Massa', 'region' => 'Souss-Massa'],
            ['commune' => 'Tiznit', 'province' => 'Souss-Massa', 'region' => 'Souss-Massa'],
            ['commune' => 'Sidi Ifni', 'province' => 'Guelmim-Oued Noun', 'region' => 'Guelmim-Oued Noun'],
            ['commune' => 'Guelmim', 'province' => 'Guelmim-Oued Noun', 'region' => 'Guelmim-Oued Noun'],
            ['commune' => 'Laâyoune', 'province' => 'Laâyoune-Sakia El Hamra', 'region' => 'Laâyoune-Sakia El Hamra'],
            ['commune' => 'Dakhla', 'province' => 'Dakhla-Oued Ed-Dahab', 'region' => 'Dakhla-Oued Ed-Dahab'],
        ];

        foreach ($situations as $situation) {
            SituationAdministrative::firstOrCreate(
                ['commune' => $situation['commune']],
                $situation
            );
        }
    }
}