<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Annee;
use App\Models\Essence;
use App\Models\Foret;
use App\Models\NatureDeCoupe;
use App\Models\SituationAdministrative;
use App\Models\SituationForestiere;
use App\Models\Exploitant;

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
        return view('reports.index');
    }

    public function articlesByYear(ArticlesByYearRequest $request): View
    {
        $year = $request->get('year', date('Y'));
        
        $articles = Article::with([
            'situationAdministrative',
            'situationForestiere',
            'foret',
            'essence',
            'natureDeCoupe',
            'exploitant',
            'localisation'
        ])
        ->where('annee', $year)
        ->orderBy('date', 'desc')
        ->get();

        $annees = Annee::getYearsForSelect();
        
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
        
        $query = Article::with([
            'situationAdministrative',
            'situationForestiere',
            'foret',
            'essence',
            'natureDeCoupe',
            'exploitant',
            'localisation'
        ]);

        if ($foretId) {
            $query->where('foret_id', $foretId);
        }

        $articles = $query->orderBy('date', 'desc')->get();
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
        
        $query = Article::with([
            'situationAdministrative',
            'situationForestiere',
            'foret',
            'essence',
            'natureDeCoupe',
            'exploitant',
            'localisation'
        ]);

        if ($essenceId) {
            $query->where('essence_id', $essenceId);
        }

        $articles = $query->orderBy('date', 'desc')->get();
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
        $exploitantNcp = $request->get('exploitant_n_cp');
        
        $query = Article::with([
            'situationAdministrative',
            'situationForestiere',
            'foret',
            'essence',
            'natureDeCoupe',
            'exploitant',
            'localisation'
        ]);

        if ($exploitantNcp) {
            $query->where('exploitant_n_cp', $exploitantNcp);
        }

        $articles = $query->orderBy('date', 'desc')->get();
        $exploitants = Exploitant::orderBy('nom')->get();

        $stats = [
            'total' => $articles->count(),
            'vendus' => $articles->where('invendu', false)->count(),
            'invendus' => $articles->where('invendu', true)->count(),
            'total_prix_vente' => $articles->sum('prix_vente'),
            'total_prix_retrait' => $articles->sum('prix_de_retrait'),
        ];

        return view('reports.articles-by-exploitant', compact('articles', 'exploitants', 'exploitantNcp', 'stats'));
    }

    public function invendus(): View
    {
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
        ->orderBy('date', 'desc')
        ->get();

        $stats = [
            'total' => $articles->count(),
            'total_prix_retrait' => $articles->sum('prix_de_retrait'),
        ];

        return view('reports.invendus', compact('articles', 'stats'));
    }

    public function vendus(): View
    {
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
        ->orderBy('date', 'desc')
        ->get();

        $stats = [
            'total' => $articles->count(),
            'total_prix_vente' => $articles->sum('prix_vente'),
            'total_fourniture' => $articles->sum('fourniture_mise_charge'),
        ];

        return view('reports.vendus', compact('articles', 'stats'));
    }

    public function summary(): View
    {
        // Summary statistics
        $totalArticles = Article::count();
        $vendus = Article::where('invendu', false)->count();
        $invendus = Article::where('invendu', true)->count();
        $totalPrixVente = Article::sum('prix_vente');
        $totalPrixRetrait = Article::sum('prix_de_retrait');
        $totalFourniture = Article::sum('fourniture_mise_charge');

        // Articles by year
        $articlesByYear = Article::select('annee', DB::raw('count(*) as total'))
            ->groupBy('annee')
            ->orderBy('annee', 'desc')
            ->get();

        // Articles by forest
        $articlesByForet = Article::join('forets', 'articles.foret_id', '=', 'forets.id')
            ->select('forets.foret', DB::raw('count(*) as total'))
            ->groupBy('forets.id', 'forets.foret')
            ->orderBy('total', 'desc')
            ->get();

        // Articles by essence
        $articlesByEssence = Article::join('essences', 'articles.essence_id', '=', 'essences.id')
            ->select('essences.essence', DB::raw('count(*) as total'))
            ->groupBy('essences.id', 'essences.essence')
            ->orderBy('total', 'desc')
            ->get();

        // Top exploitants
        $topExploitants = Article::join('exploitants', 'articles.exploitant_n_cp', '=', 'exploitants.n_cp')
            ->select('exploitants.nom', 'exploitants.prenom', DB::raw('count(*) as total'))
            ->groupBy('exploitants.n_cp', 'exploitants.nom', 'exploitants.prenom')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        return view('reports.summary', compact(
            'totalArticles',
            'vendus',
            'invendus',
            'totalPrixVente',
            'totalPrixRetrait',
            'totalFourniture',
            'articlesByYear',
            'articlesByForet',
            'articlesByEssence',
            'topExploitants'
        ));
    }

    public function exportSummary()
    {
        $articles = Article::with([
            'situationAdministrative',
            'situationForestiere.annee',
            'situationForestiere.zdtf',
            'situationForestiere.dpanef',
            'situationForestiere.dranef',
            'foret',
            'essence',
            'natureDeCoupe',
            'exploitant',

            'localisation'
        ])->orderBy('date', 'desc')->get();

        $filename = 'rapport_complet_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($articles) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID', 'Année', 'Numéro', 'Date', 'Statut', 'Prix de retrait', 'Prix de vente',
                'Commune', 'Province', 'DFP', 'ZDTF', 'DPANEF', 'DRANEF', 'Année Situation',
                'Forêt', 'Essence', 'Nature de coupe', 'Exploitant',
                'Localisation', 'Lot', 'Parcelle', 'Superficie', 'Fourniture mise en charge',
                'Date DR', 'Observations'
            ]);

            foreach ($articles as $article) {
                fputcsv($file, [
                    $article->id,
                    $article->annee,
                    $article->numero,
                    $article->date,
                    $article->invendu ? 'Invendu' : 'Vendu',
                    $article->prix_de_retrait,
                    $article->prix_vente,
                    $article->situationAdministrative?->commune,
                    $article->situationAdministrative?->province,
                    $article->situationForestiere?->dfp,
                    $article->situationForestiere?->zdtf?->zdtf,
                    $article->situationForestiere?->dpanef?->dpanef,
                    $article->situationForestiere?->dranef?->dranef,
                    $article->situationForestiere?->annee?->annee,
                    $article->foret?->foret,
                    $article->essence?->essence,
                    $article->natureDeCoupe?->nature_de_coupe,
                    $article->exploitant ? ($article->exploitant->nom . ' ' . $article->exploitant->prenom) : '',

                    $article->localisation?->display_name,
                    $article->lot,
                    $article->parcelle,
                    $article->superficie,
                    $article->fourniture_mise_charge,
                    $article->date_dr,
                    $article->observations
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
} 