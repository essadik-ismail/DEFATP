<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Essence;
use App\Models\Foret;
use App\Models\Localisation;
use App\Models\NatureDeCoupe;
use App\Models\SituationAdministrative;

use App\Models\Exploitant;
use App\Models\SessionAdjudication;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Requests\IndexArticleRequest;
use App\Http\Requests\ExportArticleRequest;
use App\Exports\ArticlesExport;
use App\Imports\ArticlesImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(IndexArticleRequest $request): View
    {
        $query = Article::with([
            'situationAdministrative',
            'foret',
            'essence',
            'natureDeCoupe',
            'sessionAdjudication',
            'localisation'
        ]);

        // Filtering
        if ($request->filled('annee')) {
            $query->where('annee', $request->annee);
        }

        if ($request->filled('foret_id')) {
            $query->where('foret_id', $request->foret_id);
        }

        if ($request->filled('essence_id')) {
            $query->where('essence_id', $request->essence_id);
        }

        if ($request->filled('invendu')) {
            $query->where('invendu', $request->invendu);
        }

        $articles = $query->orderBy('date', 'desc')->paginate(20);

        // Get filter options
        $forets = Foret::orderBy('foret')->get();
        $essences = Essence::orderBy('essence')->get();

        return view('articles.index', compact('articles', 'forets', 'essences'));
    }

    public function create(): View
    {
        $situationAdministratives = SituationAdministrative::orderBy('commune')->get();
        $forets = Foret::orderBy('foret')->get();
        $essences = Essence::orderBy('essence')->get();
        $natureDeCoupes = NatureDeCoupe::orderBy('nature_de_coupe')->get();
        $exploitants = Exploitant::orderBy('nom_complet')->get();
        $sessionAdjudications = SessionAdjudication::orderBy('date', 'desc')->get();
        $localisations = Localisation::orderBy('CODE')->get();

        return view('articles.create', compact(
            'situationAdministratives',
            'forets',
            'essences',
            'natureDeCoupes',
            'exploitants',
            'sessionAdjudications',
            'localisations'
        ));
    }

    public function store(StoreArticleRequest $request): RedirectResponse
    {
        // Prepare article data
        $articleData = $request->only([
            'annee', 'numero', 'date', 'parcelle', 'foret_id', 'lot', 'superficie',
            'bo_m3', 'bi_m3', 'bf_st', 'tanin_t', 'fleur_acacia_t', 'caroube_t',
            'romarin_t', 'ps_t', 'liege_st', 'charbon_bois_ox', 'observations'
        ]);

        // Handle checkbox fields - only include values if checkbox is checked
        $checkboxFields = [
            'has_lot' => 'lot',
            'has_superficie' => 'superficie',
            'has_bo_m3' => 'bo_m3',
            'has_bi_m3' => 'bi_m3',
            'has_bf_st' => 'bf_st',
            'has_tanin_t' => 'tanin_t',
            'has_fleur_acacia_t' => 'fleur_acacia_t',
            'has_caroube_t' => 'caroube_t',
            'has_romarin_t' => 'romarin_t',
            'has_ps_t' => 'ps_t',
            'has_liege_st' => 'liege_st',
            'has_charbon_bois_ox' => 'charbon_bois_ox'
        ];

        foreach ($checkboxFields as $checkbox => $field) {
            if (!$request->has($checkbox)) {
                $articleData[$field] = null;
            }
        }

        // Handle contract data
        if ($request->filled('invendu') || $request->filled('exploitant_id') || $request->filled('type')) {
            // Create session adjudication if contract data is provided
            $sessionData = [
                'type' => $request->type,
                'date' => $request->session_date,
                'numero' => $request->session_numero,
                'nature_juridique' => $request->nature_juridique,
                'adjudicatire' => $request->adjudicatire,
                'dc' => $request->has('dc'),
                'rc' => $request->has('rc'),
                'date_de_resiliation' => $request->date_de_resiliation,
                'date_de_decheance' => $request->date_de_decheance,
                'exploitant_id' => $request->exploitant_id,
                'is_validated' => false
            ];

            $sessionAdjudication = SessionAdjudication::create($sessionData);
            $articleData['session_adjudication_id'] = $sessionAdjudication->id;
            $articleData['invendu'] = $request->invendu;
        }

        // Create the article
        Article::create($articleData);

        return redirect()->route('articles.index')->with('success', 'Article ajouté avec succès.');
    }

    public function show(Article $article): View
    {
        $article->load([
            'situationAdministrative',
            'foret',
            'essence',
            'natureDeCoupe',
            'exploitant',
            'sessionAdjudication',
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
        $sessionAdjudications = SessionAdjudication::orderBy('date', 'desc')->get();
        $localisations = Localisation::orderBy('CODE')->get();

        return view('articles.edit', compact(
            'article',
            'situationAdministratives',
            'forets',
            'essences',
            'natureDeCoupes',
            'exploitants',
            'sessionAdjudications',
            'localisations'
        ));
    }

    public function update(UpdateArticleRequest $request, Article $article): RedirectResponse
    {
        $article->update($request->all());
        return redirect()->route('articles.index')->with('success', 'Article mis à jour avec succès.');
    }

    public function destroy(Article $article): RedirectResponse
    {
        $article->update(['is_deleted' => true]);
        return redirect()->route('articles.index')->with('success', 'Article supprimé avec succès.');
    }

    public function export(ExportArticleRequest $request)
    {
        $filters = $request->only(['annee', 'foret_id', 'essence_id', 'invendu']);
        
        return Excel::download(new ArticlesExport($filters), 'articles_' . date('Y-m-d_H-i-s') . '.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240', // 10MB max
        ]);

        try {
            Excel::import(new ArticlesImport, $request->file('file'));
            return redirect()->route('articles.index')->with('success', 'Articles importés avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'import: ' . $e->getMessage());
        }
    }
} 