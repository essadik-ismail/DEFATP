<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Commune;
use App\Models\Province;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class CommuneSeeder extends Seeder
{
    /**
     * Must run AFTER ProvinceSeeder.
     */
    public function run(): void
    {
        $jsonPath = base_path('database/data/decoupage_administratif.json');

        if (!File::exists($jsonPath)) {
            $this->command->error('JSON file not found at: ' . $jsonPath);
            return;
        }

        $this->command->info('Loading Commune data from JSON file...');

        $data = json_decode(File::get($jsonPath), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->command->error('JSON decode error: ' . json_last_error_msg());
            return;
        }

        $communeRows = $data['Commune'] ?? [];
        $provinceRows = $data['province'] ?? [];

        // Build province code -> name map
        $provinceCodeToName = [];
        foreach ($provinceRows as $pRow) {
            $code = trim($pRow['CODE_PROVINCE'] ?? '');
            $name = trim($pRow['NOM_PROVINCE'] ?? '');
            if ($code && $name && !isset($provinceCodeToName[$code])) {
                $provinceCodeToName[$code] = $name;
            }
        }

        $communes = [];

        foreach ($communeRows as $row) {
            $communeName  = trim($row['COMMUNE'] ?? '');
            $provinceCode = trim($row['CODE_PROVINCE'] ?? '');

            if (empty($communeName) || empty($provinceCode)) {
                continue;
            }

            $provinceName = $provinceCodeToName[$provinceCode] ?? null;

            if ($provinceName && !isset($communes[$communeName])) {
                $communes[$communeName] = [
                    'nom'          => $communeName,
                    'province_nom' => $provinceName,
                ];
            }
        }

        $this->command->info('Found ' . count($communes) . ' unique Communes');

        $provinceNames = array_unique(array_column($communes, 'province_nom'));
        $provinces = Province::whereIn('nom', $provinceNames)->get()->keyBy('nom');

        $missingProvinces = array_diff($provinceNames, $provinces->keys()->toArray());
        if (!empty($missingProvinces)) {
            $this->command->warn('Missing provinces (communes will be skipped): ' . implode(', ', $missingProvinces));
        }

        $createdCount = $updatedCount = $skippedCount = 0;

        DB::beginTransaction();
        try {
            foreach ($communes as $communeData) {
                $province = $provinces->get($communeData['province_nom']);

                if (!$province) {
                    $skippedCount++;
                    continue;
                }

                $commune = Commune::updateOrCreate(
                    ['nom' => $communeData['nom']],
                    ['nom' => $communeData['nom'], 'province_id' => $province->id]
                );

                $commune->wasRecentlyCreated ? $createdCount++ : $updatedCount++;
            }

            DB::commit();
            $this->command->info("Commune Seeder completed: {$createdCount} created, {$updatedCount} updated, {$skippedCount} skipped");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error seeding Communes: ' . $e->getMessage());
            throw $e;
        }
    }
}
