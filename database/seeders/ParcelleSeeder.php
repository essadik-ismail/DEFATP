<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Parcelle;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class ParcelleSeeder extends Seeder
{
    /**
     * Must run AFTER CantonSeeder.
     */
    public function run(): void
    {
        $jsonPath       = base_path('database/data/forets_rsk.json');
        $cantonMapPath  = base_path('database/data/canton_code_map.json');
        $foretMapPath   = base_path('database/data/foret_code_map.json');

        if (!File::exists($jsonPath) || !File::exists($cantonMapPath)) {
            $this->command->error('Required JSON files not found. Run ForetSeeder and CantonSeeder first.');
            return;
        }

        $this->command->info('Loading Parcelle data from JSON file...');

        $data          = json_decode(File::get($jsonPath), true);
        $cantonCodeToId = json_decode(File::get($cantonMapPath), true);
        $foretCodeToId  = json_decode(File::get($foretMapPath), true);
        $rows           = $data['parcelle'] ?? [];

        $parcelles = [];

        foreach ($rows as $row) {
            $foretCode    = trim($row['code foret'] ?? '');
            $cantonCode   = trim($row['code canton'] ?? '');
            $parcelleCode = trim($row['code parcelle'] ?? '');
            $parcelleName = trim($row['PARCELLE'] ?? '');

            if (empty($parcelleCode) || empty($cantonCode)) {
                continue;
            }

            $cantonId = $cantonCodeToId[$cantonCode] ?? null;
            $foretId  = $foretCodeToId[$foretCode] ?? null;

            if (!$cantonId) {
                continue;
            }

            if (!isset($parcelles[$parcelleCode])) {
                $parcelles[$parcelleCode] = [
                    'parcelle'  => $parcelleName ?: $parcelleCode,
                    'canton_id' => $cantonId,
                    'foret_id'  => $foretId,
                ];
            }
        }

        $this->command->info('Found ' . count($parcelles) . ' unique parcelles');

        $createdCount = $updatedCount = 0;

        DB::beginTransaction();
        try {
            foreach ($parcelles as $parcelleData) {
                $parcelle = Parcelle::updateOrCreate(
                    ['parcelle' => $parcelleData['parcelle'], 'canton_id' => $parcelleData['canton_id']],
                    ['foret_id' => $parcelleData['foret_id']]
                );

                $parcelle->wasRecentlyCreated ? $createdCount++ : $updatedCount++;
            }

            DB::commit();
            $this->command->info("Parcelle Seeder completed: {$createdCount} created, {$updatedCount} updated");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error seeding Parcelles: ' . $e->getMessage());
            throw $e;
        }
    }
}
