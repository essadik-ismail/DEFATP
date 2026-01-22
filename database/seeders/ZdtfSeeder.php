<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Zdtf;
use App\Models\Dpanef;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class ZdtfSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rows = [
            [
                "codeZDTF" => "01-110",
                "ZDTF" => "Assilah",
                "code dpanef (fk)" => "01-100"
            ],
            [
                "codeZDTF" => "01-310",
                "ZDTF" => "Fahs Anjra",
                "code dpanef (fk)" => "01-300"
            ],
            [
                "codeZDTF" => "01-210",
                "ZDTF" => "Tetouan-Ouest",
                "code dpanef (fk)" => "01-200"
            ],
            [
                "codeZDTF" => "01-220",
                "ZDTF" => "Tetouan-Est",
                "code dpanef (fk)" => "01-200"
            ],
            [
                "codeZDTF" => "01-410",
                "ZDTF" => "Mdiq-Tétouan",
                "code dpanef (fk)" => "01-400"
            ],
            [
                "codeZDTF" => "01-520",
                "ZDTF" => "Béni Arouss",
                "code dpanef (fk)" => "01-500"
            ],
            [
                "codeZDTF" => "01-510",
                "ZDTF" => "Larache",
                "code dpanef (fk)" => "01-500"
            ],
            [
                "codeZDTF" => "01-530",
                        "ZDTF" => "Tatoft",
                        "code dpanef (fk)" => "01-500"
            ],
            [
                "codeZDTF" => "01-610",
                        "ZDTF" => "Al Hoceima",
                        "code dpanef (fk)" => "01-600"
            ],
            [
                "codeZDTF" => "01-630",
                        "ZDTF" => "Ikaouene",
                        "code dpanef (fk)" => "01-600"
            ],
            [
                "codeZDTF" => "01-620",
                        "ZDTF" => "Kétama",
                        "code dpanef (fk)" => "01-600"
            ],
            [
                "codeZDTF" => "01-640",
                        "ZDTF" => "Targuist",
                        "code dpanef (fk)" => "01-600"
            ],
            [
                "codeZDTF" => "01-720",
                        "code dpanef (fk)" => "01-700"
            ],
            [
                "codeZDTF" => "01-730",
                        "ZDTF" => "Bab Berred",
                        "code dpanef (fk)" => "01-700"
            ],
            [
                "codeZDTF" => "01-710",
                        "ZDTF" => "Chefchaouen Nord",
                        "code dpanef (fk)" => "01-700"
            ],
            [
                "codeZDTF" => "01-720",
                        "ZDTF" => "Chefchaouen Sud",
                        "code dpanef (fk)" => "01-700"
            ],
            [
                "codeZDTF" => "01-740",
                        "ZDTF" => "Jebha",
                        "code dpanef (fk)" => "01-700"
            ],
            [
                "codeZDTF" => "01-810",
                        "ZDTF" => "Mokrisset",
                        "code dpanef (fk)" => "01-800"
            ],
            [
                "codeZDTF" => "01-820",
                        "ZDTF" => "Ouezzane",
                        "code dpanef (fk)" => "01-800"
            ],
            [
                "codeZDTF" => "02-110",
                        "code dpanef (fk)" => "02-100"
            ],
            [
                "codeZDTF" => "02-210",
                        "ZDTF" => "Jerada",
                        "code dpanef (fk)" => "02-200"
            ],
            [
                "codeZDTF" => "02-220",
                        "ZDTF" => "Ain Beni Mathar",
                        "code dpanef (fk)" => "02-200"
            ],
            [
                "codeZDTF" => "02-310",
                        "code dpanef (fk)" => "02-300"
            ],
            [
                "codeZDTF" => "02-410",
                        "ZDTF" => "Debdou",
                        "code dpanef (fk)" => "02-400"
            ],
            [
                "codeZDTF" => "02-420",
                        "ZDTF" => "Taourirt",
                        "code dpanef (fk)" => "02-400"
            ],
            [
                "codeZDTF" => "02-430",
                        "ZDTF" => "El Ayoune ",
                        "code dpanef (fk)" => "02-400"
            ],
            [
                "codeZDTF" => "02-510",
                        "ZDTF" => "Bouarfa",
                        "code dpanef (fk)" => "02-500"
            ],
            [
                "codeZDTF" => "02-520",
                        "ZDTF" => "Talsint",
                        "code dpanef (fk)" => "02-500"
            ],
            [
                "codeZDTF" => "02-610",
                        "ZDTF" => "Guercif",
                        "code dpanef (fk)" => "02-600"
            ],
            [
                "codeZDTF" => "02-620",
                        "ZDTF" => "Tamjilt",
                        "code dpanef (fk)" => "02-600"
            ],
            [
                "codeZDTF" => "02-630",
                        "ZDTF" => "Taddart",
                        "code dpanef (fk)" => "02-600"
            ],
            [
                "codeZDTF" => "02-710",
                        "ZDTF" => "Nador",
                        "code dpanef (fk)" => "02-700"
            ],
            [
                "codeZDTF" => "02-720",
                        "ZDTF" => "Zaio",
                        "code dpanef (fk)" => "02-700"
            ],
            [
                "codeZDTF" => "02-810",
                        "code dpanef (fk)" => "02-800"
            ],
            [
                "codeZDTF" => "03-110",
                        "code dpanef (fk)" => "03-100"
            ],
            [
                "codeZDTF" => "03-220",
                        "ZDTF" => "ElHajeb",
                        "code dpanef (fk)" => "03-200"
            ],
            [
                "codeZDTF" => "03-210",
                        "ZDTF" => "Meknès",
                        "code dpanef (fk)" => "03-200"
            ],
            [
                "codeZDTF" => "03-310",
                        "ZDTF" => "Aknoul",
                        "code dpanef (fk)" => "03-300"
            ],
            [
                "codeZDTF" => "03-320",
                        "ZDTF" => "Maghraoua",
                        "code dpanef (fk)" => "03-300"
            ],
            [
                "codeZDTF" => "03-330",
                        "ZDTF" => "Tahla",
                        "code dpanef (fk)" => "03-300"
            ],
            [
                "codeZDTF" => "03-340",
                        "ZDTF" => "Taïnest",
                        "code dpanef (fk)" => "03-300"
            ],
            [
                "codeZDTF" => "03-350",
                        "ZDTF" => "Taza",
                        "code dpanef (fk)" => "03-300"
            ],
            [
                "codeZDTF" => "03-410",
                        "ZDTF" => "Boulemane",
                        "code dpanef (fk)" => "03-400"
            ],
            [
                "codeZDTF" => "03-420",
                        "ZDTF" => "Imouzzer Marmoucha",
                        "code dpanef (fk)" => "03-400"
            ],
            [
                "codeZDTF" => "03-430",
                        "ZDTF" => "Outat El Haj",
                        "code dpanef (fk)" => "03-400"
            ],
            [
                "codeZDTF" => "03-510",
                        "ZDTF" => "Ribat El Kheir",
                        "code dpanef (fk)" => "03-500"
            ],
            [
                "codeZDTF" => "03-520",
                        "ZDTF" => "Sefrou",
                        "code dpanef (fk)" => "03-500"
            ],
            [
                "codeZDTF" => "03-530",
                        "ZDTF" => "Imouzzer Kandar",
                        "code dpanef (fk)" => "03-500"
            ],
            [
                "codeZDTF" => "03-610",
                        "ZDTF" => "Ghafsaï",
                        "code dpanef (fk)" => "03-600"
            ],
            [
                "codeZDTF" => "03-620",
                        "ZDTF" => "Thar Souk",
                        "code dpanef (fk)" => "03-600"
            ],
            [
                "codeZDTF" => "03-710",
                        "code dpanef (fk)" => "03-700"
            ],
            [
                "codeZDTF" => "03-710",
                        "ZDTF" => "Aïn Leuh",
                        "code dpanef (fk)" => "03-700"
            ],
            [
                "codeZDTF" => "03-720",
                        "ZDTF" => "Azrou",
                        "code dpanef (fk)" => "03-700"
            ],
            [
                "codeZDTF" => "03-730",
                        "ZDTF" => "Bakrit",
                        "code dpanef (fk)" => "03-700"
            ],
            [
                "codeZDTF" => "03-740",
                        "ZDTF" => "Ifrane",
                        "code dpanef (fk)" => "03-700"
            ],
            [
                "codeZDTF" => "03-750",
                        "ZDTF" => "Timahdite",
                        "code dpanef (fk)" => "03-700"
            ],
            [
                "codeZDTF" => "04-110",
                        "code dpanef (fk)" => "04-100"
            ],
            [
                "codeZDTF" => "04-110",
                        "ZDTF" => "Rabat-Témara",
                        "code dpanef (fk)" => "04-100"
            ],
            [
                "codeZDTF" => "04-120",
                        "ZDTF" => "Salé",
                        "code dpanef (fk)" => "04-100"
            ],
            [
                "codeZDTF" => "04-210",
                        "ZDTF" => "Rommani",
                        "code dpanef (fk)" => "04-200"
            ],
            [
                "codeZDTF" => "04-220",
                        "ZDTF" => "Khémisset",
                        "code dpanef (fk)" => "04-200"
            ],
            [
                "codeZDTF" => "04-230",
                        "ZDTF" => "Maâziz",
                        "code dpanef (fk)" => "04-200"
            ],
            [
                "codeZDTF" => "04-240",
                        "ZDTF" => "Oulmès",
                        "code dpanef (fk)" => "04-200"
            ],
            [
                "codeZDTF" => "04-250",
                        "ZDTF" => "Sidi Allal Bahraoui",
                        "code dpanef (fk)" => "04-200"
            ],
            [
                "codeZDTF" => "04-260",
                        "ZDTF" => "Tiflet",
                        "code dpanef (fk)" => "04-200"
            ],
            [
                "codeZDTF" => "04-310",
                        "ZDTF" => "Kénitra",
                        "code dpanef (fk)" => "04-300"
            ],
            [
                "codeZDTF" => "04-320",
                        "ZDTF" => "Sidi Yahia Ouest ",
                        "code dpanef (fk)" => "04-300"
            ],
            [
                "codeZDTF" => "04-410",
                        "ZDTF" => "Souk El Arbaâ ",
                        "code dpanef (fk)" => "04-400"
            ],
            [
                "codeZDTF" => "04-610",
                        "ZDTF" => "Sidi Kacem",
                        "code dpanef (fk)" => "04-600"
            ],
            [
                "codeZDTF" => "04-510",
                        "ZDTF" => "Sidi Slimane ",
                        "code dpanef (fk)" => "04-500"
            ],
            [
                "codeZDTF" => "04-520",
                        "ZDTF" => "Sidi Yahya Est",
                        "code dpanef (fk)" => "04-500"
            ],
            [
                "codeZDTF" => "05-110",
                        "ZDTF" => "Aghbala",
                        "code dpanef (fk)" => "05-100"
            ],
            [
                "codeZDTF" => "05-120",
                        "ZDTF" => "Béni Mellal",
                        "code dpanef (fk)" => "05-100"
            ],
            [
                "codeZDTF" => "05-130",
                        "ZDTF" => "El Ksiba",
                        "code dpanef (fk)" => "05-100"
            ],
            [
                "codeZDTF" => "05-210",
                        "ZDTF" => "Aït Mhammed",
                        "code dpanef (fk)" => "05-200"
            ],
            [
                "codeZDTF" => "05-220",
                        "ZDTF" => "Azilal",
                        "code dpanef (fk)" => "05-200"
            ],
            [
                "codeZDTF" => "05-230",
                        "ZDTF" => "Demnate",
                        "code dpanef (fk)" => "05-200"
            ],
            [
                "codeZDTF" => "05-240",
                        "ZDTF" => "Ouaouizeght",
                        "code dpanef (fk)" => "05-200"
            ],
            [
                "codeZDTF" => "05-250",
                        "ZDTF" => "Tagleft",
                        "code dpanef (fk)" => "05-200"
            ],
            [
                "codeZDTF" => "05-360",
                        "code dpanef (fk)" => "05-300"
            ],
            [
                "codeZDTF" => "05-310",
                        "ZDTF" => "Ajdir",
                        "code dpanef (fk)" => "05-300"
            ],
            [
                "codeZDTF" => "05-320",
                        "ZDTF" => "El Kbab",
                        "code dpanef (fk)" => "05-300"
            ],
            [
                "codeZDTF" => "05-330",
                        "ZDTF" => "Khénifra",
                        "code dpanef (fk)" => "05-300"
            ],
            [
                "codeZDTF" => "05-340",
                        "ZDTF" => "Mrirt ",
                        "code dpanef (fk)" => "05-300"
            ],
            [
                "codeZDTF" => "05-350",
                        "ZDTF" => "My Bouazza",
                        "code dpanef (fk)" => "05-300"
            ],
            [
                "codeZDTF" => "05-360",
                        "ZDTF" => "Aguelmous",
                        "code dpanef (fk)" => "05-300"
            ],
            [
                "codeZDTF" => "05-410",
                        "ZDTF" => "Boujaâd",
                        "code dpanef (fk)" => "05-400"
            ],
            [
                "codeZDTF" => "05-420",
                        "ZDTF" => "Oued Zem",
                        "code dpanef (fk)" => "05-400"
            ],
            [
                "codeZDTF" => "06-110",
                        "code dpanef (fk)" => "06-100"
            ],
            [
                "codeZDTF" => "06-210",
                        "ZDTF" => "Benslimane",
                        "code dpanef (fk)" => "06-200"
            ],
            [
                "codeZDTF" => "06-220",
                        "ZDTF" => "Sidi Bettach",
                        "code dpanef (fk)" => "06-200"
            ],
            [
                "codeZDTF" => "06-310",
                        "code dpanef (fk)" => "06-300"
            ],
            [
                "codeZDTF" => "06-410",
                        "code dpanef (fk)" => "06-400"
            ],
            [
                "codeZDTF" => "07-010",
                        "code dpanef (fk)" => "07-000"
            ],
            [
                "codeZDTF" => "07-210",
                        "code dpanef (fk)" => "07-200"
            ],
            [
                "codeZDTF" => "07-210",
                        "ZDTF" => "Aît Ourir",
                        "code dpanef (fk)" => "07-200"
            ],
            [
                "codeZDTF" => "07-220",
                        "ZDTF" => "Amizmiz",
                        "code dpanef (fk)" => "07-200"
            ],
            [
                "codeZDTF" => "07-230",
                        "ZDTF" => "Tahanoute",
                        "code dpanef (fk)" => "07-200"
            ],
            [
                "codeZDTF" => "07-310",
                        "ZDTF" => "Imintanout",
                        "code dpanef (fk)" => "07-300"
            ],
            [
                "codeZDTF" => "07-320",
                        "ZDTF" => "Timlilt",
                        "code dpanef (fk)" => "07-300"
            ],
            [
                "codeZDTF" => "07-410",
                        "ZDTF" => "Essaouira",
                        "code dpanef (fk)" => "07-400"
            ],
            [
                "codeZDTF" => "07-420",
                        "ZDTF" => "Smimou",
                        "code dpanef (fk)" => "07-400"
            ],
            [
                "codeZDTF" => "07-430",
                        "ZDTF" => "Tamanar",
                        "code dpanef (fk)" => "07-400"
            ],
            [
                "codeZDTF" => "07-610",
                        "code dpanef (fk)" => "07-600"
            ],
            [
                "codeZDTF" => "07-510",
                        "ZDTF" => "Youssoufia",
                        "code dpanef (fk)" => "07-500"
            ],
            [
                "codeZDTF" => "07-710",
                        "code dpanef (fk)" => "07-700"
            ],
            [
                "codeZDTF" => "07-810",
                        "code dpanef (fk)" => "07-800"
            ],
            [
                "codeZDTF" => "08-110",
                        "code dpanef (fk)" => "08-100"
            ],
            [
                "codeZDTF" => "08-210",
                        "ZDTF" => "Agoudim",
                        "code dpanef (fk)" => "08-200"
            ],
            [
                "codeZDTF" => "08-220",
                        "ZDTF" => "Itzer",
                        "code dpanef (fk)" => "08-200"
            ],
            [
                "codeZDTF" => "08-230",
                        "ZDTF" => "Boumia",
                        "code dpanef (fk)" => "08-200"
            ],
            [
                "codeZDTF" => "08-240",
                        "ZDTF" => "Midelt ",
                        "code dpanef (fk)" => "08-200"
            ],
            [
                "codeZDTF" => "08-250",
                        "ZDTF" => "Rich",
                        "code dpanef (fk)" => "08-200"
            ],
            [
                "codeZDTF" => "08-260",
                        "ZDTF" => "Tounfite",
                        "code dpanef (fk)" => "08-200"
            ],
            [
                "codeZDTF" => "08-310",
                        "code dpanef (fk)" => "08-300"
            ],
            [
                "codeZDTF" => "08-410",
                        "code dpanef (fk)" => "08-400"
            ],
            [
                "codeZDTF" => "08-510",
                        "code dpanef (fk)" => "08-500"
            ],
            [
                "codeZDTF" => "09-110",
                        "code dpanef (fk)" => "09-100"
            ],
            [
                "codeZDTF" => "09-110",
                        "ZDTF" => "Agadir-Inezgane",
                        "code dpanef (fk)" => "09-100"
            ],
            [
                "codeZDTF" => "09-120",
                        "ZDTF" => "Tamri",
                        "code dpanef (fk)" => "09-100"
            ],
            [
                "codeZDTF" => "09-210",
                        "code dpanef (fk)" => "09-200"
            ],
            [
                "codeZDTF" => "09-310",
                        "ZDTF" => "Aoulouz",
                        "code dpanef (fk)" => "09-300"
            ],
            [
                "codeZDTF" => "09-320",
                        "ZDTF" => "Oulad Taïma",
                        "code dpanef (fk)" => "09-300"
            ],
            [
                "codeZDTF" => "09-330",
                        "ZDTF" => "Taroudant",
                        "code dpanef (fk)" => "09-300"
            ],
            [
                "codeZDTF" => "09-410",
                        "ZDTF" => "Tafraout",
                        "code dpanef (fk)" => "09-400"
            ],
            [
                "codeZDTF" => "09-510",
                        "code dpanef (fk)" => "09-500"
            ],
            [
                "codeZDTF" => "10-110",
                        "code dpanef (fk)" => "10-100"
            ],
            [
                "codeZDTF" => "10-210",
                        "code dpanef (fk)" => "10-200"
            ],
            [
                "codeZDTF" => "10-310",
                        "code dpanef (fk)" => "10-300"
            ],
            [
                "codeZDTF" => "10-410",
                        "code dpanef (fk)" => "10-400"
            ],
            [
                "codeZDTF" => "11-110",
                        "code dpanef (fk)" => "11-100"
            ],
            [
                "codeZDTF" => "11-210",
                        "ZDTF" => "Tarfaya",
                        "code dpanef (fk)" => "11-200"
            ],
            [
                "codeZDTF" => "11-310",
                        "code dpanef (fk)" => "11-300"
            ],
            [
                "codeZDTF" => "11-410",
                        "code dpanef (fk)" => "11-400"
            ],
            [
                "codeZDTF" => "12-110",
                        "code dpanef (fk)" => "12-100"
            ],
            [
                "codeZDTF" => "12-210",
                "ZDTF" => "Bir Guendouz",
                "code dpanef (fk)" => "12-200"
            ]
        ];

        $this->command->info('Loading ZDTF data from array...');
        $zdtfs = [];
        $zdtfNameKey = 'ZDTF';

        // Extract unique ZDTFs
        foreach ($rows as $row) {
            // Field mappings:
            // "codeZDTF" => code
            // "ZDTF" => zdtf
            // "code dpanef (fk)" => dpanef_code
            
            if (isset($row['codeZDTF'])) {
                $code = trim($row['codeZDTF']);
                
                // Get dpanef_code from "code dpanef (fk)"
                $dpanefCode = null;
                if (isset($row['code dpanef (fk)'])) {
                    $dpanefCode = trim($row['code dpanef (fk)']);
                }
                
                // Get ZDTF name from "ZDTF"
                $name = null;
                if (isset($row[$zdtfNameKey]) && !empty(trim($row[$zdtfNameKey]))) {
                    $name = trim($row[$zdtfNameKey]);
                }
                
                if (!empty($code) && !empty($dpanefCode)) {
                    if (!isset($zdtfs[$code])) {
                        $zdtfs[$code] = [
                            'code' => $code,
                            'zdtf' => $name ?? 'ZDTF ' . $code, // Fallback to code if name not available
                            'dpanef_code' => $dpanefCode,
                        ];
                    } else {
                        // Update name if we found a better one
                        if (!empty($name) && $zdtfs[$code]['zdtf'] === 'ZDTF ' . $code) {
                            $zdtfs[$code]['zdtf'] = $name;
                        }
                    }
                }
            }
        }

        $this->command->info('Found ' . count($zdtfs) . ' unique ZDTFs');

        // Get DPANEF IDs for foreign key
        $dpanefCodes = array_unique(array_column($zdtfs, 'dpanef_code'));
        $dpanefs = Dpanef::whereIn('code', $dpanefCodes)->get()->keyBy('code');

        $createdCount = 0;
        $updatedCount = 0;
        $skippedCount = 0;

        DB::beginTransaction();
        try {
            foreach ($zdtfs as $zdtfData) {
                $dpanef = $dpanefs->get($zdtfData['dpanef_code']);
                
                if (!$dpanef) {
                    $this->command->warn("Skipping ZDTF {$zdtfData['code']}: DPANEF code {$zdtfData['dpanef_code']} not found");
                    $skippedCount++;
                    continue;
                }

                $zdtf = Zdtf::updateOrCreate(
                    ['code' => $zdtfData['code']],
                    [
                        'zdtf' => $zdtfData['zdtf'],
                        'sdtf' => $zdtfData['zdtf'], // Keep for backward compatibility
                        'dpanef_id' => $dpanef->id,
                        'dpanef_code' => $zdtfData['dpanef_code'],
                    ]
                );

                if ($zdtf->wasRecentlyCreated) {
                    $createdCount++;
                } else {
                    $updatedCount++;
                }
            }

            DB::commit();
            $this->command->info("ZDTF Seeder completed: {$createdCount} created, {$updatedCount} updated, {$skippedCount} skipped");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error seeding ZDTFs: ' . $e->getMessage());
            throw $e;
        }
    }
}
