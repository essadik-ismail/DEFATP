<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dpanef;
use App\Models\Dranef;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class DpanefSeeder extends Seeder
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

        $this->command->info('Loading DPANEF data from JSON file...');
        
        $jsonContent = File::get($jsonPath);
        $data = json_decode($jsonContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->command->error('JSON decode error: ' . json_last_error_msg());
            return;
        }

        // Check for both possible JSON structures
        $rows = [];
        if (isset($data['DPANEF']) && is_array($data['DPANEF'])) {
            // New structure with DPANEF key
            $rows = $data['DPANEF'];
        } elseif (isset($data['Sheet1']) && is_array($data['Sheet1'])) {
            // Old structure with Sheet1 key
            $rows = $data['Sheet1'];
        } else {
            $this->command->error('Invalid JSON structure. Expected DPANEF or Sheet1 array.');
            return;
        }

        $dpanefs = [];

        // Extract unique DPANEFs
        foreach ($rows as $row) {
            // Try new structure first: "code dpanef", "DPANEF", "code dranef"
            if (isset($row['code dpanef'])) {
                $code = trim($row['code dpanef']);
                $dranefCode = isset($row['code dranef']) ? trim($row['code dranef']) : null;
                
                // Get DPANEF name from "DPANEF" field
                $name = null;
                if (isset($row['DPANEF']) && !empty(trim($row['DPANEF']))) {
                    $name = trim($row['DPANEF']);
                } elseif (isset($row['DP']) && !empty(trim($row['DP']))) {
                    // Fallback to old "DP" field name
                    $name = trim($row['DP']);
                }
                
                if (!empty($code) && !empty($dranefCode)) {
                    if (!isset($dpanefs[$code])) {
                        $dpanefs[$code] = [
                            'code' => $code,
                            'dpanef' => $name ?? 'DPANEF ' . $code, // Fallback to code if name not available
                            'dranef_code' => $dranefCode,
                        ];
                    } else {
                        // Update name if we found a better one
                        if (!empty($name) && $dpanefs[$code]['dpanef'] === 'DPANEF ' . $code) {
                            $dpanefs[$code]['dpanef'] = $name;
                        }
                    }
                }
            }
        }

        $this->command->info('Found ' . count($dpanefs) . ' unique DPANEFs');

        // Get DRANEF IDs for foreign key
        $dranefCodes = array_unique(array_column($dpanefs, 'dranef_code'));
        $dranefs = Dranef::whereIn('code', $dranefCodes)->get()->keyBy('code');

        $createdCount = 0;
        $updatedCount = 0;
        $skippedCount = 0;

        DB::beginTransaction();
        try {
            foreach ($dpanefs as $dpanefData) {
                $dranef = $dranefs->get($dpanefData['dranef_code']);
                
                if (!$dranef) {
                    $this->command->warn("Skipping DPANEF {$dpanefData['code']}: DRANEF code {$dpanefData['dranef_code']} not found");
                    $skippedCount++;
                    continue;
                }

                $dpanef = Dpanef::updateOrCreate(
                    ['code' => $dpanefData['code']],
                    [
                        'dpanef' => $dpanefData['dpanef'],
                        'dranef_id' => $dranef->id,
                        'dranef_code' => $dpanefData['dranef_code'],
                    ]
                );

                if ($dpanef->wasRecentlyCreated) {
                    $createdCount++;
                } else {
                    $updatedCount++;
                }
            }

            DB::commit();
            $this->command->info("DPANEF Seeder completed: {$createdCount} created, {$updatedCount} updated, {$skippedCount} skipped");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error seeding DPANEFs: ' . $e->getMessage());
            throw $e;
        }
    }
}
