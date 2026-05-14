<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Province;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class ProvinceSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = base_path('database/data/decoupage_administratif.json');

        if (!File::exists($jsonPath)) {
            $this->command->error('JSON file not found at: ' . $jsonPath);
            return;
        }

        $this->command->info('Loading Province data from JSON file...');

        $data = json_decode(File::get($jsonPath), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->command->error('JSON decode error: ' . json_last_error_msg());
            return;
        }

        $rows = $data['province'] ?? [];

        $provinces = [];

        foreach ($rows as $row) {
            $name = trim($row['NOM_PROVINCE'] ?? '');
            $code = trim($row['CODE_PROVINCE'] ?? '');

            if (!empty($name) && !isset($provinces[$name])) {
                $provinces[$name] = ['nom' => $name, 'code' => $code];
            }
        }

        $this->command->info('Found ' . count($provinces) . ' unique Provinces');

        $createdCount = $updatedCount = 0;

        DB::beginTransaction();
        try {
            foreach ($provinces as $provinceData) {
                $province = Province::updateOrCreate(
                    ['nom' => $provinceData['nom']],
                    ['nom' => $provinceData['nom']]
                );

                $province->wasRecentlyCreated ? $createdCount++ : $updatedCount++;
            }

            DB::commit();
            $this->command->info("Province Seeder completed: {$createdCount} created, {$updatedCount} updated");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error seeding Provinces: ' . $e->getMessage());
            throw $e;
        }
    }
}
