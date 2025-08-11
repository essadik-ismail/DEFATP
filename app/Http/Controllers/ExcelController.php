<?php

namespace App\Http\Controllers;

use App\Exports\ArticlesExport;
use App\Exports\EssencesExport;
use App\Exports\ForetsExport;
use App\Exports\NatureDeCoupesExport;
use App\Exports\SituationAdministrativesExport;
use App\Exports\ExploitantsExport;
use App\Exports\SessionAdjudicationsExport;
use App\Exports\LocalisationsExport;
use App\Imports\ArticlesImport;
use App\Imports\EssencesImport;
use App\Imports\ForetsImport;
use App\Imports\NatureDeCoupesImport;
use App\Imports\SituationAdministrativesImport;
use App\Imports\ExploitantsImport;
use App\Imports\LocalisationsImport;
use App\Imports\SessionAdjudicationsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use ZipArchive;

class ExcelController extends Controller
{
    public function index(): View
    {
        return view('excel.index');
    }

    // Export all data
    public function exportAll()
    {
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
            $this->addToZip($zip, new SessionAdjudicationsExport(), "session_adjudications_{$timestamp}.xlsx");
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
                } elseif (str_contains($filename, 'session') || str_contains($filename, 'adjudication') || str_contains($filename, 'Session') || str_contains($filename, 'Adjudication')) {
                    Excel::import(new SessionAdjudicationsImport, $file);
                    $results[] = "Sessions d'adjudication importées avec succès depuis {$filename}";
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
        return Excel::download(new ArticlesExport($filters), 'articles_' . date('Y-m-d_H-i-s') . '.xlsx');
    }

    public function exportEssences()
    {
        return Excel::download(new EssencesExport, 'essences_' . date('Y-m-d_H-i-s') . '.xlsx');
    }

    public function exportForets()
    {
        return Excel::download(new ForetsExport, 'forets_' . date('Y-m-d_H-i-s') . '.xlsx');
    }

    public function exportNatureDeCoupes()
    {
        return Excel::download(new NatureDeCoupesExport, 'nature_de_coupes_' . date('Y-m-d_H-i-s') . '.xlsx');
    }

    public function exportSituationAdministratives()
    {
        return Excel::download(new SituationAdministrativesExport, 'situation_administratives_' . date('Y-m-d_H-i-s') . '.xlsx');
    }

    public function exportExploitants()
    {
        return Excel::download(new ExploitantsExport, 'exploitants_' . date('Y-m-d_H-i-s') . '.xlsx');
    }

    public function exportSessionAdjudications()
    {
        return Excel::download(new SessionAdjudicationsExport, 'session_adjudications_' . date('Y-m-d_H-i-s') . '.xlsx');
    }

    public function exportLocalisations()
    {
        return Excel::download(new LocalisationsExport, 'localisations_' . date('Y-m-d_H-i-s') . '.xlsx');
    }

    // Individual import methods
    public function importArticles(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            Excel::import(new ArticlesImport, $request->file('file'));
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
            Excel::import(new EssencesImport, $request->file('file'));
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
            Excel::import(new ForetsImport, $request->file('file'));
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
            Excel::import(new NatureDeCoupesImport, $request->file('file'));
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
            Excel::import(new SituationAdministrativesImport, $request->file('file'));
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
            Excel::import(new ExploitantsImport, $request->file('file'));
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
            Excel::import(new LocalisationsImport, $request->file('file'));
            return redirect()->back()->with('success', 'Localisations importées avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'import: ' . $e->getMessage());
        }
    }

    public function importSessionAdjudications(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            Excel::import(new SessionAdjudicationsImport, $request->file('file'));
            return redirect()->back()->with('success', 'Sessions d\'adjudication importées avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'import: ' . $e->getMessage());
        }
    }
}
