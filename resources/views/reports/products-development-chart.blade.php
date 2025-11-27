@extends('layouts.app')

@section('title', 'Graphique de Développement des Produits - DEFATP')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-50 py-8">
    <div class="container mx-auto px-4">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-chart-line text-white text-2xl"></i>
                        </div>
                        <div>
                            <h1 class="text-4xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent mb-2">
                                Graphique de Développement des Produits
                            </h1>
                            <p class="text-gray-600 text-lg">Analysez l'évolution des produits par année et par localisation</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('reports.products') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all shadow-md">
                            <i class="fas fa-boxes"></i>
                            <span>Rapport Produits</span>
                        </a>
                        <a href="{{ route('reports.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all shadow-md">
                            <i class="fas fa-arrow-left"></i>
                            <span>Retour</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="mb-8">
            <div class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-filter text-purple-600"></i>
                    Filtres
                </h2>
                <form method="GET" action="{{ route('reports.products-development-chart') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="product_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-box text-purple-500 mr-2"></i>
                                Produit (optionnel)
                            </label>
                            <select name="product_id" id="product_id" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                                <option value="">Tous les produits</option>
                                @foreach($allProducts as $product)
                                    <option value="{{ $product->id }}" {{ $productId == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="localisation_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-map-marker-alt text-purple-500 mr-2"></i>
                                Localisation (optionnel)
                            </label>
                            <select name="localisation_id" id="localisation_id" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                                <option value="">Toutes les localisations</option>
                                @foreach($allLocalisations as $localisation)
                                    <option value="{{ $localisation->id }}" {{ $localisationId == $localisation->id ? 'selected' : '' }}>{{ $localisation->DRANEF }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="start_year" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-calendar-plus text-purple-500 mr-2"></i>
                                Année de début
                            </label>
                            <select name="start_year" id="start_year" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                                <option value="">Toutes les années</option>
                                @foreach($allYears as $year)
                                    <option value="{{ $year }}" {{ $startYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="end_year" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-calendar-minus text-purple-500 mr-2"></i>
                                Année de fin
                            </label>
                            <select name="end_year" id="end_year" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                                <option value="">Toutes les années</option>
                                @foreach($allYears as $year)
                                    <option value="{{ $year }}" {{ $endYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="flex gap-3">
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all shadow-lg hover:shadow-xl transform hover:scale-105">
                            <i class="fas fa-filter mr-2"></i>
                            Appliquer les filtres
                        </button>
                        <a href="{{ route('reports.products-development-chart') }}" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all">
                            <i class="fas fa-undo mr-2"></i>
                            Réinitialiser
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Chart 1: Products by Year -->
        <div class="mb-8">
            <div class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-chart-line text-purple-600"></i>
                        Évolution des Produits par Année
                    </h2>
                    <p class="text-gray-600 text-sm mt-2">Graphique linéaire montrant l'évolution des quantités de produits au fil des années</p>
                </div>
                <div class="p-6">
                    <div style="position: relative; height: 400px;">
                        <canvas id="chartByYear"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart 2: Products by Localisation -->
        <div class="mb-8">
            <div class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-map-marked-alt text-purple-600"></i>
                        Répartition des Produits par Localisation
                    </h2>
                    <p class="text-gray-600 text-sm mt-2">Graphique en barres montrant la répartition des quantités de produits par localisation</p>
                </div>
                <div class="p-6">
                    <div style="position: relative; height: 400px;">
                        <canvas id="chartByLocalisation"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart 3: Products by Year and Localisation (Combined) -->
        <div class="mb-8">
            <div class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-chart-area text-purple-600"></i>
                        Produits par Année et Localisation
                    </h2>
                    <p class="text-gray-600 text-sm mt-2">Graphique combiné montrant l'évolution des produits par année et par localisation</p>
                </div>
                <div class="p-6">
                    <div style="position: relative; height: 500px;">
                        <canvas id="chartByYearAndLocalisation"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function() {
    'use strict';
    
    // Prepare data for Chart 1: By Year
    const chartDataByYear = @json($chartDataByYear);
    const chartDataByLocalisation = @json($chartDataByLocalisation);
    const chartDataByYearAndLocalisation = @json($chartDataByYearAndLocalisation);
    
    // Get all unique product names
    const allProductNames = [];
    Object.values(chartDataByYear).forEach(yearData => {
        Object.keys(yearData).forEach(productName => {
            if (!allProductNames.includes(productName)) {
                allProductNames.push(productName);
            }
        });
    });
    
    // Chart 1: By Year (Line Chart)
    const years = Object.keys(chartDataByYear).sort();
    const datasetsByYear = allProductNames.map((productName, index) => {
        const colors = [
            '#6366f1', '#8b5cf6', '#ec4899', '#f59e0b', '#10b981',
            '#06b6d4', '#3b82f6', '#14b8a6', '#f97316', '#ef4444',
            '#84cc16', '#a855f7', '#06b6d4', '#f43f5e', '#8b5cf6'
        ];
        return {
            label: productName,
            data: years.map(year => chartDataByYear[year]?.[productName] || 0),
            borderColor: colors[index % colors.length],
            backgroundColor: colors[index % colors.length] + '20',
            borderWidth: 2,
            fill: false,
            tension: 0.4
        };
    });
    
    const chartByYearCtx = document.getElementById('chartByYear');
    if (chartByYearCtx && typeof Chart !== 'undefined') {
        new Chart(chartByYearCtx, {
            type: 'line',
            data: {
                labels: years,
                datasets: datasetsByYear
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString('fr-FR', {maximumFractionDigits: 2});
                            }
                        }
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                }
            }
        });
    }
    
    // Chart 2: By Localisation (Bar Chart)
    const localisations = Object.keys(chartDataByLocalisation).sort();
    const datasetsByLocalisation = allProductNames.map((productName, index) => {
        const colors = [
            '#6366f1', '#8b5cf6', '#ec4899', '#f59e0b', '#10b981',
            '#06b6d4', '#3b82f6', '#14b8a6', '#f97316', '#ef4444',
            '#84cc16', '#a855f7', '#06b6d4', '#f43f5e', '#8b5cf6'
        ];
        return {
            label: productName,
            data: localisations.map(loc => chartDataByLocalisation[loc]?.[productName] || 0),
            backgroundColor: colors[index % colors.length] + '80',
            borderColor: colors[index % colors.length],
            borderWidth: 1
        };
    });
    
    const chartByLocalisationCtx = document.getElementById('chartByLocalisation');
    if (chartByLocalisationCtx && typeof Chart !== 'undefined') {
        new Chart(chartByLocalisationCtx, {
            type: 'bar',
            data: {
                labels: localisations,
                datasets: datasetsByLocalisation
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        stacked: false,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString('fr-FR', {maximumFractionDigits: 2});
                            }
                        }
                    },
                    x: {
                        stacked: false
                    }
                }
            }
        });
    }
    
    // Chart 3: By Year and Localisation (Grouped Bar Chart)
    const yearLocalisationKeys = Object.keys(chartDataByYearAndLocalisation).sort();
    const labelsForCombined = yearLocalisationKeys.map(key => {
        const data = chartDataByYearAndLocalisation[key];
        return data.year + ' - ' + data.localisation;
    });
    
    const datasetsByYearAndLocalisation = allProductNames.map((productName, index) => {
        const colors = [
            '#6366f1', '#8b5cf6', '#ec4899', '#f59e0b', '#10b981',
            '#06b6d4', '#3b82f6', '#14b8a6', '#f97316', '#ef4444',
            '#84cc16', '#a855f7', '#06b6d4', '#f43f5e', '#8b5cf6'
        ];
        return {
            label: productName,
            data: yearLocalisationKeys.map(key => {
                const data = chartDataByYearAndLocalisation[key];
                return data.products?.[productName] || 0;
            }),
            backgroundColor: colors[index % colors.length] + '80',
            borderColor: colors[index % colors.length],
            borderWidth: 1
        };
    });
    
    const chartByYearAndLocalisationCtx = document.getElementById('chartByYearAndLocalisation');
    if (chartByYearAndLocalisationCtx && typeof Chart !== 'undefined') {
        new Chart(chartByYearAndLocalisationCtx, {
            type: 'bar',
            data: {
                labels: labelsForCombined,
                datasets: datasetsByYearAndLocalisation
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        stacked: false,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString('fr-FR', {maximumFractionDigits: 2});
                            }
                        }
                    },
                    x: {
                        stacked: false,
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45
                        }
                    }
                }
            }
        });
    }
})();
</script>
@endpush

