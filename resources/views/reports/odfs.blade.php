@extends('layouts.app')

@section('title', 'Rapport des ODFs - DEFATP')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Content -->
    <div class="mb-8">
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 p-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center shadow-lg" style="background: linear-gradient(to bottom right, #6366f1, #8b5cf6);">
                        <i class="fas fa-users text-white text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold bg-clip-text text-transparent" style="background: linear-gradient(to right, #6366f1, #8b5cf6); -webkit-background-clip: text; background-clip: text;">
                            Rapport des ODFs
                        </h1>
                        <p class="text-gray-600 text-lg mt-2">Analysez les Organisations de la Femme avec des statistiques détaillées</p>
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
            subtitle="Filtrez les ODFs selon vos critères"
            variant="colored"
            color="purple"
            icon="fas fa-filter"
            padding="compact"
        >
            <form method="GET" action="{{ route('reports.odfs') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
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
                    
                    <div>
                        <label for="localisation_id" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-map-marker-alt text-blue-500 mr-1"></i>Localisation
                        </label>
                        <select name="localisation_id" 
                                id="localisation_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Toutes les localisations</option>
                            @foreach($localisations as $localisation)
                                <option value="{{ $localisation->id }}" {{ request('localisation_id') == $localisation->id ? 'selected' : '' }}>
                                    {{ $localisation->DRANEF }} - {{ $localisation->DPANEF }} - {{ $localisation->ENTITE }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="situation_administrative_id" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-building text-green-500 mr-1"></i>Situation Administrative
                        </label>
                        <select name="situation_administrative_id" 
                                id="situation_administrative_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Toutes les situations</option>
                            @foreach($situations as $situation)
                                <option value="{{ $situation->id }}" {{ request('situation_administrative_id') == $situation->id ? 'selected' : '' }}>
                                    {{ $situation->commune }} - {{ $situation->province }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <div class="flex gap-3">
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-xl hover:from-purple-700 hover:to-indigo-700 transition-all duration-300">
                            <i class="fas fa-filter"></i>
                            <span>Appliquer les filtres</span>
                        </button>
                        <a href="{{ route('reports.odfs') }}" class="inline-flex items-center gap-2 px-4 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300">
                            <i class="fas fa-redo"></i>
                            <span>Réinitialiser</span>
                        </a>
                    </div>
                </div>
            </form>
        </x-card>
    </div>

    <!-- Charts Section -->
    <div class="mb-8">
        <x-card 
            title="ODFs par Critère" 
            subtitle="Sélectionnez un critère pour visualiser la distribution des ODFs"
            variant="gradient"
            color="purple"
            icon="fas fa-chart-bar"
        >
            <div class="mb-4">
                <label for="odfChartType" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-filter text-purple-500 mr-1"></i>Sélectionner le critère
                </label>
                <select id="odfChartType" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="localisation">Par Localisation</option>
                    <option value="situation">Par Situation Administrative</option>
                    <option value="member_type">Par Type de Membre</option>
                    <option value="year">Par Année</option>
                </select>
            </div>
            <div class="mb-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-2xl p-4 border border-purple-200" style="position: relative; height: 400px;">
                <canvas id="odfsChart"></canvas>
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
        localisation: {
            labels: @json($stats['by_localisation']->pluck('label')->toArray()),
            data: @json($stats['by_localisation']->pluck('total')->toArray()),
            type: 'doughnut',
            label: 'Nombre d\'ODFs'
        },
        situation: {
            labels: @json($stats['by_situation']->pluck('label')->toArray()),
            data: @json($stats['by_situation']->pluck('total')->toArray()),
            type: 'doughnut',
            label: 'Nombre d\'ODFs'
        },
        member_type: {
            labels: @json($stats['by_member_type']->pluck('label')->toArray()),
            data: @json($stats['by_member_type']->pluck('total')->toArray()),
            type: 'bar',
            label: 'Nombre d\'ODFs'
        },
        year: {
            labels: @json($stats['by_year']->pluck('year')->map(function($year) { return (string)$year; })->toArray()),
            data: @json($stats['by_year']->pluck('total')->toArray()),
            type: 'bar',
            label: 'Nombre d\'ODFs'
        }
    };
    
    let odfsChart = null;
    
    function renderOdfsChart(chartType) {
        const canvas = document.getElementById('odfsChart');
        if (!canvas) {
            console.error('Canvas element not found');
            return;
        }
        
        if (typeof Chart === 'undefined') {
            console.error('Chart.js is not loaded');
            return;
        }
        
        // Destroy existing chart
        if (odfsChart) {
            odfsChart.destroy();
            odfsChart = null;
        }
        
        const data = chartData[chartType];
        console.log('Chart type:', chartType);
        console.log('Chart data:', data);
        
        if (!data || !data.labels || data.labels.length === 0) {
            console.warn('No data available for chart type:', chartType);
            // Show "No data" message
            const ctx = canvas.getContext('2d');
            const parent = canvas.parentElement;
            if (parent) {
                canvas.width = parent.clientWidth;
                canvas.height = parent.clientHeight;
            }
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.font = '16px Arial';
            ctx.fillStyle = '#666';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
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
                    backgroundColor: isBarChart ? '#6366f1' : [
                        '#6366f1', '#8b5cf6', '#ec4899', '#f59e0b', '#10b981',
                        '#06b6d4', '#3b82f6', '#14b8a6', '#f97316', '#ef4444'
                    ],
                    borderColor: isBarChart ? '#4f46e5' : undefined,
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
            odfsChart = new Chart(canvas, config);
        } catch (error) {
            console.error('Error creating chart:', error);
        }
    }
    
    function updateOdfsChart() {
        const select = document.getElementById('odfChartType');
        if (!select) {
            console.error('Select element not found');
            return;
        }
        const chartType = select.value;
        renderOdfsChart(chartType);
    }
    
    // Initialize chart when DOM is ready
    function initChart() {
        const canvas = document.getElementById('odfsChart');
        const select = document.getElementById('odfChartType');
        
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
        
        updateOdfsChart();
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
    const selectElement = document.getElementById('odfChartType');
    if (selectElement) {
        selectElement.addEventListener('change', updateOdfsChart);
    }
})();
</script>
@endpush
@endsection

