@extends('layouts.app')

@section('title', 'Rapports Unifiés - Articles + Articles Historiques')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Content -->
    <div class="mb-8">
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 p-8">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-chart-pie text-white text-2xl"></i>
                </div>
                <div class="flex-1">
                    <h1 class="text-4xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">
                        Rapports Unifiés
                    </h1>
                    <p class="text-gray-600 text-lg mt-2">Analysez les données combinées des articles actuels et historiques</p>
                </div>
                <div class="flex gap-3">
                    <x-button href="{{ route('reports.unified-table') }}" variant="primary" icon="fas fa-table">
                        Voir le tableau unifié
                    </x-button>
                </div>
            </div>
        </div>
    </div>

    <!-- Combined Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Articles Card -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-file-alt text-white text-xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-900">Total Articles</h3>
                    <p class="text-gray-600 text-sm">{{ number_format($combinedStats['total_articles']) }}</p>
                    <div class="flex items-center text-xs text-gray-500 mt-1">
                        <span class="text-blue-600 font-medium">{{ number_format($combinedStats['current_articles']['total']) }} actuels</span>
                        <span class="mx-1">•</span>
                        <span class="text-amber-600">{{ number_format($combinedStats['legacy_articles']['total']) }} historiques</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Total Revenue Card -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-coins text-white text-xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-900">Revenus Totaux</h3>
                    <p class="text-gray-600 text-sm">{{ number_format($combinedStats['total_revenue'], 0) }} DH</p>
                    <div class="flex items-center text-xs text-gray-500 mt-1">
                        <span class="text-blue-600 font-medium">{{ number_format($combinedStats['current_articles']['total_revenue'], 0) }} DH actuels</span>
                        <span class="mx-1">•</span>
                        <span class="text-amber-600">{{ number_format($combinedStats['legacy_articles']['total_revenue'], 0) }} DH historiques</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Total Volume Card -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-cube text-white text-xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-900">Volume Total</h3>
                    <p class="text-gray-600 text-sm">{{ number_format($combinedStats['total_volume'], 2) }} m³</p>
                    <div class="flex items-center text-xs text-gray-500 mt-1">
                        <span class="text-blue-600 font-medium">{{ number_format($combinedStats['current_articles']['total_volume'], 2) }} m³ actuels</span>
                        <span class="mx-1">•</span>
                        <span class="text-amber-600">{{ number_format($combinedStats['legacy_articles']['total_volume'], 2) }} m³ historiques</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Current Articles Status Card -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-chart-line text-white text-xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-900">Articles Actuels</h3>
                    <p class="text-gray-600 text-sm">{{ number_format($combinedStats['current_articles']['total']) }} total</p>
                    <div class="flex items-center text-xs text-gray-500 mt-1">
                        <span class="text-green-600 font-medium">{{ number_format($combinedStats['current_articles']['sold']) }} vendus</span>
                        <span class="mx-1">•</span>
                        <span class="text-orange-600">{{ number_format($combinedStats['current_articles']['unsold']) }} invendus</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Articles by Year Chart -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-white"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900">Articles par Année</h3>
            </div>
            <canvas id="articlesByYearChart" height="300"></canvas>
        </div>

        <!-- Articles by Forest Chart -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-tree text-white"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900">Articles par Forêt</h3>
            </div>
            <canvas id="articlesByForetChart" height="300"></canvas>
        </div>
    </div>

    <!-- Essence Distribution Chart -->
    <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20 mb-8">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-leaf text-white"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900">Distribution par Essence</h3>
        </div>
        <canvas id="articlesByEssenceChart" height="200"></canvas>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <x-card 
            title="Articles Actuels" 
            subtitle="Consultez les rapports détaillés des articles actuels"
            variant="gradient"
            color="blue"
            icon="fas fa-file-alt"
        >
            <div class="card-actions">
                <x-button href="{{ route('reports.index') }}" variant="primary" icon="fas fa-arrow-right">
                    Voir les rapports actuels
                </x-button>
            </div>
        </x-card>

        <x-card 
            title="Articles Historiques" 
            subtitle="Consultez les rapports des articles historiques"
            variant="gradient"
            color="amber"
            icon="fas fa-archive"
        >
            <div class="card-actions">
                <x-button href="{{ route('reports.legacy-articles') }}" variant="primary" icon="fas fa-arrow-right">
                    Voir les rapports historiques
                </x-button>
            </div>
        </x-card>

        <x-card 
            title="Tableau Unifié" 
            subtitle="Consultez le tableau combiné des articles"
            variant="gradient"
            color="purple"
            icon="fas fa-table"
        >
            <div class="card-actions">
                <x-button href="{{ route('reports.unified-table') }}" variant="primary" icon="fas fa-arrow-right">
                    Voir le tableau unifié
                </x-button>
            </div>
        </x-card>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Articles by Year Chart
    const yearCtx = document.getElementById('articlesByYearChart').getContext('2d');
    const currentYears = @json($currentArticlesByYear->pluck('annee'));
    const currentYearData = @json($currentArticlesByYear->pluck('total'));
    const legacyYears = @json($legacyArticlesByYear->pluck('annee'));
    const legacyYearData = @json($legacyArticlesByYear->pluck('total'));
    
    new Chart(yearCtx, {
        type: 'line',
        data: {
            labels: [...new Set([...currentYears, ...legacyYears])].sort(),
            datasets: [
                {
                    label: 'Articles Actuels',
                    data: currentYearData,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4
                },
                {
                    label: 'Articles Historiques',
                    data: legacyYearData,
                    borderColor: '#f59e0b',
                    backgroundColor: 'rgba(245, 158, 11, 0.1)',
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Articles by Forest Chart
    const foretCtx = document.getElementById('articlesByForetChart').getContext('2d');
    const currentForets = @json($currentArticlesByForet->pluck('foret'));
    const currentForetData = @json($currentArticlesByForet->pluck('total'));
    const legacyForets = @json($legacyArticlesByForet->pluck('foret'));
    const legacyForetData = @json($legacyArticlesByForet->pluck('total'));
    
    new Chart(foretCtx, {
        type: 'bar',
        data: {
            labels: [...new Set([...currentForets, ...legacyForets])].slice(0, 10),
            datasets: [
                {
                    label: 'Articles Actuels',
                    data: currentForetData,
                    backgroundColor: 'rgba(34, 197, 94, 0.8)',
                    borderColor: '#22c55e',
                    borderWidth: 1
                },
                {
                    label: 'Articles Historiques',
                    data: legacyForetData,
                    backgroundColor: 'rgba(245, 158, 11, 0.8)',
                    borderColor: '#f59e0b',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Articles by Essence Chart
    const essenceCtx = document.getElementById('articlesByEssenceChart').getContext('2d');
    const currentEssences = @json($currentArticlesByEssence->pluck('essence'));
    const currentEssenceData = @json($currentArticlesByEssence->pluck('total'));
    const legacyEssences = @json($legacyArticlesByEssence->pluck('essence'));
    const legacyEssenceData = @json($legacyArticlesByEssence->pluck('total'));
    
    new Chart(essenceCtx, {
        type: 'doughnut',
        data: {
            labels: [...new Set([...currentEssences, ...legacyEssences])].slice(0, 8),
            datasets: [
                {
                    label: 'Articles Actuels',
                    data: currentEssenceData,
                    backgroundColor: [
                        '#3b82f6', '#22c55e', '#f59e0b', '#ef4444',
                        '#8b5cf6', '#06b6d4', '#84cc16', '#f97316'
                    ]
                },
                {
                    label: 'Articles Historiques',
                    data: legacyEssenceData,
                    backgroundColor: [
                        '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4',
                        '#84cc16', '#f97316', '#3b82f6', '#22c55e'
                    ]
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
});
</script>
@endpush
