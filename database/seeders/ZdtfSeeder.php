<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Zdtf;
use App\Models\Dpanef;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class ZdtfSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = base_path('database/data/decoupage_forestier.json');

        if (!File::exists($jsonPath)) {
            $this->command->error('JSON file not found at: ' . $jsonPath);
            return;
        }

        $this->command->info('Loading ZDTF data from JSON file...');

        $data = json_decode(File::get($jsonPath), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->command->error('JSON decode error: ' . json_last_error_msg());
            return;
        }

        $rows = $data['zdtf'] ?? [];

        $zdtfs = [];

        foreach ($rows as $row) {
            $code = trim($row['code zdtf'] ?? '');
            $name = trim($row['ZDTF/CPF/CGF'] ?? '');
            $dpanefCode = trim($row['code dpanef'] ?? '');

            if (empty($code) || empty($dpanefCode)) {
                continue;
            }

            if (!isset($zdtfs[$code])) {
                $zdtfs[$code] = [
                    'code'        => $code,
                    'zdtf'        => $name ?: 'ZDTF ' . $code,
                    'dpanef_code' => $dpanefCode,
                ];
            } elseif (!empty($name) && $zdtfs[$code]['zdtf'] === 'ZDTF ' . $code) {
                $zdtfs[$code]['zdtf'] = $name;
            }
        }

        $this->command->info('Found ' . count($zdtfs) . ' unique ZDTFs');

        $dpanefCodes = array_unique(array_column($zdtfs, 'dpanef_code'));
        $dpanefs = Dpanef::whereIn('code', $dpanefCodes)->get()->keyBy('code');

        $createdCount = $updatedCount = $skippedCount = 0;

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
                        'zdtf'        => $zdtfData['zdtf'],
                        'sdtf'        => $zdtfData['zdtf'],
                        'dpanef_id'   => $dpanef->id,
                        'dpanef_code' => $zdtfData['dpanef_code'],
                    ]
                );

                $zdtf->wasRecentlyCreated ? $createdCount++ : $updatedCount++;
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
