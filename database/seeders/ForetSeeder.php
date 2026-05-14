<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Foret;
use App\Models\Dpanef;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class ForetSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = base_path('database/data/forets_rsk.json');

        if (!File::exists($jsonPath)) {
            $this->command->error('JSON file not found at: ' . $jsonPath);
            return;
        }

        $this->command->info('Loading Foret data from JSON file...');

        $data = json_decode(File::get($jsonPath), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->command->error('JSON decode error: ' . json_last_error_msg());
            return;
        }

        $rows = $data['forets'] ?? [];

        // Deduplicate by code foret: one DB record per numeric code
        $foretsByCode = [];
        foreach ($rows as $row) {
            $code       = trim($row['code foret'] ?? '');
            $name       = trim($row['FORET'] ?? '');
            $dpanefCode = trim($row['code dpanef'] ?? '');

            if (empty($code) || empty($name)) {
                continue;
            }

            if (!isset($foretsByCode[$code])) {
                $foretsByCode[$code] = [
                    'name'        => $name,
                    'dpanef_code' => $dpanefCode,
                ];
            }
        }

        $this->command->info('Found ' . count($foretsByCode) . ' unique forets (by code)');

        $dpanefCodes = array_filter(array_unique(array_column($foretsByCode, 'dpanef_code')));
        $dpanefs = Dpanef::whereIn('code', $dpanefCodes)->get()->keyBy('code');

        $createdCount = $updatedCount = 0;
        $codeToId = [];

        DB::beginTransaction();
        try {
            foreach ($foretsByCode as $code => $foretData) {
                $dpanefId = null;
                if ($foretData['dpanef_code']) {
                    $dpanef = $dpanefs->get($foretData['dpanef_code']);
                    $dpanefId = $dpanef?->id;
                }

                $foret = Foret::updateOrCreate(
                    ['foret' => $foretData['name']],
                    [
                        'lat'       => '0',
                        'log'       => '0',
                        'dpanef_id' => $dpanefId,
                    ]
                );

                $codeToId[(string)$code] = $foret->id;
                $foret->wasRecentlyCreated ? $createdCount++ : $updatedCount++;
            }

            DB::commit();

            // Write code→id map for CantonSeeder
            File::put(
                base_path('database/data/foret_code_map.json'),
                json_encode($codeToId, JSON_PRETTY_PRINT)
            );

            $this->command->info("Foret Seeder completed: {$createdCount} created, {$updatedCount} updated");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error seeding Forets: ' . $e->getMessage());
            throw $e;
        }
    }
}
