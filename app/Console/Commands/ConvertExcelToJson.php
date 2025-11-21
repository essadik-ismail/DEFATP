<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception as ReaderException;

class ConvertExcelToJson extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'excel:to-json {path?} {--output=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert Excel files to JSON format';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $path = $this->argument('path') ?? base_path('data');
        
        if (!File::exists($path)) {
            $this->error("Path does not exist: {$path}");
            return 1;
        }

        $this->info("Scanning for Excel files in: {$path}");
        
        // Find all Excel files recursively
        $excelFiles = $this->findExcelFiles($path);
        
        if (empty($excelFiles)) {
            $this->warn("No Excel files found in: {$path}");
            return 0;
        }

        $this->info("Found " . count($excelFiles) . " Excel file(s) to convert");
        
        $converted = 0;
        $failed = 0;

        foreach ($excelFiles as $excelFile) {
            try {
                $this->line("Converting: " . basename($excelFile));
                
                $jsonData = $this->convertExcelToJson($excelFile);
                
                if ($jsonData === null) {
                    $this->warn("  Skipped (empty or invalid)");
                    continue;
                }

                // Determine output path
                $outputPath = $this->option('output');
                if ($outputPath) {
                    $jsonPath = $outputPath . '/' . basename($excelFile, '.xlsx') . '.json';
                } else {
                    $jsonPath = dirname($excelFile) . '/' . basename($excelFile, '.xlsx') . '.json';
                }

                // Create directory if it doesn't exist
                $jsonDir = dirname($jsonPath);
                if (!File::exists($jsonDir)) {
                    File::makeDirectory($jsonDir, 0755, true);
                }

                // Write JSON file
                File::put($jsonPath, json_encode($jsonData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
                
                $this->info("  ✓ Converted to: " . basename($jsonPath) . " (" . count($jsonData) . " rows)");
                $converted++;
                
            } catch (\Exception $e) {
                $this->error("  ✗ Failed: " . $e->getMessage());
                $failed++;
            }
        }

        $this->newLine();
        $this->info("Conversion complete!");
        $this->info("  Converted: {$converted}");
        if ($failed > 0) {
            $this->warn("  Failed: {$failed}");
        }

        return 0;
    }

    /**
     * Find all Excel files recursively
     */
    private function findExcelFiles(string $path): array
    {
        $files = [];
        
        if (File::isFile($path) && $this->isExcelFile($path)) {
            return [$path];
        }
        
        if (File::isDirectory($path)) {
            $items = File::allFiles($path);
            foreach ($items as $item) {
                if ($this->isExcelFile($item->getPathname())) {
                    $files[] = $item->getPathname();
                }
            }
        }
        
        return $files;
    }

    /**
     * Check if file is an Excel file
     */
    private function isExcelFile(string $path): bool
    {
        $extension = strtolower(File::extension($path));
        return in_array($extension, ['xlsx', 'xls']);
    }

    /**
     * Convert Excel file to JSON array
     */
    private function convertExcelToJson(string $excelPath): ?array
    {
        try {
            // Increase memory limit for large files
            ini_set('memory_limit', '1024M');
            
            $reader = IOFactory::createReaderForFile($excelPath);
            $reader->setReadDataOnly(true);
            $reader->setReadEmptyCells(false);
            $spreadsheet = $reader->load($excelPath);
            
            $data = [];
            $sheetCount = $spreadsheet->getSheetCount();
            
            // If multiple sheets, wrap in object with sheet names
            if ($sheetCount > 1) {
                $result = [];
                for ($i = 0; $i < $sheetCount; $i++) {
                    $sheet = $spreadsheet->getSheet($i);
                    $sheetName = $sheet->getTitle();
                    $sheetData = $this->sheetToArray($sheet);
                    if (!empty($sheetData)) {
                        $result[$sheetName] = $sheetData;
                    }
                }
                return !empty($result) ? $result : null;
            } else {
                // Single sheet - return array directly
                $sheet = $spreadsheet->getActiveSheet();
                $data = $this->sheetToArray($sheet);
                return !empty($data) ? $data : null;
            }
            
        } catch (ReaderException $e) {
            throw new \Exception("Failed to read Excel file: " . $e->getMessage());
        }
    }

    /**
     * Convert sheet to array with headers
     */
    private function sheetToArray($sheet): array
    {
        $data = [];
        $highestRow = $sheet->getHighestRow();
        
        // Get the highest column that has data in row 1 (headers) or any data row
        $highestDataColumn = $sheet->getHighestDataColumn(1);
        $highestColumn = $sheet->getHighestColumn();
        
        // Convert to column indices to find the maximum
        $highestDataColIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestDataColumn);
        $highestColIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);
        $maxColumnIndex = max($highestDataColIndex, $highestColIndex);
        
        // Also check data rows to find the actual maximum column
        for ($row = 2; $row <= min($highestRow, 100); $row++) { // Check first 100 rows
            $rowHighestCol = $sheet->getHighestDataColumn($row);
            if ($rowHighestCol) {
                $rowColIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($rowHighestCol);
                $maxColumnIndex = max($maxColumnIndex, $rowColIndex);
            }
        }
        
        if ($highestRow < 2) {
            return []; // No data rows
        }
        
        // Get headers from first row - include ALL columns
        $headers = [];
        for ($colIndex = 1; $colIndex <= $maxColumnIndex; $colIndex++) {
            $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
            $cellValue = $sheet->getCell($col . '1')->getValue();
            $header = $cellValue !== null ? trim((string) $cellValue) : '';
            
            // Use column letter as fallback if header is empty
            if (empty($header)) {
                $header = 'Column_' . $col;
            }
            
            $headers[$colIndex - 1] = $header;
        }
        
        if (empty($headers)) {
            return []; // No headers found
        }
        
        // Get data rows
        for ($row = 2; $row <= $highestRow; $row++) {
            $rowData = [];
            $hasData = false;
            
            for ($colIndex = 1; $colIndex <= $maxColumnIndex; $colIndex++) {
                $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
                $header = $headers[$colIndex - 1];
                $cell = $sheet->getCell($col . $row);
                $cellValue = $cell->getValue();
                
                // Handle formulas
                if ($cellValue instanceof \PhpOffice\PhpSpreadsheet\Cell\Cell) {
                    $cellValue = $cellValue->getCalculatedValue();
                }
                
                // For DATE column, try to preserve formatted value to keep leading zeros
                if (strtoupper($header) === 'DATE') {
                    // First check if it's stored as text (preserves leading zeros)
                    if (is_string($cellValue) && preg_match('/^0+\d+$/', $cellValue)) {
                        $rowData[$header] = $cellValue;
                        if ($cellValue !== null && $cellValue !== '') {
                            $hasData = true;
                        }
                        continue;
                    }
                    
                    // If it's numeric, try to get formatted value to preserve leading zeros
                    if (is_numeric($cellValue)) {
                        $formattedValue = $cell->getFormattedValue();
                        // Check if formatted value has leading zeros (like 000106)
                        if (is_string($formattedValue) && preg_match('/^0+\d+$/', $formattedValue)) {
                            // Verify it's different from the numeric value (has leading zeros)
                            $numericString = (string)(int)$cellValue;
                            if ($formattedValue !== $numericString && strlen($formattedValue) > strlen($numericString)) {
                                $rowData[$header] = $formattedValue;
                                if ($formattedValue !== null && $formattedValue !== '') {
                                    $hasData = true;
                                }
                                continue;
                            }
                        }
                    }
                }
                
                // Convert to appropriate type
                $value = $this->cleanCellValue($cellValue, $header);
                
                // Include value even if null/empty to preserve column structure
                $rowData[$header] = $value;
                
                if ($value !== null && $value !== '') {
                    $hasData = true;
                }
            }
            
            if ($hasData) {
                $data[] = $rowData;
            }
        }
        
        return $data;
    }

    /**
     * Clean and convert cell value
     */
    private function cleanCellValue($value, $header = null)
    {
        if ($value === null) {
            return null;
        }
        
        // Handle dates
        if ($value instanceof \DateTime) {
            return $value->format('Y-m-d');
        }
        
        // Handle numeric strings
        if (is_numeric($value)) {
            // Check if it's a date serial number (Excel dates)
            // Only convert if it's clearly an Excel date serial (not a custom date format like 000106)
            if ($value > 25569 && $value < 2958466 && strtoupper($header) !== 'DATE') {
                try {
                    $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
                    return $date->format('Y-m-d');
                } catch (\Exception $e) {
                    // Not a date, return as number
                }
            }
            
            // For DATE column, preserve as string to keep leading zeros if present
            if (strtoupper($header) === 'DATE') {
                // Return as string to preserve leading zeros
                return (string) $value;
            }
            
            // Return as float if it has decimals, otherwise as int
            return (float) $value == (int) $value ? (int) $value : (float) $value;
        }
        
        // Return as string, trimmed
        $stringValue = trim((string) $value);
        return $stringValue === '' ? null : $stringValue;
    }
}
