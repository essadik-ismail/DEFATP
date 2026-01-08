<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Province;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * This seeder creates Provinces from the "Province" field in the JSON file.
     * Provinces are the parent entities (one Province has many Communes).
     * Must run BEFORE CommuneSeeder.
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

        // Extract unique "Province" values from JSON (these become Provinces in database)
        foreach ($data as $row) {
            if (isset($row['Province'])) {
                $provinceName = trim($row['Province']);
                
                if (!empty($provinceName)) {
                    // Use the name as key to ensure uniqueness
                    if (!isset($provinces[$provinceName])) {
                        $provinces[$provinceName] = [
                            'nom' => $provinceName,
                        ];
                    }
                }
            }
        }

        $this->command->info('Found ' . count($provinces) . ' unique Provinces');

        $createdCount = 0;
        $updatedCount = 0;

        DB::beginTransaction();
        try {
            foreach ($provinces as $provinceData) {
                $province = Province::updateOrCreate(
                    ['nom' => $provinceData['nom']],
                    [
                        'nom' => $provinceData['nom'],
                    ]
                );

                if ($province->wasRecentlyCreated) {
                    $createdCount++;
                } else {
                    $updatedCount++;
                }
            }

            DB::commit();
            $this->command->info("Province Seeder completed: {$createdCount} created, {$updatedCount} updated");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error seeding Provinces: ' . $e->getMessage());
            $this->command->error('Stack trace: ' . $e->getTraceAsString());
            throw $e;
        }
    }
}
