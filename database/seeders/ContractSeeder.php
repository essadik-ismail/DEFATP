<?php

namespace Database\Seeders;

use App\Models\Contract;
use App\Models\Localisation;
use App\Models\SituationAdministrative;
use App\Models\Foret;
use App\Models\Coperative;
use App\Models\Essence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContractSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Try to load from JSON file first
        $jsonPath = base_path('data/Contrat.json');
        
        if (file_exists($jsonPath)) {
            $this->loadFromJson($jsonPath);
            return;
        }

        // If JSON doesn't exist, try Excel file
        $excelPath = base_path('data/Contrat.xlsx');
        if (file_exists($excelPath)) {
            $this->command->warn('Excel file found but JSON reader not implemented. Please convert Contrat.xlsx to Contrat.json');
            return;
        }

        $this->command->info('No data file found for contracts.');
    }

    /**
     * Load contracts from JSON file
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
                $this->command->warn('Contrat.json file is empty or invalid JSON');
                return;
            }

            $this->command->info('Loading ' . count($data) . ' contracts from Contrat.json');

            $createdCount = 0;
            $skippedCount = 0;

            foreach ($data as $index => $item) {
                try {
                    // Map JSON fields to contract fields
                    $contractData = $this->mapContractData($item);
                    
                    if (empty($contractData['contarct']) || empty($contractData['annee'])) {
                        $skippedCount++;
                        continue;
                    }

                    // Check if contract already exists
                    $existing = Contract::where('annee', $contractData['annee'])
                        ->where('contarct', $contractData['contarct'])
                        ->first();

                    if ($existing) {
                        $skippedCount++;
                        continue;
                    }

                    // Handle essences relationship
                    $essences = $contractData['essences'] ?? [];
                    unset($contractData['essences']);

                    // Handle forets relationship
                    $forets = $contractData['forets'] ?? [];
                    unset($contractData['forets']);

                    $contract = Contract::create($contractData);

                    // Attach essences
                    if (!empty($essences)) {
                        $contract->essences()->attach($essences);
                    }

                    // Attach forets
                    if (!empty($forets)) {
                        $contract->forets()->attach($forets);
                    }

                    $createdCount++;
                } catch (\Exception $e) {
                    $this->command->warn("Error creating contract at index {$index}: " . $e->getMessage());
                    $skippedCount++;
                    continue;
                }
            }

            $this->command->info("Contracts seeded successfully! Created: {$createdCount}, Skipped: {$skippedCount}");
        } catch (\Exception $e) {
            $this->command->error('Error loading contracts from JSON: ' . $e->getMessage());
        }
    }

    /**
     * Map JSON data to contract fillable fields
     */
    private function mapContractData(array $item): array
    {
        $data = [];

        // Required fields - handle format "09/2020" or direct number
        $data['annee'] = isset($item['annee']) ? (int) $item['annee'] : (isset($item['Année']) ? (int) $item['Année'] : date('Y'));
        
        // Handle contract format "09/2020" (numéro/année)
        if (isset($item['Contrat']) && is_string($item['Contrat']) && strpos($item['Contrat'], '/') !== false) {
            $parts = explode('/', $item['Contrat']);
            $data['contarct'] = (int) trim($parts[0]);
            // Update annee if provided in contract format
            if (isset($parts[1])) {
                $data['annee'] = (int) trim($parts[1]);
            }
        } elseif (isset($item['contarct'])) {
            $data['contarct'] = (int) $item['contarct'];
        } elseif (isset($item['Contrat'])) {
            $data['contarct'] = (int) $item['Contrat'];
        } else {
            $data['contarct'] = null;
        }

        // Foreign keys - Localisation
        if (isset($item['localisation_id'])) {
            $data['localisation_id'] = (int) $item['localisation_id'];
        } elseif (isset($item['localisation'])) {
            $localisation = Localisation::where('CODE', $item['localisation'])->first();
            $data['localisation_id'] = $localisation ? $localisation->id : null;
        } elseif (isset($item['ZDTF']) && !empty($item['ZDTF'])) {
            // Try to find by ZDTF code
            $zdtfValue = $item['ZDTF'];
            $localisation = Localisation::where('CODE', $zdtfValue)
                ->orWhere('id', $zdtfValue)
                ->first();
            $data['localisation_id'] = $localisation ? $localisation->id : null;
        }
        
        // If still no localisation_id, try to get a default one or skip this contract
        if (empty($data['localisation_id'])) {
            // Try to get first available localisation as fallback
            $defaultLocalisation = Localisation::first();
            $data['localisation_id'] = $defaultLocalisation ? $defaultLocalisation->id : null;
        }

        if (isset($item['situation_administrative_id'])) {
            $data['situation_administrative_id'] = (int) $item['situation_administrative_id'];
        } elseif (isset($item['situation_administrative'])) {
            $situation = SituationAdministrative::where('commune', 'like', '%' . $item['situation_administrative'] . '%')->first();
            $data['situation_administrative_id'] = $situation ? $situation->id : null;
        } elseif (isset($item['Commune']) && !empty($item['Commune'])) {
            // Try to find by commune code or name
            $communeValue = $item['Commune'];
            $situation = SituationAdministrative::where('commune', 'like', '%' . $communeValue . '%')
                ->orWhere('id', $communeValue)
                ->first();
            $data['situation_administrative_id'] = $situation ? $situation->id : null;
        }
        
        // If still no situation_administrative_id, try to get a default one
        if (empty($data['situation_administrative_id'])) {
            $defaultSituation = SituationAdministrative::first();
            $data['situation_administrative_id'] = $defaultSituation ? $defaultSituation->id : null;
        }

        // Handle forets separately (many-to-many relationship)
        $forets = [];
        if (isset($item['foret_id'])) {
            $foret = Foret::find((int) $item['foret_id']);
            if ($foret) {
                $forets[] = $foret->id;
            }
        } elseif (isset($item['foret'])) {
            $foret = Foret::where('foret', $item['foret'])->first();
            if ($foret) {
                $forets[] = $foret->id;
            }
        } elseif (isset($item['Forêt']) && !empty($item['Forêt'])) {
            // Handle multiple forets separated by ";"
            $foretValue = $item['Forêt'];
            $foretValues = strpos($foretValue, ';') !== false 
                ? explode(';', $foretValue) 
                : [$foretValue];
            
            foreach ($foretValues as $fv) {
                $fv = trim($fv);
                if (empty($fv)) continue;
                
                $foret = Foret::where('id', $fv)
                    ->orWhere('foret', 'like', '%' . $fv . '%')
                    ->first();
                if ($foret && !in_array($foret->id, $forets)) {
                    $forets[] = $foret->id;
                }
            }
        }
        $data['forets'] = $forets;

        if (isset($item['coperative_id'])) {
            $data['coperative_id'] = (int) $item['coperative_id'];
        } elseif (isset($item['coperative'])) {
            $coperative = Coperative::where('nom', $item['coperative'])->first();
            $data['coperative_id'] = $coperative ? $coperative->id : null;
        } elseif (isset($item['Coopérative/Groupement']) && !empty($item['Coopérative/Groupement'])) {
            // Try to find by ID first, then by name
            $coopValue = $item['Coopérative/Groupement'];
            $coperative = Coperative::where('id', $coopValue)
                ->orWhere('nom', 'like', '%' . $coopValue . '%')
                ->first();
            $data['coperative_id'] = $coperative ? $coperative->id : null;
        }
        
        // If still no coperative_id, try to get a default one (required field)
        if (empty($data['coperative_id'])) {
            $defaultCoperative = Coperative::first();
            if ($defaultCoperative) {
                $data['coperative_id'] = $defaultCoperative->id;
            } else {
                // If no coperatives exist, we can't create contracts
                // This will be handled by the validation in the create method
                $data['coperative_id'] = null;
            }
        }

        // Essences - handle multiple values separated by ";"
        $essences = [];
        if (isset($item['essences']) && is_array($item['essences'])) {
            foreach ($item['essences'] as $essenceName) {
                $essence = Essence::where('essence', $essenceName)->first();
                if ($essence) {
                    $essences[] = $essence->id;
                }
            }
        } elseif (isset($item['essence'])) {
            $essenceValue = $item['essence'];
            // Handle multiple essences separated by ";"
            if (strpos($essenceValue, ';') !== false) {
                $essenceNames = explode(';', $essenceValue);
                foreach ($essenceNames as $essenceName) {
                    $essenceName = trim($essenceName);
                    $essence = Essence::where('id', $essenceName)
                        ->orWhere('essence', 'like', '%' . $essenceName . '%')
                        ->first();
                    if ($essence) {
                        $essences[] = $essence->id;
                    }
                }
            } else {
                $essence = Essence::where('id', $essenceValue)
                    ->orWhere('essence', 'like', '%' . $essenceValue . '%')
                    ->first();
                if ($essence) {
                    $essences[] = $essence->id;
                }
            }
        } elseif (isset($item['Espèce']) && !empty($item['Espèce'])) {
            $essenceValue = $item['Espèce'];
            // Handle multiple essences separated by ";"
            if (strpos($essenceValue, ';') !== false) {
                $essenceNames = explode(';', $essenceValue);
                foreach ($essenceNames as $essenceName) {
                    $essenceName = trim($essenceName);
                    $essence = Essence::where('id', $essenceName)
                        ->orWhere('essence', 'like', '%' . $essenceName . '%')
                        ->first();
                    if ($essence) {
                        $essences[] = $essence->id;
                    }
                }
            } else {
                $essence = Essence::where('id', $essenceValue)
                    ->orWhere('essence', 'like', '%' . $essenceValue . '%')
                    ->first();
                if ($essence) {
                    $essences[] = $essence->id;
                }
            }
        }
        $data['essences'] = $essences;

        // Numeric fields with mapping for different column names
        $numericFieldMap = [
            'superficie' => ['superficie', 'Superficie (Ha)', 'Superficie'],
            'bo_m3' => ['bo_m3', 'BO (m3)', 'BO'],
            'bi_m3' => ['bi_m3', 'BI (m3)', 'BI'],
            'bf_st' => ['bf_st', 'BF (St)', 'BF'],
            'laurier_sauce' => ['laurier_sauce', 'Laurier sauce (T)', 'Laurier sauce'],
            'myrte' => ['myrte', 'Myrthe (T)', 'Myrthe', 'Myrte (T)', 'Myrte'],
            'callune' => ['callune', 'Callune (T)', 'Callune'],
            'thym' => ['thym', 'Thym (T)', 'Thym'],
            'bruyetre' => ['bruyetre', 'Bruyère (T)', 'Bruyère'],
            'lichen' => ['lichen', 'Lichen (T)', 'Lichen'],
            'romarin' => ['romarin', 'Romarin (T/campagne)', 'Romarin (T)', 'Romarin'],
            'liege_male' => ['liege_male', 'Liège male (St)', 'Liège male'],
            'liege_de_reproduction' => ['liege_de_reproduction', 'Liège de reproduction (St)', 'Liège de reproduction'],
            'sauge' => ['sauge', 'Sauge (T)', 'Sauge'],
            'lavande' => ['lavande', 'Lavande (T)', 'Lavande'],
            'armoise' => ['armoise', 'Armoise (T)', 'Armoise'],
            'origan' => ['origan', 'Origan (T)', 'Origan'],
            'alfa' => ['alfa', 'Alfa (T)', 'Alfa'],
            'lentisque' => ['lentisque', 'Lentisque (T)', 'Lentisque'],
            'ciste' => ['ciste', 'Ciste (T)', 'Ciste'],
            'fleur_acacia_t' => ['fleur_acacia_t', 'Fleur d\'acacia (Q)', 'Fleurs d\'acacia (Q)', 'Fleur d\'acacia']
        ];

        foreach ($numericFieldMap as $field => $possibleNames) {
            $value = null;
            foreach ($possibleNames as $name) {
                if (isset($item[$name])) {
                    $value = $item[$name];
                    break;
                }
            }
            if ($value !== null && $value !== '') {
                // Handle comma as decimal separator
                if (is_string($value)) {
                    $value = str_replace(',', '.', $value);
                }
                if (is_numeric($value)) {
                    $data[$field] = (float) $value;
                }
            }
        }

        // String/decimal fields with mapping
        $stringFieldMap = [
            'gardiennage' => ['gardiennage', 'Gardiennage (jt)', 'Gardiennage'],
            'prevention_contre_les_incendies' => ['prevention_contre_les_incendies', 'Prévention contre les incendies (jt)', 'Prévention contre les incendies', 'Prévention incendies (jt)'],
            'elagage' => ['elagage', 'Elagage'],
            'eclaircie' => ['eclaircie', 'Eclaircie'],
            'rajeunissement_romarin' => ['rajeunissement_romarin', 'Rajeunissement de romarin', 'Rajeunissement romarin'],
            'autre' => ['autre', 'autre', 'Autres'],
            'valeurs_des_produits' => ['valeurs_des_produits', 'Valeur des produits (Dh)', 'Valeurs des Produits (Dh)'],
            'valeur_des_prestations' => ['valeur_des_prestations', 'Valeurs des prestation (Dh)', 'Valeur des prestations (Dh)', 'Valeurs des prestations (Dh)'],
            'redevances' => ['redevances', 'Redevances (Dh)', 'Redevances'],
            'taxes' => ['taxes', 'taxes (Dh)', 'Taxes (Dh)', 'Taxes'],
            'total_avenant' => ['total_avenant', 'Total contrat (Dh)', 'Total Avenant (Dh)', 'Total contrat']
        ];

        foreach ($stringFieldMap as $field => $possibleNames) {
            $value = null;
            foreach ($possibleNames as $name) {
                if (isset($item[$name])) {
                    $value = $item[$name];
                    break;
                }
            }
            if ($value !== null && $value !== '') {
                // Convert to numeric if it's a number, otherwise keep as string
                if (is_numeric($value)) {
                    $data[$field] = (float) $value;
                } else {
                    $data[$field] = (string) $value;
                }
            }
        }

        // Boolean fields
        if (isset($item['resiliation'])) {
            $resiliationValue = $item['resiliation'];
            if (is_bool($resiliationValue)) {
                $data['resiliation'] = $resiliationValue;
            } elseif (is_string($resiliationValue)) {
                $data['resiliation'] = strtolower($resiliationValue) === 'true' || $resiliationValue === '1';
            } else {
                $data['resiliation'] = (bool) $resiliationValue;
            }
        } elseif (isset($item['Résiliation'])) {
            $resiliationValue = $item['Résiliation'];
            if (is_bool($resiliationValue)) {
                $data['resiliation'] = $resiliationValue;
            } elseif (is_string($resiliationValue)) {
                $data['resiliation'] = strtolower($resiliationValue) === 'true' || $resiliationValue === '1';
            } else {
                $data['resiliation'] = (bool) $resiliationValue;
            }
        }

        // Date fields
        if (isset($item['date_resiliation'])) {
            try {
                $data['date_resiliation'] = \Carbon\Carbon::parse($item['date_resiliation'])->format('Y-m-d');
            } catch (\Exception $e) {
                $data['date_resiliation'] = null;
            }
        }

        return array_filter($data, function($value) {
            return $value !== null && $value !== '';
        });
    }
}
