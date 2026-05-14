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

        // Sheet name is "dfp", fields: "code dfp", "DFP/BM", "code zdtf", "code dpanef"
        $rows = $data['dfp'] ?? $data['DFP'] ?? $data['Sheet1'] ?? [];

        if (empty($rows)) {
            $this->command->error('Invalid JSON structure. Expected dfp/DFP array.');
            return;
        }

        $dfps = [];

        foreach ($rows as $row) {
            $code = trim($row['code dfp'] ?? $row['Codedfp'] ?? $row['CodeDFP'] ?? '');
            $name = trim($row['DFP/BM'] ?? $row['DFP'] ?? '');
            $zdtfCode = trim($row['code zdtf'] ?? $row['codeZDTF (fk)'] ?? $row['codeZDTF'] ?? '');
            $dpanefCode = trim($row['code dpanef'] ?? $row['code dpanef (fk)'] ?? '');

            if (empty($code)) {
                continue;
            }

            if (!isset($dfps[$code])) {
                $dfps[$code] = [
                    'code'        => $code,
                    'dfp'         => $name ?: 'DFP ' . $code,
                    'zdtf_code'   => $zdtfCode ?: null,
                    'dpanef_code' => $dpanefCode ?: null,
                ];
            } elseif (!empty($name) && $dfps[$code]['dfp'] === 'DFP ' . $code) {
                $dfps[$code]['dfp'] = $name;
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
