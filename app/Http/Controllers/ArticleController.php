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
use App\Imports\ArticlesImport;
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
        
        // Get articles with enhanced pagination and filtering
        $articles = Article::where('is_deleted', false)
            ->with(['foret', 'essence', 'localisation', 'situationAdministrative', 'exploitant', 'natureDeCoupe'])
            ->when($request->filled('search'), function($query) use ($request) {
                $query->where(function($q) use ($request) {
                    $q->where('numero', 'like', '%' . $request->search . '%')
                      ->orWhere('annee', 'like', '%' . $request->search . '%')
                      ->orWhereHas('foret', function($foretQuery) use ($request) {
                          $foretQuery->where('foret', 'like', '%' . $request->search . '%');
                      })
                      ->orWhereHas('essence', function($essenceQuery) use ($request) {
                          $essenceQuery->where('essence', 'like', '%' . $request->search . '%');
                      });
                });
            })
            ->when($request->filled('status'), function($query) use ($request) {
                if ($request->status === 'validated') {
                    $query->where('is_validated', true);
                } elseif ($request->status === 'pending') {
                    $query->where('is_validated', false);
                }
            })
            ->when($request->filled('type'), function($query) use ($request) {
                $query->where('type', $request->type);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        // Get entities with search and pagination
        $essences = Essence::where('is_deleted', '')
            ->when($request->filled('essence_search'), function($query) use ($request) {
                $query->where('essence', 'like', '%' . $request->essence_search . '%');
            })
            ->orderBy('essence')
            ->paginate(10, ['*'], 'essences_page');

        $forets = Foret::where('is_deleted', '')
            ->when($request->filled('foret_search'), function($query) use ($request) {
                $query->where('foret', 'like', '%' . $request->foret_search . '%');
            })
            ->orderBy('foret')
            ->paginate(10, ['*'], 'forets_page');

        $localisations = Localisation::where('is_deleted', '')
            ->when($request->filled('localisation_search'), function($query) use ($request) {
                $query->where(function($q) use ($request) {
                    $q->where('CODE', 'like', '%' . $request->localisation_search . '%')
                      ->orWhere('DRANEF', 'like', '%' . $request->localisation_search . '%')
                      ->orWhere('ENTITE', 'like', '%' . $request->localisation_search . '%');
                });
            })
            ->orderBy('CODE')
            ->paginate(10, ['*'], 'localisations_page');

        $situationAdministratives = SituationAdministrative::where('is_deleted', '')
            ->when($request->filled('situation_search'), function($query) use ($request) {
                $query->where(function($q) use ($request) {
                    $q->where('commune', 'like', '%' . $request->localisation_search . '%')
                      ->orWhere('province', 'like', '%' . $request->localisation_search . '%');
                });
            })
            ->orderBy('commune')
            ->paginate(10, ['*'], 'situations_page');

        $exploitants = Exploitant::where('is_deleted', '')
            ->when($request->filled('exploitant_search'), function($query) use ($request) {
                $query->where('nom_complet', 'like', '%' . $request->exploitant_search . '%');
            })
            ->orderBy('nom_complet')
            ->paginate(10, ['*'], 'exploitants_page');

        $natureDeCoupes = NatureDeCoupe::where('is_deleted', '')
            ->when($request->filled('nature_search'), function($query) use ($request) {
                $query->where('nature_de_coupe', 'like', '%' . $request->nature_search . '%');
            })
            ->orderBy('nature_de_coupe')
            ->paginate(10, ['*'], 'natures_page');

        return view('articles.index', compact(
            'articles',
            'essences',
            'forets', 
            'localisations',
            'situationAdministratives',
            'exploitants',
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
            // Prepare article data with new field order
            $articleData = $request->only([
                'date_adjudication', 'annee', 'numero', 'localisation_id', 'situation_administrative_id',
                'parcelle', 'foret_id', 'situation_administrative_id', 'essence_id', 'nature_de_coupe_id', 'lot',
                'superficie', 'bo_m3', 'bi_m3', 'bf_st', 'tanin_t', 'fleur_acacia_t', 'caroube_t',
                'romarin_t', 'ps_t', 'charbon_bois_ox', 'invendu', 'prix_de_retrait',
                'date_dr', 'exploitant_id', 'type', 'prix_vente', 'dc', 'rc', 'date_de_resiliation',
                'date_de_decheance', 'is_validated', 'observations'
            ]);

            // Handle checkbox fields - only include values if checkbox is checked
            $checkboxFields = [
                'has_superficie' => 'superficie',
                'has_bo_m3' => 'bo_m3',
                'has_bi_m3' => 'bi_m3',
                'has_bf_st' => 'bf_st',
                'has_tanin_t' => 'tanin_t',
                'has_fleur_acacia_t' => 'fleur_acacia_t',
                'has_caroube_t' => 'caroube_t',
                'has_romarin_t' => 'romarin_t',
                'has_ps_t' => 'ps_t',
                'has_charbon_bois_ox' => 'charbon_bois_ox'
            ];

            foreach ($checkboxFields as $checkbox => $field) {
                if (!$request->has($checkbox)) {
                    $articleData[$field] = null;
                }
            }

            // Handle boolean fields
            $booleanFields = ['dc', 'rc', 'is_validated'];
            foreach ($booleanFields as $field) {
                $articleData[$field] = $request->has($field) ? true : false;
            }

            // Create the article
            $article = Article::create($articleData);

            // Log article creation
            ActivityLogger::logCreate(
                Article::class,
                $article->id,
                "Article {$article->numero} ({$article->annee})",
                $request
            );

            // Check if this is an AJAX request
            if ($request->ajax()) {
                $isCreateAndNext = $request->input('action') === 'create_and_next';
                
                return response()->json([
                    'success' => true,
                    'message' => 'Article ajouté avec succès.',
                    'create_and_next' => $isCreateAndNext,
                    'article' => [
                        'id' => $article->id,
                        'numero' => $article->numero,
                        'annee' => $article->annee,
                        'foret' => $article->foret->foret ?? null,
                        'essence' => $article->essence->essence ?? null
                    ]
                ]);
            }
            
            // Handle create_and_next action for regular requests
            if ($request->input('action') === 'create_and_next') {
                return redirect()->route('articles.create')
                    ->with('success', 'Article ajouté avec succès. Vous pouvez créer un autre article.');
            }

            return redirect()->route('articles.index')->with('success', 'Article ajouté avec succès.');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreurs de validation',
                    'errors' => $e->errors()
                ], 422);
            }
            
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la création de l\'article: ' . $e->getMessage()
                ], 500);
            }
            
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
            'situationAdministrative',
            'foret',
            'essence',
            'natureDeCoupe',
            'exploitant',
            'localisation'
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
            'date_adjudication', 'annee', 'numero', 'localisation_id', 'situation_administrative_id',
            'parcelle', 'foret_id', 'essence_id', 'nature_de_coupe_id', 'lot',
            'superficie', 'bo_m3', 'bi_m3', 'bf_st', 'tanin_t', 'fleur_acacia_t', 'caroube_t',
            'romarin_t', 'ps_t', 'charbon_bois_ox', 'invendu', 'prix_de_retrait',
            'date_dr', 'exploitant_id', 'type', 'prix_vente', 'dc', 'rc', 'date_de_resiliation',
            'date_de_decheance', 'is_validated', 'observations'
        ]);

        $article->update($request->all());

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
} 