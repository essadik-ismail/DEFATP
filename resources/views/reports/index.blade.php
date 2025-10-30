@extends('layouts.app')

@section('title', 'Rapports')

@section('content')
<div class="container mx-auto px-4 py-8">
        <!-- Header Content -->
        <div class="mb-8">
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 p-8">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-chart-line text-white text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">Rapports</h1>
                        <p class="text-gray-600 text-lg mt-2">Générez et consultez différents types de rapports pour analyser vos données</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Date Range Filter -->
        <div class="mb-8">
            <x-card 
                title="Filtres de Date" 
                subtitle="Sélectionnez une période pour filtrer les rapports"
                variant="colored"
                color="blue"
                icon="fas fa-calendar-alt"
                padding="compact"
            >
                <form method="GET" action="{{ route('reports.index') }}" class="flex flex-col md:flex-row gap-4 items-end">
                    <div class="flex-1">
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-plus text-blue-500 mr-2"></i>
                            Date de début
                        </label>
                        <input 
                            type="date" 
                            id="start_date" 
                            name="start_date" 
                            value="{{ request('start_date') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        >
                    </div>
                    
                    <div class="flex-1">
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-minus text-blue-500 mr-2"></i>
                            Date de fin
                        </label>
                        <input 
                            type="date" 
                            id="end_date" 
                            name="end_date" 
                            value="{{ request('end_date') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        >
                    </div>
                    
                    <div class="flex gap-2">
                        <button 
                            type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors flex items-center gap-2"
                        >
                            <i class="fas fa-filter"></i>
                            Filtrer
                        </button>
                        
                        <a 
                            href="{{ route('reports.index') }}" 
                            class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors flex items-center gap-2"
                        >
                            <i class="fas fa-times"></i>
                            Effacer
                        </a>
                    </div>
                </form>
                
                @if(request('start_date') || request('end_date'))
                    <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-center gap-2 text-blue-800">
                            <i class="fas fa-info-circle"></i>
                            <span class="font-medium">Période sélectionnée :</span>
                            <span>
                                @if(request('start_date') && request('end_date'))
                                    Du {{ \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') }} au {{ \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') }}
                                @elseif(request('start_date'))
                                    À partir du {{ \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') }}
                                @elseif(request('end_date'))
                                    Jusqu'au {{ \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') }}
                                @endif
                            </span>
                        </div>
                    </div>
                @endif
            </x-card>
        </div>

        <!-- Reports Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-6">
        <!-- Product Quantities Charts -->
        <x-card 
            title="Graphiques des Quantités de Produits" 
            subtitle="Analysez les quantités de produits par année et par localisation avec des graphiques interactifs"
            variant="gradient"
            color="green"
            icon="fas fa-chart-bar"
            collapsible="true"
            id="product-quantities-charts"
        >
            <div class="mb-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-4 border border-green-200">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-bar text-white"></i>
                    </div>
                    <h3 class="text-lg font-bold text-green-900">Analyse des quantités</h3>
                </div>
                <div class="text-sm text-green-700">
                    <p class="mb-2"><i class="fas fa-chart-line mr-2"></i>Graphique 1: Quantités par année</p>
                    <p><i class="fas fa-map-marker-alt mr-2"></i>Graphique 2: Quantités par localisation</p>
                </div>
            </div>
            <div class="card-stats">
                <span class="stat-item">
                    <i class="fas fa-chart-bar"></i>
                    Graphiques interactifs
                </span>
            </div>
            <div class="card-actions">
                <x-button href="{{ route('reports.product-quantities-charts') }}" variant="primary" icon="fas fa-arrow-right">
                    Voir les graphiques
                </x-button>
            </div>
        </x-card>

        <!-- Legacy Quantities Charts -->
        <x-card 
            title="Graphiques des Quantités de Produits (Legacy)" 
            subtitle="Analysez les quantités de produits par année et par province pour les articles historiques"
            variant="gradient"
            color="orange"
            icon="fas fa-chart-bar"
            collapsible="true"
            id="legacy-quantities-charts"
        >
            <div class="mb-4 bg-gradient-to-r from-orange-50 to-red-50 rounded-2xl p-4 border border-orange-200">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-bar text-white"></i>
                    </div>
                    <h3 class="text-lg font-bold text-orange-900">Analyse des quantités (Legacy)</h3>
                </div>
                <div class="text-sm text-orange-700">
                    <p class="mb-2"><i class="fas fa-chart-line mr-2"></i>Graphique 1: Quantités par année</p>
                    <p><i class="fas fa-map-marker-alt mr-2"></i>Graphique 2: Quantités par province</p>
                </div>
            </div>
            <div class="card-stats">
                <span class="stat-item">
                    <i class="fas fa-chart-bar"></i>
                    Articles historiques uniquement
                </span>
            </div>
            <div class="card-actions">
                <x-button href="{{ route('reports.legacy-quantities-charts') }}" variant="primary" icon="fas fa-arrow-right">
                    Voir les graphiques
                </x-button>
            </div>
        </x-card>

        <!-- Article Quantities Charts -->
        <x-card 
            title="Graphiques des Quantités de Produits (Articles)" 
            subtitle="Analysez les quantités de produits par année et par localisation pour les articles actuels"
            variant="gradient"
            color="blue"
            icon="fas fa-chart-bar"
            collapsible="true"
            id="article-quantities-charts"
        >
            <div class="mb-4 bg-gradient-to-r from-blue-50 to-cyan-50 rounded-2xl p-4 border border-blue-200">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-bar text-white"></i>
                    </div>
                    <h3 class="text-lg font-bold text-blue-900">Analyse des quantités (Articles)</h3>
                </div>
                <div class="text-sm text-blue-700">
                    <p class="mb-2"><i class="fas fa-chart-line mr-2"></i>Graphique 1: Quantités par année</p>
                    <p><i class="fas fa-map-marker-alt mr-2"></i>Graphique 2: Quantités par localisation</p>
                </div>
            </div>
            <div class="card-stats">
                <span class="stat-item">
                    <i class="fas fa-chart-bar"></i>
                    Articles actuels uniquement
                </span>
            </div>
            <div class="card-actions">
                <x-button href="{{ route('reports.article-quantities-charts') }}" variant="primary" icon="fas fa-arrow-right">
                    Voir les graphiques
                </x-button>
            </div>
        </x-card>

        <!-- Legacy Articles -->
        <x-card 
            title="Articles Historiques" 
            subtitle="Consultez et analysez les données historiques des articles forestiers"
            variant="gradient"
            color="amber"
            icon="fas fa-archive"
            collapsible="true"
            id="legacy-articles"
        >
            <div class="mb-4 bg-gradient-to-r from-amber-50 to-orange-50 rounded-2xl p-4 border border-amber-200">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-archive text-white"></i>
                    </div>
                    <h3 class="text-lg font-bold text-amber-900">Données historiques</h3>
                </div>
                <div class="text-center py-4">
                    <i class="fas fa-database text-4xl text-amber-500 mb-2"></i>
                    <p class="text-amber-700 font-medium">Articles historiques disponibles</p>
                </div>
            </div>
            <div class="card-stats">
                <span class="stat-item">
                    <i class="fas fa-archive"></i>
                    Données historiques
                </span>
            </div>
            <div class="card-actions">
                <x-button href="{{ route('reports.legacy-articles') }}" variant="primary" icon="fas fa-arrow-right">
                    Voir les articles historiques
                </x-button>
            </div>
        </x-card>

        <!-- Unified Reports -->
        <x-card 
            title="Rapports Unifiés" 
            subtitle="Analysez les données combinées des articles actuels et historiques"
            variant="gradient"
            color="purple"
            icon="fas fa-chart-pie"
            collapsible="true"
            id="unified-reports"
        >
            <div class="mb-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-2xl p-4 border border-purple-200">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-pie text-white"></i>
                    </div>
                    <h3 class="text-lg font-bold text-purple-900">Analyse combinée</h3>
                </div>
                <div class="text-center py-4">
                    <i class="fas fa-layer-group text-4xl text-purple-500 mb-2"></i>
                    <p class="text-purple-700 font-medium">Articles actuels + historiques</p>
                </div>
            </div>
            <div class="card-stats">
                <span class="stat-item">
                    <i class="fas fa-chart-pie"></i>
                    Analyse combinée
                </span>
            </div>
            <div class="card-actions">
                <x-button href="{{ route('reports.unified') }}" variant="primary" icon="fas fa-arrow-right">
                    Voir les rapports unifiés
                </x-button>
            </div>
        </x-card>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  function renderBarChart(canvasId, labels, data, color) {
    const el = document.getElementById(canvasId);
    if (!el || !window.Chart) return;
    new Chart(el, {
      type: 'bar',
      data: { labels, datasets: [{ data, backgroundColor: color || '#3b82f6' }] },
      options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
    });
  }
});
</script>
@endpush
