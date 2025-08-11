<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Foret;
use App\Models\Exploitant;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Get statistics
        $totalArticles = Article::count();
        $vendus = Article::where('invendu', false)->count();
        $invendus = Article::where('invendu', true)->count();
        $totalPrixVente = Article::sum('prix_vente');
        $totalForets = Foret::count();
        $totalExploitants = Exploitant::count();

        // Calculate percentages
        $vendusPercentage = $totalArticles > 0 ? ($vendus / $totalArticles) * 100 : 0;
        $invendusPercentage = $totalArticles > 0 ? ($invendus / $totalArticles) * 100 : 0;
        $caPercentage = 85; // Placeholder percentage

        // Get recent articles
        $recentArticles = Article::with(['foret', 'essence'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $stats = [
            'total_articles' => $totalArticles,
            'vendus' => $vendus,
            'invendus' => $invendus,
            'total_prix_vente' => $totalPrixVente,
            'total_forets' => $totalForets,
            'total_exploitants' => $totalExploitants,
            'vendus_percentage' => round($vendusPercentage),
            'invendus_percentage' => round($invendusPercentage),
            'ca_percentage' => $caPercentage,
        ];

        return view('dashboard', compact('stats', 'recentArticles'));
    }
} 