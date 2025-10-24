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
            ->selectRaw('articles.annee, COUNT(*) as total')
            ->groupBy('articles.annee')
            ->orderBy('articles.annee', 'asc')
            ->get();

        $byForet = (clone $baseQuery)
            ->join('article_foret', 'articles.id', '=', 'article_foret.article_id')
            ->join('forets', 'article_foret.foret_id', '=', 'forets.id')
            ->selectRaw('forets.foret as label, COUNT(*) as total')
            ->where('forets.is_deleted', false)
            ->groupBy('forets.id', 'forets.foret')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        $byEssence = (clone $baseQuery)
            ->join('article_essence', 'articles.id', '=', 'article_essence.article_id')
            ->join('essences', 'article_essence.essence_id', '=', 'essences.id')
            ->selectRaw('essences.essence as label, COUNT(*) as total')
            ->where('essences.is_deleted', false)
            ->groupBy('essences.id', 'essences.essence')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        $byExploitant = (clone $baseQuery)
            ->join('exploitants', 'articles.exploitant_id', '=', 'exploitants.id')
            ->selectRaw('COALESCE(exploitants.nom_complet, exploitants.raison_sociale) as label, COUNT(*) as total')
            ->where('exploitants.is_deleted', false)
            ->groupBy('exploitants.id', 'exploitants.nom_complet', 'exploitants.raison_sociale')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        $byNature = (clone $baseQuery)
            ->join('article_nature_de_coupe', 'articles.id', '=', 'article_nature_de_coupe.article_id')
            ->join('nature_de_coupes', 'article_nature_de_coupe.nature_de_coupe_id', '=', 'nature_de_coupes.id')
            ->selectRaw('nature_de_coupes.nature_de_coupe as label, COUNT(*) as total')
            ->where('nature_de_coupes.is_deleted', false)
            ->groupBy('nature_de_coupes.id', 'nature_de_coupes.nature_de_coupe')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        $byLocalisation = (clone $baseQuery)
            ->join('article_localisation', 'articles.id', '=', 'article_localisation.article_id')
            ->join('localisations', 'article_localisation.localisation_id', '=', 'localisations.id')
            ->selectRaw('localisations.ENTITE as label, COUNT(*) as total')
            ->where('localisations.is_deleted', false)
            ->groupBy('localisations.id', 'localisations.ENTITE')
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
            $query->where(function ($q) use ($foretId) {
                $q->where('foret_id', $foretId)
                  ->orWhereHas('forets', function ($qq) use ($foretId) {
                      $qq->where('forets.id', $foretId);
                  });
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
            $query->where(function ($q) use ($essenceId) {
                $q->where('essence_id', $essenceId)
                  ->orWhereHas('essences', function ($qq) use ($essenceId) {
                      $qq->where('essences.id', $essenceId);
                  });
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
        $statsByForet = Article::join('article_foret', 'articles.id', '=', 'article_foret.article_id')
            ->join('forets', 'article_foret.foret_id', '=', 'forets.id')
            ->selectRaw('forets.foret, COUNT(*) as total, SUM(CASE WHEN articles.invendu = 0 THEN 1 ELSE 0 END) as vendus, SUM(CASE WHEN articles.invendu = 1 THEN 1 ELSE 0 END) as invendus, SUM(articles.prix_vente) as total_prix_vente, SUM(articles.prix_de_retrait) as total_prix_retrait')
            ->groupBy('forets.id', 'forets.foret')
            ->orderBy('forets.foret')
            ->get();

        // Get statistics by essence
        $statsByEssence = Article::join('article_essence', 'articles.id', '=', 'article_essence.article_id')
            ->join('essences', 'article_essence.essence_id', '=', 'essences.id')
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
        ];

        return view('reports.articles-by-validation-status', compact('articles', 'status', 'stats'));
    }
} 