<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dranef;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class DranefSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = base_path('database/data/decoupage_forestier.json');
        
        if (!File::exists($jsonPath)) {
            $this->command->error('JSON file not found at: ' . $jsonPath);
            return;
        }

        $this->command->info('Loading DRANEF data from JSON file...');
        
        $jsonContent = File::get($jsonPath);
        $data = json_decode($jsonContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->command->error('JSON decode error: ' . json_last_error_msg());
            return;
        }

        // Check for both possible JSON structures
        $rows = [];
        if (isset($data['DRANEF']) && is_array($data['DRANEF'])) {
            // New structure with DRANEF key
            $rows = $data['DRANEF'];
        } elseif (isset($data['Sheet1']) && is_array($data['Sheet1'])) {
            // Old structure with Sheet1 key
            $rows = $data['Sheet1'];
        } else {
            $this->command->error('Invalid JSON structure. Expected DRANEF or Sheet1 array.');
            return;
        }

        $dranefs = [];

        // Extract unique DRANEFs
        foreach ($rows as $row) {
            // Try new structure first: "Code DRANEF (pk)", "Nom DRANEF", "Abréviation"
            if (isset($row['Code DRANEF (pk)'])) {
                $code = trim($row['Code DRANEF (pk)']);
                $name = isset($row['Nom DRANEF']) ? trim($row['Nom DRANEF']) : '';
                $abreviation = isset($row['Abréviation']) ? trim($row['Abréviation']) : null;
                
                if (!empty($code) && !empty($name)) {
                    if (!isset($dranefs[$code])) {
                        $dranefs[$code] = [
                            'code' => $code,
                            'dranef' => $name,
                            'Abréviation' => $abreviation,
                        ];
                    }
                }
            }
            // Fallback to old structure: "code dranef", "DRANEF"
            elseif (isset($row['code dranef']) && isset($row['DRANEF'])) {
                $code = trim($row['code dranef']);
                $name = trim($row['DRANEF']);
                $abreviation = isset($row['Abréviation']) ? trim($row['Abréviation']) : null;
                
                if (!empty($code) && !empty($name)) {
                    if (!isset($dranefs[$code])) {
                        $dranefs[$code] = [
                            'code' => $code,
                            'dranef' => $name,
                            'Abréviation' => $abreviation,
                        ];
                    }
                }
            }
        }

        $this->command->info('Found ' . count($dranefs) . ' unique DRANEFs');

        $createdCount = 0;
        $updatedCount = 0;

        DB::beginTransaction();
        try {
            foreach ($dranefs as $dranefData) {
                $dranef = Dranef::updateOrCreate(
                    ['code' => $dranefData['code']],
                    [
                        'dranef' => $dranefData['dranef'],
                        'Abréviation' => $dranefData['Abréviation'] ?? null,
                    ]
                );

                if ($dranef->wasRecentlyCreated) {
                    $createdCount++;
                } else {
                    $updatedCount++;
                }
            }

            DB::commit();
            $this->command->info("DRANEF Seeder completed: {$createdCount} created, {$updatedCount} updated");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error seeding DRANEFs: ' . $e->getMessage());
            throw $e;
        }
    }
}
