<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Canton;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class CantonSeeder extends Seeder
{
    /**
     * Must run AFTER ForetSeeder.
     */
    public function run(): void
    {
        $jsonPath    = base_path('database/data/forets_rsk.json');
        $codeMapPath = base_path('database/data/foret_code_map.json');

        if (!File::exists($jsonPath) || !File::exists($codeMapPath)) {
            $this->command->error('Required JSON files not found. Run ForetSeeder first.');
            return;
        }

        $this->command->info('Loading Canton data from JSON file...');

        $data       = json_decode(File::get($jsonPath), true);
        $codeToId   = json_decode(File::get($codeMapPath), true);
        $rows       = $data['canton'] ?? [];

        $cantons = [];

        foreach ($rows as $row) {
            $foretCode  = trim($row['code foret'] ?? '');
            $cantonCode = trim($row['code canton'] ?? '');
            $cantonName = trim($row['CANTON'] ?? '');

            if (empty($foretCode) || empty($cantonCode)) {
                continue;
            }

            $foretId = $codeToId[$foretCode] ?? null;
            if (!$foretId) {
                continue;
            }

            if (!isset($cantons[$cantonCode])) {
                $cantons[$cantonCode] = [
                    'canton'   => $cantonName ?: $cantonCode,
                    'foret_id' => $foretId,
                    'code'     => $cantonCode,
                ];
            }
        }

        $this->command->info('Found ' . count($cantons) . ' unique cantons');

        $createdCount = $updatedCount = 0;

        DB::beginTransaction();
        try {
            // Write canton code→id map for ParcelleSeeder
            $cantonCodeToId = [];

            foreach ($cantons as $cantonData) {
                $canton = Canton::updateOrCreate(
                    ['canton' => $cantonData['canton'], 'foret_id' => $cantonData['foret_id']],
                    ['foret_id' => $cantonData['foret_id']]
                );

                $cantonCodeToId[$cantonData['code']] = $canton->id;
                $canton->wasRecentlyCreated ? $createdCount++ : $updatedCount++;
            }

            DB::commit();

            File::put(
                base_path('database/data/canton_code_map.json'),
                json_encode($cantonCodeToId, JSON_PRETTY_PRINT)
            );

            $this->command->info("Canton Seeder completed: {$createdCount} created, {$updatedCount} updated");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error seeding Cantons: ' . $e->getMessage());
            throw $e;
        }
    }
}
