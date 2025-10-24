<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class LegacyArticlesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $articlesPath = base_path('articles');
        $jsonFiles = [
            'HIST-V15.json',
            'HIST-V16.json', 
            'HIST-V17.json',
            'HIST-V18.json',
            'HIST-V19.json',
            'HIST-V20.json',
            'HIST-V21.json',
            'HIST-V22.json'
        ];

        $totalRecords = 0;

        foreach ($jsonFiles as $fileName) {
            $filePath = $articlesPath . '/' . $fileName;
            
            if (!File::exists($filePath)) {
                $this->command->warn("File not found: {$fileName}");
                continue;
            }

            $this->command->info("Processing file: {$fileName}");
            
            $jsonContent = File::get($filePath);
            $data = json_decode($jsonContent, true);

            if (!$data || !isset($data['Feuil1'])) {
                $this->command->error("Invalid JSON structure in: {$fileName}");
                continue;
            }

            $articles = $data['Feuil1'];
            $batchSize = 1000;
            $chunks = array_chunk($articles, $batchSize);

            foreach ($chunks as $chunk) {
                $insertData = [];
                
                foreach ($chunk as $article) {
                    $insertData[] = [
                        'dref' => $article['DREF'] ?? null,
                        'foret' => $article['FORET'] ?? null,
                        'province' => $article['PROVINCE'] ?? null,
                        'date' => $article['DATE'] ?? null,
                        'essence' => $article['ESSENCE'] ?? null,
                        'intervent' => $article['INTERVENT'] ?? null,
                        'surface' => isset($article['SURFACE']) ? (float) $article['SURFACE'] : null,
                        'bom3' => isset($article['BOM3']) ? (float) $article['BOM3'] : null,
                        'bim3' => isset($article['BIM3']) ? (float) $article['BIM3'] : null,
                        'bfst' => isset($article['BFST']) ? (float) $article['BFST'] : null,
                        'lcst' => isset($article['LCST']) ? (float) $article['LCST'] : null,
                        'ett' => isset($article['ETT']) ? (float) $article['ETT'] : null,
                        'pst' => isset($article['PST']) ? (float) $article['PST'] : null,
                        'acheteur' => $article['ACHETEUR'] ?? null,
                        'ppdh' => isset($article['PPDH']) ? (float) $article['PPDH'] : null,
                        'dr' => $article['DR'] ?? null,
                        'source_file' => $fileName,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                DB::table('legacy_articles')->insert($insertData);
                $totalRecords += count($insertData);
            }

            $this->command->info("Imported " . count($articles) . " records from {$fileName}");
        }

        $this->command->info("Total records imported: {$totalRecords}");
    }
}
