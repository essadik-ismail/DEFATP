<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Foret;
use App\Models\Exploitant;
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

        return view('dashboard', compact('stats', 'recentArticles'));
    }
} 