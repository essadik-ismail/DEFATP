<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Essence;
use App\Models\Foret;
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
        
        // Get date filters from request (for date_adjudication)
        $startDate = $request->filled('start_date') ? \Carbon\Carbon::parse($request->start_date)->startOfDay() : null;
        $endDate = $request->filled('end_date') ? \Carbon\Carbon::parse($request->end_date)->endOfDay() : null;
        
        // Build base query
        $articlesQuery = Article::where('is_deleted', false)
            ->with(['exploitant', 'products', 'locations', 'forets', 'essences', 'situationsAdministratives', 'naturesDeCoupe']);
            
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
            ->when($startDate || $endDate, function($query) use ($startDate, $endDate) {
                if ($startDate && $endDate) {
                    $query->whereBetween('date_adjudication', [$startDate, $endDate]);
                } elseif ($startDate) {
                    $query->where('date_adjudication', '>=', $startDate);
                } elseif ($endDate) {
                    $query->where('date_adjudication', '<=', $endDate);
                }
            })
            ->when($request->filled('years'), function($query) use ($request) {
                $years = is_array($request->years) ? $request->years : [$request->years];
                $query->whereIn('annee', $years);
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

        // Calculate statistics based on the same filtered query as the articles
        $filteredQuery = Article::where('is_deleted', false);
        
        // Apply the same filters as the main query
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
        
        if ($startDate || $endDate) {
            if ($startDate && $endDate) {
                $filteredQuery->whereBetween('date_adjudication', [$startDate, $endDate]);
            } elseif ($startDate) {
                $filteredQuery->where('date_adjudication', '>=', $startDate);
            } elseif ($endDate) {
                $filteredQuery->where('date_adjudication', '<=', $endDate);
            }
        }
        
        if ($request->filled('years')) {
            $years = is_array($request->years) ? $request->years : [$request->years];
            $filteredQuery->whereIn('annee', $years);
        }
        
        if ($request->filled('type')) {
            $filteredQuery->where('type', $request->type);
        }
        
        $stats = [
            'total_articles' => $articles->total(),
            // Removed sold_articles/unsold_articles - invendu column was removed
            'sold_articles' => 0,
            'unsold_articles' => 0,
            // Removed total_revenue/total_retrait - prix_vente and prix_de_retrait columns were removed
            'total_revenue' => 0,
            'total_retrait' => 0,
            'total_volume' => $this->calculateTotalVolume($filteredQuery),
            'total_forets' => Foret::where('is_deleted', false)->count(),
            'total_essences' => Essence::where('is_deleted', false)->count(),
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
        $products = \App\Models\Product::orderBy('name')->get();
        $modeExploitations = \App\Models\ModeExploitation::orderBy('mode_exploiattion')->get();
        $zdtfs = \App\Models\Zdtf::with('dpanef.dranef')->orderBy('sdtf')->get();

        return view('articles.create', compact(
            'situationAdministratives',
            'forets',
            'essences',
            'natureDeCoupes',
            'exploitants',
            'products',
            'modeExploitations',
            'zdtfs'
        ));
    }

    public function store(StoreArticleRequest $request): RedirectResponse
    {
        try {
            // Prepare article data with new field structure (aligned with ERD)
            $articleData = $request->only([
                'annee', 'numero', 'date_adjudication', 'numero_adjudication', 'lot', 'type',
                'exploitant_id', 'parcelle', 'superficie', 'fourniture_mise_charge',
                'taxe_refection_chemins', 'service_rendu_anef', 'bois_chauffage_volume', 'bois_chauffage_destination',
                'date_payement_service_anef', 'date_livaison_mise_en_charge_bf', 'zdtf_id'
            ]);


            // Create the article
            $article = Article::create($articleData);

            // Handle products if provided
            if ($request->has('products') && is_array($request->products)) {
                $productSync = [];
                foreach ($request->products as $product) {
                    if (!empty($product['name'])) {
                        // Find or create product (normalize name to match seeder)
                        $productName = trim($product['name']);
                        $productModel = \App\Models\Product::firstOrCreate(
                            ['name' => $productName]
                        );
                        $quantity = isset($product['quantity']) && $product['quantity'] > 0 ? $product['quantity'] : 1;
                        $productSync[$productModel->id] = ['quantity' => $quantity];
                    }
                }
                if (!empty($productSync)) {
                    $article->products()->sync($productSync);
                }
            }

            // Sync many-to-many relations from multi-selects
            $foretIds = $request->input('foret_ids', []);
            $essenceIds = $request->input('essence_ids', []);
            $situationIds = $request->input('situation_administrative_ids', []);
            $natureIds = $request->input('nature_de_coupe_ids', []);
            $localisationIds = $request->input('localisation_ids', []);
            $modeExploitationIds = $request->input('mode_exploitation_ids', []);

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
            if (!empty($modeExploitationIds)) {
                $article->modeExploitations()->sync($modeExploitationIds);
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

    public function show(Request $request, Article $article): View
    {
        // If this is a POST request (shouldn't happen, but handle gracefully)
        if ($request->isMethod('post')) {
            return redirect()->route('articles.show', $article)
                ->with('warning', 'Méthode POST non supportée. Utilisez PUT/PATCH pour modifier un article.');
        }

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
        // Load products and locations for the article
        $article->load(['products', 'locations', 'modeExploitations', 'zdtf']);
        $products = \App\Models\Product::orderBy('name')->get();
        $modeExploitations = \App\Models\ModeExploitation::orderBy('mode_exploiattion')->get();
        $zdtfs = \App\Models\Zdtf::with('dpanef.dranef')->orderBy('sdtf')->get();

        return view('articles.edit', compact(
            'article',
            'situationAdministratives',
            'forets',
            'essences',
            'natureDeCoupes',
            'exploitants',
            'localisations',
            'products',
            'modeExploitations',
            'zdtfs'
        ));
    }

    public function update(UpdateArticleRequest $request, Article $article): RedirectResponse
    {
        $oldData = $article->only([
            'annee', 'numero', 'date_adjudication', 'numero_adjudication', 'lot', 'type',
            'exploitant_id', 'parcelle', 'superficie', 'nommer_a_la_vente', 'fourniture_mise_charge',
            'taxe_refection_chemins', 'service_rendu_anef', 'bois_chauffage_volume', 'bois_chauffage_destination',
            'date_payement_service_anef', 'date_livaison_mise_en_charge_bf', 'zdtf_id'
        ]);

        // Update article data (aligned with ERD)
        $articleData = $request->only([
            'annee', 'numero', 'date_adjudication', 'numero_adjudication', 'lot', 'type',
            'exploitant_id', 'parcelle', 'superficie', 'nommer_a_la_vente', 'fourniture_mise_charge',
            'taxe_refection_chemins', 'service_rendu_anef', 'bois_chauffage_volume', 'bois_chauffage_destination',
            'date_payement_service_anef', 'date_livaison_mise_en_charge_bf', 'zdtf_id'
        ]);

        $article->update($articleData);


        // Handle products update
        if ($request->has('products') && is_array($request->products)) {
            $productSync = [];
            foreach ($request->products as $product) {
                if (!empty($product['name'])) {
                    // Find or create product (normalize name to match seeder)
                    $productName = trim($product['name']);
                    $productModel = \App\Models\Product::firstOrCreate(
                        ['name' => $productName]
                    );
                    $quantity = isset($product['quantity']) && $product['quantity'] > 0 ? $product['quantity'] : 1;
                    $productSync[$productModel->id] = ['quantity' => $quantity];
                }
            }
            $article->products()->sync($productSync);
        } else {
            // If no products provided, detach all
            $article->products()->sync([]);
        }

        // Sync many-to-many from multi-selects
        $foretIds = $request->input('foret_ids', []);
        $essenceIds = $request->input('essence_ids', []);
        $situationIds = $request->input('situation_administrative_ids', []);
        $natureIds = $request->input('nature_de_coupe_ids', []);
        $localisationIds = $request->input('localisation_ids', []);
        $modeExploitationIds = $request->input('mode_exploitation_ids', []);

        $article->forets()->sync($foretIds);
        $article->essences()->sync($essenceIds);
        $article->situationsAdministratives()->sync($situationIds);
        $article->naturesDeCoupe()->sync($natureIds);
        $article->localisations()->sync($localisationIds);
        $article->modeExploitations()->sync($modeExploitationIds);

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
        $filters = $request->only(['annee', 'foret_id', 'essence_id']);
        
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
        $products = \App\Models\Product::orderBy('name')->get();
        $modeExploitations = \App\Models\ModeExploitation::orderBy('mode_exploiattion')->get();
        $zdtfs = \App\Models\Zdtf::with('dpanef.dranef')->orderBy('sdtf')->get();

        return view('articles.create-simple', compact(
            'situationAdministratives',
            'forets',
            'essences',
            'natureDeCoupes',
            'exploitants',
            'products',
            'modeExploitations',
            'zdtfs'
        ));
    }

    /**
     * Store article from simple form - only handles selects and Excel import
     */
    public function storeSimple(Request $request): RedirectResponse
    {
        $request->validate([
            'exploitant_id' => 'required|exists:exploitants,id',
            'foret_ids' => 'required|array|min:1',
            'foret_ids.*' => 'exists:forets,id',
            'essence_ids' => 'required|array|min:1',
            'essence_ids.*' => 'exists:essences,id',
            'situation_administrative_ids' => 'required|array|min:1',
            'situation_administrative_ids.*' => 'exists:situation_administratives,id',
            'nature_de_coupe_ids' => 'required|array|min:1',
            'nature_de_coupe_ids.*' => 'exists:nature_de_coupes,id',
            'products' => 'nullable|array',
            'products.*.name' => 'required_with:products|string|max:255',
            'products.*.quantity' => 'nullable|numeric|min:0.01',
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:10240'
        ]);

        try {
            // Prepare products array for import
            $productsData = [];
            if ($request->has('products') && is_array($request->products)) {
                foreach ($request->products as $product) {
                    if (!empty($product['name'])) {
                        $productsData[] = [
                            'name' => trim($product['name']),
                            'quantity' => isset($product['quantity']) && $product['quantity'] > 0 ? (float)$product['quantity'] : 1
                        ];
                    }
                }
            }

            // Create import instance with relationship IDs and products
            $import = new ArticlesImport(
                $request->exploitant_id,
                $request->foret_ids,
                $request->essence_ids,
                $request->situation_administrative_ids,
                $request->nature_de_coupe_ids,
                $request->mode_exploitation_ids ?? [],
                $request->zdtf_id ?? null,
                $productsData
            );

            // Import articles from Excel
            Excel::import($import, $request->file('excel_file'));

            $rowCount = $import->getRowCount();

            // Log import action
            ActivityLogger::logImport(
                'Articles',
                $request->file('excel_file')->getClientOriginalName(),
                $rowCount,
                $request
            );

            return redirect()->route('articles.index')->with('success', "{$rowCount} article(s) importé(s) avec succès.");
            
        } catch (\Exception $e) {
            \Log::error('Error importing articles: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de l\'importation: ' . $e->getMessage());
        }
    }

    /**
     * Calculate total volume from products (BO + BI)
     */
    private function calculateTotalVolume($query)
    {
        $articles = $query->with('products')->get();
        $totalVolume = 0;
        
        foreach ($articles as $article) {
            $boProduct = $article->products()->where('name', 'BO (m³)')->first();
            $biProduct = $article->products()->where('name', 'BI (m³)')->first();
            
            $boQuantity = $boProduct ? $boProduct->pivot->quantity : 0;
            $biQuantity = $biProduct ? $biProduct->pivot->quantity : 0;
            
            $totalVolume += $boQuantity + $biQuantity;
        }
        
        return $totalVolume;
    }

    /**
     * Download Excel template for article creation
     */
    public function downloadTemplate()
    {
        return Excel::download(new ArticlesTemplateExport, 'template_article_creation.xlsx');
    }
} 