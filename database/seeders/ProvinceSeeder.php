<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Province;
use App\Models\Commune;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = base_path('database/data/situation_administrative.json');
        
        if (!File::exists($jsonPath)) {
            $this->command->error('JSON file not found at: ' . $jsonPath);
            return;
        }

        $this->command->info('Loading Province data from JSON file...');
        
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

        $provinces = [];

        // Extract unique Communes from JSON (these become Provinces in database)
        // Link them to their parent Commune (which is the "Province" value in JSON)
        foreach ($data as $row) {
            if (isset($row['Commune']) && isset($row['Province'])) {
                $communeName = trim($row['Commune']); // This becomes Province in database
                $provinceName = trim($row['Province']); // This is the parent Commune
                
                if (!empty($communeName) && !empty($provinceName)) {
                    if (!isset($provinces[$communeName])) {
                        $provinces[$communeName] = [
                            'nom' => $communeName,
                            'commune_nom' => $provinceName, // Parent commune name
                        ];
                    }
                }
            }
        }

        $this->command->info('Found ' . count($provinces) . ' unique Provinces');

        // Get Commune IDs for foreign key
        $communeNames = array_unique(array_column($provinces, 'commune_nom'));
        $communes = Commune::whereIn('nom', $communeNames)->get()->keyBy('nom');

        $createdCount = 0;
        $updatedCount = 0;
        $skippedCount = 0;

        DB::beginTransaction();
        try {
            foreach ($provinces as $provinceData) {
                $commune = $communes->get($provinceData['commune_nom']);
                
                if (!$commune) {
                    $this->command->warn("Skipping Province {$provinceData['nom']}: Commune '{$provinceData['commune_nom']}' not found");
                    $skippedCount++;
                    continue;
                }

                $province = Province::updateOrCreate(
                    ['nom' => $provinceData['nom']],
                    [
                        'nom' => $provinceData['nom'],
                        'commune_id' => $commune->id,
                    ]
                );

                if ($province->wasRecentlyCreated) {
                    $createdCount++;
                } else {
                    $updatedCount++;
                }
            }

            DB::commit();
            $this->command->info("Province Seeder completed: {$createdCount} created, {$updatedCount} updated, {$skippedCount} skipped");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error seeding Provinces: ' . $e->getMessage());
            throw $e;
        }
    }
}
