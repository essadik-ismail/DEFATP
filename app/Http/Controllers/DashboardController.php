<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Foret;
use App\Models\Exploitant;
use App\Models\Contract;
use App\Models\Odf;
use App\Models\Pdfc;
use App\Services\ActivityLogger;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Log dashboard access
        ActivityLogger::log('view', 'Accès au tableau de bord principal', null);
        
        // Get date filters from request
        $startDate = request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->startOfDay() : now()->startOfMonth();
        $endDate = request('end_date') ? \Carbon\Carbon::parse(request('end_date'))->endOfDay() : now()->endOfDay();
        
        // Build base query with date filtering
        $articlesQuery = Article::query();
        if (request('start_date') || request('end_date')) {
            $articlesQuery->whereBetween('created_at', [$startDate, $endDate]);
        }
        
        // Get statistics with date filtering
        $totalArticles = $articlesQuery->count();
        $vendus = (clone $articlesQuery)->where('invendu', false)->count();
        $invendus = (clone $articlesQuery)->where('invendu', true)->count();
        $totalPrixVente = (clone $articlesQuery)->sum('prix_vente');
        $totalPrixRetrait = (clone $articlesQuery)->sum('prix_de_retrait');
        
        // Get volume statistics
        $totalVolume = (clone $articlesQuery)->sum('bo_m3') + (clone $articlesQuery)->sum('bi_m3');
        
        // Get validation statistics (no validation column exists, so all articles are considered valid)
        $validatedArticles = $totalArticles; // All existing articles are considered validated
        $pendingArticles = 0; // No pending articles in current system
        
        // Get other statistics (not date-dependent)
        $totalForets = Foret::count();
        $totalExploitants = Exploitant::count();
        $totalEssences = \App\Models\Essence::count();
        $totalLocalisations = \App\Models\Localisation::count();
        $totalUsers = \App\Models\User::count();
        $activeExploitants = Exploitant::where('is_deleted', false)->count();

        // Calculate percentages
        $vendusPercentage = $totalArticles > 0 ? ($vendus / $totalArticles) * 100 : 0;
        $invendusPercentage = $totalArticles > 0 ? ($invendus / $totalArticles) * 100 : 0;
        $validatedPercentage = $totalArticles > 0 ? ($validatedArticles / $totalArticles) * 100 : 0;

        // Get recent articles (with date filtering)
        $recentArticles = (clone $articlesQuery)
            ->with(['forets', 'essences'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get actions required
        $actionsRequired = [];
        
        // 1. Articles invendus
        $unpaidArticles = Article::where('invendu', true)->count();
        if ($unpaidArticles > 0) {
            $actionsRequired[] = [
                'type' => 'articles_invendus',
                'title' => 'Articles Invendus',
                'count' => $unpaidArticles,
                'description' => 'Articles qui n\'ont pas encore été vendus',
                'icon' => 'fa-shopping-cart',
                'color' => 'from-orange-500 to-red-600',
                'route' => route('articles.index', ['invendu' => 1]),
                'priority' => 'high'
            ];
        }
        
        // 2. Articles avec dates de résiliation proches (dans les 30 prochains jours)
        $upcomingResiliations = Article::whereNotNull('date_de_resiliation')
            ->where('date_de_resiliation', '>=', now())
            ->where('date_de_resiliation', '<=', now()->addDays(30))
            ->count();
        if ($upcomingResiliations > 0) {
            $actionsRequired[] = [
                'type' => 'articles_resiliation',
                'title' => 'Résiliations Prochaines',
                'count' => $upcomingResiliations,
                'description' => 'Articles avec résiliation dans les 30 prochains jours',
                'icon' => 'fa-calendar-times',
                'color' => 'from-yellow-500 to-orange-600',
                'route' => route('articles.index'),
                'priority' => 'medium'
            ];
        }
        
        // 3. Articles avec dates de déchéance proches (dans les 30 prochains jours)
        $upcomingDecheances = Article::whereNotNull('date_de_decheance')
            ->where('date_de_decheance', '>=', now())
            ->where('date_de_decheance', '<=', now()->addDays(30))
            ->count();
        if ($upcomingDecheances > 0) {
            $actionsRequired[] = [
                'type' => 'articles_decheance',
                'title' => 'Déchéances Prochaines',
                'count' => $upcomingDecheances,
                'description' => 'Articles avec déchéance dans les 30 prochains jours',
                'icon' => 'fa-exclamation-triangle',
                'color' => 'from-red-500 to-pink-600',
                'route' => route('articles.index'),
                'priority' => 'high'
            ];
        }
        
        // 4. PDFCs non validés
        $unvalidatedPdfcs = Pdfc::where('etat', '!=', 'validé C.C')->count();
        if ($unvalidatedPdfcs > 0) {
            $actionsRequired[] = [
                'type' => 'pdfcs_non_valides',
                'title' => 'PDFCs Non Validés',
                'count' => $unvalidatedPdfcs,
                'description' => 'PDFCs en attente de validation',
                'icon' => 'fa-file-alt',
                'color' => 'from-blue-500 to-indigo-600',
                'route' => route('pdfcs.index'),
                'priority' => 'medium'
            ];
        }
        
        // 5. ODFs sans constitution
        $odfsWithoutConstitution = Odf::where('constitution', false)->count();
        if ($odfsWithoutConstitution > 0) {
            $actionsRequired[] = [
                'type' => 'odfs_sans_constitution',
                'title' => 'ODFs Sans Constitution',
                'count' => $odfsWithoutConstitution,
                'description' => 'ODFs qui n\'ont pas encore été constitués',
                'icon' => 'fa-folder-open',
                'color' => 'from-purple-500 to-pink-600',
                'route' => route('odfs.index'),
                'priority' => 'medium'
            ];
        }
        
        // 6. Exploitants avec permis expirés
        $expiredPermits = Exploitant::where('is_deleted', false)
            ->whereNotNull('duree_validite')
            ->where('duree_validite', '<=', now())
            ->count();
        if ($expiredPermits > 0) {
            $actionsRequired[] = [
                'type' => 'exploitants_permis_expires',
                'title' => 'Permis Expirés',
                'count' => $expiredPermits,
                'description' => 'Exploitants avec permis expirés',
                'icon' => 'fa-id-card',
                'color' => 'from-red-500 to-orange-600',
                'route' => route('exploitants.index'),
                'priority' => 'high'
            ];
        }
        
        // 7. Contrats avec dates de résiliation proches
        $upcomingContractResiliations = Contract::whereNotNull('date_resiliation')
            ->where('date_resiliation', '>=', now())
            ->where('date_resiliation', '<=', now()->addDays(30))
            ->count();
        if ($upcomingContractResiliations > 0) {
            $actionsRequired[] = [
                'type' => 'contrats_resiliation',
                'title' => 'Résiliations de Contrats',
                'count' => $upcomingContractResiliations,
                'description' => 'Contrats avec résiliation dans les 30 prochains jours',
                'icon' => 'fa-file-contract',
                'color' => 'from-amber-500 to-yellow-600',
                'route' => route('contracts.index'),
                'priority' => 'medium'
            ];
        }
        
        // Sort by priority (high first)
        usort($actionsRequired, function($a, $b) {
            $priorityOrder = ['high' => 1, 'medium' => 2, 'low' => 3];
            return $priorityOrder[$a['priority']] <=> $priorityOrder[$b['priority']];
        });

        $stats = [
            'totalArticles' => $totalArticles,
            'validatedArticles' => $validatedArticles,
            'pendingArticles' => $pendingArticles,
            'vendus' => $vendus,
            'invendus' => $invendus,
            'totalRevenue' => $totalPrixVente,
            'totalRetrait' => $totalPrixRetrait,
            'totalVolume' => $totalVolume,
            'totalForests' => $totalForets,
            'totalEssences' => $totalEssences,
            'totalLocalisations' => $totalLocalisations,
            'totalExploitants' => $totalExploitants,
            'activeExploitants' => $activeExploitants,
            'totalUsers' => $totalUsers,
            'vendusPercentage' => round($vendusPercentage),
            'invendusPercentage' => round($invendusPercentage),
            'validatedPercentage' => round($validatedPercentage),
        ];

        return view('dashboard', compact('stats', 'recentArticles', 'actionsRequired'));
    }
} 