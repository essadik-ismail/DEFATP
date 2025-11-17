@extends('layouts.app')

@section('title', 'Rapport des Contrats - DEFATP')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Content -->
    <div class="mb-8">
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 p-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center shadow-lg" style="background: linear-gradient(to bottom right, #059669, #047857);">
                        <i class="fas fa-handshake text-white text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold bg-clip-text text-transparent" style="background: linear-gradient(to right, #059669, #047857); -webkit-background-clip: text; background-clip: text;">
                            Rapport des Contrats
                        </h1>
                        <p class="text-gray-600 text-lg mt-2">Analysez les contrats de partenariat avec des statistiques détaillées</p>
                    </div>
                </div>
                <a href="{{ route('reports.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-arrow-left"></i>
                    <span>Retour</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="mb-8">
        <x-card 
            title="Filtres" 
            subtitle="Filtrez les contrats selon vos critères"
            variant="colored"
            color="blue"
            icon="fas fa-filter"
            padding="compact"
        >
            <form method="GET" action="{{ route('reports.contracts') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-plus text-orange-500 mr-1"></i>Date de début
                        </label>
                        <input type="date" 
                               name="start_date" 
                               id="start_date" 
                               value="{{ request('start_date') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-minus text-red-500 mr-1"></i>Date de fin
                        </label>
                        <input type="date" 
                               name="end_date" 
                               id="end_date" 
                               value="{{ request('end_date') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                
                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <div class="flex gap-3">
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-300">
                            <i class="fas fa-filter"></i>
                            <span>Appliquer les filtres</span>
                        </button>
                        <a href="{{ route('reports.contracts') }}" class="inline-flex items-center gap-2 px-4 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300">
                            <i class="fas fa-redo"></i>
                            <span>Réinitialiser</span>
                        </a>
                    </div>
                </div>
            </form>
        </x-card>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-file-contract text-white text-xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-900">Total Contrats</h3>
                    <p class="text-gray-600 text-sm">{{ number_format($stats['total_contracts']) }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-coins text-white text-xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-900">Valeur Totale</h3>
                    <p class="text-gray-600 text-sm">{{ number_format((float)($stats['total_value'] ?? 0), 2) }} DH</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-yellow-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-ruler-combined text-white text-xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-900">Superficie Totale</h3>
                    <p class="text-gray-600 text-sm">{{ number_format((float)($stats['total_superficie'] ?? 0), 2) }} ha</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="mb-8">
        <x-card 
            title="Contrats par Critère" 
            subtitle="Sélectionnez un critère pour visualiser la distribution des contrats"
            variant="gradient"
            color="blue"
            icon="fas fa-chart-bar"
        >
            <div class="mb-4">
                <label for="contractChartType" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-filter text-blue-500 mr-1"></i>Sélectionner le critère
                </label>
                <select id="contractChartType" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="year">Par Année</option>
                    <option value="localisation">Par Localisation</option>
                    <option value="situation">Par Situation Administrative</option>
                    <option value="espece">Par Espèce</option>
                    <option value="foret">Par Forêt</option>
                    <option value="coperative">Par Coopérative</option>
                </select>
            </div>
            <div class="mb-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-4 border border-blue-200" style="position: relative; height: 400px;">
                <canvas id="contractsChart"></canvas>
            </div>
        </x-card>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function() {
    'use strict';
    
    // Prepare chart data
    const chartData = {
        year: {
            labels: @json($stats['by_year']->pluck('annee')->toArray()),
            data: @json($stats['by_year']->pluck('total')->toArray()),
            type: 'bar',
            label: 'Nombre de contrats'
        },
        localisation: {
            labels: @json($stats['by_localisation']->pluck('label')->toArray()),
            data: @json($stats['by_localisation']->pluck('total')->toArray()),
            type: 'doughnut',
            label: 'Nombre de contrats'
        },
        situation: {
            labels: @json($stats['by_situation']->pluck('label')->toArray()),
            data: @json($stats['by_situation']->pluck('total')->toArray()),
            type: 'doughnut',
            label: 'Nombre de contrats'
        },
        espece: {
            labels: @json($stats['by_espece']->pluck('label')->toArray()),
            data: @json($stats['by_espece']->pluck('total')->toArray()),
            type: 'doughnut',
            label: 'Nombre de contrats'
        },
        foret: {
            labels: @json($stats['by_foret']->pluck('label')->filter()->values()->toArray()),
            data: @json($stats['by_foret']->pluck('total')->map(function($item) { return (int)$item; })->toArray()),
            type: 'doughnut',
            label: 'Nombre de contrats'
        },
        coperative: {
            labels: @json($stats['by_coperative']->pluck('label')->toArray()),
            data: @json($stats['by_coperative']->pluck('total')->toArray()),
            type: 'doughnut',
            label: 'Nombre de contrats'
        }
    };
    
    let contractsChart = null;
    
    function renderContractsChart(chartType) {
        const canvas = document.getElementById('contractsChart');
        if (!canvas) {
            console.error('Canvas element not found');
            return;
        }
        
        if (typeof Chart === 'undefined') {
            console.error('Chart.js is not loaded');
            return;
        }
        
        // Destroy existing chart
        if (contractsChart) {
            contractsChart.destroy();
            contractsChart = null;
        }
        
        const data = chartData[chartType];
        console.log('Chart type:', chartType);
        console.log('Chart data:', data);
        
        if (!data || !data.labels || data.labels.length === 0) {
            console.warn('No data available for chart type:', chartType);
            // Show "No data" message
            const ctx = canvas.getContext('2d');
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.font = '16px Arial';
            ctx.fillStyle = '#666';
            ctx.textAlign = 'center';
            ctx.fillText('Aucune donnée disponible', canvas.width / 2, canvas.height / 2);
            return;
        }
        
        const isBarChart = data.type === 'bar';
        
        const config = {
            type: data.type,
            data: {
                labels: data.labels,
                datasets: [{
                    label: data.label,
                    data: data.data,
                    backgroundColor: isBarChart ? '#3b82f6' : [
                        '#3b82f6', '#22c55e', '#f59e0b', '#ef4444', '#8b5cf6',
                        '#06b6d4', '#ec4899', '#14b8a6', '#f97316', '#6366f1'
                    ],
                    borderColor: isBarChart ? '#2563eb' : undefined,
                    borderWidth: isBarChart ? 1 : 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        display: true, 
                        position: isBarChart ? 'top' : 'right' 
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                if (isBarChart) {
                                    return context.dataset.label + ': ' + (context.parsed.y || 0);
                                } else {
                                    return context.label + ': ' + (context.parsed || 0);
                                }
                            }
                        }
                    }
                },
                scales: isBarChart ? {
                    y: { 
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                } : undefined
            }
        };
        
        try {
            contractsChart = new Chart(canvas, config);
        } catch (error) {
            console.error('Error creating chart:', error);
        }
    }
    
    function updateContractsChart() {
        const select = document.getElementById('contractChartType');
        if (!select) {
            console.error('Select element not found');
            return;
        }
        const chartType = select.value;
        renderContractsChart(chartType);
    }
    
    // Initialize chart when DOM is ready
    function initChart() {
        const canvas = document.getElementById('contractsChart');
        const select = document.getElementById('contractChartType');
        
        if (!canvas) {
            console.error('Canvas element not found');
            return false;
        }
        
        if (!select) {
            console.error('Select element not found');
            return false;
        }
        
        if (typeof Chart === 'undefined') {
            console.error('Chart.js is not loaded');
            return false;
        }
        
        updateContractsChart();
        return true;
    }
    
    // Try to initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(initChart, 100);
        });
    } else {
        setTimeout(initChart, 100);
    }
    
    // Update chart when selection changes
    const selectElement = document.getElementById('contractChartType');
    if (selectElement) {
        selectElement.addEventListener('change', updateContractsChart);
    }
})();
</script>
@endpush
@endsection

