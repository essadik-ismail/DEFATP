<?php

namespace Database\Seeders;

use App\Models\Avenant;
use App\Models\Contract;
use App\Models\Coperative;
use Illuminate\Database\Seeder;

class AvenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Try to load from JSON file first
        $jsonPath = base_path('data/Avenant.json');
        
        if (file_exists($jsonPath)) {
            $this->loadFromJson($jsonPath);
            return;
        }

        // If JSON doesn't exist, try Excel file
        $excelPath = base_path('data/Avenant.xlsx');
        if (file_exists($excelPath)) {
            $this->command->warn('Excel file found but JSON reader not implemented. Please convert Avenant.xlsx to Avenant.json');
            return;
        }

        $this->command->info('No data file found for avenants.');
    }

    /**
     * Load avenants from JSON file
     */
    private function loadFromJson(string $jsonPath): void
    {
        try {
            $json = file_get_contents($jsonPath);
            $data = json_decode($json, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('JSON decode error: ' . json_last_error_msg());
            }

            if (empty($data) || !is_array($data)) {
                $this->command->warn('Avenant.json file is empty or invalid JSON');
                return;
            }

            $this->command->info('Loading ' . count($data) . ' avenants from Avenant.json');

            $createdCount = 0;
            $skippedCount = 0;

            foreach ($data as $index => $item) {
                try {
                    $avenantData = $this->mapAvenantData($item);
                    
                    if (empty($avenantData['contact_id']) || empty($avenantData['avenant'])) {
                        $skippedCount++;
                        continue;
                    }

                    // Check if avenant already exists
                    $existing = Avenant::where('contact_id', $avenantData['contact_id'])
                        ->where('avenant', $avenantData['avenant'])
                        ->where('annee', $avenantData['annee'] ?? date('Y'))
                        ->first();

                    if ($existing) {
                        $skippedCount++;
                        continue;
                    }

                    Avenant::create($avenantData);
                    $createdCount++;
                } catch (\Exception $e) {
                    $this->command->warn("Error creating avenant at index {$index}: " . $e->getMessage());
                    $skippedCount++;
                    continue;
                }
            }

            $this->command->info("Avenants seeded successfully! Created: {$createdCount}, Skipped: {$skippedCount}");
        } catch (\Exception $e) {
            $this->command->error('Error loading avenants from JSON: ' . $e->getMessage());
        }
    }

    /**
     * Map JSON data to avenant fillable fields
     */
    private function mapAvenantData(array $item): array
    {
        $data = [];

        // Required fields
        $data['avenant'] = isset($item['avenant']) ? (string) $item['avenant'] : (isset($item['Avenant']) ? (string) $item['Avenant'] : null);
        $data['annee'] = isset($item['annee']) ? (int) $item['annee'] : (isset($item['Année']) ? (int) $item['Année'] : date('Y'));

        // Contract relationship - handle format "09/2020" or direct number
        if (isset($item['contact_id'])) {
            $data['contact_id'] = (int) $item['contact_id'];
        } elseif (isset($item['contrat']) || isset($item['Contrat'])) {
            $contratValue = isset($item['contrat']) ? $item['contrat'] : $item['Contrat'];
            
            // Handle format "09/2020" (numéro/année)
            if (is_string($contratValue) && strpos($contratValue, '/') !== false) {
                $parts = explode('/', $contratValue);
                $contratNum = (int) trim($parts[0]);
                $contratAnnee = isset($parts[1]) ? (int) trim($parts[1]) : $data['annee'];
            } else {
                $contratNum = (int) $contratValue;
                $contratAnnee = $data['annee'];
            }
            
            $contract = Contract::where('contarct', $contratNum)
                ->where('annee', $contratAnnee)
                ->first();
            $data['contact_id'] = $contract ? $contract->id : null;
        }

        // Date - handle "Date d'approbation" field
        if (isset($item['date'])) {
            try {
                $data['date'] = \Carbon\Carbon::parse($item['date'])->format('Y-m-d');
            } catch (\Exception $e) {
                $data['date'] = now()->format('Y-m-d');
            }
        } elseif (isset($item['Date'])) {
            try {
                $data['date'] = \Carbon\Carbon::parse($item['Date'])->format('Y-m-d');
            } catch (\Exception $e) {
                $data['date'] = now()->format('Y-m-d');
            }
        } elseif (isset($item["Date d'approbation"])) {
            try {
                $dateStr = $item["Date d'approbation"];
                // Remove time part if present
                $dateStr = explode(' ', $dateStr)[0];
                $data['date'] = \Carbon\Carbon::parse($dateStr)->format('Y-m-d');
            } catch (\Exception $e) {
                $data['date'] = now()->format('Y-m-d');
            }
        } else {
            $data['date'] = now()->format('Y-m-d');
        }

        // Coperative
        if (isset($item['coperative_id'])) {
            $data['coperative_id'] = (int) $item['coperative_id'];
        } elseif (isset($item['coperative'])) {
            $coperative = Coperative::where('nom', $item['coperative'])->first();
            $data['coperative_id'] = $coperative ? $coperative->id : null;
        }

        // Decimal fields with mapping for different column names
        $decimalFieldMap = [
            'superficie' => ['superficie', 'Superficie (Ha)', 'Superficie'],
            'gardiennage' => ['gardiennage', 'Gardiennage (jt)', 'Gardiennage'],
            'prevention_incendies' => ['prevention_incendies', 'Prévention incendies (jt)', 'Prévention incendies'],
            'elagage' => ['elagage', 'Elagage'],
            'eclaircie' => ['eclaircie', 'eclaircie'],
            'rajeunissement_romarin' => ['rajeunissement_romarin', 'Rajeunissement romarin', 'Rajeunissement Romarin'],
            'valeurs_des_produits' => ['valeurs_des_produits', 'Valeurs des Produits (Dh)', 'Valeurs des Produits'],
            'valeur_des_prestations' => ['valeur_des_prestations', 'Valeur des presattions (Dh)', 'Valeur des prestations (Dh)', 'Valeur des prestations'],
            'redevances' => ['redevances', 'Redevances (Dh)', 'Redevances'],
            'taxes' => ['taxes', 'Taxes (Dh)', 'Taxes'],
            'total_avenant' => ['total_avenant', 'Total Avenant (Dh)', 'Total Avenant']
        ];

        foreach ($decimalFieldMap as $field => $possibleNames) {
            $value = null;
            foreach ($possibleNames as $name) {
                if (isset($item[$name])) {
                    $value = $item[$name];
                    break;
                }
            }
            if ($value !== null && $value !== '' && is_numeric($value)) {
                $data[$field] = (float) $value;
            }
        }

        // Integer fields with mapping for different column names
        $integerFieldMap = [
            'bo_m3' => ['bo_m3', 'BO (m3)', 'BO'],
            'bi_m3' => ['bi_m3', 'BI (M3)', 'BI'],
            'bf_st' => ['bf_st', 'BF (St)', 'BF'],
            'laurier_sauce' => ['laurier_sauce', 'Laurier sauce (T)', 'Laurier sauce'],
            'myrte' => ['myrte', 'Myrte (T)', 'Myrte'],
            'callune' => ['callune', 'Callune (T)', 'Callune'],
            'thym' => ['thym', 'Thym (T)', 'Thym'],
            'bruyetre' => ['bruyetre', 'Bruyère (T)', 'Bruyère'],
            'lichen' => ['lichen', 'Lichen (T)', 'Lichen'],
            'romarin' => ['romarin', 'Romarin (T)', 'Romarin'],
            'liege_male' => ['liege_male', 'Liège male (St)', 'Liège male'],
            'liege_de_reproduction' => ['liege_de_reproduction', 'Liège de reproduction (st)', 'Liège de reproduction'],
            'sauge' => ['sauge', 'Sauge (T)', 'Sauge'],
            'lavande' => ['lavande', 'Lavande (T)', 'Lavande'],
            'armoise' => ['armoise', 'Armoise (T)', 'Armoise'],
            'origan' => ['origan', 'Origan (T)', 'Origan'],
            'alfa' => ['alfa', 'Alfa (T)', 'Alfa'],
            'lentisque' => ['lentisque', 'Lentisque (T)', 'Lentisque'],
            'ciste' => ['ciste', 'Ciste (T)', 'Ciste'],
            'fleur_acacia_t' => ['fleur_acacia_t', 'Fleurs d\'acacia (Q)', 'Fleurs d\'acacia', 'Fleur d\'acacia (Q)']
        ];

        foreach ($integerFieldMap as $field => $possibleNames) {
            $value = null;
            foreach ($possibleNames as $name) {
                if (isset($item[$name])) {
                    $value = $item[$name];
                    break;
                }
            }
            if ($value !== null && $value !== '' && is_numeric($value)) {
                $data[$field] = (int) $value;
            }
        }

        // Handle "Autres" field
        if (isset($item['Autres']) && !empty($item['Autres'])) {
            // Store in a field if available, or skip
            // Note: There's no 'autre' field in the model, so we skip it
        }

        return array_filter($data, function($value) {
            return $value !== null && $value !== '';
        });
    }
}
