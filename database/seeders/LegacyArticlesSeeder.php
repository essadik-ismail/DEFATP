<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class LegacyArticlesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataArticlesPath = base_path('data/articles');
        
        if (!File::exists($dataArticlesPath)) {
            $this->command->error("Directory not found: data/articles");
            return;
        }

        // Get all JSON files from data/articles directory
        $jsonFiles = File::glob($dataArticlesPath . DIRECTORY_SEPARATOR . '*.json');
        
        if (empty($jsonFiles)) {
            $this->command->warn("No JSON files found in data/articles");
            return;
        }

        $totalRecords = 0;

        foreach ($jsonFiles as $filePath) {
            $fileName = basename($filePath);
            $this->command->info("Processing file: data/articles/{$fileName}");
            
            $jsonContent = File::get($filePath);
            $data = json_decode($jsonContent, true);

            if (!$data) {
                $this->command->error("Invalid JSON in: {$fileName}");
                continue;
            }

            // Handle different JSON structures
            $articles = [];
            if (isset($data['Feuil1']) && is_array($data['Feuil1'])) {
                $articles = $data['Feuil1'];
            } elseif (is_array($data) && array_is_list($data)) {
                $articles = $data;
            } else {
                $this->command->warn("Unrecognized JSON structure in: {$fileName}");
                continue;
            }

            if (empty($articles)) {
                $this->command->warn("No articles found in: {$fileName}");
                continue;
            }

            $batchSize = 1000;
            $chunks = array_chunk($articles, $batchSize);

            foreach ($chunks as $chunk) {
                $insertData = [];
                
                foreach ($chunk as $article) {
                    // Parse date - handle Excel serial numbers and YYMMDD format
                    $date = null;
                    if (!empty($article['DATE'])) {
                        $dateValue = $article['DATE'];
                        
                        // Check if it's a string in YYMMDD format (6 digits)
                        if (is_string($dateValue) && preg_match('/^\d{6}$/', $dateValue)) {
                            // Format: YYMMDD (e.g., "010103" = 2001-01-03, "970103" = 1997-01-03)
                            $yy = (int) substr($dateValue, 0, 2);
                            $month = (int) substr($dateValue, 2, 2);
                            $day = (int) substr($dateValue, 4, 2);
                            
                            // Determine century: if YY >= 30, it's 1900s, otherwise 2000s
                            // e.g., "97" = 1997, "01" = 2001
                            $year = ($yy >= 30) ? (1900 + $yy) : (2000 + $yy);
                            
                            try {
                                $date = Carbon::create($year, $month, $day);
                                
                                // If the resulting date is >= 2016, force it to 1900s
                                // But if the result is < 1950, it's likely incorrect, so use 2000s instead
                                // All dates must be between 1950 and 2015
                                if ($date->year >= 2016) {
                                    $yy = $date->year % 100;
                                    $year = 1900 + $yy; // Force to 1900s
                                    // If still too old (< 1950), use 2000s instead
                                    if ($year < 1950) {
                                        $year = 2000 + $yy;
                                        // But if still >= 2016, force to 1900s again
                                        if ($year >= 2016) {
                                            $year = 1900 + $yy;
                                        }
                                    }
                                    $date = Carbon::create($year, $date->month, $date->day);
                                }
                                // Also check if date is too old (< 1950)
                                if ($date && $date->year < 1950) {
                                    $yy = $date->year % 100;
                                    $year = 2000 + $yy; // Try 2000s
                                    // If still >= 2016, use 1900s but ensure >= 1950
                                    if ($year >= 2016) {
                                        $year = 1900 + $yy;
                                    }
                                    // Ensure minimum year is 1950
                                    if ($year < 1950) {
                                        $year = 1950;
                                    }
                                    $date = Carbon::create($year, $date->month, $date->day);
                                }
                            } catch (\Exception $e) {
                                // Invalid date, keep as null
                                $date = null;
                            }
                        } elseif (is_numeric($dateValue) && $dateValue > 25569 && $dateValue < 2958466) {
                            // Excel date serial number
                            try {
                                if (class_exists('\PhpOffice\PhpSpreadsheet\Shared\Date')) {
                                    $date = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float) $dateValue));
                                } else {
                                    $date = Carbon::createFromTimestamp(($dateValue - 25569) * 86400);
                                }
                            } catch (\Exception $e) {
                                // If PhpSpreadsheet is not available, try manual conversion
                                try {
                                    $date = Carbon::createFromTimestamp(($dateValue - 25569) * 86400);
                                } catch (\Exception $e2) {
                                    $date = null;
                                }
                            }
                        } else {
                            // Try to parse as date string
                            try {
                                $date = Carbon::parse($dateValue);
                                
                                // If year is >= 2016, force it to 1900s
                                // But if the result is < 1950, it's likely incorrect, so use 2000s instead
                                // All dates must be between 1950 and 2015
                                // e.g., "2256-03-30" should be "1956-03-30", "2037-03-27" should be "1937-03-27", "2016-01-01" should be "1916-01-01", "2029-12-14" should be "1929-12-14"
                                if ($date->year >= 2016) {
                                    $yy = $date->year % 100;
                                    $year = 1900 + $yy; // Force to 1900s
                                    // If still too old (< 1950), use 2000s instead
                                    if ($year < 1950) {
                                        $year = 2000 + $yy;
                                        // But if still >= 2016, force to 1900s again
                                        if ($year >= 2016) {
                                            $year = 1900 + $yy;
                                        }
                                    }
                                    $date = Carbon::create($year, $date->month, $date->day);
                                }
                                // Also check if date is too old (< 1950)
                                if ($date && $date->year < 1950) {
                                    $yy = $date->year % 100;
                                    $year = 2000 + $yy; // Try 2000s
                                    // If still >= 2016, use 1900s but ensure >= 1950
                                    if ($year >= 2016) {
                                        $year = 1900 + $yy;
                                    }
                                    // Ensure minimum year is 1950
                                    if ($year < 1950) {
                                        $year = 1950;
                                    }
                                    $date = Carbon::create($year, $date->month, $date->day);
                                }
                            } catch (\Exception $e) {
                                // Keep as null if parsing fails
                            }
                        }
                    }

                    $insertData[] = [
                        'dref' => $article['DREF'] ?? null,
                        'foret' => $article['FORET'] ?? null,
                        'province' => $article['PROVINCE'] ?? null,
                        'date' => $date ? $date->format('Y-m-d') : null,
                        'essence' => isset($article['ESSENCE']) ? mb_substr((string) $article['ESSENCE'], 0, 50) : null,
                        'intervent' => isset($article['INTERVENT']) ? mb_substr((string) $article['INTERVENT'], 0, 50) : null,
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
                        'source_file' => 'data/articles/' . $fileName,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                DB::table('legacy_articles')->insert($insertData);
                $totalRecords += count($insertData);
            }

            $this->command->info("Imported " . count($articles) . " records from data/articles/{$fileName}");
        }

        $this->command->info("Total records imported: {$totalRecords}");
    }
}
