<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Essence;
use App\Models\Foret;
use App\Models\NatureDeCoupe;
use App\Models\SituationAdministrative;
use App\Models\Exploitant;
use App\Models\Localisation;
use App\Services\ActivityLogger;
use App\Http\Requests\ArticlesByYearRequest;
use App\Http\Requests\ArticlesByForetRequest;
use App\Http\Requests\ArticlesByEssenceRequest;
use App\Http\Requests\ArticlesByExploitantRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(): View
    {
        // Log report dashboard view
        ActivityLogger::log('view', 'Consultation du tableau de bord des rapports', null);
        
        return view('reports.index');
    }

    public function articlesByYear(ArticlesByYearRequest $request): View
    {
        $year = $request->get('year', date('Y'));
        
        // Log report generation
        ActivityLogger::log('view', "Génération du rapport des articles par année: {$year}", Article::class);
        
        $articles = Article::with([
            'situationAdministrative', 'foret', 'essence', 'natureDeCoupe',
            'situationsAdministratives', 'forets', 'essences', 'naturesDeCoupe',
            'situationForestiere', 'exploitant', 'localisation'
        ])
        ->where('annee', $year)
        ->orderBy('date_adjudication', 'desc')
        ->get();

        $annees = Article::select('annee')->distinct()->orderBy('annee', 'desc')->get();
        
        $stats = [
            'total' => $articles->count(),
            'vendus' => $articles->where('invendu', false)->count(),
            'invendus' => $articles->where('invendu', true)->count(),
            'total_prix_vente' => $articles->sum('prix_vente'),
            'total_prix_retrait' => $articles->sum('prix_de_retrait'),
        ];

        return view('reports.articles-by-year', compact('articles', 'annees', 'year', 'stats'));
    }

    public function articlesByForet(ArticlesByForetRequest $request): View
    {
        $foretId = $request->get('foret_id');
        
        // Log report generation
        $foretName = $foretId ? Foret::find($foretId)->foret ?? 'Toutes' : 'Toutes';
        ActivityLogger::log('view', "Génération du rapport des articles par forêt: {$foretName}", Article::class);
        
        $query = Article::with([
            'situationAdministrative', 'foret', 'essence', 'natureDeCoupe',
            'situationsAdministratives', 'forets', 'essences', 'naturesDeCoupe',
            'situationForestiere', 'exploitant', 'localisation'
        ]);

        if ($foretId) {
            $query->where(function ($q) use ($foretId) {
                $q->where('foret_id', $foretId)
                  ->orWhereHas('forets', function ($qq) use ($foretId) {
                      $qq->where('forets.id', $foretId);
                  });
            });
        }

        $articles = $query->orderBy('date_adjudication', 'desc')->get();
        $forets = Foret::orderBy('foret')->get();

        $stats = [
            'total' => $articles->count(),
            'vendus' => $articles->where('invendu', false)->count(),
            'invendus' => $articles->where('invendu', true)->count(),
            'total_prix_vente' => $articles->sum('prix_vente'),
            'total_prix_retrait' => $articles->sum('prix_de_retrait'),
        ];

        return view('reports.articles-by-foret', compact('articles', 'forets', 'foretId', 'stats'));
    }

    public function articlesByEssence(ArticlesByEssenceRequest $request): View
    {
        $essenceId = $request->get('essence_id');
        
        // Log report generation
        $essenceName = $essenceId ? Essence::find($essenceId)->essence ?? 'Toutes' : 'Toutes';
        ActivityLogger::log('view', "Génération du rapport des articles par essence: {$essenceName}", Article::class);
        
        $query = Article::with([
            'situationAdministrative', 'foret', 'essence', 'natureDeCoupe',
            'situationsAdministratives', 'forets', 'essences', 'naturesDeCoupe',
            'situationForestiere', 'exploitant', 'localisation'
        ]);

        if ($essenceId) {
            $query->where(function ($q) use ($essenceId) {
                $q->where('essence_id', $essenceId)
                  ->orWhereHas('essences', function ($qq) use ($essenceId) {
                      $qq->where('essences.id', $essenceId);
                  });
            });
        }

        $articles = $query->orderBy('date_adjudication', 'desc')->get();
        $essences = Essence::orderBy('essence')->get();

        $stats = [
            'total' => $articles->count(),
            'vendus' => $articles->where('invendu', false)->count(),
            'invendus' => $articles->where('invendu', true)->count(),
            'total_prix_vente' => $articles->sum('prix_vente'),
            'total_prix_retrait' => $articles->sum('prix_de_retrait'),
        ];

        return view('reports.articles-by-essence', compact('articles', 'essences', 'essenceId', 'stats'));
    }

    public function articlesByExploitant(ArticlesByExploitantRequest $request): View
    {
        $exploitantId = $request->get('exploitant_id');
        
        // Log report generation
        $exploitantName = $exploitantId ? Exploitant::find($exploitantId)->nom_complet ?? 'Tous' : 'Tous';
        ActivityLogger::log('view', "Génération du rapport des articles par exploitant: {$exploitantName}", Article::class);
        
        $query = Article::with([
            'situationAdministrative', 'foret', 'essence', 'natureDeCoupe',
            'situationsAdministratives', 'forets', 'essences', 'naturesDeCoupe',
            'situationForestiere', 'exploitant', 'localisation'
        ]);

        if ($exploitantId) {
            $query->where('exploitant_id', $exploitantId);
        }

        $articles = $query->orderBy('date_adjudication', 'desc')->get();
        $exploitants = Exploitant::orderBy('nom_complet')->get();

        $stats = [
            'total' => $articles->count(),
            'vendus' => $articles->where('invendu', false)->count(),
            'invendus' => $articles->where('invendu', true)->count(),
            'total_prix_vente' => $articles->sum('prix_vente'),
            'total_prix_retrait' => $articles->sum('prix_de_retrait'),
        ];

        return view('reports.articles-by-exploitant', compact('articles', 'exploitants', 'exploitantId', 'stats'));
    }

    public function invendus(): View
    {
        // Log report generation
        ActivityLogger::log('view', 'Génération du rapport des articles invendus', Article::class);
        
        $articles = Article::with([
            'situationAdministrative',
            'situationForestiere',
            'foret',
            'essence',
            'natureDeCoupe',
            'exploitant',
            'localisation'
        ])
        ->where('invendu', true)
        ->orderBy('date_adjudication', 'desc')
        ->get();

        $stats = [
            'total' => $articles->count(),
            'total_prix_retrait' => $articles->sum('prix_de_retrait'),
        ];

        return view('reports.invendus', compact('articles', 'stats'));
    }

    public function vendus(): View
    {
        // Log report generation
        ActivityLogger::log('view', 'Génération du rapport des articles vendus', Article::class);
        
        $articles = Article::with([
            'situationAdministrative',
            'situationForestiere',
            'foret',
            'essence',
            'natureDeCoupe',
            'exploitant',
            'localisation'
        ])
        ->where('invendu', false)
        ->orderBy('date_adjudication', 'desc')
        ->get();

        $stats = [
            'total' => $articles->count(),
            'total_prix_vente' => $articles->sum('prix_vente'),
            'total_prix_retrait' => $articles->sum('prix_de_retrait'),
        ];

        return view('reports.vendus', compact('articles', 'stats'));
    }

    public function summary(): View
    {
        // Log report generation
        ActivityLogger::log('view', 'Génération du rapport de synthèse général', Article::class);
        
        // Get summary statistics
        $totalArticles = Article::count();
        $totalVendus = Article::where('invendu', false)->count();
        $totalInvendus = Article::where('invendu', true)->count();
        $totalPrixVente = Article::sum('prix_vente');
        $totalPrixRetrait = Article::sum('prix_de_retrait');

        // Get statistics by year
        $statsByYear = Article::selectRaw('annee, COUNT(*) as total, SUM(CASE WHEN invendu = 0 THEN 1 ELSE 0 END) as vendus, SUM(CASE WHEN invendu = 1 THEN 1 ELSE 0 END) as invendus, SUM(prix_vente) as total_prix_vente, SUM(prix_de_retrait) as total_prix_retrait')
            ->groupBy('annee')
            ->orderBy('annee', 'desc')
            ->get();

        // Get statistics by forest
        $statsByForet = Article::join('forets', 'articles.foret_id', '=', 'forets.id')
            ->selectRaw('forets.foret, COUNT(*) as total, SUM(CASE WHEN articles.invendu = 0 THEN 1 ELSE 0 END) as vendus, SUM(CASE WHEN articles.invendu = 1 THEN 1 ELSE 0 END) as invendus, SUM(articles.prix_vente) as total_prix_vente, SUM(articles.prix_de_retrait) as total_prix_retrait')
            ->groupBy('forets.id', 'forets.foret')
            ->orderBy('forets.foret')
            ->get();

        // Get statistics by essence
        $statsByEssence = Article::join('essences', 'articles.essence_id', '=', 'essences.id')
            ->selectRaw('essences.essence, COUNT(*) as total, SUM(CASE WHEN articles.invendu = 0 THEN 1 ELSE 0 END) as vendus, SUM(CASE WHEN articles.invendu = 1 THEN 1 ELSE 0 END) as invendus, SUM(articles.prix_vente) as total_prix_vente, SUM(articles.prix_de_retrait) as total_prix_retrait')
            ->groupBy('essences.id', 'essences.essence')
            ->orderBy('essences.essence')
            ->get();

        $summary = [
            'total_articles' => $totalArticles,
            'total_vendus' => $totalVendus,
            'total_invendus' => $totalInvendus,
            'total_prix_vente' => $totalPrixVente,
            'total_prix_retrait' => $totalPrixRetrait,
            'stats_by_year' => $statsByYear,
            'stats_by_foret' => $statsByForet,
            'stats_by_essence' => $statsByEssence,
        ];

        return view('reports.summary', compact('summary'));
    }

    public function exportSummary()
    {
        // Log export action
        ActivityLogger::logExport('Rapport de Synthèse', 'Excel', request());
        
        // Get summary data for export
        $data = $this->getSummaryDataForExport();
        
        $filename = 'rapport_synthese_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download(new SummaryExport($data), $filename);
    }

    public function articlesByNatureDeCoupe(Request $request): View
    {
        $natureDeCoupeId = $request->get('nature_de_coupe_id');
        
        // Log report generation
        $natureName = $natureDeCoupeId ? NatureDeCoupe::find($natureDeCoupeId)->nature_de_coupe ?? 'Toutes' : 'Toutes';
        ActivityLogger::log('view', "Génération du rapport des articles par nature de coupe: {$natureName}", Article::class);
        
        $query = Article::with([
            'situationAdministrative', 'foret', 'essence', 'natureDeCoupe',
            'situationsAdministratives', 'forets', 'essences', 'naturesDeCoupe',
            'situationForestiere', 'exploitant', 'localisation'
        ]);

        if ($natureDeCoupeId) {
            $query->where(function ($q) use ($natureDeCoupeId) {
                $q->where('nature_de_coupe_id', $natureDeCoupeId)
                  ->orWhereHas('naturesDeCoupe', function ($qq) use ($natureDeCoupeId) {
                      $qq->where('nature_de_coupes.id', $natureDeCoupeId);
                  });
            });
        }

        $articles = $query->orderBy('date_adjudication', 'desc')->get();
        $natureDeCoupes = NatureDeCoupe::orderBy('nature_de_coupe')->get();

        $stats = [
            'total' => $articles->count(),
            'vendus' => $articles->where('invendu', false)->count(),
            'invendus' => $articles->where('invendu', true)->count(),
            'total_prix_vente' => $articles->sum('prix_vente'),
            'total_prix_retrait' => $articles->sum('prix_de_retrait'),
        ];

        return view('reports.articles-by-nature-de-coupe', compact('articles', 'natureDeCoupes', 'natureDeCoupeId', 'stats'));
    }

    public function articlesByLocalisation(Request $request): View
    {
        $localisationId = $request->get('localisation_id');
        
        // Log report generation
        $localisationName = $localisationId ? Localisation::find($localisationId)->ENTITE ?? 'Toutes' : 'Toutes';
        ActivityLogger::log('view', "Génération du rapport des articles par localisation: {$localisationName}", Article::class);
        
        $query = Article::with([
            'situationAdministrative',
            'situationForestiere',
            'foret',
            'essence',
            'natureDeCoupe',
            'exploitant',
            'localisation'
        ]);

        if ($localisationId) {
            $query->where('localisation_id', $localisationId);
        }

        $articles = $query->orderBy('date_adjudication', 'desc')->get();
        $localisations = Localisation::orderBy('ENTITE')->get();

        $stats = [
            'total' => $articles->count(),
            'vendus' => $articles->where('invendu', false)->count(),
            'invendus' => $articles->where('invendu', true)->count(),
            'total_prix_vente' => $articles->sum('prix_vente'),
            'total_prix_retrait' => $articles->sum('prix_de_retrait'),
        ];

        return view('reports.articles-by-localisation', compact('articles', 'localisations', 'localisationId', 'stats'));
    }

    public function articlesByValidationStatus(Request $request): View
    {
        $status = $request->get('status');
        
        // Log report generation
        $statusName = $status === 'validated' ? 'Validés' : ($status === 'pending' ? 'En attente' : 'Tous');
        ActivityLogger::log('view', "Génération du rapport des articles par statut de validation: {$statusName}", Article::class);
        
        $query = Article::with([
            'situationAdministrative',
            'situationForestiere',
            'foret',
            'essence',
            'natureDeCoupe',
            'exploitant',
            'localisation'
        ]);

        if ($status) {
            if ($status === 'validated') {
                $query->where('is_validated', true);
            } elseif ($status === 'pending') {
                $query->where('is_validated', false);
            }
        }

        $articles = $query->orderBy('date', 'desc')->get();

        $stats = [
            'total' => $articles->count(),
            'validated' => $articles->where('is_validated', true)->count(),
            'pending' => $articles->where('is_validated', false)->count(),
            'vendus' => $articles->where('invendu', false)->count(),
            'invendus' => $articles->where('invendu', true)->count(),
            'total_prix_vente' => $articles->sum('prix_vente'),
            'total_prix_retrait' => $articles->sum('prix_de_retrait'),
        ];

        return view('reports.articles-by-validation-status', compact('articles', 'status', 'stats'));
    }
} 