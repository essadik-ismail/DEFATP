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
        $this->command->info('Starting LegacyArticlesSeeder...');
        
        // Clear existing legacy articles
        $this->clearLegacyArticles();
        
        // Seed CESSION files (CESSION-00 to CESSION-99)
        $cessionRecords = $this->seedCessionFiles();
        
        // Seed HIST files (HIST-V15 to HIST-V22)
        $histRecords = $this->seedHistFiles();
        
        $totalRecords = $cessionRecords + $histRecords;
        $this->command->info("Total records imported: {$totalRecords}");
        
        // Normalize all dates to YYMMDD format
        $this->normalizeAllDates();
    }

    /**
     * Clear all existing legacy articles from the database
     */
    private function clearLegacyArticles(): void
    {
        $this->command->info('Clearing existing legacy articles...');
        
        $count = DB::table('legacy_articles')->count();
        
        if ($count > 0) {
            DB::table('legacy_articles')->truncate();
            $this->command->info("Cleared {$count} existing legacy article records.");
        } else {
            $this->command->info('No existing legacy articles to clear.');
        }
    }

    /**
     * Seed CESSION files (CESSION-00 to CESSION-99)
     */
    private function seedCessionFiles(): int
    {
        $this->command->info('Processing CESSION files (CESSION-00 to CESSION-99)...');
        
        $dataArticlesPath = base_path('data/articles');
        $totalRecords = 0;

        // Get CESSION files (CESSION-00 to CESSION-99)
        for ($i = 0; $i <= 99; $i++) {
            $fileName = sprintf('CESSION-%02d.json', $i);
            $filePath = $dataArticlesPath . DIRECTORY_SEPARATOR . $fileName;
            
            if (!File::exists($filePath)) {
                continue; // Skip if file doesn't exist
            }

            $records = $this->processFile($filePath, $fileName);
            $totalRecords += $records;
        }

        $this->command->info("Total CESSION records imported: {$totalRecords}");
        return $totalRecords;
    }

    /**
     * Seed HIST files (HIST-V15 to HIST-V22)
     */
    private function seedHistFiles(): int
    {
        $this->command->info('Processing HIST files (HIST-V15 to HIST-V22)...');
        
        $dataArticlesPath = base_path('data/articles');
        $totalRecords = 0;

        // Get HIST files (HIST-V15 to HIST-V22)
        for ($i = 15; $i <= 22; $i++) {
            $fileName = sprintf('HIST-V%d.json', $i);
            $filePath = $dataArticlesPath . DIRECTORY_SEPARATOR . $fileName;
            
            if (!File::exists($filePath)) {
                continue; // Skip if file doesn't exist
            }

            $records = $this->processFile($filePath, $fileName);
            $totalRecords += $records;
        }

        $this->command->info("Total HIST records imported: {$totalRecords}");
        return $totalRecords;
    }

    /**
     * Process a single JSON file and import its data
     */
    private function processFile(string $filePath, string $fileName): int
    {
        $this->command->info("Processing file: data/articles/{$fileName}");
        
        $jsonContent = File::get($filePath);
        $data = json_decode($jsonContent, true);

        if (!$data) {
            $this->command->error("Invalid JSON in: {$fileName}");
            return 0;
        }

        // Handle different JSON structures
        $articles = [];
        if (isset($data['Feuil1']) && is_array($data['Feuil1'])) {
            $articles = $data['Feuil1'];
        } elseif (is_array($data) && array_is_list($data)) {
            $articles = $data;
        } else {
            $this->command->warn("Unrecognized JSON structure in: {$fileName}");
            return 0;
        }

        if (empty($articles)) {
            $this->command->warn("No articles found in: {$fileName}");
            return 0;
        }

        $batchSize = 1000;
        $chunks = array_chunk($articles, $batchSize);
        $totalRecords = 0;

        foreach ($chunks as $chunk) {
            $insertData = [];
            
            foreach ($chunk as $article) {
                // Parse and format date as YYMMDD string (matching migration comment)
                $dateValue = $article['DATE'] ?? null;
                $dateStr = $this->formatDateAsYYMMDD($dateValue);
                
                // Parse PPDH - can be a date string or a number
                $ppdh = $this->parsePPDH($article['PPDH'] ?? null);

                $insertData[] = [
                    'dref' => $article['DREF'] ?? null,
                    'foret' => $article['FORET'] ?? null,
                    'province' => $article['PROVINCE'] ?? null,
                    'date' => $dateStr,
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
                    'ppdh' => $ppdh,
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
        return $totalRecords;
    }

    /**
     * Format date as YYMMDD string (matching migration comment)
     * Handles various input formats: YYMMDD string/number, Excel serial, date strings
     */
    private function formatDateAsYYMMDD($dateValue): ?string
    {
        if (empty($dateValue) && $dateValue !== '0' && $dateValue !== 0) {
            return null;
        }
        
        // Convert to string first to handle numeric values that might be strings in JSON
        $dateStr = (string) $dateValue;
        
        // Check if it's already in YYMMDD format (6 digits, possibly with leading zeros)
        // This handles both "000106" (string) and 106 (number that should be "000106")
        if (preg_match('/^\d{1,6}$/', $dateStr)) {
            // Pad with leading zeros to ensure 6 digits
            $dateStr = str_pad($dateStr, 6, '0', STR_PAD_LEFT);
            
            // Now check if it's exactly 6 digits
            if (preg_match('/^\d{6}$/', $dateStr)) {
                // Validate it's a valid date
                $yy = (int) substr($dateStr, 0, 2);
                $month = (int) substr($dateStr, 2, 2);
                $day = (int) substr($dateStr, 4, 2);
                
                // Validate month and day
                if ($month >= 1 && $month <= 12 && $day >= 1 && $day <= 31) {
                    // Determine century based on YY value
                    // If YY >= 90, it means 19YY (e.g., "90" = 1990, "99" = 1999)
                    // If YY < 90, it means 20YY (e.g., "00" = 2000, "15" = 2015)
                    if ($yy >= 90) {
                        $year = 1900 + $yy; // 90-99 -> 1990-1999
                    } else {
                        $year = 2000 + $yy; // 00-15 -> 2000-2015
                    }
                    
                    try {
                        $date = Carbon::create($year, $month, $day);
                        // Validate the date (e.g., check if Feb 30 is invalid)
                        if ($date->month == $month && $date->day == $day) {
                            return $dateStr; // Return as-is in YYMMDD format
                        }
                    } catch (\Exception $e) {
                        // Invalid date, continue to other parsing methods
                    }
                }
            }
        }
        
        // Try to parse as a date and convert to YYMMDD
        $parsedDate = $this->parseDate($dateValue);
        if ($parsedDate) {
            $year = $parsedDate->year;
            $month = $parsedDate->month;
            $day = $parsedDate->day;
            
            // Convert to YYMMDD format
            // If year >= 2000, use last 2 digits (00-99 for 2000-2099)
            // If year < 2000, use last 2 digits (00-99 for 1900-1999)
            $yy = $year % 100;
            return sprintf('%02d%02d%02d', $yy, $month, $day);
        }
        
        return null;
    }

    /**
     * Parse date from various formats (YYMMDD, Excel serial, date string)
     */
    private function parseDate($dateValue): ?Carbon
    {
        if (empty($dateValue) && $dateValue !== '0' && $dateValue !== 0) {
            return null;
        }
        
        // Convert to string first to handle numeric values that might be strings in JSON
        $dateStr = (string) $dateValue;
        
        // Check if it's a string in YYMMDD format (6 digits, possibly with leading zeros)
        // This handles both "000106" (string) and 106 (number that should be "000106")
        if (preg_match('/^\d{1,6}$/', $dateStr)) {
            // Pad with leading zeros to ensure 6 digits
            $dateStr = str_pad($dateStr, 6, '0', STR_PAD_LEFT);
            
            // Now check if it's exactly 6 digits
            if (preg_match('/^\d{6}$/', $dateStr)) {
                // Format: YYMMDD
                // Logic: If YY >= 90, it means 19YY (e.g., "90" = 1990, "99" = 1999)
                //        If YY < 90, it means 20YY (e.g., "00" = 2000, "89" = 2089)
                $yy = (int) substr($dateStr, 0, 2);
                $month = (int) substr($dateStr, 2, 2);
                $day = (int) substr($dateStr, 4, 2);
                
                // Validate month and day
                if ($month < 1 || $month > 12 || $day < 1 || $day > 31) {
                    return null;
                }
                
                // Determine century based on YY value
                if ($yy >= 90) {
                    $year = 1900 + $yy; // 90-99 -> 1990-1999
                } else {
                    $year = 2000 + $yy; // 00-89 -> 2000-2089
                }
                
                try {
                    $date = Carbon::create($year, $month, $day);
                    // Validate the date (e.g., check if Feb 30 is invalid)
                    if ($date->month != $month || $date->day != $day) {
                        return null;
                    }
                    return $date;
                } catch (\Exception $e) {
                    return null;
                }
            }
        }
        
        // Check if it's an Excel date serial number (large number)
        if (is_numeric($dateValue) && $dateValue > 25569 && $dateValue < 2958466) {
            try {
                if (class_exists('\PhpOffice\PhpSpreadsheet\Shared\Date')) {
                    return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float) $dateValue));
                } else {
                    return Carbon::createFromTimestamp(($dateValue - 25569) * 86400);
                }
            } catch (\Exception $e) {
                try {
                    return Carbon::createFromTimestamp(($dateValue - 25569) * 86400);
                } catch (\Exception $e2) {
                    return null;
                }
            }
        }
        
        // Try to parse as date string (last resort)
        try {
            return Carbon::parse($dateValue);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Parse PPDH field - can be a date string or a numeric value
     */
    private function parsePPDH($ppdhValue): ?float
    {
        if (empty($ppdhValue) && $ppdhValue !== '0' && $ppdhValue !== 0) {
            return null;
        }
        
        // If it's already numeric, return as float
        if (is_numeric($ppdhValue)) {
            return (float) $ppdhValue;
        }
        
        // If it's a date string (like "2119-01-11"), try to parse it
        // This might be an Excel date serial that was converted to string
        if (is_string($ppdhValue)) {
            // Try to parse as date and convert to Excel serial number
            try {
                $date = Carbon::parse($ppdhValue);
                // Convert to Excel serial number (days since 1900-01-01)
                $excelSerial = ($date->timestamp / 86400) + 25569;
                return (float) $excelSerial;
            } catch (\Exception $e) {
                // If parsing fails, try to extract numeric value from string
                if (preg_match('/[\d.]+/', $ppdhValue, $matches)) {
                    return (float) $matches[0];
                }
            }
        }
        
        return null;
    }

    /**
     * Normalize all legacy article dates to YYMMDD format
     * This ensures consistency across all records in the database
     */
    private function normalizeAllDates(): void
    {
        $this->command->info('Normalizing all legacy article dates to YYMMDD format...');
        
        $batchSize = 1000;
        $totalUpdated = 0;
        $totalProcessed = 0;
        $updates = [];
        
        // Process in batches to avoid memory issues
        DB::table('legacy_articles')
            ->whereNotNull('date')
            ->orderBy('id')
            ->chunk($batchSize, function ($articles) use (&$totalUpdated, &$totalProcessed, &$updates) {
                foreach ($articles as $article) {
                    $currentDate = $article->date;
                    $normalizedDate = $this->normalizeDateValue($currentDate);
                    
                    if ($normalizedDate !== null && $normalizedDate !== $currentDate) {
                        $updates[$article->id] = $normalizedDate;
                    }
                    $totalProcessed++;
                }
                
                // Batch update records
                if (!empty($updates)) {
                    foreach ($updates as $id => $normalizedDate) {
                        DB::table('legacy_articles')
                            ->where('id', $id)
                            ->update(['date' => $normalizedDate]);
                        $totalUpdated++;
                    }
                    $updates = []; // Clear the batch
                }
            });
        
        // Handle any remaining updates
        if (!empty($updates)) {
            foreach ($updates as $id => $normalizedDate) {
                DB::table('legacy_articles')
                    ->where('id', $id)
                    ->update(['date' => $normalizedDate]);
                $totalUpdated++;
            }
        }
        
        $this->command->info("Date normalization complete: {$totalProcessed} records processed, {$totalUpdated} records updated");
    }

    /**
     * Normalize a date value to YYMMDD format
     * Handles various formats that might exist in the database
     */
    private function normalizeDateValue($dateValue): ?string
    {
        if (empty($dateValue)) {
            return null;
        }
        
        // If already in YYMMDD format (6 digits), validate and return
        $dateStr = (string) $dateValue;
        if (preg_match('/^\d{6}$/', $dateStr)) {
            // Validate it's a valid date
            $yy = (int) substr($dateStr, 0, 2);
            $month = (int) substr($dateStr, 2, 2);
            $day = (int) substr($dateStr, 4, 2);
            
            if ($month >= 1 && $month <= 12 && $day >= 1 && $day <= 31) {
                // Determine century based on YY value
                // If YY >= 90, it means 19YY (e.g., "90" = 1990, "99" = 1999)
                // If YY < 90, it means 20YY (e.g., "00" = 2000, "89" = 2089)
                if ($yy >= 90) {
                    $year = 1900 + $yy; // 90-99 -> 1990-1999
                } else {
                    $year = 2000 + $yy; // 00-89 -> 2000-2089
                }
                
                try {
                    $date = Carbon::create($year, $month, $day);
                    if ($date->month == $month && $date->day == $day) {
                        return $dateStr; // Already in correct format
                    }
                } catch (\Exception $e) {
                    // Invalid date, try to parse and convert
                }
            }
        }
        
        // Try to parse the date value and convert to YYMMDD
        $parsedDate = $this->parseDate($dateValue);
        if ($parsedDate) {
            $year = $parsedDate->year;
            $month = $parsedDate->month;
            $day = $parsedDate->day;
            
            // Convert to YYMMDD format
            $yy = $year % 100;
            return sprintf('%02d%02d%02d', $yy, $month, $day);
        }
        
        // If we can't parse it, return null
        return null;
    }
}
