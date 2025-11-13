<?php

namespace Database\Seeders;

use App\Models\Contract;
use App\Models\Localisation;
use App\Models\SituationAdministrative;
use App\Models\Foret;
use App\Models\Coperative;
use App\Models\Espece;
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

                    // Handle especes relationship
                    $especes = $contractData['especes'] ?? [];
                    unset($contractData['especes']);

                    $contract = Contract::create($contractData);

                    // Attach especes
                    if (!empty($especes)) {
                        $contract->especes()->attach($especes);
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

        // Required fields
        $data['annee'] = isset($item['annee']) ? (int) $item['annee'] : (isset($item['Année']) ? (int) $item['Année'] : date('Y'));
        $data['contarct'] = isset($item['contarct']) ? (int) $item['contarct'] : (isset($item['Contrat']) ? (int) $item['Contrat'] : null);

        // Foreign keys
        if (isset($item['localisation_id'])) {
            $data['localisation_id'] = (int) $item['localisation_id'];
        } elseif (isset($item['localisation'])) {
            $localisation = Localisation::where('CODE', $item['localisation'])->first();
            $data['localisation_id'] = $localisation ? $localisation->id : null;
        }

        if (isset($item['situation_administrative_id'])) {
            $data['situation_administrative_id'] = (int) $item['situation_administrative_id'];
        } elseif (isset($item['situation_administrative'])) {
            $situation = SituationAdministrative::where('commune', 'like', '%' . $item['situation_administrative'] . '%')->first();
            $data['situation_administrative_id'] = $situation ? $situation->id : null;
        }

        if (isset($item['foret_id'])) {
            $data['foret_id'] = (int) $item['foret_id'];
        } elseif (isset($item['foret'])) {
            $foret = Foret::where('foret', $item['foret'])->first();
            $data['foret_id'] = $foret ? $foret->id : null;
        }

        if (isset($item['coperative_id'])) {
            $data['coperative_id'] = (int) $item['coperative_id'];
        } elseif (isset($item['coperative'])) {
            $coperative = Coperative::where('nom', $item['coperative'])->first();
            $data['coperative_id'] = $coperative ? $coperative->id : null;
        }

        // Especes
        $especes = [];
        if (isset($item['especes']) && is_array($item['especes'])) {
            foreach ($item['especes'] as $especeName) {
                $espece = Espece::where('name', $especeName)->first();
                if ($espece) {
                    $especes[] = $espece->id;
                }
            }
        } elseif (isset($item['espece'])) {
            $espece = Espece::where('name', $item['espece'])->first();
            if ($espece) {
                $especes[] = $espece->id;
            }
        }
        $data['especes'] = $especes;

        // Numeric fields
        $numericFields = [
            'superficie', 'bo_m3', 'bi_m3', 'bf_st', 'tanin_t', 'laurier_sauce',
            'myrte', 'callune', 'thym', 'bruyetre', 'lichen', 'tanin', 'romarin',
            'liege_male', 'liege_de_reproduction', 'sauge', 'lavande', 'armoise',
            'origan', 'alfa', 'lentisque', 'ciste', 'fleur_acacia_t'
        ];

        foreach ($numericFields as $field) {
            if (isset($item[$field])) {
                $data[$field] = is_numeric($item[$field]) ? $item[$field] : null;
            }
        }

        // String fields
        $stringFields = [
            'gardiennage', 'prevention_contre_les_incendies', 'elagage',
            'eclaircie', 'rajeunissement_romarin', 'autre',
            'valeurs_des_produits', 'valeur_des_prestations', 'redevances',
            'taxes', 'total_avenant'
        ];

        foreach ($stringFields as $field) {
            if (isset($item[$field])) {
                $data[$field] = (string) $item[$field];
            }
        }

        // Boolean fields
        if (isset($item['resiliation'])) {
            $data['resiliation'] = (bool) $item['resiliation'];
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
