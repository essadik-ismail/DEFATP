<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dfp;
use App\Models\Zdtf;
use App\Models\Dpanef;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class DfpSeeder extends Seeder
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

        $this->command->info('Loading DFP data from JSON file...');
        
        $jsonContent = File::get($jsonPath);
        $data = json_decode($jsonContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->command->error('JSON decode error: ' . json_last_error_msg());
            return;
        }

        // Check for both possible JSON structures
        $rows = [];
        if (isset($data['DFP']) && is_array($data['DFP'])) {
            // New structure with DFP key
            $rows = $data['DFP'];
        } elseif (isset($data['Sheet1']) && is_array($data['Sheet1'])) {
            // Old structure with Sheet1 key
            $rows = $data['Sheet1'];
        } else {
            $this->command->error('Invalid JSON structure. Expected DFP or Sheet1 array.');
            return;
        }

        $dfps = [];

        // Extract unique DFPs
        foreach ($rows as $row) {
            // Try new structure first: "Codedfp", "DFP", "codeZDTF (fk)", "code dpanef (fk)"
            if (isset($row['Codedfp'])) {
                $code = trim($row['Codedfp']);
                
                // Get zdtf_code from "codeZDTF (fk)" or fallback to "codeZDTF"
                $zdtfCode = null;
                if (isset($row['codeZDTF (fk)'])) {
                    $zdtfCode = trim($row['codeZDTF (fk)']);
                } elseif (isset($row['codeZDTF'])) {
                    $zdtfCode = trim($row['codeZDTF']);
                }
                
                // Get dpanef_code from "code dpanef (fk)" or fallback to "code dpanef"
                $dpanefCode = null;
                if (isset($row['code dpanef (fk)'])) {
                    $dpanefCode = trim($row['code dpanef (fk)']);
                } elseif (isset($row['code dpanef'])) {
                    $dpanefCode = trim($row['code dpanef']);
                }
                
                // Get DFP name from "DFP" field
                $name = null;
                if (isset($row['DFP']) && !empty(trim($row['DFP']))) {
                    $name = trim($row['DFP']);
                } elseif (isset($row['DFP 486 ( 471,CPFCI 4,\nR Chasse 11)']) && !empty(trim($row['DFP 486 ( 471,CPFCI 4,\nR Chasse 11)']))) {
                    // Fallback to old field name
                    $name = trim($row['DFP 486 ( 471,CPFCI 4,\nR Chasse 11)']);
                }
                
                if (!empty($code)) {
                    if (!isset($dfps[$code])) {
                        $dfps[$code] = [
                            'code' => $code,
                            'dfp' => $name ?? 'DFP ' . $code, // Fallback to code if name not available
                            'zdtf_code' => $zdtfCode,
                            'dpanef_code' => $dpanefCode,
                        ];
                    } else {
                        // Update name if we found a better one
                        if (!empty($name) && $dfps[$code]['dfp'] === 'DFP ' . $code) {
                            $dfps[$code]['dfp'] = $name;
                        }
                    }
                }
            }
            // Fallback to old structure: "CodeDFP", "DFP 486 ( 471,CPFCI 4,\nR Chasse 11)"
            elseif (isset($row['CodeDFP'])) {
                $code = trim($row['CodeDFP']);
                $zdtfCode = isset($row['codeZDTF']) ? trim($row['codeZDTF']) : null;
                $dpanefCode = isset($row['code dpanef']) ? trim($row['code dpanef']) : null;
                
                $dfpNameKey = 'DFP 486 ( 471,CPFCI 4,\nR Chasse 11)';
                $name = null;
                if (isset($row[$dfpNameKey]) && !empty(trim($row[$dfpNameKey]))) {
                    $name = trim($row[$dfpNameKey]);
                }
                
                if (!empty($code)) {
                    if (!isset($dfps[$code])) {
                        $dfps[$code] = [
                            'code' => $code,
                            'dfp' => $name ?? 'DFP ' . $code,
                            'zdtf_code' => $zdtfCode,
                            'dpanef_code' => $dpanefCode,
                        ];
                    } else {
                        if (!empty($name) && $dfps[$code]['dfp'] === 'DFP ' . $code) {
                            $dfps[$code]['dfp'] = $name;
                        }
                    }
                }
            }
        }

        $this->command->info('Found ' . count($dfps) . ' unique DFPs');

        // Get ZDTF and DPANEF codes for validation
        $zdtfCodes = array_filter(array_unique(array_column($dfps, 'zdtf_code')));
        $dpanefCodes = array_filter(array_unique(array_column($dfps, 'dpanef_code')));
        
        $zdtfs = Zdtf::whereIn('code', $zdtfCodes)->get()->keyBy('code');
        $dpanefs = Dpanef::whereIn('code', $dpanefCodes)->get()->keyBy('code');

        $createdCount = 0;
        $updatedCount = 0;
        $skippedCount = 0;

        DB::beginTransaction();
        try {
            foreach ($dfps as $dfpData) {
                // Validate ZDTF code if provided
                if (!empty($dfpData['zdtf_code']) && !$zdtfs->has($dfpData['zdtf_code'])) {
                    $this->command->warn("Skipping DFP {$dfpData['code']}: ZDTF code {$dfpData['zdtf_code']} not found");
                    $skippedCount++;
                    continue;
                }

                // Validate DPANEF code if provided
                if (!empty($dfpData['dpanef_code']) && !$dpanefs->has($dfpData['dpanef_code'])) {
                    $this->command->warn("Skipping DFP {$dfpData['code']}: DPANEF code {$dfpData['dpanef_code']} not found");
                    $skippedCount++;
                    continue;
                }

                $dfp = Dfp::updateOrCreate(
                    ['code' => $dfpData['code']],
                    [
                        'dfp' => $dfpData['dfp'],
                        'zdtf_code' => $dfpData['zdtf_code'] ?? null,
                        'dpanef_code' => $dfpData['dpanef_code'] ?? null,
                    ]
                );

                if ($dfp->wasRecentlyCreated) {
                    $createdCount++;
                } else {
                    $updatedCount++;
                }
            }

            DB::commit();
            $this->command->info("DFP Seeder completed: {$createdCount} created, {$updatedCount} updated, {$skippedCount} skipped");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error seeding DFPs: ' . $e->getMessage());
            throw $e;
        }
    }
}
