<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Essence;
use App\Models\Foret;
use App\Models\Localisation;
use App\Models\NatureDeCoupe;
use App\Models\SituationAdministrative;
use App\Models\Exploitant;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Requests\IndexArticleRequest;
use App\Http\Requests\ExportArticleRequest;
use App\Exports\ArticlesExport;
use App\Exports\ArticlesTemplateExport;
use App\Imports\ArticlesImport;
use App\Imports\LocationsImport;
use App\Services\ActivityLogger;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(Request $request): View
    {
        // Log view action
        ActivityLogger::log('view', 'Consultation de la liste des articles', Article::class);
        
        // Get date filters from request
        $startDate = $request->filled('start_date') ? \Carbon\Carbon::parse($request->start_date)->startOfDay() : null;
        $endDate = $request->filled('end_date') ? \Carbon\Carbon::parse($request->end_date)->endOfDay() : null;
        
        // Build base query with date filtering
        $articlesQuery = Article::where('is_deleted', false)
            ->with(['exploitant', 'products', 'locations', 'forets', 'essences', 'situationsAdministratives', 'naturesDeCoupe', 'localisations']);
            
        // Apply date filtering if provided
        if ($startDate && $endDate) {
            $articlesQuery->whereBetween('created_at', [$startDate, $endDate]);
        }
        
        // Get articles with enhanced pagination and filtering
        $articles = $articlesQuery
            ->when($request->filled('search'), function($query) use ($request) {
                $query->where(function($q) use ($request) {
                    $q->where('numero', 'like', '%' . $request->search . '%')
                      ->orWhere('annee', 'like', '%' . $request->search . '%')
                      ->orWhereHas('forets', function($foretQuery) use ($request) {
                          $foretQuery->where('foret', 'like', '%' . $request->search . '%');
                      })
                      ->orWhereHas('essences', function($essenceQuery) use ($request) {
                          $essenceQuery->where('essence', 'like', '%' . $request->search . '%');
                      });
                });
            })
            ->when($request->filled('status'), function($query) use ($request) {
                if ($request->status === 'sold') {
                    $query->where('invendu', false);
                } elseif ($request->status === 'unsold') {
                    $query->where('invendu', true);
                }
            })
            ->when($request->filled('year'), function($query) use ($request) {
                $query->where('annee', $request->year);
            })
            ->when($request->filled('type'), function($query) use ($request) {
                $query->where('type', $request->type);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        $exploitants = Exploitant::where('is_deleted', false)
            ->when($request->filled('exploitant_search'), function($query) use ($request) {
                $query->where('nom_complet', 'like', '%' . $request->exploitant_search . '%');
            })
            ->orderBy('nom_complet')
            ->paginate(10, ['*'], 'exploitants_page');

        // Calculate comprehensive statistics with date filtering
        $statsQuery = Article::where('is_deleted', false);
        if ($startDate && $endDate) {
            $statsQuery->whereBetween('created_at', [$startDate, $endDate]);
        }
        
        // Calculate statistics based on the same filtered query as the articles
        $filteredQuery = Article::where('is_deleted', false);
        
        // Apply the same filters as the main query
        if ($startDate && $endDate) {
            $filteredQuery->whereBetween('created_at', [$startDate, $endDate]);
        }
        
        if ($request->filled('search')) {
            $filteredQuery->where(function($q) use ($request) {
                $q->where('numero', 'like', '%' . $request->search . '%')
                  ->orWhere('annee', 'like', '%' . $request->search . '%')
                  ->orWhereHas('forets', function($foretQuery) use ($request) {
                      $foretQuery->where('foret', 'like', '%' . $request->search . '%');
                  })
                  ->orWhereHas('essences', function($essenceQuery) use ($request) {
                      $essenceQuery->where('essence', 'like', '%' . $request->search . '%');
                  });
            });
        }
        
        if ($request->filled('status')) {
            if ($request->status === 'sold') {
                $filteredQuery->where('invendu', false);
            } elseif ($request->status === 'unsold') {
                $filteredQuery->where('invendu', true);
            }
        }
        
        if ($request->filled('year')) {
            $filteredQuery->where('annee', $request->year);
        }
        
        if ($request->filled('type')) {
            $filteredQuery->where('type', $request->type);
        }
        
        $stats = [
            'total_articles' => $articles->total(),
            'sold_articles' => (clone $filteredQuery)->where('invendu', false)->count(),
            'unsold_articles' => (clone $filteredQuery)->where('invendu', true)->count(),
            'total_revenue' => (clone $filteredQuery)->sum('prix_vente'),
            'total_retrait' => (clone $filteredQuery)->sum('prix_de_retrait'),
            'total_volume' => (clone $filteredQuery)->sum('bo_m3') + (clone $filteredQuery)->sum('bi_m3'),
            'total_forets' => Foret::where('is_deleted', false)->count(),
            'total_essences' => Essence::where('is_deleted', false)->count(),
            'total_localisations' => Localisation::where('is_deleted', false)->count(),
            'total_exploitants' => Exploitant::where('is_deleted', false)->count(),
            'articles_by_type' => [
                'appel_doffre' => (clone $filteredQuery)->where('type', 'appel_doffre')->count(),
                'adjudication' => (clone $filteredQuery)->where('type', 'adjudication')->count(),
                'marche_negocié' => (clone $filteredQuery)->where('type', 'marche_negocié')->count(),
            ],
            'recent_articles' => (clone $filteredQuery)
                ->with(['forets', 'essences'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
        ];

        // Provide essences list for the essences table section
        $essences = Essence::where('is_deleted', false)
            ->when($request->filled('essence_search'), function($query) use ($request) {
                $query->where('essence', 'like', '%' . $request->essence_search . '%');
            })
            ->orderBy('essence')
            ->paginate(10, ['*'], 'essences_page');

        // Provide forets list for the forets table section
        $forets = Foret::where('is_deleted', false)
            ->when($request->filled('foret_search'), function($query) use ($request) {
                $query->where('foret', 'like', '%' . $request->foret_search . '%');
            })
            ->orderBy('foret')
            ->paginate(10, ['*'], 'forets_page');

        // Provide localisations list for the localisations table section
        $localisations = Localisation::where('is_deleted', false)
            ->when($request->filled('localisation_search'), function($query) use ($request) {
                $query->where('CODE', 'like', '%' . $request->localisation_search . '%')
                      ->orWhere('DRANEF', 'like', '%' . $request->localisation_search . '%')
                      ->orWhere('ENTITE', 'like', '%' . $request->localisation_search . '%');
            })
            ->orderBy('CODE')
            ->paginate(10, ['*'], 'localisations_page');

        // Provide natures de coupe list for the natures table section
        $natureDeCoupes = NatureDeCoupe::where('is_deleted', false)
            ->when($request->filled('nature_search'), function($query) use ($request) {
                $query->where('nature_de_coupe', 'like', '%' . $request->nature_search . '%');
            })
            ->orderBy('nature_de_coupe')
            ->paginate(10, ['*'], 'natures_page');

        return view('articles.index', compact(
            'articles',
            'exploitants',
            'stats',
            'essences',
            'forets',
            'localisations',
            'natureDeCoupes'
        ));
    }

    public function create(): View
    {
        $situationAdministratives = SituationAdministrative::orderBy('commune')->get();
        $forets = Foret::orderBy('foret')->get();
        $essences = Essence::orderBy('essence')->get();
        $natureDeCoupes = NatureDeCoupe::orderBy('nature_de_coupe')->get();
        $exploitants = Exploitant::orderBy('nom_complet')->get();
        $localisations = Localisation::orderBy('CODE')->get();

        return view('articles.create', compact(
            'situationAdministratives',
            'forets',
            'essences',
            'natureDeCoupes',
            'exploitants',
            'localisations'
        ));
    }

    public function store(StoreArticleRequest $request): RedirectResponse
    {
        try {
            // Prepare article data with new field structure
            $articleData = $request->only([
                'annee', 'numero', 'date_adjudication', 'numero_adjudication', 'lot', 'type',
                'exploitant_id', 'nature_juridique', 'parcelle', 'lat', 'log',
                'superficie', 'bo_m3', 'bi_m3', 'bf_st', 'tanin_t', 'fleur_acacia_t', 'caroube_t',
                'romarin_t', 'liége_st', 'charbon_bois_ox', 'prix_de_retrait', 'prix_vente'
            ]);


            // Create the article
            $article = Article::create($articleData);

            // Handle products if provided
            if ($request->has('products') && is_array($request->products)) {
                foreach ($request->products as $product) {
                    if (!empty($product['name'])) {
                        $article->products()->create([
                            'name' => $product['name'],
                            'quantity' => $product['quantity'] ?? 1,
                            'is_deleted' => false
                        ]);
                    }
                }
            }

            // Sync many-to-many relations from multi-selects
            $foretIds = $request->input('foret_ids', []);
            $essenceIds = $request->input('essence_ids', []);
            $situationIds = $request->input('situation_administrative_ids', []);
            $natureIds = $request->input('nature_de_coupe_ids', []);
            $localisationIds = $request->input('localisation_ids', []);

            if (!empty($foretIds)) {
                $article->forets()->sync($foretIds);
            }
            if (!empty($essenceIds)) {
                $article->essences()->sync($essenceIds);
            }
            if (!empty($situationIds)) {
                $article->situationsAdministratives()->sync($situationIds);
            }
            if (!empty($natureIds)) {
                $article->naturesDeCoupe()->sync($natureIds);
            }
            if (!empty($localisationIds)) {
                $article->localisations()->sync($localisationIds);
            }

            // Handle locations if provided
            if ($request->has('locations') && is_array($request->locations)) {
                foreach ($request->locations as $location) {
                    if (!empty($location['mat']) || !empty($location['x']) || !empty($location['y'])) {
                        $article->locations()->create([
                            'mat' => $location['mat'] ?? null,
                            'x' => $location['x'] ?? null,
                            'y' => $location['y'] ?? null
                        ]);
                    }
                }
            }

            // Handle locations file upload if provided
            if ($request->hasFile('locations_file')) {
                try {
                    Excel::import(new LocationsImport($article->id), $request->file('locations_file'));
                } catch (\Exception $e) {
                    // Log error but don't fail the article creation
                    \Log::error('Error importing locations file: ' . $e->getMessage());
                }
            }

            // Log article creation
            ActivityLogger::logCreate(
                Article::class,
                $article->id,
                "Article {$article->numero} ({$article->annee})",
                $request
            );

            // Handle create_and_next action for regular requests
            if ($request->input('action') === 'create_and_next') {
                return redirect()->route('articles.create')
                    ->with('success', 'Article ajouté avec succès. Vous pouvez créer un autre article.');
            }

            return redirect()->route('articles.index')->with('success', 'Article ajouté avec succès.');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création de l\'article: ' . $e->getMessage());
        }
    }

    public function show(Article $article): View
    {
        // Log article view
        ActivityLogger::logView(
            Article::class,
            $article->id,
            "Article {$article->numero} ({$article->annee})",
            request()
        );

        $article->load([
            'exploitant',
            'products',
            'locations'
        ]);

        return view('articles.show', compact('article'));
    }

    public function edit(Article $article): View
    {
        $situationAdministratives = SituationAdministrative::orderBy('commune')->get();
        $forets = Foret::orderBy('foret')->get();
        $essences = Essence::orderBy('essence')->get();
        $natureDeCoupes = NatureDeCoupe::orderBy('nature_de_coupe')->get();
        $exploitants = Exploitant::orderBy('nom_complet')->get();
        $localisations = Localisation::orderBy('CODE')->get();

        // Load products and locations for the article
        $article->load(['products', 'locations']);

        return view('articles.edit', compact(
            'article',
            'situationAdministratives',
            'forets',
            'essences',
            'natureDeCoupes',
            'exploitants',
            'localisations'
        ));
    }

    public function update(UpdateArticleRequest $request, Article $article): RedirectResponse
    {
        $oldData = $article->only([
            'annee', 'numero', 'date_adjudication', 'numero_adjudication', 'lot', 'type',
            'exploitant_id', 'nature_juridique', 'parcelle', 'lat', 'log',
            'superficie', 'bo_m3', 'bi_m3', 'bf_st', 'tanin_t', 'fleur_acacia_t', 'caroube_t',
            'romarin_t', 'liége_st', 'charbon_bois_ox', 'prix_de_retrait', 'prix_vente'
        ]);

        // Update article data
        $articleData = $request->only([
            'annee', 'numero', 'date_adjudication', 'numero_adjudication', 'lot', 'type', 'statut',
            'exploitant_id', 'nature_juridique', 'parcelle', 'lat', 'log',
            'superficie', 'bo_m3', 'bi_m3', 'bf_st', 'tanin_t', 'fleur_acacia_t', 'caroube_t',
            'romarin_t', 'liége_st', 'charbon_bois_ox', 'prix_de_retrait', 'prix_vente'
        ]);

        $article->update($articleData);


        // Handle products update
        if ($request->has('products') && is_array($request->products)) {
            // Delete existing products
            $article->products()->delete();
            
            // Create new products
            foreach ($request->products as $product) {
                if (!empty($product['name'])) {
                    $article->products()->create([
                        'name' => $product['name'],
                        'quantity' => $product['quantity'] ?? 1,
                        'is_deleted' => false
                    ]);
                }
            }
        }

        // Sync many-to-many from multi-selects
        $foretIds = $request->input('foret_ids', []);
        $essenceIds = $request->input('essence_ids', []);
        $situationIds = $request->input('situation_administrative_ids', []);
        $natureIds = $request->input('nature_de_coupe_ids', []);
        $localisationIds = $request->input('localisation_ids', []);

        $article->forets()->sync($foretIds);
        $article->essences()->sync($essenceIds);
        $article->situationsAdministratives()->sync($situationIds);
        $article->naturesDeCoupe()->sync($natureIds);
        $article->localisations()->sync($localisationIds);

        // Handle locations update
        if ($request->has('locations') && is_array($request->locations)) {
            // Delete existing locations
            $article->locations()->delete();
            
            // Create new locations
            foreach ($request->locations as $location) {
                if (!empty($location['mat']) || !empty($location['x']) || !empty($location['y'])) {
                    $article->locations()->create([
                        'mat' => $location['mat'] ?? null,
                        'x' => $location['x'] ?? null,
                        'y' => $location['y'] ?? null
                    ]);
                }
            }
        }

        // Log article update
        $changes = array_diff_assoc($article->fresh()->only(array_keys($oldData)), $oldData);
        ActivityLogger::logUpdate(
            Article::class,
            $article->id,
            "Article {$article->numero} ({$article->annee})",
            $changes,
            $request
        );

        return redirect()->route('articles.index')->with('success', 'Article mis à jour avec succès.');
    }

    public function destroy(Article $article): RedirectResponse
    {
        $articleInfo = "Article {$article->numero} ({$article->annee})";
        
        $article->update(['is_deleted' => true]);
        
        // Log article deletion
        ActivityLogger::logDelete(
            Article::class,
            $article->id,
            $articleInfo,
            request()
        );

        return redirect()->route('articles.index')->with('success', 'Article supprimé avec succès.');
    }

    public function export(ExportArticleRequest $request)
    {
        $filters = $request->only(['annee', 'foret_id', 'essence_id', 'invendu']);
        
        // Log export action
        ActivityLogger::logExport(
            'Articles',
            'Excel',
            $request
        );
        
        return Excel::download(new ArticlesExport($filters), 'articles_' . date('Y-m-d_H-i-s') . '.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240', // 10MB max
        ]);

        try {
            $filename = $request->file('file')->getClientOriginalName();
            $import = new ArticlesImport;
            
            Excel::import($import, $request->file('file'));
            
            // Log import action
            ActivityLogger::logImport(
                'Articles',
                $filename,
                $import->getRowCount(),
                $request
            );
            
            return redirect()->route('articles.index')->with('success', 'Articles importés avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'import: ' . $e->getMessage());
        }
    }

    /**
     * Import locations from Excel file for a specific article
     */
    public function importLocations(Request $request, Article $article)
    {
        $request->validate([
            'locations_file' => 'required|file|mimes:xlsx,xls,csv|max:10240'
        ]);

        try {
            // Delete existing locations for this article
            $article->locations()->delete();

            // Import new locations
            Excel::import(new LocationsImport($article->id), $request->file('locations_file'));

            // Log the import action
            ActivityLogger::log('import', "Importation du plan de situation pour l'article {$article->numero}", Article::class);

            return redirect()->back()->with('success', 'Plan de situation importé avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'importation: ' . $e->getMessage());
        }
    }

    /**
     * Show simple article creation form
     */
    public function createSimple(): View
    {
        $situationAdministratives = SituationAdministrative::orderBy('commune')->get();
        $forets = Foret::orderBy('foret')->get();
        $essences = Essence::orderBy('essence')->get();
        $natureDeCoupes = NatureDeCoupe::orderBy('nature_de_coupe')->get();
        $exploitants = Exploitant::orderBy('nom_complet')->get();
        $localisations = Localisation::orderBy('CODE')->get();

        return view('articles.create-simple', compact(
            'situationAdministratives',
            'forets',
            'essences',
            'natureDeCoupes',
            'exploitants',
            'localisations'
        ));
    }

    /**
     * Store article from simple form
     */
    public function storeSimple(Request $request): RedirectResponse
    {
        $request->validate([
            'type' => 'required|in:appel_doffre,adjudication',
            'annee' => 'required|integer|min:2000|max:2100',
            'numero' => 'required|string|max:255',
            'date_adjudication' => 'required|date',
            'numero_adjudication' => 'nullable|string|max:255',
            'lot' => 'nullable|integer|min:0',
            'exploitant_id' => 'required|exists:exploitants,id',
            'foret_ids' => 'required|array|min:1',
            'foret_ids.*' => 'exists:forets,id',
            'essence_ids' => 'required|array|min:1',
            'essence_ids.*' => 'exists:essences,id',
            'localisation_ids' => 'required|array|min:1',
            'localisation_ids.*' => 'exists:localisations,id',
            'situation_administrative_ids' => 'required|array|min:1',
            'situation_administrative_ids.*' => 'exists:situation_administratives,id',
            'nature_de_coupe_ids' => 'required|array|min:1',
            'nature_de_coupe_ids.*' => 'exists:nature_de_coupes,id',
            'excel_file' => 'nullable|file|mimes:xlsx,xls,csv|max:10240'
        ]);

        try {
            // Prepare article data
            $articleData = $request->only([
                'annee', 'numero', 'date_adjudication', 'numero_adjudication', 'lot', 'type',
                'exploitant_id', 'nature_juridique', 'parcelle', 'lat', 'log',
                'superficie', 'bo_m3', 'bi_m3', 'bf_st', 'tanin_t', 'fleur_acacia_t', 'caroube_t',
                'romarin_t', 'liége_st', 'charbon_bois_ox', 'prix_de_retrait', 'prix_vente'
            ]);

            // Create the article
            $article = Article::create($articleData);

            // Sync many-to-many relations
            $article->forets()->sync($request->foret_ids);
            $article->essences()->sync($request->essence_ids);
            $article->situationsAdministratives()->sync($request->situation_administrative_ids);
            $article->naturesDeCoupe()->sync($request->nature_de_coupe_ids);
            $article->localisations()->sync($request->localisation_ids);

            // Handle Excel file import if provided
            if ($request->hasFile('excel_file')) {
                try {
                    Excel::import(new ArticlesImport, $request->file('excel_file'));
                } catch (\Exception $e) {
                    \Log::error('Error importing Excel file: ' . $e->getMessage());
                }
            }

            // Log article creation
            ActivityLogger::logCreate(
                Article::class,
                $article->id,
                "Article {$article->numero} ({$article->annee})",
                $request
            );

            return redirect()->route('articles.index')->with('success', 'Article créé avec succès via le formulaire simplifié.');
            
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création de l\'article: ' . $e->getMessage());
        }
    }

    /**
     * Download Excel template for article creation
     */
    public function downloadTemplate()
    {
        return Excel::download(new ArticlesTemplateExport, 'template_article_creation.xlsx');
    }
} 