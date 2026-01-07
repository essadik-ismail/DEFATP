<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Commune;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class CommuneSeeder extends Seeder
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

        // Extract unique Provinces from JSON (these become Communes in database)
        // Based on model: Commune is parent, Province is child
        foreach ($data as $row) {
            if (isset($row['Province'])) {
                $provinceName = trim($row['Province']);
                
                if (!empty($provinceName)) {
                    if (!isset($communes[$provinceName])) {
                        $communes[$provinceName] = [
                            'nom' => $provinceName,
                        ];
                    }
                }
            }
        }

        $this->command->info('Found ' . count($communes) . ' unique Communes');

        $createdCount = 0;
        $updatedCount = 0;

        DB::beginTransaction();
        try {
            foreach ($communes as $communeData) {
                $commune = Commune::updateOrCreate(
                    ['nom' => $communeData['nom']],
                    $communeData
                );

                if ($commune->wasRecentlyCreated) {
                    $createdCount++;
                } else {
                    $updatedCount++;
                }
            }

            DB::commit();
            $this->command->info("Commune Seeder completed: {$createdCount} created, {$updatedCount} updated");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error seeding Communes: ' . $e->getMessage());
            throw $e;
        }
    }
}
