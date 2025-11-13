<?php

namespace Database\Seeders;

use App\Models\Localisation;
use Illuminate\Database\Seeder;

class LocalisationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = base_path('data/Localisation.json');
        
        if (file_exists($jsonPath)) {
            $this->loadFromJson($jsonPath);
            return;
        }

        // Fallback to hardcoded data if JSON doesn't exist
        $localisations = [
            ['CODE' => '01-620', 'DRANEF' => 'TTH', 'DPANEF' => 'Al-Hoceima', 'ENTITE' => 'Ketama'],
            ['CODE' => '01-640', 'DRANEF' => 'TTH', 'DPANEF' => 'Al-Hoceima', 'ENTITE' => 'Targuist'],
            ['CODE' => '01-630', 'DRANEF' => 'TTH', 'DPANEF' => 'Al-Hoceima', 'ENTITE' => 'Ikaouene'],
            ['CODE' => '01-610', 'DRANEF' => 'TTH', 'DPANEF' => 'Al-Hoceima', 'ENTITE' => 'Al Hoceima'],
            ['CODE' => '01-700CP1', 'DRANEF' => 'TTH', 'DPANEF' => 'Chefchaouen', 'ENTITE' => 'CPF Chefchaouen'],
            ['CODE' => '01-700CP2', 'DRANEF' => 'TTH', 'DPANEF' => 'Chefchaouen', 'ENTITE' => 'CPF Bab Berred'],
            ['CODE' => '01-700CP3', 'DRANEF' => 'TTH', 'DPANEF' => 'Chefchaouen', 'ENTITE' => 'CPF Jebha'],
            ['CODE' => '01-700CG1', 'DRANEF' => 'TTH', 'DPANEF' => 'Chefchaouen', 'ENTITE' => 'CGF Chefchaouen'],
            ['CODE' => '01-700CG2', 'DRANEF' => 'TTH', 'DPANEF' => 'Chefchaouen', 'ENTITE' => 'CGF Bab Berred-Jebha'],
            ['CODE' => '01-510', 'DRANEF' => 'TTH', 'DPANEF' => 'Larache', 'ENTITE' => 'Larache'],
            ['CODE' => '01-520', 'DRANEF' => 'TTH', 'DPANEF' => 'Larache', 'ENTITE' => 'Beni Arouss'],
            ['CODE' => '01-530', 'DRANEF' => 'TTH', 'DPANEF' => 'Larache', 'ENTITE' => 'Tatoft'],
            ['CODE' => '01-810', 'DRANEF' => 'TTH', 'DPANEF' => 'Ouezzane', 'ENTITE' => 'Mokrisset'],
            ['CODE' => '01-820', 'DRANEF' => 'TTH', 'DPANEF' => 'Ouezzane', 'ENTITE' => 'Ouezzane'],
            ['CODE' => '01-110', 'DRANEF' => 'TTH', 'DPANEF' => 'Tanger', 'ENTITE' => 'Assilah'],
            ['CODE' => '01-310', 'DRANEF' => 'TTH', 'DPANEF' => 'Tanger', 'ENTITE' => 'Fahs Anjra'],
            ['CODE' => '01-200CP1', 'DRANEF' => 'TTH', 'DPANEF' => 'Tetouan', 'ENTITE' => 'CPF Tetouan Ouest'],
            ['CODE' => '01-200CP2', 'DRANEF' => 'TTH', 'DPANEF' => 'Tetouan', 'ENTITE' => 'CPF Mdiq-Fnideq'],
            ['CODE' => '01-200CP3', 'DRANEF' => 'TTH', 'DPANEF' => 'Tetouan', 'ENTITE' => 'CPF Tetouan Est'],
            ['CODE' => '01-200CG1', 'DRANEF' => 'TTH', 'DPANEF' => 'Tetouan', 'ENTITE' => 'CGF Tetouan Ouest'],
            ['CODE' => '01-200CG2', 'DRANEF' => 'TTH', 'DPANEF' => 'Tetouan', 'ENTITE' => 'CGF MdiqTetouan Est'],
            ['CODE' => '02-520', 'DRANEF' => 'O', 'DPANEF' => 'Figuig', 'ENTITE' => 'Talsint'],
            ['CODE' => '02-510', 'DRANEF' => 'O', 'DPANEF' => 'Figuig', 'ENTITE' => 'Bouarfa'],
            ['CODE' => '02-620', 'DRANEF' => 'O', 'DPANEF' => 'Guercif', 'ENTITE' => 'Tamjilt'],
            ['CODE' => '02-610', 'DRANEF' => 'O', 'DPANEF' => 'Guercif', 'ENTITE' => 'Guercif'],
            ['CODE' => '02-630', 'DRANEF' => 'O', 'DPANEF' => 'Guercif', 'ENTITE' => 'Taddart'],
            ['CODE' => '02-210', 'DRANEF' => 'O', 'DPANEF' => 'Jerada', 'ENTITE' => 'Jerada'],
            ['CODE' => '02-220', 'DRANEF' => 'O', 'DPANEF' => 'Jerada', 'ENTITE' => 'Ain Beni Mathar'],
            ['CODE' => '02-710', 'DRANEF' => 'O', 'DPANEF' => 'Nador', 'ENTITE' => 'Nador'],
            ['CODE' => '02-720', 'DRANEF' => 'O', 'DPANEF' => 'Nador', 'ENTITE' => 'Zaio'],
            ['CODE' => '02-400CP1', 'DRANEF' => 'O', 'DPANEF' => 'Taourirt', 'ENTITE' => 'CPF Debdou'],
            ['CODE' => '02-400CP2', 'DRANEF' => 'O', 'DPANEF' => 'Taourirt', 'ENTITE' => 'CPF El Aioun'],
            ['CODE' => '02-400CG1', 'DRANEF' => 'O', 'DPANEF' => 'Taourirt', 'ENTITE' => 'CGF Debdou'],
            ['CODE' => '02-400CG2', 'DRANEF' => 'O', 'DPANEF' => 'Taourirt', 'ENTITE' => 'CGF El Ayoune '],
            ['CODE' => '03-420', 'DRANEF' => 'FM', 'DPANEF' => 'Boulemane', 'ENTITE' => 'Imouzzer Marmoucha'],
            ['CODE' => '03-430', 'DRANEF' => 'FM', 'DPANEF' => 'Boulemane', 'ENTITE' => 'Outat El Haj'],
            ['CODE' => '03-410', 'DRANEF' => 'FM', 'DPANEF' => 'Boulemane', 'ENTITE' => 'Boulemane'],
            ['CODE' => '03-740', 'DRANEF' => 'FM', 'DPANEF' => 'Ifrane', 'ENTITE' => 'CGF Azrou'],
            ['CODE' => '03-760', 'DRANEF' => 'FM', 'DPANEF' => 'Ifrane', 'ENTITE' => 'CGF Timahdite'],
            ['CODE' => '03-780', 'DRANEF' => 'FM', 'DPANEF' => 'Ifrane', 'ENTITE' => 'CPF Ifrane'],
            ['CODE' => '03-750', 'DRANEF' => 'FM', 'DPANEF' => 'Ifrane', 'ENTITE' => 'CPF Bakrit'],
            ['CODE' => '03-770', 'DRANEF' => 'FM', 'DPANEF' => 'Ifrane', 'ENTITE' => 'CPF Aïn Leuh'],
            ['CODE' => '03-730', 'DRANEF' => 'FM', 'DPANEF' => 'Ifrane', 'ENTITE' => 'CPF Azrou'],
            ['CODE' => '03-210', 'DRANEF' => 'FM', 'DPANEF' => 'Meknes-Elhajeb', 'ENTITE' => 'Meknès'],
            ['CODE' => '03-220', 'DRANEF' => 'FM', 'DPANEF' => 'Meknes-Elhajeb', 'ENTITE' => 'ElHajeb'],
            ['CODE' => '03-510', 'DRANEF' => 'FM', 'DPANEF' => 'Sefrou', 'ENTITE' => 'Ribat El Kheir'],
            ['CODE' => '03-520', 'DRANEF' => 'FM', 'DPANEF' => 'Sefrou', 'ENTITE' => 'Sefrou'],
            ['CODE' => '03-530', 'DRANEF' => 'FM', 'DPANEF' => 'Sefrou', 'ENTITE' => 'Imouzzer Kandar'],
            ['CODE' => '03-610', 'DRANEF' => 'FM', 'DPANEF' => 'Taounate', 'ENTITE' => 'Ghafsaï'],
            ['CODE' => '03-620', 'DRANEF' => 'FM', 'DPANEF' => 'Taounate', 'ENTITE' => 'Thar Souk'],
            ['CODE' => '03-340', 'DRANEF' => 'FM', 'DPANEF' => 'Taza', 'ENTITE' => 'Taïnest'],
            ['CODE' => '03-350', 'DRANEF' => 'FM', 'DPANEF' => 'Taza', 'ENTITE' => 'Taza'],
            ['CODE' => '03-330', 'DRANEF' => 'FM', 'DPANEF' => 'Taza', 'ENTITE' => 'Tahla'],
            ['CODE' => '03-310', 'DRANEF' => 'FM', 'DPANEF' => 'Taza', 'ENTITE' => 'Aknoul'],
            ['CODE' => '03-320', 'DRANEF' => 'FM', 'DPANEF' => 'Taza', 'ENTITE' => 'Maghraoua'],
            ['CODE' => '04-300CP1', 'DRANEF' => 'RSK', 'DPANEF' => 'Kénitra', 'ENTITE' => 'CPF Kénitra'],
            ['CODE' => '04-300CP2', 'DRANEF' => 'RSK', 'DPANEF' => 'Kénitra', 'ENTITE' => 'CPF Sidi Yahia Ouest '],
            ['CODE' => '04-300CP3', 'DRANEF' => 'RSK', 'DPANEF' => 'Kénitra', 'ENTITE' => 'CPF Souk El Arbaâ '],
            ['CODE' => '04-300CG1', 'DRANEF' => 'RSK', 'DPANEF' => 'Kénitra', 'ENTITE' => 'CGF Kénitra'],
            ['CODE' => '04-300CG2', 'DRANEF' => 'RSK', 'DPANEF' => 'Kénitra', 'ENTITE' => 'CGF Sidi Yahia El Gharb '],
            ['CODE' => '04-260', 'DRANEF' => 'RSK', 'DPANEF' => 'Khémisset', 'ENTITE' => 'Tiflet'],
            ['CODE' => '04-250', 'DRANEF' => 'RSK', 'DPANEF' => 'Khémisset', 'ENTITE' => 'Sidi Allal Bahraoui'],
            ['CODE' => '04-220', 'DRANEF' => 'RSK', 'DPANEF' => 'Khémisset', 'ENTITE' => 'Khémisset'],
            ['CODE' => '04-240', 'DRANEF' => 'RSK', 'DPANEF' => 'Khémisset', 'ENTITE' => 'Oulmès'],
            ['CODE' => '04-230', 'DRANEF' => 'RSK', 'DPANEF' => 'Khémisset', 'ENTITE' => 'Maâziz'],
            ['CODE' => '04-210', 'DRANEF' => 'RSK', 'DPANEF' => 'Khémisset', 'ENTITE' => 'Rommani'],
            ['CODE' => '04-110', 'DRANEF' => 'RSK', 'DPANEF' => 'Rabat', 'ENTITE' => 'Rabat-Témara'],
            ['CODE' => '04-120', 'DRANEF' => 'RSK', 'DPANEF' => 'Rabat', 'ENTITE' => 'Salé '],
            ['CODE' => '04-130', 'DRANEF' => 'RSK', 'DPANEF' => 'Rabat', 'ENTITE' => 'Unité Ceinture Verte'],
            ['CODE' => '04-510', 'DRANEF' => 'RSK', 'DPANEF' => 'Sidi Slimane', 'ENTITE' => 'Sidi Slimane '],
            ['CODE' => '04-610', 'DRANEF' => 'RSK', 'DPANEF' => 'Sidi Slimane', 'ENTITE' => 'Sidi Kacem'],
            ['CODE' => '04-520', 'DRANEF' => 'RSK', 'DPANEF' => 'Sidi Slimane', 'ENTITE' => 'Sidi Yahya Est'],
            ['CODE' => '05-210', 'DRANEF' => 'BMK', 'DPANEF' => 'Azilal', 'ENTITE' => 'Aït Mhammed'],
            ['CODE' => '05-220', 'DRANEF' => 'BMK', 'DPANEF' => 'Azilal', 'ENTITE' => 'Azilal'],
            ['CODE' => '05-230', 'DRANEF' => 'BMK', 'DPANEF' => 'Azilal', 'ENTITE' => 'Demnate'],
            ['CODE' => '05-240', 'DRANEF' => 'BMK', 'DPANEF' => 'Azilal', 'ENTITE' => 'Ouaouizeght'],
            ['CODE' => '05-250', 'DRANEF' => 'BMK', 'DPANEF' => 'Azilal', 'ENTITE' => 'Tagleft'],
            ['CODE' => '05-110', 'DRANEF' => 'BMK', 'DPANEF' => 'Béni-Mellal', 'ENTITE' => 'Aghbala'],
            ['CODE' => '05-120', 'DRANEF' => 'BMK', 'DPANEF' => 'Béni-Mellal', 'ENTITE' => 'Béni Mellal'],
            ['CODE' => '05-130', 'DRANEF' => 'BMK', 'DPANEF' => 'Béni-Mellal', 'ENTITE' => 'El Ksiba'],
            ['CODE' => '05-370', 'DRANEF' => 'BMK', 'DPANEF' => 'Khénifra', 'ENTITE' => 'CGF M\'rirt '],
            ['CODE' => '05-350', 'DRANEF' => 'BMK', 'DPANEF' => 'Khénifra', 'ENTITE' => 'CGF Khénifra-Ajdir'],
            ['CODE' => '05-390', 'DRANEF' => 'BMK', 'DPANEF' => 'Khénifra', 'ENTITE' => 'CGF My Bouazza'],
            ['CODE' => '05-330', 'DRANEF' => 'BMK', 'DPANEF' => 'Khénifra', 'ENTITE' => 'CGF El Kbab'],
            ['CODE' => '05-360', 'DRANEF' => 'BMK', 'DPANEF' => 'Khénifra', 'ENTITE' => 'CPF M\'rirt '],
            ['CODE' => '05-380', 'DRANEF' => 'BMK', 'DPANEF' => 'Khénifra', 'ENTITE' => 'CPF My Bouazza'],
            ['CODE' => '05-320', 'DRANEF' => 'BMK', 'DPANEF' => 'Khénifra', 'ENTITE' => 'CPF El Kbab'],
            ['CODE' => '05-340', 'DRANEF' => 'BMK', 'DPANEF' => 'Khénifra', 'ENTITE' => 'CPF Khénifra-Ajdir'],
            ['CODE' => '05-410', 'DRANEF' => 'BMK', 'DPANEF' => 'Khouribga', 'ENTITE' => 'Boujaâd'],
            ['CODE' => '05-420', 'DRANEF' => 'BMK', 'DPANEF' => 'Khouribga', 'ENTITE' => 'Oued Zem'],
            ['CODE' => '06-200CP1', 'DRANEF' => 'CS', 'DPANEF' => 'Benslimane', 'ENTITE' => 'CPF Benslimane'],
            ['CODE' => '06-200CG1', 'DRANEF' => 'CS', 'DPANEF' => 'Benslimane', 'ENTITE' => 'CGF Benslimane'],
            ['CODE' => '07-310', 'DRANEF' => 'MS', 'DPANEF' => 'Chichaoua', 'ENTITE' => 'Imintanout'],
            ['CODE' => '07-320', 'DRANEF' => 'MS', 'DPANEF' => 'Chichaoua', 'ENTITE' => 'Timlilt'],
            ['CODE' => '07-400CP1', 'DRANEF' => 'MS', 'DPANEF' => 'Essaouira', 'ENTITE' => 'CPF Essaouira'],
            ['CODE' => '07-400CP2', 'DRANEF' => 'MS', 'DPANEF' => 'Essaouira', 'ENTITE' => 'CPF Smimou'],
            ['CODE' => '07-400CP3', 'DRANEF' => 'MS', 'DPANEF' => 'Essaouira', 'ENTITE' => 'CPF Tamanar'],
            ['CODE' => '07-400CG1', 'DRANEF' => 'MS', 'DPANEF' => 'Essaouira', 'ENTITE' => 'CGF Essaouira'],
            ['CODE' => '07-400CG2', 'DRANEF' => 'MS', 'DPANEF' => 'Essaouira', 'ENTITE' => 'CGF Smimou'],
            ['CODE' => '07-400CG3', 'DRANEF' => 'MS', 'DPANEF' => 'Essaouira', 'ENTITE' => 'CGF Tamanar'],
            ['CODE' => '07-100CP1', 'DRANEF' => 'MS', 'DPANEF' => 'Marrakech', 'ENTITE' => 'CPF Al Haouz Est'],
            ['CODE' => '07-100CP2', 'DRANEF' => 'MS', 'DPANEF' => 'Marrakech', 'ENTITE' => 'CPF Al Haouz Ouest'],
            ['CODE' => '07-100CG1', 'DRANEF' => 'MS', 'DPANEF' => 'Marrakech', 'ENTITE' => 'CGF Aît Ourir'],
            ['CODE' => '07-100CG2', 'DRANEF' => 'MS', 'DPANEF' => 'Marrakech', 'ENTITE' => 'CGF Tahanoute'],
            ['CODE' => '07-100CG3', 'DRANEF' => 'MS', 'DPANEF' => 'Marrakech', 'ENTITE' => 'CGF Amizmiz'],
            ['CODE' => '07-510', 'DRANEF' => 'MS', 'DPANEF' => 'Safi', 'ENTITE' => 'Youssoufia'],
            ['CODE' => '08-200CP1', 'DRANEF' => 'DT', 'DPANEF' => 'Midelt ', 'ENTITE' => 'CPF Tounfit'],
            ['CODE' => '08-200CP2', 'DRANEF' => 'DT', 'DPANEF' => 'Midelt ', 'ENTITE' => 'CPF Itzer-Midelt '],
            ['CODE' => '08-200CP3', 'DRANEF' => 'DT', 'DPANEF' => 'Midelt ', 'ENTITE' => 'CPF Rich'],
            ['CODE' => '08-200CG1', 'DRANEF' => 'DT', 'DPANEF' => 'Midelt ', 'ENTITE' => 'CGF Tounfit'],
            ['CODE' => '08-200CG2', 'DRANEF' => 'DT', 'DPANEF' => 'Midelt ', 'ENTITE' => 'CGF Itzer'],
            ['CODE' => '08-200CG3', 'DRANEF' => 'DT', 'DPANEF' => 'Midelt ', 'ENTITE' => 'CGF Midelt-Rich'],
            ['CODE' => '09-120', 'DRANEF' => 'SM', 'DPANEF' => 'Agadir', 'ENTITE' => 'Tamri'],
            ['CODE' => '09-110', 'DRANEF' => 'SM', 'DPANEF' => 'Agadir', 'ENTITE' => 'Agadir-Inezgane'],
            ['CODE' => '09-330', 'DRANEF' => 'SM', 'DPANEF' => 'Taroudant', 'ENTITE' => 'Taroudant'],
            ['CODE' => '09-320', 'DRANEF' => 'SM', 'DPANEF' => 'Taroudant', 'ENTITE' => 'Oulad Taïma'],
            ['CODE' => '09-310', 'DRANEF' => 'SM', 'DPANEF' => 'Taroudant', 'ENTITE' => 'Aoulouz'],
            ['CODE' => '09-400CP1', 'DRANEF' => 'SM', 'DPANEF' => 'Tiznit', 'ENTITE' => 'CPF Tiznit'],
            ['CODE' => '09-400CP2', 'DRANEF' => 'SM', 'DPANEF' => 'Tiznit', 'ENTITE' => 'CPF Tafraout'],
            ['CODE' => '09-400CG1', 'DRANEF' => 'SM', 'DPANEF' => 'Tiznit', 'ENTITE' => 'CGF Tiznit'],
            ['CODE' => '10-200CP1', 'DRANEF' => 'GON', 'DPANEF' => 'Sidi Ifni', 'ENTITE' => 'CPF Sidi Ifni'],
            ['CODE' => '10-200CG1', 'DRANEF' => 'GON', 'DPANEF' => 'Sidi Ifni', 'ENTITE' => 'CGF Sidi Ifni'],
            ['CODE' => '11-100CP1', 'DRANEF' => 'LSH', 'DPANEF' => 'Laayoune', 'ENTITE' => 'CPF Laayoune'],
            ['CODE' => '11-100CG1', 'DRANEF' => 'LSH', 'DPANEF' => 'Laayoune', 'ENTITE' => 'CGF Laayoune'],
            ['CODE' => '12-100CP1', 'DRANEF' => 'DOE', 'DPANEF' => 'Oued Ed-Dahab', 'ENTITE' => 'CPF Dakhla'],
            ['CODE' => '12-100CG1', 'DRANEF' => 'DOE', 'DPANEF' => 'Oued Ed-Dahab', 'ENTITE' => 'CGF Dakhla'],
        ];

        foreach ($localisations as $localisation) {
            Localisation::firstOrCreate(
                ['CODE' => $localisation['CODE']],
                $localisation
            );
        }
    }

    /**
     * Load localisations from JSON file
     */
    private function loadFromJson(string $jsonPath): void
    {
        $json = file_get_contents($jsonPath);
        $data = json_decode($json, true) ?? [];

        if (empty($data)) {
            $this->command->warn('Localisation.json file is empty or invalid JSON');
            return;
        }

        $this->command->info('Loading ' . count($data) . ' localisations from Localisation.json');

        foreach ($data as $item) {
            $code = $item['CODE'] ?? null;
            if (!$code) {
                continue;
            }

            Localisation::firstOrCreate(
                ['CODE' => $code],
                [
                    'DRANEF' => $item['DRANEF'] ?? null,
                    'DPANEF' => $item['DPANEF'] ?? null,
                    'ENTITE' => $item['ENTITE'] ?? null,
                ]
            );
        }

        $this->command->info('Localisations seeded successfully!');
    }
} 