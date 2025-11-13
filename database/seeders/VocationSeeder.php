<?php

namespace Database\Seeders;

use App\Models\Vocation;
use Illuminate\Database\Seeder;

class VocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Try to load from JSON file first
        $jsonPath = base_path('data/Vocation.json');
        
        if (file_exists($jsonPath)) {
            $this->loadFromJson($jsonPath);
            return;
        }

        // If JSON doesn't exist, try Excel file
        $excelPath = base_path('data/Vocation.xlsx');
        if (file_exists($excelPath)) {
            $this->command->warn('Excel file found but JSON reader not implemented. Please convert Vocation.xlsx to Vocation.json');
            $this->command->info('Using fallback data instead...');
        }

        // Fallback: create some default vocations
        $this->loadFallbackData();
    }

    /**
     * Load vocations from JSON file
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
                $this->command->warn('Vocation.json file is empty or invalid JSON. Using fallback data...');
                $this->loadFallbackData();
                return;
            }

            $this->command->info('Loading ' . count($data) . ' vocations from Vocation.json');

            $createdCount = 0;
            $skippedCount = 0;

            foreach ($data as $index => $item) {
                try {
                    $name = null;
                    
                    // Handle different JSON structures
                    if (isset($item['name'])) {
                        $name = $item['name'];
                    } elseif (isset($item['Vocation'])) {
                        $name = $item['Vocation'];
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
                    
                    $vocation = Vocation::firstOrCreate(
                        ['name' => $name],
                        []
                    );

                    if ($vocation->wasRecentlyCreated) {
                        $createdCount++;
                    } else {
                        $skippedCount++;
                    }
                } catch (\Exception $e) {
                    $this->command->warn("Error processing vocation at index {$index}: " . $e->getMessage());
                    $skippedCount++;
                    continue;
                }
            }

            $this->command->info("Vocations seeded successfully! Created: {$createdCount}, Skipped: {$skippedCount}");
        } catch (\Exception $e) {
            $this->command->error('Error loading vocations from JSON: ' . $e->getMessage());
            $this->command->info('Falling back to default data...');
            $this->loadFallbackData();
        }
    }

    /**
     * Load fallback data
     */
    private function loadFallbackData(): void
    {
        $defaultVocations = [
            'Exploitation forestière',
            'Transformation du bois',
            'Apiculture',
            'Élevage',
            'Agriculture',
            'Artisanat',
        ];

        $this->command->info('Loading ' . count($defaultVocations) . ' default vocations');

        foreach ($defaultVocations as $vocation) {
            Vocation::firstOrCreate(
                ['name' => $vocation],
                []
            );
        }

        $this->command->info('Default vocations seeded successfully!');
    }
}
