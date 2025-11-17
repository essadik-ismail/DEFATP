<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Essence;
use App\Models\Foret;
use App\Models\NatureDeCoupe;
use App\Models\SituationAdministrative;
use App\Models\Exploitant;
use App\Models\Localisation;
use App\Models\LegacyArticle;
use App\Models\Contract;
use App\Services\ActivityLogger;
use App\Http\Requests\ArticlesByYearRequest;
use App\Http\Requests\ArticlesByForetRequest;
use App\Http\Requests\ArticlesByEssenceRequest;
use App\Http\Requests\ArticlesByExploitantRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        // Log report dashboard view
        ActivityLogger::log('view', 'Consultation du tableau de bord des rapports', null);
        
        // Get date filters from request
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        
        // Build base query with date filtering
        $baseQuery = Article::withoutGlobalScope('not_deleted')
            ->where('articles.is_deleted', false);
            
        if ($startDate) {
            $baseQuery->where('articles.created_at', '>=', $startDate);
        }
        
        if ($endDate) {
            $baseQuery->where('articles.created_at', '<=', $endDate . ' 23:59:59');
        }
        
        // Lightweight aggregates for charts on the reports dashboard
        $byYear = (clone $baseQuery)
            ->selectRaw('articles.annee, COUNT(*) as total, SUM(COALESCE(bo_m3, 0) + COALESCE(bi_m3, 0)) as volume')
            ->groupBy('articles.annee')
            ->orderBy('articles.annee', 'asc')
            ->get();

        $byForet = (clone $baseQuery)
            ->join('article_foret', 'articles.id', '=', 'article_foret.article_id')
            ->join('forets', 'article_foret.foret_id', '=', 'forets.id')
            ->selectRaw('forets.foret as label, COUNT(*) as total, SUM(COALESCE(articles.bo_m3, 0) + COALESCE(articles.bi_m3, 0)) as volume')
            ->where('forets.is_deleted', false)
            ->groupBy('forets.id', 'forets.foret')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        $byEssence = (clone $baseQuery)
            ->join('article_essence', 'articles.id', '=', 'article_essence.article_id')
            ->join('essences', 'article_essence.essence_id', '=', 'essences.id')
            ->selectRaw('essences.essence as label, COUNT(*) as total, SUM(COALESCE(articles.bo_m3, 0) + COALESCE(articles.bi_m3, 0)) as volume')
            ->where('essences.is_deleted', false)
            ->groupBy('essences.id', 'essences.essence')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        $byExploitant = (clone $baseQuery)
            ->join('exploitants', 'articles.exploitant_id', '=', 'exploitants.id')
            ->selectRaw('COALESCE(exploitants.nom_complet, exploitants.raison_sociale) as label, COUNT(*) as total, SUM(COALESCE(articles.bo_m3, 0) + COALESCE(articles.bi_m3, 0)) as volume')
            ->where('exploitants.is_deleted', false)
            ->groupBy('exploitants.id', 'exploitants.nom_complet', 'exploitants.raison_sociale')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        $byNature = (clone $baseQuery)
            ->join('article_nature_de_coupe', 'articles.id', '=', 'article_nature_de_coupe.article_id')
            ->join('nature_de_coupes', 'article_nature_de_coupe.nature_de_coupe_id', '=', 'nature_de_coupes.id')
            ->selectRaw('nature_de_coupes.nature_de_coupe as label, COUNT(*) as total, SUM(COALESCE(articles.bo_m3, 0) + COALESCE(articles.bi_m3, 0)) as volume')
            ->where('nature_de_coupes.is_deleted', false)
            ->groupBy('nature_de_coupes.id', 'nature_de_coupes.nature_de_coupe')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        $byLocalisation = (clone $baseQuery)
            ->join('article_localisation', 'articles.id', '=', 'article_localisation.article_id')
            ->join('localisations', 'article_localisation.localisation_id', '=', 'localisations.id')
            ->selectRaw('localisations.DRANEF as label, COUNT(*) as total, SUM(COALESCE(articles.bo_m3, 0) + COALESCE(articles.bi_m3, 0)) as volume')
            ->where('localisations.is_deleted', false)
            ->groupBy('localisations.id', 'localisations.DRANEF')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        if (Schema::hasColumn('articles', 'is_validated')) {
            $byValidation = (clone $baseQuery)
                ->selectRaw('articles.is_validated, COUNT(*) as total')
                ->groupBy('articles.is_validated')
                ->get();
        } else {
            // Fallback if column doesn't exist
            $validated = (clone $baseQuery)
                ->whereRaw('1=0') // no validated info available
                ->count();
            $pending = (clone $baseQuery)
                ->count();
            $byValidation = collect([
                (object)['is_validated' => 1, 'total' => $validated],
                (object)['is_validated' => 0, 'total' => $pending],
            ]);
        }
        
        return view('reports.index', compact(
            'byYear', 'byForet', 'byEssence', 'byExploitant', 'byNature', 'byLocalisation', 'byValidation'
        ));
    }

    public function articlesByYear(ArticlesByYearRequest $request): View
    {
        $year = $request->get('year', date('Y'));
        
        // Log report generation
        ActivityLogger::log('view', "Génération du rapport des articles par année: {$year}", Article::class);
        
        $articles = Article::with([
            'situationAdministrative', 'foret', 'essence', 'natureDeCoupe',
            'situationsAdministratives', 'forets', 'essences', 'naturesDeCoupe',
            'exploitant', 'localisation'
        ])
        ->where('annee', $year)
        ->orderBy('date_adjudication', 'desc')
        ->paginate(15);

        $annees = Article::select('annee')->distinct()->orderBy('annee', 'desc')->get();
        
        // Calculate stats from all articles for the year (not just paginated ones)
        $allArticles = Article::where('annee', $year)->get();
        $stats = [
            'total' => $allArticles->count(),
            'vendus' => $allArticles->where('invendu', false)->count(),
            'invendus' => $allArticles->where('invendu', true)->count(),
            'total_prix_vente' => $allArticles->sum('prix_vente'),
            'total_prix_retrait' => $allArticles->sum('prix_de_retrait'),
            'total_volume' => $allArticles->sum(function($article) {
                return ($article->bo_m3 ?? 0) + ($article->bi_m3 ?? 0);
            }),
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
            'exploitant', 'localisation'
        ]);

        if ($foretId) {
            $query->whereHas('forets', function ($qq) use ($foretId) {
                $qq->where('forets.id', $foretId);
            });
        }

        $articles = $query->orderBy('date_adjudication', 'desc')->paginate(15);
        $forets = Foret::orderBy('foret')->get();

        // Calculate stats from all articles (not just paginated ones)
        $allArticles = $query->get();
        $stats = [
            'total' => $allArticles->count(),
            'vendus' => $allArticles->where('invendu', false)->count(),
            'invendus' => $allArticles->where('invendu', true)->count(),
            'total_prix_vente' => $allArticles->sum('prix_vente'),
            'total_prix_retrait' => $allArticles->sum('prix_de_retrait'),
            'total_volume' => $allArticles->sum(function($article) {
                return ($article->bo_m3 ?? 0) + ($article->bi_m3 ?? 0);
            }),
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
            'exploitant', 'localisation'
        ]);

        if ($essenceId) {
            $query->whereHas('essences', function ($qq) use ($essenceId) {
                $qq->where('essences.id', $essenceId);
            });
        }

        $articles = $query->orderBy('date_adjudication', 'desc')->paginate(15);
        $essences = Essence::orderBy('essence')->get();

        // Calculate stats from all articles (not just paginated ones)
        $allArticles = $query->get();
        $stats = [
            'total' => $allArticles->count(),
            'vendus' => $allArticles->where('invendu', false)->count(),
            'invendus' => $allArticles->where('invendu', true)->count(),
            'total_prix_vente' => $allArticles->sum('prix_vente'),
            'total_prix_retrait' => $allArticles->sum('prix_de_retrait'),
            'total_volume' => $allArticles->sum(function($article) {
                return ($article->bo_m3 ?? 0) + ($article->bi_m3 ?? 0);
            }),
        ];

        // Get essence statistics for chart
        $essenceStats = Article::withoutGlobalScope('not_deleted')
            ->join('article_essence', 'articles.id', '=', 'article_essence.article_id')
            ->join('essences', 'article_essence.essence_id', '=', 'essences.id')
            ->selectRaw('essences.essence as label, COUNT(*) as total')
            ->where('articles.is_deleted', false)
            ->where('essences.is_deleted', false)
            ->groupBy('essences.id', 'essences.essence')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return view('reports.articles-by-essence', compact('articles', 'essences', 'essenceId', 'stats', 'essenceStats'));
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
            'exploitant', 'localisation'
        ]);

        if ($exploitantId) {
            $query->where('exploitant_id', $exploitantId);
        }

        $articles = $query->orderBy('date_adjudication', 'desc')->paginate(15);
        $exploitants = Exploitant::orderBy('nom_complet')->get();

        // Calculate stats from all articles (not just paginated ones)
        $allArticles = $query->get();
        $stats = [
            'total' => $allArticles->count(),
            'vendus' => $allArticles->where('invendu', false)->count(),
            'invendus' => $allArticles->where('invendu', true)->count(),
            'total_prix_vente' => $allArticles->sum('prix_vente'),
            'total_prix_retrait' => $allArticles->sum('prix_de_retrait'),
            'total_volume' => $allArticles->sum(function($article) {
                return ($article->bo_m3 ?? 0) + ($article->bi_m3 ?? 0);
            }),
        ];

        return view('reports.articles-by-exploitant', compact('articles', 'exploitants', 'exploitantId', 'stats'));
    }

    public function invendus(): View
    {
        // Log report generation
        ActivityLogger::log('view', 'Génération du rapport des articles invendus', Article::class);
        
        $articles = Article::with([
            'situationAdministrative',
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
            'total_volume' => $articles->sum(function($article) {
                return ($article->bo_m3 ?? 0) + ($article->bi_m3 ?? 0);
            }),
        ];

        return view('reports.invendus', compact('articles', 'stats'));
    }

    public function vendus(): View
    {
        // Log report generation
        ActivityLogger::log('view', 'Génération du rapport des articles vendus', Article::class);
        
        $articles = Article::with([
            'situationAdministrative',
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
            'total_volume' => $articles->sum(function($article) {
                return ($article->bo_m3 ?? 0) + ($article->bi_m3 ?? 0);
            }),
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
        $totalVolume = Article::selectRaw('SUM(COALESCE(bo_m3, 0) + COALESCE(bi_m3, 0)) as total')->value('total') ?? 0;

        // Get statistics by year
        $statsByYear = Article::selectRaw('annee, COUNT(*) as total, SUM(CASE WHEN invendu = 0 THEN 1 ELSE 0 END) as vendus, SUM(CASE WHEN invendu = 1 THEN 1 ELSE 0 END) as invendus, SUM(prix_vente) as total_prix_vente, SUM(prix_de_retrait) as total_prix_retrait, SUM(COALESCE(bo_m3, 0) + COALESCE(bi_m3, 0)) as total_volume')
            ->groupBy('annee')
            ->orderBy('annee', 'desc')
            ->get();

        // Get statistics by forest
        $statsByForet = Article::join('article_foret', 'articles.id', '=', 'article_foret.article_id')
            ->join('forets', 'article_foret.foret_id', '=', 'forets.id')
            ->selectRaw('forets.foret, COUNT(*) as total, SUM(CASE WHEN articles.invendu = 0 THEN 1 ELSE 0 END) as vendus, SUM(CASE WHEN articles.invendu = 1 THEN 1 ELSE 0 END) as invendus, SUM(articles.prix_vente) as total_prix_vente, SUM(articles.prix_de_retrait) as total_prix_retrait, SUM(COALESCE(articles.bo_m3, 0) + COALESCE(articles.bi_m3, 0)) as total_volume')
            ->groupBy('forets.id', 'forets.foret')
            ->orderBy('forets.foret')
            ->get();

        // Get statistics by essence
        $statsByEssence = Article::join('article_essence', 'articles.id', '=', 'article_essence.article_id')
            ->join('essences', 'article_essence.essence_id', '=', 'essences.id')
            ->selectRaw('essences.essence, COUNT(*) as total, SUM(CASE WHEN articles.invendu = 0 THEN 1 ELSE 0 END) as vendus, SUM(CASE WHEN articles.invendu = 1 THEN 1 ELSE 0 END) as invendus, SUM(articles.prix_vente) as total_prix_vente, SUM(articles.prix_de_retrait) as total_prix_retrait, SUM(COALESCE(articles.bo_m3, 0) + COALESCE(articles.bi_m3, 0)) as total_volume')
            ->groupBy('essences.id', 'essences.essence')
            ->orderBy('essences.essence')
            ->get();

        // Get statistics by exploitant
        $statsByExploitant = Article::join('exploitants', 'articles.exploitant_id', '=', 'exploitants.id')
            ->selectRaw('COALESCE(exploitants.nom_complet, exploitants.raison_sociale) as exploitant, COUNT(*) as total, SUM(CASE WHEN articles.invendu = 0 THEN 1 ELSE 0 END) as vendus, SUM(CASE WHEN articles.invendu = 1 THEN 1 ELSE 0 END) as invendus, SUM(articles.prix_vente) as total_prix_vente, SUM(articles.prix_de_retrait) as total_prix_retrait, SUM(COALESCE(articles.bo_m3, 0) + COALESCE(articles.bi_m3, 0)) as total_volume')
            ->where('exploitants.is_deleted', false)
            ->groupBy('exploitants.id', 'exploitants.nom_complet', 'exploitants.raison_sociale')
            ->orderBy('exploitant')
            ->get();

        // Get statistics by localisation
        $statsByLocalisation = Article::join('article_localisation', 'articles.id', '=', 'article_localisation.article_id')
            ->join('localisations', 'article_localisation.localisation_id', '=', 'localisations.id')
            ->selectRaw('localisations.DRANEF, COUNT(*) as total, SUM(CASE WHEN articles.invendu = 0 THEN 1 ELSE 0 END) as vendus, SUM(CASE WHEN articles.invendu = 1 THEN 1 ELSE 0 END) as invendus, SUM(articles.prix_vente) as total_prix_vente, SUM(articles.prix_de_retrait) as total_prix_retrait, SUM(COALESCE(articles.bo_m3, 0) + COALESCE(articles.bi_m3, 0)) as total_volume')
            ->where('localisations.is_deleted', false)
            ->groupBy('localisations.id', 'localisations.DRANEF')
            ->orderBy('localisations.DRANEF')
            ->get();

        $summary = [
            'total_articles' => $totalArticles,
            'total_vendus' => $totalVendus,
            'total_invendus' => $totalInvendus,
            'total_prix_vente' => $totalPrixVente,
            'total_prix_retrait' => $totalPrixRetrait,
            'total_volume' => $totalVolume,
            'stats_by_year' => $statsByYear,
            'stats_by_foret' => $statsByForet,
            'stats_by_essence' => $statsByEssence,
            'stats_by_exploitant' => $statsByExploitant,
            'stats_by_localisation' => $statsByLocalisation,
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
            'exploitant', 'localisation'
        ]);

        if ($natureDeCoupeId) {
            $query->where(function ($q) use ($natureDeCoupeId) {
                $q->where('nature_de_coupe_id', $natureDeCoupeId)
                  ->orWhereHas('naturesDeCoupe', function ($qq) use ($natureDeCoupeId) {
                      $qq->where('nature_de_coupes.id', $natureDeCoupeId);
                  });
            });
        }

        $articles = $query->orderBy('date_adjudication', 'desc')->paginate(15);
        $natureDeCoupes = NatureDeCoupe::orderBy('nature_de_coupe')->get();

        // Calculate stats from all articles (not just paginated ones)
        $allArticles = $query->get();
        $stats = [
            'total' => $allArticles->count(),
            'vendus' => $allArticles->where('invendu', false)->count(),
            'invendus' => $allArticles->where('invendu', true)->count(),
            'total_prix_vente' => $allArticles->sum('prix_vente'),
            'total_prix_retrait' => $allArticles->sum('prix_de_retrait'),
            'total_volume' => $allArticles->sum(function($article) {
                return ($article->bo_m3 ?? 0) + ($article->bi_m3 ?? 0);
            }),
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
            'foret',
            'essence',
            'natureDeCoupe',
            'exploitant',
            'localisation'
        ]);

        if ($localisationId) {
            $query->where('localisation_id', $localisationId);
        }

        $articles = $query->orderBy('date_adjudication', 'desc')->paginate(15);
        $localisations = Localisation::orderBy('ENTITE')->get();

        // Calculate stats from all articles (not just paginated ones)
        $allArticles = $query->get();
        $stats = [
            'total' => $allArticles->count(),
            'vendus' => $allArticles->where('invendu', false)->count(),
            'invendus' => $allArticles->where('invendu', true)->count(),
            'total_prix_vente' => $allArticles->sum('prix_vente'),
            'total_prix_retrait' => $allArticles->sum('prix_de_retrait'),
            'total_volume' => $allArticles->sum(function($article) {
                return ($article->bo_m3 ?? 0) + ($article->bi_m3 ?? 0);
            }),
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

        $articles = $query->orderBy('date_adjudication', 'desc')->paginate(15);

        // Calculate stats from all articles (not just paginated ones)
        $allArticles = $query->get();
        $stats = [
            'total' => $allArticles->count(),
            'validated' => $allArticles->where('is_validated', true)->count(),
            'pending' => $allArticles->where('is_validated', false)->count(),
            'vendus' => $allArticles->where('invendu', false)->count(),
            'invendus' => $allArticles->where('invendu', true)->count(),
            'total_prix_vente' => $allArticles->sum('prix_vente'),
            'total_prix_retrait' => $allArticles->sum('prix_de_retrait'),
            'total_volume' => $allArticles->sum(function($article) {
                return ($article->bo_m3 ?? 0) + ($article->bi_m3 ?? 0);
            }),
        ];

        return view('reports.articles-by-validation-status', compact('articles', 'status', 'stats'));
    }

    public function legacyArticles(Request $request): View
    {
        // Log report generation
        ActivityLogger::log('view', 'Génération du rapport des articles historiques', LegacyArticle::class);
        
        // Build base query with date filtering
        $query = LegacyArticle::query();
        
        // Apply date filters if provided
        if ($request->filled('start_date') || $request->filled('end_date')) {
            $query->where(function($q) use ($request) {
                if ($request->filled('start_date')) {
                    $startDate = \Carbon\Carbon::parse($request->start_date);
                    $startDateFormatted = $startDate->format('ymd'); // Convert to YYMMDD format
                    $q->where('date', '>=', $startDateFormatted);
                }
                
                if ($request->filled('end_date')) {
                    $endDate = \Carbon\Carbon::parse($request->end_date);
                    $endDateFormatted = $endDate->format('ymd'); // Convert to YYMMDD format
                    $q->where('date', '<=', $endDateFormatted);
                }
            });
        }
        
        // Get basic statistics
        $totalLegacyArticles = (clone $query)->count();
        $totalProvinces = (clone $query)->distinct('province')->count();
        $totalEssences = (clone $query)->distinct('essence')->count();
        $totalForets = (clone $query)->distinct('foret')->count();
        
        // Calculate total revenue and volume
        $totalRevenue = (clone $query)->sum('ppdh') ?? 0;
        $totalVolume = (clone $query)->sum('bom3') ?? 0;
        
        // Get statistics by province
        $byProvince = (clone $query)
            ->selectRaw('province, COUNT(*) as total')
            ->whereNotNull('province')
            ->groupBy('province')
            ->orderByDesc('total')
            ->limit(10)
            ->get();
            
        // Get statistics by essence
        $byEssence = (clone $query)
            ->selectRaw('essence, COUNT(*) as total')
            ->whereNotNull('essence')
            ->groupBy('essence')
            ->orderByDesc('total')
            ->limit(10)
            ->get();
            
        // Get statistics by year
        $byYear = (clone $query)
            ->selectRaw('CASE 
                WHEN LENGTH(date) >= 8 THEN SUBSTRING(date, 1, 4)
                WHEN LENGTH(date) >= 6 THEN CONCAT(\'20\', SUBSTRING(date, 1, 2))
                ELSE NULL
            END as year, COUNT(*) as total')
            ->whereNotNull('date')
            ->where('date', '!=', '')
            ->whereRaw('LENGTH(date) >= 6')
            ->groupBy('year')
            ->orderBy('year')
            ->get();
            
        // Get statistics by DREF
        $byDref = (clone $query)
            ->selectRaw('dref, COUNT(*) as total')
            ->whereNotNull('dref')
            ->groupBy('dref')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // Get quantities by year (Bo m3, Bi m3, Bf st, Liége st)
        $quantitiesByYear = (clone $query)
            ->selectRaw('CASE 
                WHEN LENGTH(date) >= 8 THEN SUBSTRING(date, 1, 4)
                WHEN LENGTH(date) >= 6 THEN CONCAT(\'20\', SUBSTRING(date, 1, 2))
                ELSE NULL
            END as year, 
                        SUM(COALESCE(bom3, 0)) as bom3,
                        SUM(COALESCE(bim3, 0)) as bim3,
                        SUM(COALESCE(bfst, 0)) as bfst,
                        SUM(COALESCE(lcst, 0)) as lcst')
            ->whereNotNull('date')
            ->where('date', '!=', '')
            ->whereRaw('LENGTH(date) >= 6')
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        // Get quantities by province
        $quantitiesByProvince = (clone $query)
            ->selectRaw('province, 
                        SUM(COALESCE(bom3, 0)) as bom3,
                        SUM(COALESCE(bim3, 0)) as bim3,
                        SUM(COALESCE(bfst, 0)) as bfst,
                        SUM(COALESCE(lcst, 0)) as lcst')
            ->whereNotNull('province')
            ->groupBy('province')
            ->orderByDesc('bom3')
            ->limit(10)
            ->get();

        // Get quantities by essence
        $quantitiesByEssence = (clone $query)
            ->selectRaw('essence, 
                        SUM(COALESCE(bom3, 0)) as bom3,
                        SUM(COALESCE(bim3, 0)) as bim3,
                        SUM(COALESCE(bfst, 0)) as bfst,
                        SUM(COALESCE(lcst, 0)) as lcst')
            ->whereNotNull('essence')
            ->groupBy('essence')
            ->orderByDesc('bom3')
            ->limit(10)
            ->get();

        // Get quantities by DREF
        $quantitiesByDref = (clone $query)
            ->selectRaw('dref, 
                        SUM(COALESCE(bom3, 0)) as bom3,
                        SUM(COALESCE(bim3, 0)) as bim3,
                        SUM(COALESCE(bfst, 0)) as bfst,
                        SUM(COALESCE(lcst, 0)) as lcst')
            ->whereNotNull('dref')
            ->groupBy('dref')
            ->orderByDesc('bom3')
            ->limit(10)
            ->get();

        // Get all data for the table (limited to reasonable amount for client-side processing)
        $tableData = (clone $query)->orderBy('created_at', 'desc')->limit(1000)->get()->map(function($article) {
            // Format date
            $formattedDate = 'N/A';
            if ($article->date && strlen(trim($article->date)) >= 6) {
                $dateStr = trim($article->date);
                try {
                    if (preg_match('/^\d{6}$/', $dateStr)) {
                        $formattedDate = \Carbon\Carbon::createFromFormat('ymd', $dateStr)->format('d/m/Y');
                    } elseif (preg_match('/^\d{8}$/', $dateStr)) {
                        $formattedDate = \Carbon\Carbon::createFromFormat('Ymd', $dateStr)->format('d/m/Y');
                    } else {
                        $formattedDate = $dateStr;
                    }
                } catch (\Exception $e) {
                    $formattedDate = $dateStr;
                }
            }

            // Calculate total volume
            $totalVolume = ($article->bom3 ?? 0) + ($article->bim3 ?? 0) + ($article->bfst ?? 0) + 
                          ($article->lcst ?? 0) + ($article->ett ?? 0) + ($article->pst ?? 0);
            $formattedVolume = $totalVolume > 0 ? number_format($totalVolume, 2) : 'N/A';

            return [
                'dref' => $article->dref ?? 'N/A',
                'foret' => $article->foret ?? 'N/A',
                'province' => $article->province ?? 'N/A',
                'date' => $formattedDate,
                'essence' => $article->essence ?? 'N/A',
                'surface' => $article->surface ? number_format($article->surface, 2) : 'N/A',
                'volume' => $formattedVolume,
                'ppdh' => $article->ppdh ? number_format($article->ppdh, 2) : 'N/A',
            ];
        });

        // Get filter options for the view
        $provinces = LegacyArticle::select('province')->distinct()->whereNotNull('province')->orderBy('province')->pluck('province');
        $essences = LegacyArticle::select('essence')->distinct()->whereNotNull('essence')->orderBy('essence')->pluck('essence');
        $drefs = LegacyArticle::select('dref')->distinct()->whereNotNull('dref')->orderBy('dref')->pluck('dref');
        $years = LegacyArticle::selectRaw('CASE 
                WHEN LENGTH(date) >= 8 THEN SUBSTRING(date, 1, 4)
                WHEN LENGTH(date) >= 6 THEN CONCAT(\'20\', SUBSTRING(date, 1, 2))
                ELSE NULL
            END as year')
            ->distinct()
            ->whereNotNull('date')
            ->where('date', '!=', '')
            ->whereRaw('LENGTH(date) >= 6')
            ->orderBy('year')
            ->get()
            ->pluck('year')
            ->filter()
            ->unique()
            ->sort()
            ->values();

        $stats = [
            'total_records' => $totalLegacyArticles,
            'total_revenue' => $totalRevenue,
            'total_volume' => $totalVolume,
            'total_provinces' => $totalProvinces,
            'total_essences' => $totalEssences,
            'total_forets' => $totalForets,
            'by_year' => $byYear,
            'by_province' => $byProvince,
            'by_essence' => $byEssence,
            'by_dref' => $byDref,
            'quantities_by_year' => $quantitiesByYear,
            'quantities_by_province' => $quantitiesByProvince,
            'quantities_by_essence' => $quantitiesByEssence,
            'quantities_by_dref' => $quantitiesByDref,
        ];

        return view('reports.legacy-articles', compact('stats', 'tableData', 'provinces', 'essences', 'drefs', 'years'));
    }

    public function legacyArticlesTable(Request $request): View|JsonResponse
    {
        // Log report generation
        ActivityLogger::log('view', 'Consultation du tableau des articles historiques', LegacyArticle::class);
        
        // If this is an AJAX request for DataTables
        if ($request->ajax()) {
            return $this->getLegacyArticlesData($request);
        }
        
        // Get filter options for the view
        $provinces = LegacyArticle::select('province')->distinct()->whereNotNull('province')->orderBy('province')->pluck('province');
        $essences = LegacyArticle::select('essence')->distinct()->whereNotNull('essence')->orderBy('essence')->pluck('essence');
        $forets = LegacyArticle::select('foret')->distinct()->whereNotNull('foret')->orderBy('foret')->pluck('foret');
        $drefs = LegacyArticle::select('dref')->distinct()->whereNotNull('dref')->orderBy('dref')->pluck('dref');
        $years = LegacyArticle::selectRaw('CASE 
                WHEN LENGTH(date) >= 8 THEN SUBSTRING(date, 1, 4)
                WHEN LENGTH(date) >= 6 THEN CONCAT(\'20\', SUBSTRING(date, 1, 2))
                ELSE NULL
            END as year')
            ->distinct()
            ->whereNotNull('date')
            ->where('date', '!=', '')
            ->whereRaw('LENGTH(date) >= 6')
            ->orderBy('year')
            ->get()
            ->pluck('year')
            ->filter()
            ->unique()
            ->sort()
            ->values();
        
        // Get initial stats (will be updated via AJAX)
        $stats = [
            'total_records' => LegacyArticle::count(),
            'total_revenue' => LegacyArticle::sum('ppdh') ?? 0,
            'total_volume' => LegacyArticle::sum('bom3') ?? 0,
            'total_surface' => LegacyArticle::sum('surface') ?? 0,
            'avg_price' => LegacyArticle::avg('ppdh') ?? 0,
            'avg_volume' => LegacyArticle::avg('bom3') ?? 0,
        ];

        return view('reports.legacy-articles-table', compact('provinces', 'essences', 'forets', 'drefs', 'years', 'stats'));
    }

    /**
     * Get legacy articles data for DataTables (AJAX)
     */
    private function getLegacyArticlesData(Request $request): JsonResponse
    {
        $query = LegacyArticle::query();

        // Get DataTables parameters
        $draw = $request->get('draw', 1);
        $start = $request->get('start', 0);
        $length = $request->get('length', 10);
        
        // Safely get search value
        $search = $request->get('search', []);
        $searchValue = is_array($search) && isset($search['value']) ? $search['value'] : '';
        
        // Safely get order parameters
        $order = $request->get('order', []);
        $orderColumn = (is_array($order) && isset($order[0]['column'])) ? $order[0]['column'] : 3;
        $orderDir = (is_array($order) && isset($order[0]['dir'])) ? $order[0]['dir'] : 'desc';

        // Column mapping for DataTables
        $columnMap = [
            0 => 'dref',
            1 => 'foret',
            2 => 'province',
            3 => 'date',
            4 => 'essence',
            5 => 'intervent',
            6 => 'surface',
            7 => 'bom3',
            8 => 'bim3',
            9 => 'bfst',
            10 => 'acheteur',
            11 => 'ppdh',
        ];
        $orderColumnName = $columnMap[$orderColumn] ?? 'date';

        // Search functionality
        if (!empty($searchValue)) {
            $query->where(function($q) use ($searchValue) {
                $q->where('dref', 'like', "%{$searchValue}%")
                  ->orWhere('foret', 'like', "%{$searchValue}%")
                  ->orWhere('province', 'like', "%{$searchValue}%")
                  ->orWhere('essence', 'like', "%{$searchValue}%")
                  ->orWhere('acheteur', 'like', "%{$searchValue}%")
                  ->orWhere('intervent', 'like', "%{$searchValue}%");
            });
        }

        // Apply date filters
        if ($request->filled('start_date') || $request->filled('end_date')) {
            $query->where(function($q) use ($request) {
                if ($request->filled('start_date')) {
                    $startDate = \Carbon\Carbon::parse($request->start_date);
                    $startDateFormatted = $startDate->format('ymd'); // Convert to YYMMDD format
                    $q->where('date', '>=', $startDateFormatted);
                }
                
                if ($request->filled('end_date')) {
                    $endDate = \Carbon\Carbon::parse($request->end_date);
                    $endDateFormatted = $endDate->format('ymd'); // Convert to YYMMDD format
                    $q->where('date', '<=', $endDateFormatted);
                }
            });
        }

        // Apply filters from form
        if ($request->filled('province')) {
            $query->where('province', $request->province);
        }
        
        if ($request->filled('essence')) {
            $query->where('essence', $request->essence);
        }
        
        if ($request->filled('foret')) {
            $query->where('foret', 'like', '%' . $request->foret . '%');
        }
        
        if ($request->filled('year')) {
            $year = $request->year;
            // Handle year format: if it's "2024", extract "24", if it's already "24", use as is
            if (strlen($year) == 4) {
                $yearSuffix = substr($year, 2, 2);
            } else {
                $yearSuffix = $year;
            }
            $query->where('date', 'like', $yearSuffix . '%');
        }
        
        if ($request->filled('dref')) {
            $query->where('dref', 'like', '%' . $request->dref . '%');
        }
        
        if ($request->filled('min_volume')) {
            $query->where('bom3', '>=', $request->min_volume);
        }
        
        if ($request->filled('max_volume')) {
            $query->where('bom3', '<=', $request->max_volume);
        }
        
        if ($request->filled('min_price')) {
            $query->where('ppdh', '>=', $request->min_price);
        }
        
        if ($request->filled('max_price')) {
            $query->where('ppdh', '<=', $request->max_price);
        }
        
        if ($request->filled('min_surface')) {
            $query->where('surface', '>=', $request->min_surface);
        }
        
        if ($request->filled('max_surface')) {
            $query->where('surface', '<=', $request->max_surface);
        }

        // Get total records before filtering
        $totalRecords = LegacyArticle::count();
        $filteredRecords = $query->count();

        // Apply ordering and pagination
        $articles = $query->orderBy($orderColumnName, $orderDir)
            ->skip($start)
            ->take($length)
            ->get();

        // Format data for DataTables
        $data = [];
        foreach ($articles as $article) {
            // Format date
            $formattedDate = 'N/A';
            if ($article->date && strlen(trim($article->date)) >= 6) {
                $dateStr = trim($article->date);
                try {
                    if (preg_match('/^\d{6}$/', $dateStr)) {
                        $formattedDate = \Carbon\Carbon::createFromFormat('ymd', $dateStr)->format('d/m/Y');
                    } elseif (preg_match('/^\d{8}$/', $dateStr)) {
                        $formattedDate = \Carbon\Carbon::createFromFormat('Ymd', $dateStr)->format('d/m/Y');
                    } else {
                        $formattedDate = $dateStr;
                    }
                } catch (\Exception $e) {
                    $formattedDate = $dateStr;
                }
            }

            // Calculate total volume
            $totalVolume = ($article->bom3 ?? 0) + ($article->bim3 ?? 0) + ($article->bfst ?? 0) + 
                          ($article->lcst ?? 0) + ($article->ett ?? 0) + ($article->pst ?? 0);
            $formattedVolume = $totalVolume > 0 ? number_format($totalVolume, 2) : 'N/A';

            $data[] = [
                e($article->dref ?? 'N/A'),
                e($article->foret ?? 'N/A'),
                e($article->province ?? 'N/A'),
                $formattedDate,
                e($article->essence ?? 'N/A'),
                e($article->intervent ?? 'N/A'),
                $article->surface ? number_format($article->surface, 2) : 'N/A',
                $article->bom3 ? number_format($article->bom3, 2) : 'N/A',
                $article->bim3 ? number_format($article->bim3, 2) : 'N/A',
                $article->bfst ? number_format($article->bfst, 2) : 'N/A',
                e($article->acheteur ?? 'N/A'),
                $article->ppdh ? number_format($article->ppdh, 2) : 'N/A',
                $formattedVolume, // Total volume for preview table
            ];
        }

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data,
        ]);
    }

    public function legacyArticlesByYear(Request $request): View
    {
        // Log report generation
        ActivityLogger::log('view', 'Génération du rapport des articles historiques par année', LegacyArticle::class);
        
        $year = $request->get('year');
        
        $query = LegacyArticle::query();
        
        if ($year) {
            $query->where('date', 'like', $year . '%');
        }
        
        $articles = $query->orderBy('date', 'desc')->paginate(20);
        
        // Get available years for filter
        $years = LegacyArticle::selectRaw('CASE 
                WHEN LENGTH(date) >= 8 THEN SUBSTRING(date, 1, 4)
                WHEN LENGTH(date) >= 6 THEN CONCAT(\'20\', SUBSTRING(date, 1, 2))
                ELSE NULL
            END as year')
            ->distinct()
            ->whereNotNull('date')
            ->where('date', '!=', '')
            ->whereRaw('LENGTH(date) >= 6')
            ->orderBy('year')
            ->get()
            ->pluck('year')
            ->filter()
            ->unique()
            ->sort()
            ->values();
        
        // Calculate stats
        $allArticles = $query->get();
        $stats = [
            'total' => $allArticles->count(),
            'total_revenue' => $allArticles->sum('ppdh'),
            'total_volume' => $allArticles->sum('bom3'),
        ];

        return view('reports.legacy-articles-by-year', compact('articles', 'years', 'year', 'stats'));
    }

    public function legacyArticlesByProvince(Request $request): View
    {
        // Log report generation
        ActivityLogger::log('view', 'Génération du rapport des articles historiques par province', LegacyArticle::class);
        
        $province = $request->get('province');
        
        $query = LegacyArticle::query();
        
        if ($province) {
            $query->where('province', $province);
        }
        
        $articles = $query->orderBy('date', 'desc')->paginate(20);
        
        // Get available provinces for filter
        $provinces = LegacyArticle::select('province')
            ->distinct()
            ->whereNotNull('province')
            ->orderBy('province')
            ->pluck('province');
        
        // Calculate stats
        $allArticles = $query->get();
        $stats = [
            'total' => $allArticles->count(),
            'total_revenue' => $allArticles->sum('ppdh'),
            'total_volume' => $allArticles->sum('bom3'),
        ];

        return view('reports.legacy-articles-by-province', compact('articles', 'provinces', 'province', 'stats'));
    }

    public function legacyArticlesByEssence(Request $request): View
    {
        // Log report generation
        ActivityLogger::log('view', 'Génération du rapport des articles historiques par essence', LegacyArticle::class);
        
        $essence = $request->get('essence');
        
        $query = LegacyArticle::query();
        
        if ($essence) {
            $query->where('essence', $essence);
        }
        
        $articles = $query->orderBy('date', 'desc')->paginate(20);
        
        // Get available essences for filter
        $essences = LegacyArticle::select('essence')
            ->distinct()
            ->whereNotNull('essence')
            ->orderBy('essence')
            ->pluck('essence');
        
        // Calculate stats
        $allArticles = $query->get();
        $stats = [
            'total' => $allArticles->count(),
            'total_revenue' => $allArticles->sum('ppdh'),
            'total_volume' => $allArticles->sum('bom3'),
        ];

        return view('reports.legacy-articles-by-essence', compact('articles', 'essences', 'essence', 'stats'));
    }

    public function unifiedReports(): View
    {
        // Log report generation
        ActivityLogger::log('view', 'Génération du rapport unifié (articles + articles historiques)', null);
        
        // Get statistics for current articles
        $currentArticlesStats = [
            'total' => Article::count(),
            'total_revenue' => Article::sum('prix_vente') ?? 0,
            'total_volume' => Article::selectRaw('SUM(COALESCE(bo_m3, 0) + COALESCE(bi_m3, 0)) as total')->value('total') ?? 0,
            'sold' => Article::where('invendu', false)->count(),
            'unsold' => Article::where('invendu', true)->count(),
        ];

        // Get statistics for legacy articles
        $legacyArticlesStats = [
            'total' => LegacyArticle::count(),
            'total_revenue' => LegacyArticle::sum('ppdh') ?? 0,
            'total_volume' => LegacyArticle::sum('bom3') ?? 0,
        ];

        // Combined statistics
        $combinedStats = [
            'total_articles' => $currentArticlesStats['total'] + $legacyArticlesStats['total'],
            'total_revenue' => $currentArticlesStats['total_revenue'] + $legacyArticlesStats['total_revenue'],
            'total_volume' => $currentArticlesStats['total_volume'] + $legacyArticlesStats['total_volume'],
            'current_articles' => $currentArticlesStats,
            'legacy_articles' => $legacyArticlesStats,
        ];

        // Get year distribution for both types - VOLUME BY YEAR
        $currentArticlesByYear = Article::selectRaw('annee, SUM(COALESCE(bo_m3, 0) + COALESCE(bi_m3, 0)) as volume')
            ->groupBy('annee')
            ->orderBy('annee')
            ->get();
            
        $legacyArticlesByYear = LegacyArticle::selectRaw('CASE 
                WHEN LENGTH(date) >= 8 THEN SUBSTRING(date, 1, 4)
                WHEN LENGTH(date) >= 6 THEN CONCAT(\'20\', SUBSTRING(date, 1, 2))
                ELSE NULL
            END as year, SUM(bom3) as volume')
            ->whereNotNull('date')
            ->where('date', '!=', '')
            ->whereRaw('LENGTH(date) >= 6')
            ->groupBy('year')
            ->orderBy('year')
            ->get()
            ->map(function ($item) {
                $item->annee = $item->year;
                return $item;
            });

        // Get forest distribution - VOLUME BY FORET
        $currentArticlesByForet = Article::withoutGlobalScope('not_deleted')
            ->join('article_foret', 'articles.id', '=', 'article_foret.article_id')
            ->join('forets', 'article_foret.foret_id', '=', 'forets.id')
            ->where('articles.is_deleted', false)
            ->where('forets.is_deleted', false)
            ->selectRaw('forets.foret, SUM(COALESCE(articles.bo_m3, 0) + COALESCE(articles.bi_m3, 0)) as volume')
            ->groupBy('forets.id', 'forets.foret')
            ->orderByDesc('volume')
            ->limit(10)
            ->get();
            
        $legacyArticlesByForet = LegacyArticle::selectRaw('foret, SUM(bom3) as volume')
            ->whereNotNull('foret')
            ->groupBy('foret')
            ->orderByDesc('volume')
            ->limit(10)
            ->get();
        
        // Get essence distribution - VOLUME BY ESSENCE
        $currentArticlesByEssence = Article::withoutGlobalScope('not_deleted')
            ->join('article_essence', 'articles.id', '=', 'article_essence.article_id')
            ->join('essences', 'article_essence.essence_id', '=', 'essences.id')
            ->where('articles.is_deleted', false)
            ->where('essences.is_deleted', false)
            ->selectRaw('essences.essence, SUM(COALESCE(articles.bo_m3, 0) + COALESCE(articles.bi_m3, 0)) as volume')
            ->groupBy('essences.id', 'essences.essence')
            ->orderByDesc('volume')
            ->limit(10)
            ->get();
            
        $legacyArticlesByEssence = LegacyArticle::selectRaw('essence, SUM(bom3) as volume')
            ->whereNotNull('essence')
            ->groupBy('essence')
            ->orderByDesc('volume')
            ->limit(10)
            ->get();

        return view('reports.unified', compact(
            'combinedStats',
            'currentArticlesByYear',
            'legacyArticlesByYear',
            'currentArticlesByForet',
            'legacyArticlesByForet',
            'currentArticlesByEssence',
            'legacyArticlesByEssence'
        ));
    }

    public function productQuantitiesCharts(Request $request): View
    {
        try {
            // Log report generation
            ActivityLogger::log('view', 'Consultation des graphiques de quantités de produits', null);
            
            // Get all years from both current and legacy articles
            $currentYears = Article::select('annee')
                ->distinct()
                ->pluck('annee');
            
            // Get legacy years in a DB-agnostic way (avoid REGEXP/SUBSTRING)
            $legacyYears = LegacyArticle::whereNotNull('date')
                ->where('date', '!=', '')
                ->select('date')
                ->distinct()
                ->pluck('date')
                ->map(function ($date) {
                    $yy = substr((string) $date, 0, 2);
                    return preg_match('/^\d{2}$/', (string) $yy) ? '20' . str_pad($yy, 2, '0', STR_PAD_LEFT) : null;
                })
                ->filter()
                ->values();
            
            // Combine and sort all years
            $allYears = $currentYears->merge($legacyYears)->unique()->sort()->values();
            
            // Get all localisations for the second chart
            $localisations = Localisation::select('id', 'CODE', 'DRANEF', 'ENTITE', 'DPANEF')
                ->orderBy('CODE')
                ->get();
            
            // Chart 1: Product quantities by year (combined current + legacy)
            $yearlyData = [];
            $productFields = ['bo_m3', 'bi_m3', 'bf_st', 'tanin_t', 'fleur_acacia_t', 'caroube_t', 'romarin_t', 'liége_st', 'charbon_bois_ox'];
            
            foreach ($allYears as $year) {
                // Get data from current articles
                $currentData = Article::where('annee', $year)
                    ->selectRaw('
                        SUM(COALESCE(bo_m3, 0)) as bo_m3,
                        SUM(COALESCE(bi_m3, 0)) as bi_m3,
                        SUM(COALESCE(bf_st, 0)) as bf_st,
                        SUM(COALESCE(tanin_t, 0)) as tanin_t,
                        SUM(COALESCE(fleur_acacia_t, 0)) as fleur_acacia_t,
                        SUM(COALESCE(caroube_t, 0)) as caroube_t,
                        SUM(COALESCE(romarin_t, 0)) as romarin_t,
                        SUM(COALESCE(`liége_st`, 0)) as `liége_st`,
                        SUM(COALESCE(charbon_bois_ox, 0)) as charbon_bois_ox
                    ')
                    ->first();
                
                // Get data from legacy articles (convert bom3 to bo_m3)
                $legacyData = LegacyArticle::whereNotNull('date')
                    ->where('date', '!=', '')
                    ->where('date', 'like', substr($year, 2, 2) . '%')
                    ->selectRaw('
                        SUM(COALESCE(bom3, 0)) as bo_m3,
                        SUM(COALESCE(bim3, 0)) as bi_m3,
                        SUM(COALESCE(bfst, 0)) as bf_st,
                        0 as tanin_t,
                        0 as fleur_acacia_t,
                        0 as caroube_t,
                        0 as romarin_t,
                        SUM(COALESCE(lcst, 0)) as liége_st,
                        0 as charbon_bois_ox
                    ')
                    ->first();
                
                // Combine the data
                $combinedData = (object) [
                    'bo_m3' => ($currentData->bo_m3 ?? 0) + ($legacyData->bo_m3 ?? 0),
                    'bi_m3' => ($currentData->bi_m3 ?? 0) + ($legacyData->bi_m3 ?? 0),
                    'bf_st' => ($currentData->bf_st ?? 0) + ($legacyData->bf_st ?? 0),
                    'tanin_t' => ($currentData->tanin_t ?? 0) + ($legacyData->tanin_t ?? 0),
                    'fleur_acacia_t' => ($currentData->fleur_acacia_t ?? 0) + ($legacyData->fleur_acacia_t ?? 0),
                    'caroube_t' => ($currentData->caroube_t ?? 0) + ($legacyData->caroube_t ?? 0),
                    'romarin_t' => ($currentData->romarin_t ?? 0) + ($legacyData->romarin_t ?? 0),
                    'liége_st' => ($currentData->liége_st ?? 0) + ($legacyData->liége_st ?? 0),
                    'charbon_bois_ox' => ($currentData->charbon_bois_ox ?? 0) + ($legacyData->charbon_bois_ox ?? 0),
                ];
                
                $yearlyData[$year] = $combinedData;
            }
            
            // Chart 2: Product quantities by localisation and year (combined current + legacy)
            $localisationData = [];
            
            // Check if article_localisation table has data
            $hasLocalisationData = DB::table('article_localisation')->exists();
            
            if ($hasLocalisationData) {
                foreach ($localisations as $localisation) {
                    $localisationYearlyData = [];
                    foreach ($allYears as $year) {
                        try {
                            // Get current articles data for this localisation and year
                            $currentData = Article::join('article_localisation', 'articles.id', '=', 'article_localisation.article_id')
                                ->where('article_localisation.localisation_id', $localisation->id)
                                ->where('articles.annee', $year)
                                ->selectRaw('
                                    SUM(COALESCE(articles.bo_m3, 0)) as bo_m3,
                                    SUM(COALESCE(articles.bi_m3, 0)) as bi_m3,
                                    SUM(COALESCE(articles.bf_st, 0)) as bf_st,
                                    SUM(COALESCE(articles.tanin_t, 0)) as tanin_t,
                                    SUM(COALESCE(articles.fleur_acacia_t, 0)) as fleur_acacia_t,
                                    SUM(COALESCE(articles.caroube_t, 0)) as caroube_t,
                                    SUM(COALESCE(articles.romarin_t, 0)) as romarin_t,
                                    SUM(COALESCE(articles.`liége_st`, 0)) as `liége_st`,
                                    SUM(COALESCE(articles.charbon_bois_ox, 0)) as charbon_bois_ox
                                ')
                                ->first();
                            
                            // Get legacy articles data for this localisation and year
                            // Map legacy articles by province to localisation
                            $legacyData = LegacyArticle::whereNotNull('date')
                                ->where('date', '!=', '')
                                ->where('date', 'like', substr($year, 2, 2) . '%')
                                ->where(function($query) use ($localisation) {
                                    // Try to match province with localisation fields
                                    $query->where('province', 'LIKE', '%' . $localisation->DRANEF . '%')
                                          ->orWhere('province', 'LIKE', '%' . $localisation->ENTITE . '%')
                                          ->orWhere('province', 'LIKE', '%' . $localisation->CODE . '%');
                                })
                                ->selectRaw('
                                    SUM(COALESCE(bom3, 0)) as bo_m3,
                                    SUM(COALESCE(bim3, 0)) as bi_m3,
                                    SUM(COALESCE(bfst, 0)) as bf_st,
                                    0 as tanin_t,
                                    0 as fleur_acacia_t,
                                    0 as caroube_t,
                                    0 as romarin_t,
                                    SUM(COALESCE(lcst, 0)) as liége_st,
                                    0 as charbon_bois_ox
                                ')
                                ->first();
                            
                            // Combine the data
                            $combinedData = (object) [
                                'bo_m3' => ($currentData->bo_m3 ?? 0) + ($legacyData->bo_m3 ?? 0),
                                'bi_m3' => ($currentData->bi_m3 ?? 0) + ($legacyData->bi_m3 ?? 0),
                                'bf_st' => ($currentData->bf_st ?? 0) + ($legacyData->bf_st ?? 0),
                                'tanin_t' => ($currentData->tanin_t ?? 0) + ($legacyData->tanin_t ?? 0),
                                'fleur_acacia_t' => ($currentData->fleur_acacia_t ?? 0) + ($legacyData->fleur_acacia_t ?? 0),
                                'caroube_t' => ($currentData->caroube_t ?? 0) + ($legacyData->caroube_t ?? 0),
                                'romarin_t' => ($currentData->romarin_t ?? 0) + ($legacyData->romarin_t ?? 0),
                                'liége_st' => ($currentData->liége_st ?? 0) + ($legacyData->liége_st ?? 0),
                                'charbon_bois_ox' => ($currentData->charbon_bois_ox ?? 0) + ($legacyData->charbon_bois_ox ?? 0),
                            ];
                            
                            $localisationYearlyData[$year] = $combinedData;
                        } catch (\Exception $e) {
                            // If there's an error with the join, create empty data
                            $localisationYearlyData[$year] = (object) [
                                'bo_m3' => 0, 'bi_m3' => 0, 'bf_st' => 0, 'tanin_t' => 0,
                                'fleur_acacia_t' => 0, 'caroube_t' => 0, 'romarin_t' => 0,
                                'liége_st' => 0, 'charbon_bois_ox' => 0
                            ];
                        }
                    }
                    $localisationData[$localisation->id] = [
                        'localisation' => $localisation,
                        'data' => $localisationYearlyData
                    ];
                }
            } else {
                // If no localisation data exists, create data based on legacy articles provinces
                foreach ($localisations as $localisation) {
                    $localisationYearlyData = [];
                    foreach ($allYears as $year) {
                        // Get legacy articles data for this localisation and year by province matching
                        $legacyData = LegacyArticle::whereNotNull('date')
                            ->where('date', '!=', '')
                            ->where('date', 'like', substr($year, 2, 2) . '%')
                            ->where(function($query) use ($localisation) {
                                // Try to match province with localisation fields
                                $query->where('province', 'LIKE', '%' . $localisation->DRANEF . '%')
                                      ->orWhere('province', 'LIKE', '%' . $localisation->ENTITE . '%')
                                      ->orWhere('province', 'LIKE', '%' . $localisation->CODE . '%');
                            })
                            ->selectRaw('
                                SUM(COALESCE(bom3, 0)) as bo_m3,
                                SUM(COALESCE(bim3, 0)) as bi_m3,
                                SUM(COALESCE(bfst, 0)) as bf_st,
                                0 as tanin_t,
                                0 as fleur_acacia_t,
                                0 as caroube_t,
                                0 as romarin_t,
                                SUM(COALESCE(lcst, 0)) as liége_st,
                                0 as charbon_bois_ox
                            ')
                            ->first();
                        
                        $localisationYearlyData[$year] = $legacyData ?: (object) [
                            'bo_m3' => 0, 'bi_m3' => 0, 'bf_st' => 0, 'tanin_t' => 0,
                            'fleur_acacia_t' => 0, 'caroube_t' => 0, 'romarin_t' => 0,
                            'liége_st' => 0, 'charbon_bois_ox' => 0
                        ];
                    }
                    $localisationData[$localisation->id] = [
                        'localisation' => $localisation,
                        'data' => $localisationYearlyData
                    ];
                }
            }
            
            return view('reports.product-quantities-charts', compact(
                'allYears', 'localisations', 'yearlyData', 'localisationData', 'productFields'
            ));
            
        } catch (\Exception $e) {
            // Log the error and return a simple view with error message
            \Log::error('Error in productQuantitiesCharts: ' . $e->getMessage());
            
            return view('reports.product-quantities-charts', [
                'allYears' => collect(),
                'localisations' => collect(),
                'yearlyData' => [],
                'localisationData' => [],
                'productFields' => ['bo_m3', 'bi_m3', 'bf_st', 'tanin_t', 'fleur_acacia_t', 'caroube_t', 'romarin_t', 'liége_st', 'charbon_bois_ox'],
                'error' => 'Une erreur est survenue lors du chargement des données. Veuillez réessayer.'
            ]);
        }
    }

    public function legacyQuantitiesCharts(Request $request): View
    {
        try {
            // Log report generation
            ActivityLogger::log('view', 'Consultation des graphiques de quantités de produits (Legacy)', null);
            
            // Get all years from legacy articles
            $legacyYears = LegacyArticle::whereNotNull('date')
                ->where('date', '!=', '')
                ->select('date')
                ->distinct()
                ->pluck('date')
                ->map(function ($date) {
                    $yy = substr((string) $date, 0, 2);
                    return preg_match('/^\d{2}$/', (string) $yy) ? '20' . str_pad($yy, 2, '0', STR_PAD_LEFT) : null;
                })
                ->filter()
                ->unique()
                ->sort()
                ->values();
            
            // Get all provinces for the second chart
            $provinces = LegacyArticle::select('province')
                ->whereNotNull('province')
                ->where('province', '!=', '')
                ->distinct()
                ->orderBy('province')
                ->pluck('province');
            
            // Chart 1: Product quantities by year (legacy only)
            $yearlyData = [];
            $productFields = ['bo_m3', 'bi_m3', 'bf_st', 'liége_st'];
            
            foreach ($legacyYears as $year) {
                $legacyData = LegacyArticle::whereNotNull('date')
                    ->where('date', '!=', '')
                    ->where('date', 'like', substr($year, 2, 2) . '%')
                    ->selectRaw('
                        SUM(COALESCE(bom3, 0)) as bo_m3,
                        SUM(COALESCE(bim3, 0)) as bi_m3,
                        SUM(COALESCE(bfst, 0)) as bf_st,
                        SUM(COALESCE(lcst, 0)) as liége_st
                    ')
                    ->first();
                
                $yearlyData[$year] = (object) [
                    'bo_m3' => $legacyData->bo_m3 ?? 0,
                    'bi_m3' => $legacyData->bi_m3 ?? 0,
                    'bf_st' => $legacyData->bf_st ?? 0,
                    'liége_st' => $legacyData->liége_st ?? 0,
                ];
            }
            
            // Chart 2: Product quantities by province and year (legacy only)
            $provinceData = [];
            
            foreach ($provinces as $province) {
                $provinceYearlyData = [];
                foreach ($legacyYears as $year) {
                    $legacyData = LegacyArticle::whereNotNull('date')
                        ->where('date', '!=', '')
                        ->where('date', 'like', substr($year, 2, 2) . '%')
                        ->where('province', $province)
                        ->selectRaw('
                            SUM(COALESCE(bom3, 0)) as bo_m3,
                            SUM(COALESCE(bim3, 0)) as bi_m3,
                            SUM(COALESCE(bfst, 0)) as bf_st,
                            SUM(COALESCE(lcst, 0)) as liége_st
                        ')
                        ->first();
                    
                    $provinceYearlyData[$year] = (object) [
                        'bo_m3' => $legacyData->bo_m3 ?? 0,
                        'bi_m3' => $legacyData->bi_m3 ?? 0,
                        'bf_st' => $legacyData->bf_st ?? 0,
                        'liége_st' => $legacyData->liége_st ?? 0,
                    ];
                }
                $provinceData[$province] = [
                    'province' => $province,
                    'data' => $provinceYearlyData
                ];
            }
            
            return view('reports.legacy-quantities-charts', compact(
                'legacyYears', 'provinces', 'yearlyData', 'provinceData', 'productFields'
            ));
            
        } catch (\Exception $e) {
            \Log::error('Error in legacyQuantitiesCharts: ' . $e->getMessage());
            
            return view('reports.legacy-quantities-charts', [
                'legacyYears' => collect(),
                'provinces' => collect(),
                'yearlyData' => [],
                'provinceData' => [],
                'productFields' => ['bo_m3', 'bi_m3', 'bf_st', 'liége_st'],
                'error' => 'Une erreur est survenue lors du chargement des données. Veuillez réessayer.'
            ]);
        }
    }

    public function articleQuantitiesCharts(Request $request): View
    {
        try {
            // Log report generation
            ActivityLogger::log('view', 'Consultation des graphiques de quantités de produits (Articles)', null);
            
            // Get all years from current articles
            $currentYears = Article::select('annee')
                ->distinct()
                ->pluck('annee')
                ->filter()
                ->sort()
                ->values();
            
            // Get all localisations for the second chart
            $localisations = Localisation::select('id', 'CODE', 'DRANEF', 'ENTITE', 'DPANEF')
                ->orderBy('CODE')
                ->get();
            
            // Chart 1: Product quantities by year (current articles only)
            $yearlyData = [];
            $productFields = ['bo_m3', 'bi_m3', 'bf_st', 'tanin_t', 'fleur_acacia_t', 'caroube_t', 'romarin_t', 'liége_st', 'charbon_bois_ox'];
            
            foreach ($currentYears as $year) {
                $currentData = Article::where('annee', $year)
                    ->selectRaw('
                        SUM(COALESCE(bo_m3, 0)) as bo_m3,
                        SUM(COALESCE(bi_m3, 0)) as bi_m3,
                        SUM(COALESCE(bf_st, 0)) as bf_st,
                        SUM(COALESCE(tanin_t, 0)) as tanin_t,
                        SUM(COALESCE(fleur_acacia_t, 0)) as fleur_acacia_t,
                        SUM(COALESCE(caroube_t, 0)) as caroube_t,
                        SUM(COALESCE(romarin_t, 0)) as romarin_t,
                        SUM(COALESCE(`liége_st`, 0)) as `liége_st`,
                        SUM(COALESCE(charbon_bois_ox, 0)) as charbon_bois_ox
                    ')
                    ->first();
                
                $yearlyData[$year] = (object) [
                    'bo_m3' => $currentData->bo_m3 ?? 0,
                    'bi_m3' => $currentData->bi_m3 ?? 0,
                    'bf_st' => $currentData->bf_st ?? 0,
                    'tanin_t' => $currentData->tanin_t ?? 0,
                    'fleur_acacia_t' => $currentData->fleur_acacia_t ?? 0,
                    'caroube_t' => $currentData->caroube_t ?? 0,
                    'romarin_t' => $currentData->romarin_t ?? 0,
                    'liége_st' => $currentData->liége_st ?? 0,
                    'charbon_bois_ox' => $currentData->charbon_bois_ox ?? 0,
                ];
            }
            
            // Chart 2: Product quantities by localisation and year (current articles only)
            $localisationData = [];
            
            // Check if article_localisation table has data
            $hasLocalisationData = DB::table('article_localisation')->exists();
            
            if ($hasLocalisationData) {
                foreach ($localisations as $localisation) {
                    $localisationYearlyData = [];
                    foreach ($currentYears as $year) {
                        try {
                            $currentData = Article::join('article_localisation', 'articles.id', '=', 'article_localisation.article_id')
                                ->where('article_localisation.localisation_id', $localisation->id)
                                ->where('articles.annee', $year)
                                ->selectRaw('
                                    SUM(COALESCE(articles.bo_m3, 0)) as bo_m3,
                                    SUM(COALESCE(articles.bi_m3, 0)) as bi_m3,
                                    SUM(COALESCE(articles.bf_st, 0)) as bf_st,
                                    SUM(COALESCE(articles.tanin_t, 0)) as tanin_t,
                                    SUM(COALESCE(articles.fleur_acacia_t, 0)) as fleur_acacia_t,
                                    SUM(COALESCE(articles.caroube_t, 0)) as caroube_t,
                                    SUM(COALESCE(articles.romarin_t, 0)) as romarin_t,
                                    SUM(COALESCE(articles.`liége_st`, 0)) as `liége_st`,
                                    SUM(COALESCE(articles.charbon_bois_ox, 0)) as charbon_bois_ox
                                ')
                                ->first();
                            
                            $localisationYearlyData[$year] = (object) [
                                'bo_m3' => $currentData->bo_m3 ?? 0,
                                'bi_m3' => $currentData->bi_m3 ?? 0,
                                'bf_st' => $currentData->bf_st ?? 0,
                                'tanin_t' => $currentData->tanin_t ?? 0,
                                'fleur_acacia_t' => $currentData->fleur_acacia_t ?? 0,
                                'caroube_t' => $currentData->caroube_t ?? 0,
                                'romarin_t' => $currentData->romarin_t ?? 0,
                                'liége_st' => $currentData->liége_st ?? 0,
                                'charbon_bois_ox' => $currentData->charbon_bois_ox ?? 0,
                            ];
                        } catch (\Exception $e) {
                            $localisationYearlyData[$year] = (object) [
                                'bo_m3' => 0, 'bi_m3' => 0, 'bf_st' => 0, 'tanin_t' => 0,
                                'fleur_acacia_t' => 0, 'caroube_t' => 0, 'romarin_t' => 0,
                                'liége_st' => 0, 'charbon_bois_ox' => 0
                            ];
                        }
                    }
                    $localisationData[$localisation->id] = [
                        'localisation' => $localisation,
                        'data' => $localisationYearlyData
                    ];
                }
            } else {
                // If no localisation data exists, create empty data structure
                foreach ($localisations as $localisation) {
                    $localisationYearlyData = [];
                    foreach ($currentYears as $year) {
                        $localisationYearlyData[$year] = (object) [
                            'bo_m3' => 0, 'bi_m3' => 0, 'bf_st' => 0, 'tanin_t' => 0,
                            'fleur_acacia_t' => 0, 'caroube_t' => 0, 'romarin_t' => 0,
                            'liége_st' => 0, 'charbon_bois_ox' => 0
                        ];
                    }
                    $localisationData[$localisation->id] = [
                        'localisation' => $localisation,
                        'data' => $localisationYearlyData
                    ];
                }
            }
            
            return view('reports.article-quantities-charts', compact(
                'currentYears', 'localisations', 'yearlyData', 'localisationData', 'productFields'
            ));
            
        } catch (\Exception $e) {
            \Log::error('Error in articleQuantitiesCharts: ' . $e->getMessage());
            
            return view('reports.article-quantities-charts', [
                'currentYears' => collect(),
                'localisations' => collect(),
                'yearlyData' => [],
                'localisationData' => [],
                'productFields' => ['bo_m3', 'bi_m3', 'bf_st', 'tanin_t', 'fleur_acacia_t', 'caroube_t', 'romarin_t', 'liége_st', 'charbon_bois_ox'],
                'error' => 'Une erreur est survenue lors du chargement des données. Veuillez réessayer.'
            ]);
        }
    }

    public function unifiedTable(Request $request): View
    {
        // Log report generation
        ActivityLogger::log('view', 'Consultation du tableau unifié (articles + articles historiques)', null);
        
        $currentArticles = collect();
        $legacyArticles = collect();
        
        // Get current articles with filters
        $currentQuery = Article::with(['exploitant', 'forets', 'essences']);
            
        if ($request->filled('search')) {
            $currentQuery->where(function($q) use ($request) {
                $q->where('numero', 'like', '%' . $request->search . '%')
                  ->orWhere('annee', 'like', '%' . $request->search . '%')
                  ->orWhere('numero_adjudication', 'like', '%' . $request->search . '%')
                  ->orWhereHas('exploitant', function($exploitantQuery) use ($request) {
                      $exploitantQuery->where('nom_complet', 'like', '%' . $request->search . '%');
                  })
                  ->orWhereHas('forets', function($foretQuery) use ($request) {
                      $foretQuery->where('foret', 'like', '%' . $request->search . '%');
                  })
                  ->orWhereHas('essences', function($essenceQuery) use ($request) {
                      $essenceQuery->where('essence', 'like', '%' . $request->search . '%');
                  });
            });
        }
        
        if ($request->filled('year')) {
            $currentQuery->where('annee', $request->year);
        }
        
        if ($request->filled('type')) {
            $currentQuery->where('type', $request->type);
        }
        
        if ($request->filled('min_price')) {
            $currentQuery->where('prix_vente', '>=', $request->min_price);
        }
        
        if ($request->filled('max_price')) {
            $currentQuery->where('prix_vente', '<=', $request->max_price);
        }
        
        if ($request->filled('min_volume')) {
            $currentQuery->whereRaw('COALESCE(bo_m3, 0) + COALESCE(bi_m3, 0) >= ?', [$request->min_volume]);
        }
        
        if ($request->filled('max_volume')) {
            $currentQuery->whereRaw('COALESCE(bo_m3, 0) + COALESCE(bi_m3, 0) <= ?', [$request->max_volume]);
        }
        
        if ($request->filled('status')) {
            if ($request->status === 'sold') {
                $currentQuery->where('invendu', false);
            } elseif ($request->status === 'unsold') {
                $currentQuery->where('invendu', true);
            }
        }
        
        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        if (in_array($sortBy, ['numero', 'annee', 'prix_vente', 'bo_m3', 'date_adjudication'])) {
            $currentQuery->orderBy($sortBy, $sortOrder);
        } else {
            $currentQuery->orderBy('created_at', 'desc');
        }
        
        $currentArticles = $currentQuery->limit($request->get('per_page', 10))->get();
        
        // Get legacy articles with filters
        $legacyQuery = LegacyArticle::query();
        
        if ($request->filled('search')) {
            $legacyQuery->where(function($q) use ($request) {
                $q->where('dref', 'like', '%' . $request->search . '%')
                  ->orWhere('foret', 'like', '%' . $request->search . '%')
                  ->orWhere('province', 'like', '%' . $request->search . '%')
                  ->orWhere('essence', 'like', '%' . $request->search . '%')
                  ->orWhere('acheteur', 'like', '%' . $request->search . '%')
                  ->orWhere('intervent', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->filled('year')) {
            $legacyQuery->where('date', 'like', $request->year . '%');
        }
        
        if ($request->filled('province')) {
            $legacyQuery->where('province', $request->province);
        }
        
        if ($request->filled('essence')) {
            $legacyQuery->where('essence', $request->essence);
        }
        
        if ($request->filled('min_volume')) {
            $legacyQuery->where('bom3', '>=', $request->min_volume);
        }
        
        if ($request->filled('max_volume')) {
            $legacyQuery->where('bom3', '<=', $request->max_volume);
        }
        
        if ($request->filled('min_price')) {
            $legacyQuery->where('ppdh', '>=', $request->min_price);
        }
        
        if ($request->filled('max_price')) {
            $legacyQuery->where('ppdh', '<=', $request->max_price);
        }
        
        // Sorting for legacy articles
        $legacySortBy = $request->get('legacy_sort_by', 'created_at');
        $legacySortOrder = $request->get('legacy_sort_order', 'desc');
        
        if (in_array($legacySortBy, ['dref', 'foret', 'province', 'essence', 'bom3', 'ppdh', 'date'])) {
            $legacyQuery->orderBy($legacySortBy, $legacySortOrder);
        } else {
            $legacyQuery->orderBy('created_at', 'desc');
        }
        
        $legacyArticles = $legacyQuery->limit($request->get('per_page', 10))->get();
        
        // Get filter options
        $years = collect();
        $currentYears = Article::select('annee')->distinct()->orderBy('annee', 'desc')->pluck('annee');
        $legacyYears = LegacyArticle::selectRaw('CASE 
                WHEN LENGTH(date) >= 8 THEN SUBSTRING(date, 1, 4)
                WHEN LENGTH(date) >= 6 THEN CONCAT(\'20\', SUBSTRING(date, 1, 2))
                ELSE NULL
            END as year')
            ->distinct()
            ->whereNotNull('date')
            ->where('date', '!=', '')
            ->whereRaw('LENGTH(date) >= 6')
            ->orderBy('year')
            ->get()
            ->pluck('year')
            ->filter()
            ->unique()
            ->sort()
            ->values();
        
        $years = $currentYears->merge($legacyYears)->unique()->sort()->values();
        
        $types = ['appel_doffre', 'adjudication'];
        $statuses = ['sold', 'unsold'];
        $provinces = LegacyArticle::select('province')->distinct()->whereNotNull('province')->orderBy('province')->pluck('province');
        $essences = LegacyArticle::select('essence')->distinct()->whereNotNull('essence')->orderBy('essence')->pluck('essence');
        
        // Calculate combined statistics
        $stats = [
            'current_total' => $currentArticles->count(),
            'legacy_total' => $legacyArticles->count(),
            'combined_total' => $currentArticles->count() + $legacyArticles->count(),
            'current_revenue' => $currentArticles->sum('prix_vente'),
            'legacy_revenue' => $legacyArticles->sum('ppdh'),
            'combined_revenue' => $currentArticles->sum('prix_vente') + $legacyArticles->sum('ppdh'),
            'current_volume' => $currentArticles->sum(function($article) {
                return ($article->bo_m3 ?? 0) + ($article->bi_m3 ?? 0);
            }),
            'legacy_volume' => $legacyArticles->sum('bom3'),
            'combined_volume' => $currentArticles->sum(function($article) {
                return ($article->bo_m3 ?? 0) + ($article->bi_m3 ?? 0);
            }) + $legacyArticles->sum('bom3'),
        ];

        return view('reports.unified-table', compact(
            'currentArticles',
            'legacyArticles',
            'years',
            'types',
            'statuses',
            'provinces',
            'essences',
            'stats'
        ));
    }

    /**
     * Display contracts report
     */
    public function contractsReport(Request $request): View
    {
        // Log report generation
        ActivityLogger::log('view', 'Génération du rapport des contrats', Contract::class);
        
        // Build base query
        $query = Contract::with(['localisation', 'situationAdministrative', 'especes', 'forets', 'coperative']);
        
        // Apply filters
        if ($request->filled('year')) {
            $query->where('annee', $request->year);
        }
        
        if ($request->filled('localisation_id')) {
            $query->where('localisation_id', $request->localisation_id);
        }
        
        if ($request->filled('situation_administrative_id')) {
            $query->where('situation_administrative_id', $request->situation_administrative_id);
        }
        
        if ($request->filled('espece_id')) {
            $query->whereHas('especes', function($q) use ($request) {
                $q->where('especes.id', $request->espece_id);
            });
        }
        
        if ($request->filled('foret_id')) {
            $query->whereHas('forets', function($q) use ($request) {
                $q->where('forets.id', $request->foret_id);
            });
        }
        
        if ($request->filled('coperative_id')) {
            $query->where('coperative_id', $request->coperative_id);
        }
        
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        // Get statistics
        $totalContracts = (clone $query)->count();
        $totalValue = (clone $query)->sum('total_avenant') ?? 0;
        $totalSuperficie = (clone $query)->sum('superficie') ?? 0;
        
        // Statistics by year
        $byYear = (clone $query)
            ->selectRaw('annee, COUNT(*) as total, SUM(COALESCE(total_avenant, 0)) as total_value, SUM(COALESCE(superficie, 0)) as total_superficie')
            ->whereNotNull('annee')
            ->groupBy('annee')
            ->orderBy('annee', 'desc')
            ->get();
        
        // Statistics by localisation
        $byLocalisation = (clone $query)
            ->join('localisations', 'contacts.localisation_id', '=', 'localisations.id')
            ->selectRaw('localisations.DRANEF as label, COUNT(*) as total, SUM(COALESCE(contacts.total_avenant, 0)) as total_value')
            ->whereNotNull('localisations.DRANEF')
            ->groupBy('localisations.id', 'localisations.DRANEF')
            ->orderByDesc('total')
            ->limit(10)
            ->get();
        
        // Statistics by situation administrative
        $bySituation = (clone $query)
            ->join('situation_administratives', 'contacts.situation_administrative_id', '=', 'situation_administratives.id')
            ->selectRaw('situation_administratives.province as label, COUNT(*) as total, SUM(COALESCE(contacts.total_avenant, 0)) as total_value')
            ->whereNotNull('situation_administratives.province')
            ->groupBy('situation_administratives.id', 'situation_administratives.province')
            ->orderByDesc('total')
            ->limit(10)
            ->get();
        
        // Statistics by espece
        $byEspece = (clone $query)
            ->join('contact_espece', 'contacts.id', '=', 'contact_espece.contact_id')
            ->join('especes', 'contact_espece.espece_id', '=', 'especes.id')
            ->selectRaw('especes.name as label, COUNT(DISTINCT contacts.id) as total')
            ->groupBy('especes.id', 'especes.name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();
        
        // Statistics by foret
        $byForetQuery = Contract::query();
        
        // Apply the same filters to the foret query
        if ($request->filled('year')) {
            $byForetQuery->where('annee', $request->year);
        }
        if ($request->filled('localisation_id')) {
            $byForetQuery->where('localisation_id', $request->localisation_id);
        }
        if ($request->filled('situation_administrative_id')) {
            $byForetQuery->where('situation_administrative_id', $request->situation_administrative_id);
        }
        if ($request->filled('espece_id')) {
            $byForetQuery->whereHas('especes', function($q) use ($request) {
                $q->where('especes.id', $request->espece_id);
            });
        }
        if ($request->filled('foret_id')) {
            $byForetQuery->whereHas('forets', function($q) use ($request) {
                $q->where('forets.id', $request->foret_id);
            });
        }
        if ($request->filled('coperative_id')) {
            $byForetQuery->where('coperative_id', $request->coperative_id);
        }
        if ($request->filled('start_date')) {
            $byForetQuery->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $byForetQuery->whereDate('created_at', '<=', $request->end_date);
        }
        
        $byForet = $byForetQuery
            ->join('contact_foret', 'contacts.id', '=', 'contact_foret.contact_id')
            ->join('forets', 'contact_foret.foret_id', '=', 'forets.id')
            ->selectRaw('forets.foret as label, COUNT(DISTINCT contacts.id) as total')
            ->whereNotNull('forets.foret')
            ->groupBy('forets.id', 'forets.foret')
            ->orderByDesc('total')
            ->limit(10)
            ->get();
        
        // Statistics by coperative
        $byCoperative = (clone $query)
            ->join('coperatives', 'contacts.coperative_id', '=', 'coperatives.id')
            ->selectRaw('coperatives.nom as label, COUNT(*) as total')
            ->whereNotNull('coperatives.nom')
            ->groupBy('coperatives.id', 'coperatives.nom')
            ->orderByDesc('total')
            ->limit(10)
            ->get();
        
        // Get contracts with pagination
        $contracts = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Get filter options
        $localisations = Localisation::orderBy('CODE')->get();
        $situations = SituationAdministrative::orderBy('commune')->get();
        $especes = \App\Models\Espece::orderBy('name')->get();
        $forets = Foret::orderBy('foret')->get();
        $coperatives = \App\Models\Coperative::orderBy('nom')->get();
        $availableYears = Contract::select('annee')
            ->distinct()
            ->whereNotNull('annee')
            ->orderBy('annee', 'desc')
            ->pluck('annee');
        
        $stats = [
            'total_contracts' => $totalContracts,
            'total_value' => $totalValue,
            'total_superficie' => $totalSuperficie,
            'by_year' => $byYear,
            'by_localisation' => $byLocalisation,
            'by_situation' => $bySituation,
            'by_espece' => $byEspece,
            'by_foret' => $byForet,
            'by_coperative' => $byCoperative,
        ];
        
        return view('reports.contracts', compact('contracts', 'stats', 'localisations', 'situations', 'especes', 'forets', 'coperatives', 'availableYears'));
    }

    /**
     * Display exploitants report
     */
    public function exploitantsReport(Request $request): View
    {
        // Log report generation
        ActivityLogger::log('view', 'Génération du rapport des exploitants', Exploitant::class);
        
        // Build base query
        $query = Exploitant::with('localisation');
        
        // Apply filters - only date filters
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        // Get statistics
        $totalExploitants = (clone $query)->count();
        $totalCompanies = (clone $query)->where('categorie', 'societe')->count();
        $totalIndividuals = (clone $query)->where('categorie', 'personne_physique')->count();
        $totalActive = (clone $query)->where('exclusion', false)->count();
        $totalExcluded = (clone $query)->where('exclusion', true)->count();
        
        // Statistics by categorie
        $byCategorie = (clone $query)
            ->selectRaw('categorie, COUNT(*) as total')
            ->whereNotNull('categorie')
            ->groupBy('categorie')
            ->get();
        
        // Statistics by activite
        $byActivite = (clone $query)
            ->selectRaw('activite, COUNT(*) as total')
            ->whereNotNull('activite')
            ->groupBy('activite')
            ->orderByDesc('total')
            ->get();
        
        // Statistics by localisation - use withoutGlobalScopes to avoid ambiguous column
        $byLocalisationQuery = Exploitant::withoutGlobalScopes();
        
        // Apply the same date filters
        if ($request->filled('start_date')) {
            $byLocalisationQuery->whereDate('exploitants.created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $byLocalisationQuery->whereDate('exploitants.created_at', '<=', $request->end_date);
        }
        
        $byLocalisation = $byLocalisationQuery
            ->join('localisations', 'exploitants.localisation_id', '=', 'localisations.id')
            ->selectRaw('localisations.DRANEF as label, COUNT(*) as total')
            ->where('exploitants.is_deleted', false)
            ->where('localisations.is_deleted', false)
            ->whereNotNull('localisations.DRANEF')
            ->groupBy('localisations.id', 'localisations.DRANEF')
            ->orderByDesc('total')
            ->limit(10)
            ->get();
        
        // Statistics by qualification
        $byQualification = (clone $query)
            ->selectRaw('qualification_rc, COUNT(*) as total')
            ->whereNotNull('qualification_rc')
            ->groupBy('qualification_rc')
            ->orderByDesc('total')
            ->get();
        
        // Get exploitants with pagination
        $exploitants = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Get filter options
        $localisations = Localisation::orderBy('CODE')->get();
        $categories = ['societe', 'personne_physique'];
        $activites = Exploitant::select('activite')->distinct()->whereNotNull('activite')->orderBy('activite')->pluck('activite');
        $qualifications = Exploitant::select('qualification_rc')->distinct()->whereNotNull('qualification_rc')->orderBy('qualification_rc')->pluck('qualification_rc');
        
        $stats = [
            'total_exploitants' => $totalExploitants,
            'total_companies' => $totalCompanies,
            'total_individuals' => $totalIndividuals,
            'total_active' => $totalActive,
            'total_excluded' => $totalExcluded,
            'by_categorie' => $byCategorie,
            'by_activite' => $byActivite,
            'by_localisation' => $byLocalisation,
            'by_qualification' => $byQualification,
        ];
        
        return view('reports.exploitants', compact('exploitants', 'stats', 'localisations', 'categories', 'activites', 'qualifications'));
    }
} 