<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\NatureDeCoupe;

class NatureDeCoupeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = <<<'JSON'
[
 {
  "N°": "1",
  "Nature de coupe": "CBE"
 },
 {
  "N°": "2",
  "Nature de coupe": "CCN"
 },
 {
  "N°": "3",
  "Nature de coupe": "EBD"
 },
 {
  "N°": "4",
  "Nature de coupe": "Coupe à Ras du Sol"
 },
 {
  "N°": "5",
  "Nature de coupe": "Ramassage de BMG"
 },
 {
  "N°": "8",
  "Nature de coupe": "CBE et coupe en tétard"
 },
 {
  "N°": "9",
  "Nature de coupe": "Récolte de feuilles du romarin"
 },
 {
  "N°": "10",
  "Nature de coupe": "EP du romarin"
 },
 {
  "N°": "12",
  "Nature de coupe": "coupe sélective"
 },
 {
  "N°": "13",
  "Nature de coupe": "Enlèvement de charbon"
 },
 {
  "N°": "14",
  "Nature de coupe": "Récolte de gousse de caroube"
 },
 {
  "N°": "15",
  "Nature de coupe": "Eclaircie Géométrique"
 },
 {
  "N°": "16",
  "Nature de coupe": "Récolte des jeunes pousses"
 },
 {
  "N°": "17",
  "Nature de coupe": "En têtard"
 },
 {
  "N°": "18",
  "Nature de coupe": "Eclaircie"
 },
 {
  "N°": "19",
  "Nature de coupe": "Nettoiement"
 },
 {
  "N°": "20",
  "Nature de coupe": "Récolte de Lichen"
 },
 {
  "N°": "21",
  "Nature de coupe": "Récolte des cônes"
 }
]
JSON;

        $rows = json_decode($json, true) ?? [];

        foreach ($rows as $row) {
            $label = isset($row['Nature de coupe']) ? trim((string) $row['Nature de coupe']) : null;
            if (!$label) {
                continue;
            }

            NatureDeCoupe::firstOrCreate(
                ['nature_de_coupe' => $label],
                ['is_deleted' => false]
            );
        }
    }

    /**
     * Load nature de coupes from JSON file
     */
    private function loadFromJson(string $jsonPath): void
    {
        $json = file_get_contents($jsonPath);
        $data = json_decode($json, true) ?? [];

        if (empty($data)) {
            $this->command->warn('Nature de Coupe.json file is empty or invalid JSON');
            return;
        }

        $this->command->info('Loading nature de coupes from Nature de Coupe.json');

        foreach ($data as $row) {
            $label = $row['Nature de coupe'] ?? $row['nature_de_coupe'] ?? null;
            if (!$label) {
                continue;
            }

            NatureDeCoupe::firstOrCreate(
                ['nature_de_coupe' => trim($label)],
                ['is_deleted' => false]
            );
        }

        $this->command->info('Nature de coupes seeded successfully!');
    }
}
