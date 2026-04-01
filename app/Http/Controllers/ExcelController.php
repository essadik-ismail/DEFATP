<?php

namespace App\Http\Controllers;

use App\Exports\ArticlesExport;
use App\Exports\EssencesExport;
use App\Exports\ForetsExport;
use App\Exports\NatureDeCoupesExport;
use App\Exports\SituationAdministrativesExport;
use App\Exports\ExploitantsExport;
use App\Imports\ArticlesImport;
use App\Imports\EssencesImport;
use App\Imports\ForetsImport;
use App\Imports\NatureDeCoupesImport;
use App\Imports\SituationAdministrativesImport;
use App\Imports\ExploitantsImport;
use App\Services\ActivityLogger;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class ExcelController extends Controller
{
    /**
     * Mapping configuration for exports and imports
     */
    private const DATA_TYPES = [
        'articles' => [
            'export_class' => ArticlesExport::class,
            'import_class' => ArticlesImport::class,
            'name' => 'Articles',
            'filename' => 'articles',
            'keywords' => ['articles', 'article'],
        ],
        'essences' => [
            'export_class' => EssencesExport::class,
            'import_class' => EssencesImport::class,
            'name' => 'Essences',
            'filename' => 'essences',
            'keywords' => ['essences', 'essence'],
        ],
        'forets' => [
            'export_class' => ForetsExport::class,
            'import_class' => ForetsImport::class,
            'name' => 'Forêts',
            'filename' => 'forets',
            'keywords' => ['forets', 'foret', 'forêts', 'forêt'],
        ],
        'nature-de-coupes' => [
            'export_class' => NatureDeCoupesExport::class,
            'import_class' => NatureDeCoupesImport::class,
            'name' => 'Natures de Coupes',
            'filename' => 'nature_de_coupes',
            'keywords' => ['nature', 'coupe', 'coupes'],
        ],
        'situation-administratives' => [
            'export_class' => SituationAdministrativesExport::class,
            'import_class' => SituationAdministrativesImport::class,
            'name' => 'Situations Administratives',
            'filename' => 'situation_administratives',
            'keywords' => ['situation', 'administrative', 'administratives'],
        ],
        'exploitants' => [
            'export_class' => ExploitantsExport::class,
            'import_class' => ExploitantsImport::class,
            'name' => 'Exploitants',
            'filename' => 'exploitants',
            'keywords' => ['exploitants', 'exploitant'],
        ],
    ];

    /**
     * Display the Excel import/export management page
     */
    public function index(): View
    {
        ActivityLogger::log('view', 'Accès à la gestion des imports/exports Excel', null);

        $dataTypes = self::DATA_TYPES;

        return view('excel.index', compact('dataTypes'));
    }

    /**
     * Export all data types in a ZIP file
     */
    public function exportAll()
    {
        ActivityLogger::logExport('Toutes les données', 'ZIP (Excel)', request());
        
        $timestamp = date('Y-m-d_H-i-s');
        $zipName = "export_complet_{$timestamp}.zip";
        $zipPath = storage_path("app/temp/{$zipName}");

        // Ensure temp directory exists
        if (!Storage::exists('temp')) {
            Storage::makeDirectory('temp');
        }

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
            return redirect()->back()->with('error', 'Erreur lors de la création du fichier ZIP.');
        }

        try {
            foreach (self::DATA_TYPES as $key => $config) {
                $exportClass = $config['export_class'];
                $filename = "{$config['filename']}_{$timestamp}.xlsx";
                
                $this->addToZip($zip, new $exportClass(), $filename);
            }

            $zip->close();

            return response()->download($zipPath, $zipName)->deleteFileAfterSend();
        } catch (\Exception $e) {
            $zip->close();
            if (file_exists($zipPath)) {
                unlink($zipPath);
            }
            return redirect()->back()->with('error', 'Erreur lors de l\'export: ' . $e->getMessage());
        }
    }

    /**
     * Add an export to the ZIP archive
     */
    private function addToZip(ZipArchive $zip, $export, string $filename): void
    {
        $tempPath = "temp/{$filename}";
        Excel::store($export, $tempPath);
        $fullPath = storage_path("app/{$tempPath}");
        
        if (file_exists($fullPath)) {
            $zip->addFile($fullPath, $filename);
        }
    }

    /**
     * Import multiple files at once
     */
    public function importAll(Request $request)
    {
        $request->validate([
            'files.*' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        $fileCount = count($request->file('files'));
        ActivityLogger::logImport('Toutes les données', "{$fileCount} fichiers", $fileCount, $request);

        $results = [];
        $files = $request->file('files');

        foreach ($files as $file) {
            $filename = $file->getClientOriginalName();
            $result = $this->processFileImport($file, $filename);
            $results[] = $result;
        }

        return redirect()->back()->with('results', $results);
    }

    /**
     * Process a single file import
     */
    private function processFileImport($file, string $filename): string
    {
        try {
            $dataType = $this->detectDataType($filename);
            
            if (!$dataType) {
                return "Fichier {$filename} non reconnu. Veuillez inclure le nom de la table dans le nom du fichier.";
            }

            $config = self::DATA_TYPES[$dataType];
            $importClass = $config['import_class'];
            $import = new $importClass();
            
            Excel::import($import, $file);
            
            $rowCount = method_exists($import, 'getRowCount') ? $import->getRowCount() : 0;
            $message = $rowCount > 0 
                ? "{$config['name']} importés avec succès depuis {$filename} ({$rowCount} lignes)"
                : "{$config['name']} importés avec succès depuis {$filename}";
            
            return $message;
        } catch (\Exception $e) {
            \Log::error("Import error for {$filename}: " . $e->getMessage());
            return "Erreur lors de l'import de {$filename}: " . $e->getMessage();
        }
    }

    /**
     * Detect data type from filename
     */
    private function detectDataType(string $filename): ?string
    {
        $filenameLower = strtolower($filename);
        
        foreach (self::DATA_TYPES as $key => $config) {
            foreach ($config['keywords'] as $keyword) {
                if (str_contains($filenameLower, strtolower($keyword))) {
                    return $key;
                }
            }
        }
        
        return null;
    }

    /**
     * Generic export method handler
     */
    public function exportArticles(Request $request)
    {
        $filters = $request->only(['foret_id', 'essence_id', 'invandu']);
        return $this->handleExport('articles', $request, $filters);
    }

    public function exportEssences()
    {
        return $this->handleExport('essences', request());
    }

    public function exportForets()
    {
        return $this->handleExport('forets', request());
    }

    public function exportNatureDeCoupes()
    {
        return $this->handleExport('nature-de-coupes', request());
    }

    public function exportSituationAdministratives()
    {
        return $this->handleExport('situation-administratives', request());
    }

    public function exportExploitants()
    {
        return $this->handleExport('exploitants', request());
    }

    /**
     * Generic export handler
     */
    private function handleExport(string $dataType, Request $request, array $filters = [])
    {
        if (!isset(self::DATA_TYPES[$dataType])) {
            return redirect()->back()->with('error', 'Type de données non reconnu.');
        }

        $config = self::DATA_TYPES[$dataType];
        $exportClass = $config['export_class'];
        
        ActivityLogger::logExport($config['name'], 'Excel', $request);
        
        $timestamp = date('Y-m-d_H-i-s');
        $filename = "{$config['filename']}_{$timestamp}.xlsx";
        
        // For ArticlesExport, pass filters if provided
        if ($dataType === 'articles' && !empty($filters)) {
            $export = new $exportClass($filters);
        } else {
            $export = new $exportClass();
        }
        
        return Excel::download($export, $filename);
    }

    /**
     * Generic import method handler
     */
    public function importArticles(Request $request)
    {
        return $this->handleImport('articles', $request);
    }

    public function importEssences(Request $request)
    {
        return $this->handleImport('essences', $request);
    }

    public function importForets(Request $request)
    {
        return $this->handleImport('forets', $request);
    }

    public function importNatureDeCoupes(Request $request)
    {
        return $this->handleImport('nature-de-coupes', $request);
    }

    public function importSituationAdministratives(Request $request)
    {
        return $this->handleImport('situation-administratives', $request);
    }

    public function importExploitants(Request $request)
    {
        return $this->handleImport('exploitants', $request);
    }

    /**
     * Generic import handler
     */
    private function handleImport(string $dataType, Request $request): RedirectResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        if (!isset(self::DATA_TYPES[$dataType])) {
            return redirect()->back()->with('error', 'Type de données non reconnu.');
        }

        try {
            $config = self::DATA_TYPES[$dataType];
            $importClass = $config['import_class'];
            $filename = $request->file('file')->getClientOriginalName();
            
            $import = new $importClass();
            Excel::import($import, $request->file('file'));
            
            $rowCount = method_exists($import, 'getRowCount') ? $import->getRowCount() : 0;
            
            ActivityLogger::logImport(
                $config['name'],
                $filename,
                $rowCount,
                $request
            );
            
            $message = $rowCount > 0 
                ? "{$config['name']} importés avec succès ({$rowCount} lignes)."
                : "{$config['name']} importés avec succès.";
            
            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            \Log::error("Import error for {$dataType}: " . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors de l\'import: ' . $e->getMessage());
        }
    }
}
