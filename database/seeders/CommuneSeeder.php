<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Commune;
use App\Models\Province;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class CommuneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * This seeder creates Communes from the "Commune" field in the JSON file.
     * Communes are linked to Provinces via province_id foreign key.
     * Must run AFTER ProvinceSeeder.
     */
    public function run(): void
    {
        $jsonPath = base_path('database/data/situation_administrative.json');
        
        if (!File::exists($jsonPath)) {
            $this->command->error('JSON file not found at: ' . $jsonPath);
            return;
        }

        $this->command->info('Loading Commune data from JSON file...');
        
        $jsonContent = File::get($jsonPath);
        $data = json_decode($jsonContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->command->error('JSON decode error: ' . json_last_error_msg());
            return;
        }

        if (empty($data) || !is_array($data)) {
            $this->command->error('Invalid JSON structure. Expected array.');
            return;
        }

        $communes = [];

        // Extract unique "Commune" values from JSON (these become Communes in database)
        // Link them to their parent Province (from "Province" field in JSON)
        foreach ($data as $row) {
            if (isset($row['Commune']) && isset($row['Province'])) {
                $communeName = trim($row['Commune']); // This becomes Commune in database
                $provinceName = trim($row['Province']); // This is the parent Province name
                
                if (!empty($communeName) && !empty($provinceName)) {
                    // Use the name as key to ensure uniqueness
                    if (!isset($communes[$communeName])) {
                        $communes[$communeName] = [
                            'nom' => $communeName,
                            'province_nom' => $provinceName, // Parent province name (from "Province" field)
                        ];
                    }
                }
            }
        }

        $this->command->info('Found ' . count($communes) . ' unique Communes');

        // Get Province IDs for foreign key
        // Provinces should already exist (run ProvinceSeeder first)
        $provinceNames = array_unique(array_column($communes, 'province_nom'));
        $provinces = Province::whereIn('nom', $provinceNames)->get()->keyBy('nom');

        // Check if all required provinces exist
        $missingProvinces = array_diff($provinceNames, $provinces->keys()->toArray());
        if (!empty($missingProvinces)) {
            $this->command->warn('Warning: The following Provinces are missing and will cause communes to be skipped:');
            foreach ($missingProvinces as $missingProvince) {
                $this->command->warn("  - {$missingProvince}");
            }
            $this->command->warn('Make sure to run ProvinceSeeder before CommuneSeeder.');
        }

        $createdCount = 0;
        $updatedCount = 0;
        $skippedCount = 0;

        DB::beginTransaction();
        try {
            foreach ($communes as $communeData) {
                $province = $provinces->get($communeData['province_nom']);
                
                if (!$province) {
                    $this->command->warn("Skipping Commune '{$communeData['nom']}': Province '{$communeData['province_nom']}' not found");
                    $skippedCount++;
                    continue;
                }

                $commune = Commune::updateOrCreate(
                    ['nom' => $communeData['nom']],
                    [
                        'nom' => $communeData['nom'],
                        'province_id' => $province->id,
                    ]
                );

                if ($commune->wasRecentlyCreated) {
                    $createdCount++;
                } else {
                    $updatedCount++;
                }
            }

            DB::commit();
            $this->command->info("Commune Seeder completed: {$createdCount} created, {$updatedCount} updated, {$skippedCount} skipped");
            
            if ($skippedCount > 0) {
                $this->command->warn("Note: {$skippedCount} communes were skipped due to missing provinces.");
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error seeding Communes: ' . $e->getMessage());
            $this->command->error('Stack trace: ' . $e->getTraceAsString());
            throw $e;
        }
    }
}
