<?php

namespace App\Http\Controllers;

use App\Exports\ArticlesExport;
use App\Exports\EssencesExport;
use App\Exports\ForetsExport;
use App\Exports\NatureDeCoupesExport;
use App\Exports\SituationAdministrativesExport;
use App\Exports\ExploitantsExport;
use App\Exports\LocalisationsExport;
use App\Imports\ArticlesImport;
use App\Imports\EssencesImport;
use App\Imports\ForetsImport;
use App\Imports\NatureDeCoupesImport;
use App\Imports\SituationAdministrativesImport;
use App\Imports\ExploitantsImport;
use App\Imports\LocalisationsImport;
use App\Services\ActivityLogger;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use ZipArchive;

class ExcelController extends Controller
{
    public function index(): View
    {
        // Log excel management page view
        ActivityLogger::log('view', 'Accès à la gestion des imports/exports Excel', null);
        
        return view('excel.index');
    }

    // Export all data
    public function exportAll()
    {
        // Log bulk export action
        ActivityLogger::logExport('Toutes les données', 'ZIP (Excel)', request());
        
        $timestamp = date('Y-m-d_H-i-s');
        $zipName = "export_complet_{$timestamp}.zip";
        $zipPath = storage_path("app/public/{$zipName}");

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
            // Export each table
            $this->addToZip($zip, new ArticlesExport(), "articles_{$timestamp}.xlsx");
            $this->addToZip($zip, new EssencesExport(), "essences_{$timestamp}.xlsx");
            $this->addToZip($zip, new ForetsExport(), "forets_{$timestamp}.xlsx");
            $this->addToZip($zip, new NatureDeCoupesExport(), "nature_de_coupes_{$timestamp}.xlsx");
            $this->addToZip($zip, new SituationAdministrativesExport(), "situation_administratives_{$timestamp}.xlsx");
            $this->addToZip($zip, new ExploitantsExport(), "exploitants_{$timestamp}.xlsx");
            $this->addToZip($zip, new LocalisationsExport(), "localisations_{$timestamp}.xlsx");

            $zip->close();

            return response()->download($zipPath)->deleteFileAfterSend();
        }

        return redirect()->back()->with('error', 'Erreur lors de la création du fichier ZIP.');
    }

    private function addToZip($zip, $export, $filename)
    {
        $tempPath = storage_path("app/temp/{$filename}");
        Excel::store($export, "temp/{$filename}");
        $zip->addFile($tempPath, $filename);
    }

    // Import all data
    public function importAll(Request $request)
    {
        $request->validate([
            'files.*' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        // Log bulk import action
        $fileCount = count($request->file('files'));
        ActivityLogger::logImport('Toutes les données', "{$fileCount} fichiers", $fileCount, $request);

        $results = [];
        $files = $request->file('files');

        foreach ($files as $file) {
            $filename = $file->getClientOriginalName();
            $extension = strtolower($file->getClientOriginalExtension());

            try {
                if (str_contains($filename, 'articles') || str_contains($filename, 'Articles')) {
                    Excel::import(new ArticlesImport, $file);
                    $results[] = "Articles importés avec succès depuis {$filename}";
                } elseif (str_contains($filename, 'essences') || str_contains($filename, 'Essences')) {
                    Excel::import(new EssencesImport, $file);
                    $results[] = "Essences importées avec succès depuis {$filename}";
                } elseif (str_contains($filename, 'forets') || str_contains($filename, 'Forets')) {
                    Excel::import(new ForetsImport, $file);
                    $results[] = "Forêts importées avec succès depuis {$filename}";
                } elseif (str_contains($filename, 'nature') || str_contains($filename, 'Nature')) {
                    Excel::import(new NatureDeCoupesImport, $file);
                    $results[] = "Natures de coupe importées avec succès depuis {$filename}";
                } elseif (str_contains($filename, 'situation') || str_contains($filename, 'Situation')) {
                    Excel::import(new SituationAdministrativesImport, $file);
                    $results[] = "Situations administratives importées avec succès depuis {$filename}";
                } elseif (str_contains($filename, 'exploitants') || str_contains($filename, 'Exploitants')) {
                    Excel::import(new ExploitantsImport, $file);
                    $results[] = "Exploitants importés avec succès depuis {$filename}";
                } elseif (str_contains($filename, 'localisations') || str_contains($filename, 'Localisations')) {
                    Excel::import(new LocalisationsImport, $file);
                    $results[] = "Localisations importées avec succès depuis {$filename}";
                } else {
                    $results[] = "Fichier {$filename} non reconnu";
                }
            } catch (\Exception $e) {
                $results[] = "Erreur lors de l'import de {$filename}: " . $e->getMessage();
            }
        }

        return redirect()->back()->with('results', $results);
    }

    // Individual export methods
    public function exportArticles(Request $request)
    {
        $filters = $request->only(['annee', 'foret_id', 'essence_id', 'invendu']);
        
        // Log articles export
        ActivityLogger::logExport('Articles', 'Excel', $request);
        
        return Excel::download(new ArticlesExport($filters), 'articles_' . date('Y-m-d_H-i-s') . '.xlsx');
    }

    public function exportEssences()
    {
        // Log essences export
        ActivityLogger::logExport('Essences', 'Excel', request());
        
        return Excel::download(new EssencesExport, 'essences_' . date('Y-m-d_H-i-s') . '.xlsx');
    }

    public function exportForets()
    {
        // Log forets export
        ActivityLogger::logExport('Forêts', 'Excel', request());
        
        return Excel::download(new ForetsExport, 'forets_' . date('Y-m-d_H-i-s') . '.xlsx');
    }

    public function exportNatureDeCoupes()
    {
        // Log nature de coupes export
        ActivityLogger::logExport('Natures de Coupes', 'Excel', request());
        
        return Excel::download(new NatureDeCoupesExport, 'nature_de_coupes_' . date('Y-m-d_H-i-s') . '.xlsx');
    }

    public function exportSituationAdministratives()
    {
        // Log situation administratives export
        ActivityLogger::logExport('Situations Administratives', 'Excel', request());
        
        return Excel::download(new SituationAdministrativesExport, 'situation_administratives_' . date('Y-m-d_H-i-s') . '.xlsx');
    }

    public function exportExploitants()
    {
        // Log exploitants export
        ActivityLogger::logExport('Exploitants', 'Excel', request());
        
        return Excel::download(new ExploitantsExport, 'exploitants_' . date('Y-m-d_H-i-s') . '.xlsx');
    }

    public function exportLocalisations()
    {
        // Log localisations export
        ActivityLogger::logExport('Localisations', 'Excel', request());
        
        return Excel::download(new LocalisationsExport, 'localisations_' . date('Y-m-d_H-i-s') . '.xlsx');
    }

    // Individual import methods
    public function importArticles(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            $filename = $request->file('file')->getClientOriginalName();
            $import = new ArticlesImport;
            
            Excel::import($import, $request->file('file'));
            
            // Log articles import
            ActivityLogger::logImport(
                'Articles',
                $filename,
                $import->getRowCount(),
                $request
            );
            
            return redirect()->back()->with('success', 'Articles importés avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'import: ' . $e->getMessage());
        }
    }

    public function importEssences(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            $filename = $request->file('file')->getClientOriginalName();
            $import = new EssencesImport;
            
            Excel::import($import, $request->file('file'));
            
            // Log essences import
            ActivityLogger::logImport(
                'Essences',
                $filename,
                $import->getRowCount(),
                $request
            );
            
            return redirect()->back()->with('success', 'Essences importées avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'import: ' . $e->getMessage());
        }
    }

    public function importForets(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            $filename = $request->file('file')->getClientOriginalName();
            $import = new ForetsImport;
            
            Excel::import($import, $request->file('file'));
            
            // Log forets import
            ActivityLogger::logImport(
                'Forêts',
                $filename,
                $import->getRowCount(),
                $request
            );
            
            return redirect()->back()->with('success', 'Forêts importées avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'import: ' . $e->getMessage());
        }
    }

    public function importNatureDeCoupes(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            $filename = $request->file('file')->getClientOriginalName();
            $import = new NatureDeCoupesImport;
            
            Excel::import($import, $request->file('file'));
            
            // Log nature de coupes import
            ActivityLogger::logImport(
                'Natures de Coupes',
                $filename,
                $import->getRowCount(),
                $request
            );
            
            return redirect()->back()->with('success', 'Natures de coupe importées avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'import: ' . $e->getMessage());
        }
    }

    public function importSituationAdministratives(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            $filename = $request->file('file')->getClientOriginalName();
            $import = new SituationAdministrativesImport;
            
            Excel::import($import, $request->file('file'));
            
            // Log situation administratives import
            ActivityLogger::logImport(
                'Situations Administratives',
                $filename,
                $import->getRowCount(),
                $request
            );
            
            return redirect()->back()->with('success', 'Situations administratives importées avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'import: ' . $e->getMessage());
        }
    }

    public function importExploitants(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            $filename = $request->file('file')->getClientOriginalName();
            $import = new ExploitantsImport;
            
            Excel::import($import, $request->file('file'));
            
            // Log exploitants import
            ActivityLogger::logImport(
                'Exploitants',
                $filename,
                $import->getRowCount(),
                $request
            );
            
            return redirect()->back()->with('success', 'Exploitants importés avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'import: ' . $e->getMessage());
        }
    }

    public function importLocalisations(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            $filename = $request->file('file')->getClientOriginalName();
            $import = new LocalisationsImport;
            
            Excel::import($import, $request->file('file'));
            
            // Log localisations import
            ActivityLogger::logImport(
                'Localisations',
                $filename,
                $import->getRowCount(),
                $request
            );
            
            return redirect()->back()->with('success', 'Localisations importées avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'import: ' . $e->getMessage());
        }
    }
}
