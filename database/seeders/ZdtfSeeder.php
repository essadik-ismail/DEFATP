<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Zdtf;
use App\Models\Dpanef;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class ZdtfSeeder extends Seeder
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

        $this->command->info('Loading ZDTF data from JSON file...');
        
        $jsonContent = File::get($jsonPath);
        $data = json_decode($jsonContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->command->error('JSON decode error: ' . json_last_error_msg());
            return;
        }

        // Check for ZDTF key in JSON
        if (empty($data) || !isset($data['ZDTF']) || !is_array($data['ZDTF'])) {
            $this->command->error('Invalid JSON structure. Expected ZDTF array.');
            return;
        }

        $rows = $data['ZDTF'];
        $zdtfs = [];
        $zdtfNameKey = 'ZDTF (Zone de\n Développement  \nTerritorial Forestier)';

        // Extract unique ZDTFs
        foreach ($rows as $row) {
            // Field mappings:
            // "codeZDTF" => code
            // "ZDTF (Zone de\n Développement  \nTerritorial Forestier)" => zdtf
            // "code dpanef (fk)" => dpanef_code
            
            if (isset($row['codeZDTF'])) {
                $code = trim($row['codeZDTF']);
                
                // Get dpanef_code from "code dpanef (fk)"
                $dpanefCode = null;
                if (isset($row['code dpanef (fk)'])) {
                    $dpanefCode = trim($row['code dpanef (fk)']);
                }
                
                // Get ZDTF name from "ZDTF (Zone de\n Développement  \nTerritorial Forestier)"
                $name = null;
                if (isset($row[$zdtfNameKey]) && !empty(trim($row[$zdtfNameKey]))) {
                    $name = trim($row[$zdtfNameKey]);
                }
                
                if (!empty($code) && !empty($dpanefCode)) {
                    if (!isset($zdtfs[$code])) {
                        $zdtfs[$code] = [
                            'code' => $code,
                            'zdtf' => $name ?? 'ZDTF ' . $code, // Fallback to code if name not available
                            'dpanef_code' => $dpanefCode,
                        ];
                    } else {
                        // Update name if we found a better one
                        if (!empty($name) && $zdtfs[$code]['zdtf'] === 'ZDTF ' . $code) {
                            $zdtfs[$code]['zdtf'] = $name;
                        }
                    }
                }
            }
        }

        $this->command->info('Found ' . count($zdtfs) . ' unique ZDTFs');

        // Get DPANEF IDs for foreign key
        $dpanefCodes = array_unique(array_column($zdtfs, 'dpanef_code'));
        $dpanefs = Dpanef::whereIn('code', $dpanefCodes)->get()->keyBy('code');

        $createdCount = 0;
        $updatedCount = 0;
        $skippedCount = 0;

        DB::beginTransaction();
        try {
            foreach ($zdtfs as $zdtfData) {
                $dpanef = $dpanefs->get($zdtfData['dpanef_code']);
                
                if (!$dpanef) {
                    $this->command->warn("Skipping ZDTF {$zdtfData['code']}: DPANEF code {$zdtfData['dpanef_code']} not found");
                    $skippedCount++;
                    continue;
                }

                $zdtf = Zdtf::updateOrCreate(
                    ['code' => $zdtfData['code']],
                    [
                        'zdtf' => $zdtfData['zdtf'],
                        'sdtf' => $zdtfData['zdtf'], // Keep for backward compatibility
                        'dpanef_id' => $dpanef->id,
                        'dpanef_code' => $zdtfData['dpanef_code'],
                    ]
                );

                if ($zdtf->wasRecentlyCreated) {
                    $createdCount++;
                } else {
                    $updatedCount++;
                }
            }

            DB::commit();
            $this->command->info("ZDTF Seeder completed: {$createdCount} created, {$updatedCount} updated, {$skippedCount} skipped");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error seeding ZDTFs: ' . $e->getMessage());
            throw $e;
        }
    }
}
