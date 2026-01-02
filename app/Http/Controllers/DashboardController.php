<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Foret;
use App\Models\Exploitant;
use App\Models\Contract;
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
        // Removed vendus/invendus statistics - invendu column was removed
        $vendus = 0;
        $invendus = 0;
        // Removed prix_vente and prix_de_retrait statistics - columns were removed
        $totalPrixVente = 0;
        $totalPrixRetrait = 0;
        
        // Get volume statistics from products
        $articles = (clone $articlesQuery)->with('products')->get();
        $totalVolume = 0;
        foreach ($articles as $article) {
            $boProduct = $article->products()->where('name', 'BO (m³)')->first();
            $biProduct = $article->products()->where('name', 'BI (m³)')->first();
            $totalVolume += ($boProduct ? $boProduct->pivot->quantity : 0) + ($biProduct ? $biProduct->pivot->quantity : 0);
        }
        
        // Get validation statistics (no validation column exists, so all articles are considered valid)
        $validatedArticles = $totalArticles; // All existing articles are considered validated
        $pendingArticles = 0; // No pending articles in current system
        
        // Get other statistics (not date-dependent)
        $totalForets = Foret::count();
        $totalExploitants = Exploitant::count();
        $totalEssences = \App\Models\Essence::count();
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
        
        // Removed articles invendus check - invendu column was removed
        // Removed articles résiliation check - date_de_resiliation column was removed
        // Removed articles déchéance check - date_de_decheance column was removed
        
        // Exploitants avec permis expirés
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