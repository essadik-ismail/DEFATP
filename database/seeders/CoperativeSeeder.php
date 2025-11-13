<?php

namespace Database\Seeders;

use App\Models\Coperative;
use App\Models\Vocation;
use Illuminate\Database\Seeder;

class CoperativeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Try to load from JSON file first
        $jsonPath = base_path('data/Coopérative_Groupement.json');
        
        if (file_exists($jsonPath)) {
            $this->loadFromJson($jsonPath);
            return;
        }

        // If JSON doesn't exist, try Excel file
        $excelPath = base_path('data/Coopérative_Groupement.xlsx');
        if (file_exists($excelPath)) {
            $this->command->warn('Excel file found but JSON reader not implemented. Please convert Coopérative_Groupement.xlsx to Coopérative_Groupement.json');
            $this->command->info('No cooperatives will be seeded without JSON data.');
            return;
        }

        $this->command->info('No data file found for cooperatives.');
    }

    /**
     * Load cooperatives from JSON file
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
                $this->command->warn('Coopérative_Groupement.json file is empty or invalid JSON');
                return;
            }

            $this->command->info('Loading ' . count($data) . ' cooperatives from Coopérative_Groupement.json');

            $createdCount = 0;
            $skippedCount = 0;
            $errorCount = 0;

            foreach ($data as $index => $item) {
                try {
                    $nom = null;
                    $vocationId = null;
                    $nombreMembres = 0;
                    $nombreCoperatives = 0;

                    // Handle different JSON structures for nom
                    if (isset($item['nom'])) {
                        $nom = $item['nom'];
                    } elseif (isset($item['Nom'])) {
                        $nom = $item['Nom'];
                    } elseif (isset($item['name'])) {
                        $nom = $item['name'];
                    } elseif (isset($item['Coopérative/Groupement'])) {
                        $nom = $item['Coopérative/Groupement'];
                    }

                    if (empty($nom) || !is_string($nom)) {
                        $skippedCount++;
                        continue;
                    }

                    $nom = trim($nom);

                    // Handle vocation - try multiple field names and handle case variations
                    if (isset($item['vocation_id']) && is_numeric($item['vocation_id'])) {
                        $vocationId = (int) $item['vocation_id'];
                        // Verify vocation exists
                        if (!Vocation::find($vocationId)) {
                            $vocationId = null;
                        }
                    } elseif (isset($item['vocation']) && !empty($item['vocation'])) {
                        $vocationName = trim($item['vocation']);
                        // Try exact match first (case insensitive)
                        $vocation = Vocation::whereRaw('LOWER(name) = LOWER(?)', [$vocationName])->first();
                        if (!$vocation) {
                            // Try partial match
                            $vocation = Vocation::whereRaw('LOWER(name) LIKE LOWER(?)', ['%' . $vocationName . '%'])->first();
                        }
                        $vocationId = $vocation ? $vocation->id : null;
                    } elseif (isset($item['Vocation']) && !empty($item['Vocation'])) {
                        $vocationName = trim($item['Vocation']);
                        // Try exact match first (case insensitive)
                        $vocation = Vocation::whereRaw('LOWER(name) = LOWER(?)', [$vocationName])->first();
                        if (!$vocation) {
                            // Try partial match
                            $vocation = Vocation::whereRaw('LOWER(name) LIKE LOWER(?)', ['%' . $vocationName . '%'])->first();
                        }
                        $vocationId = $vocation ? $vocation->id : null;
                    }

                    // Handle numeric fields - try multiple field names
                    if (isset($item['nombre_membres'])) {
                        $nombreMembres = is_numeric($item['nombre_membres']) ? (int) $item['nombre_membres'] : 0;
                    } elseif (isset($item['Nombre d\'adhérant']) || isset($item["Nombre d'adhérant"])) {
                        $value = isset($item['Nombre d\'adhérant']) ? $item['Nombre d\'adhérant'] : $item["Nombre d'adhérant"];
                        $nombreMembres = is_numeric($value) ? (int) $value : 0;
                    } elseif (isset($item['Nombre de Membres'])) {
                        $nombreMembres = is_numeric($item['Nombre de Membres']) ? (int) $item['Nombre de Membres'] : 0;
                    } elseif (isset($item['NombreMembres'])) {
                        $nombreMembres = is_numeric($item['NombreMembres']) ? (int) $item['NombreMembres'] : 0;
                    }

                    if (isset($item['nombre_coperatives'])) {
                        $nombreCoperatives = is_numeric($item['nombre_coperatives']) ? (int) $item['nombre_coperatives'] : 0;
                    } elseif (isset($item['Nombre de coopératives']) || isset($item['Nombre de Coopératives'])) {
                        $value = isset($item['Nombre de coopératives']) ? $item['Nombre de coopératives'] : $item['Nombre de Coopératives'];
                        $nombreCoperatives = is_numeric($value) && $value !== '' ? (int) $value : 0;
                    } elseif (isset($item['NombreCoperatives'])) {
                        $nombreCoperatives = is_numeric($item['NombreCoperatives']) ? (int) $item['NombreCoperatives'] : 0;
                    }

                    $coperative = Coperative::firstOrCreate(
                        ['nom' => $nom],
                        [
                            'vocation_id' => $vocationId,
                            'nombre_membres' => $nombreMembres,
                            'nombre_coperatives' => $nombreCoperatives,
                            'is_deleted' => false,
                        ]
                    );

                    // Update if already exists but has different data
                    if (!$coperative->wasRecentlyCreated) {
                        $updated = false;
                        if ($coperative->vocation_id != $vocationId) {
                            $coperative->vocation_id = $vocationId;
                            $updated = true;
                        }
                        if ($coperative->nombre_membres != $nombreMembres) {
                            $coperative->nombre_membres = $nombreMembres;
                            $updated = true;
                        }
                        if ($coperative->nombre_coperatives != $nombreCoperatives) {
                            $coperative->nombre_coperatives = $nombreCoperatives;
                            $updated = true;
                        }
                        if ($updated) {
                            $coperative->save();
                        }
                        $skippedCount++;
                    } else {
                        $createdCount++;
                    }
                } catch (\Exception $e) {
                    $this->command->warn("Error processing cooperative at index {$index}: " . $e->getMessage());
                    $errorCount++;
                    continue;
                }
            }

            $this->command->info("Cooperatives seeded successfully! Created: {$createdCount}, Skipped: {$skippedCount}, Errors: {$errorCount}");
        } catch (\Exception $e) {
            $this->command->error('Error loading cooperatives from JSON: ' . $e->getMessage());
        }
    }
}
