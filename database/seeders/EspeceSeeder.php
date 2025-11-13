<?php

namespace Database\Seeders;

use App\Models\Espece;
use Illuminate\Database\Seeder;

class EspeceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting EspeceSeeder...');
        
        // Try multiple possible file paths
        $possiblePaths = [
            base_path('data/Essence.json'),
            base_path('data/Espèce.json'),
            base_path('Essence.json'),
        ];

        $jsonPath = null;
        foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
                $jsonPath = $path;
                break;
            }
        }
        
        if (!$jsonPath) {
            $this->command->warn('Essence.json file not found. Trying fallback data...');
            $this->loadFallbackData();
            return;
        }

        $this->command->info('Loading especes from: ' . $jsonPath);
        
        try {
            $json = file_get_contents($jsonPath);
            $data = json_decode($json, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('JSON decode error: ' . json_last_error_msg());
            }

            if (empty($data) || !is_array($data)) {
                $this->command->warn('Essence.json file is empty or invalid JSON. Using fallback data...');
                $this->loadFallbackData();
                return;
            }

            $this->command->info('Found ' . count($data) . ' especes in JSON file');

            $createdCount = 0;
            $skippedCount = 0;

            foreach ($data as $index => $item) {
                try {
                    $name = null;
                    
                    // Handle different JSON structures
                    if (isset($item['Essence'])) {
                        $name = $item['Essence'];
                    } elseif (isset($item['name'])) {
                        $name = $item['name'];
                    } elseif (isset($item['nom'])) {
                        $name = $item['nom'];
                    } elseif (is_string($item)) {
                        $name = $item;
                    }

                    if (empty($name) || !is_string($name)) {
                        $skippedCount++;
                        continue;
                    }

                    $name = trim($name);
                    
                    $espece = Espece::firstOrCreate(
                        ['name' => $name],
                        []
                    );

                    if ($espece->wasRecentlyCreated) {
                        $createdCount++;
                    } else {
                        $skippedCount++;
                    }
                } catch (\Exception $e) {
                    $this->command->warn("Error processing espece at index {$index}: " . $e->getMessage());
                    $skippedCount++;
                    continue;
                }
            }

            $this->command->info("Especes seeded successfully! Created: {$createdCount}, Skipped: {$skippedCount}");
        } catch (\Exception $e) {
            $this->command->error('Error loading especes from JSON: ' . $e->getMessage());
            $this->command->info('Falling back to default data...');
            $this->loadFallbackData();
        }
    }

    /**
     * Load fallback data if JSON file is not available
     */
    private function loadFallbackData(): void
    {
        $defaultEspeces = [
            'Cèdre',
            'Chêne',
            'Pin',
            'Eucalyptus',
            'Acacia',
            'Thuya',
        ];

        $this->command->info('Loading ' . count($defaultEspeces) . ' default especes');

        foreach ($defaultEspeces as $name) {
            Espece::firstOrCreate(
                ['name' => $name],
                []
            );
        }

        $this->command->info('Default especes seeded successfully!');
    }
}
