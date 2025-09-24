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
        $administrativeData = [
            // Région de Rabat-Salé-Kénitra
            ['commune' => 'Rabat-Salé-Kénitra', 'province' => 'Rabat'],
            ['commune' => 'Rabat-Salé-Kénitra', 'province' => 'Salé'],
            ['commune' => 'Rabat-Salé-Kénitra', 'province' => 'Skhirate-Témara'],
            ['commune' => 'Rabat-Salé-Kénitra', 'province' => 'Kénitra'],
            ['commune' => 'Rabat-Salé-Kénitra', 'province' => 'Khémisset'],
            ['commune' => 'Rabat-Salé-Kénitra', 'province' => 'Sidi Kacem'],
            ['commune' => 'Rabat-Salé-Kénitra', 'province' => 'Sidi Slimane'],

            // Région de Casablanca-Settat
            ['commune' => 'Casablanca-Settat', 'province' => 'Casablanca'],
            ['commune' => 'Casablanca-Settat', 'province' => 'Mohammadia'],
            ['commune' => 'Casablanca-Settat', 'province' => 'El Jadida'],
            ['commune' => 'Casablanca-Settat', 'province' => 'Nouaceur'],
            ['commune' => 'Casablanca-Settat', 'province' => 'Médiouna'],
            ['commune' => 'Casablanca-Settat', 'province' => 'Benslimane'],
            ['commune' => 'Casablanca-Settat', 'province' => 'Berrechid'],
            ['commune' => 'Casablanca-Settat', 'province' => 'Settat'],
            ['commune' => 'Casablanca-Settat', 'province' => 'Sidi Bennour'],

            // Région de Tanger-Tétouan-Al Hoceima
            ['commune' => 'Tanger-Tétouan-Al Hoceima', 'province' => 'Tanger-Assilah'],
            ['commune' => 'Tanger-Tétouan-Al Hoceima', 'province' => 'M\'diq-Fnideq'],
            ['commune' => 'Tanger-Tétouan-Al Hoceima', 'province' => 'Tétouan'],
            ['commune' => 'Tanger-Tétouan-Al Hoceima', 'province' => 'Fahs-Anjra'],
            ['commune' => 'Tanger-Tétouan-Al Hoceima', 'province' => 'Larache'],
            ['commune' => 'Tanger-Tétouan-Al Hoceima', 'province' => 'Al Hoceima'],
            ['commune' => 'Tanger-Tétouan-Al Hoceima', 'province' => 'Chefchaouen'],
            ['commune' => 'Tanger-Tétouan-Al Hoceima', 'province' => 'Ouazzane'],

            // Région de l'Oriental
            ['commune' => 'lOriental', 'province' => 'Oujda-Angad'],
            ['commune' => 'lOriental', 'province' => 'Nador'],
            ['commune' => 'lOriental', 'province' => 'Driouch'],
            ['commune' => 'lOriental', 'province' => 'Jerada'],
            ['commune' => 'lOriental', 'province' => 'Berkan'],
            ['commune' => 'lOriental', 'province' => 'Taourirt'],
            ['commune' => 'lOriental', 'province' => 'Guercif'],
            ['commune' => 'lOriental', 'province' => 'Figuig'],

            // Région de Fès-Meknès
            ['commune' => 'Fès - Meknès', 'province' => 'Fès'],
            ['commune' => 'Fès - Meknès', 'province' => 'Meknès'],
            ['commune' => 'Fès - Meknès', 'province' => 'El Hajeb'],
            ['commune' => 'Fès - Meknès', 'province' => 'Ifrane'],
            ['commune' => 'Fès - Meknès', 'province' => 'Moulay Yacoub'],
            ['commune' => 'Fès - Meknès', 'province' => 'Sefrou'],
            ['commune' => 'Fès - Meknès', 'province' => 'Boulemane'],
            ['commune' => 'Fès - Meknès', 'province' => 'Taounate'],
            ['commune' => 'Fès - Meknès', 'province' => 'Taza'],

            // Région de Béni Mellal-Khénifra
            ['commune' => 'Béni Mellal-Khénifra', 'province' => 'Béni Mellal'],
            ['commune' => 'Béni Mellal-Khénifra', 'province' => 'Azilal'],
            ['commune' => 'Béni Mellal-Khénifra', 'province' => 'Fquih Ben Salah'],
            ['commune' => 'Béni Mellal-Khénifra', 'province' => 'Khénifra'],
            ['commune' => 'Béni Mellal-Khénifra', 'province' => 'Khouribga'],

            // Région de Marrakech-Safi
            ['commune' => 'Marrakech-Safi', 'province' => 'Marrakech'],
            ['commune' => 'Marrakech-Safi', 'province' => 'Chichaoua'],
            ['commune' => 'Marrakech-Safi', 'province' => 'Al Haouz'],
            ['commune' => 'Marrakech-Safi', 'province' => 'Kelâa des Sraghna'],
            ['commune' => 'Marrakech-Safi', 'province' => 'Essaouira'],
            ['commune' => 'Marrakech-Safi', 'province' => 'Rehamna'],
            ['commune' => 'Marrakech-Safi', 'province' => 'Safi'],
            ['commune' => 'Marrakech-Safi', 'province' => 'Youssoufia'],

            // Région de Drâa-Tafilalet
            ['commune' => 'Drâa-Tafilalet', 'province' => 'Errachidia'],
            ['commune' => 'Drâa-Tafilalet', 'province' => 'Ouarzazate'],
            ['commune' => 'Drâa-Tafilalet', 'province' => 'Midelt'],
            ['commune' => 'Drâa-Tafilalet', 'province' => 'Tinghir'],
            ['commune' => 'Drâa-Tafilalet', 'province' => 'Zagora'],

            // Région de Souss-Massa
            ['commune' => 'Souss-Massa', 'province' => 'Agadir Ida-Ou-Tanane'],
            ['commune' => 'Souss-Massa', 'province' => 'Inezgane-Aït Melloul'],
            ['commune' => 'Souss-Massa', 'province' => 'Chtouka-Aït Baha'],
            ['commune' => 'Souss-Massa', 'province' => 'Taroudannt'],
            ['commune' => 'Souss-Massa', 'province' => 'Tiznit'],
            ['commune' => 'Souss-Massa', 'province' => 'Tata'],

            // Région de Guelmim-Oued Noun
            ['commune' => 'Guelmim-Oued Noun', 'province' => 'Guelmim'],
            ['commune' => 'Guelmim-Oued Noun', 'province' => 'Assa-Zag'],
            ['commune' => 'Guelmim-Oued Noun', 'province' => 'Tan-Tan'],
            ['commune' => 'Guelmim-Oued Noun', 'province' => 'Sidi Ifni'],

            // Région de Laâyoune-Sakia El Hamra
            ['commune' => 'Laâyoune-Sakia El Hamra', 'province' => 'Laâyoune'],
            ['commune' => 'Laâyoune-Sakia El Hamra', 'province' => 'Boujdour'],
            ['commune' => 'Laâyoune-Sakia El Hamra', 'province' => 'Tarfaya'],
            ['commune' => 'Laâyoune-Sakia El Hamra', 'province' => 'Es-Semara'],

            // Région de Dakhla-Oued Ed-Dahab
            ['commune' => 'Dakhla-Oued Ed-Dahab', 'province' => 'Oued Ed-Dahab'],
            ['commune' => 'Dakhla-Oued Ed-Dahab', 'province' => 'Aousserd'],
        ];

        foreach ($administrativeData as $data) {
            SituationAdministrative::create($data);
        }
    }
}