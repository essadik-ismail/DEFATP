@extends('layouts.app')

@section('title', 'Articles Historiques - Rapports')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Content -->
    <div class="mb-8">
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 p-8">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center shadow-lg" style="background: linear-gradient(to bottom right, #059669, #047857);">
                    <i class="fas fa-archive text-white text-2xl"></i>
                </div>
                <div class="flex-1">
                    <h1 class="text-4xl font-bold bg-clip-text text-transparent" style="background: linear-gradient(to right, #059669, #047857); -webkit-background-clip: text; background-clip: text;">
                        Articles Historiques
                    </h1>
                    <p class="text-gray-600 text-lg mt-2">Analysez les données historiques des articles forestiers</p>
                </div>
                <div class="flex gap-3">
                    <x-button href="{{ route('reports.legacy-articles-table') }}" variant="primary" icon="fas fa-table">
                        Voir le tableau
                    </x-button>
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
            <form method="GET" action="{{ route('reports.legacy-articles') }}" class="flex flex-col md:flex-row gap-4 items-end">
                <div class="flex-1">
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar-plus text-blue-500 mr-2"></i>
                        Date de début
                        <i class="fas fa-question-circle mx-1 text-gray-400" title="Format: jj/mm/aaaa (ex: 01/01/2024)"></i>
                    </label>
                    <input 
                        type="date" 
                        id="start_date" 
                        name="start_date" 
                        value="{{ request('start_date') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        placeholder="jj/mm/aaaa"
                    >
                </div>
                
                <div class="flex-1">
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar-minus text-blue-500 mr-2"></i>
                        Date de fin
                        <i class="fas fa-question-circle mx-1 text-gray-400" title="Format: jj/mm/aaaa (ex: 31/12/2024)"></i>
                    </label>
                    <input 
                        type="date" 
                        id="end_date" 
                        name="end_date" 
                        value="{{ request('end_date') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        placeholder="jj/mm/aaaa"
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
                        href="{{ route('reports.legacy-articles') }}" 
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

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Records Card -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-database text-white text-xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-900">Total des Enregistrements</h3>
                    <p class="text-gray-600 text-sm">{{ number_format($stats['total_records']) }}</p>
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
                    <p class="text-gray-600 text-sm">{{ number_format($stats['total_revenue'], 0) }} DH</p>
                </div>
            </div>
        </div>
        
        <!-- Total Volume Card -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-yellow-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-cube text-white text-xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-900">Volume Total</h3>
                    <p class="text-gray-600 text-sm">{{ number_format($stats['total_volume'], 2) }} m³</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quantities Charts Section -->
    <div class="mb-8">
        <x-card 
            title="Volumes par Critère" 
            subtitle="Bo m³, Bi m³, Bf st, Liége st"
            variant="gradient"
            color="blue"
            icon="fas fa-chart-bar"
        >
            <div class="mb-4">
                <label for="volumeChartType" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-filter text-blue-600 mr-2"></i>
                    Sélectionner le critère
                </label>
                <select id="volumeChartType" class="w-full md:w-auto px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white">
                    <option value="year">Par Année</option>
                    <option value="province">Par Province</option>
                    <option value="essence">Par Essence</option>
                    <option value="dref">Par DREF</option>
                </select>
            </div>
            <div class="mb-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-4 border border-blue-200" style="position: relative; height: 400px;">
                <canvas id="quantitiesChart"></canvas>
            </div>
        </x-card>
    </div>

    <!-- DataTable Preview Section -->
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 p-8">
        <div class="mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Aperçu des Données</h2>
                <p class="text-gray-600">Tableau interactif avec recherche, tri et pagination</p>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="mb-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-filter text-blue-600"></i>
                    Filtres
                </h3>
                <button type="button" id="resetFilters" class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">
                    <i class="fas fa-redo"></i>
                    Réinitialiser
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label for="filterProvince" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-map-marker-alt text-red-500 mr-1"></i>
                        Province
                    </label>
                    <select id="filterProvince" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Toutes les provinces</option>
                        @foreach($provinces as $province)
                            <option value="{{ $province }}">{{ $province }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="filterEssence" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-leaf text-green-500 mr-1"></i>
                        Essence
                    </label>
                    <select id="filterEssence" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Toutes les essences</option>
                        @foreach($essences as $essence)
                            <option value="{{ $essence }}">{{ $essence }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="filterDref" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-building text-orange-500 mr-1"></i>
                        DREF
                    </label>
                    <select id="filterDref" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les DREF</option>
                        @foreach($drefs as $dref)
                            <option value="{{ $dref }}">{{ $dref }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="filterYear" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar-alt text-purple-500 mr-1"></i>
                        Année
                    </label>
                    <select id="filterYear" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Toutes les années</option>
                        @foreach($years as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- DataTable -->
        <div class="overflow-x-auto">
            <table id="legacyArticlesPreviewTable" class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DREF</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Forêt</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Province</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Essence</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Surface (ha)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Volume (m³)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix (DH)</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($tableData as $article)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $article['dref'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $article['foret'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $article['province'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $article['date'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $article['essence'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $article['surface'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $article['volume'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $article['ppdh'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">Aucune donnée disponible</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/dataTables.tailwindcss.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

<style>
    /* Custom DataTable styling */
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
        margin-top: 1rem;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0.5rem 0.75rem;
        margin: 0 0.25rem;
        border-radius: 0.5rem;
        border: 1px solid #e5e7eb;
        background: white;
        color: #374151;
        transition: all 0.2s;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    .dataTables_wrapper .dataTables_length select {
        padding: 0.5rem;
        border-radius: 0.5rem;
        border: 1px solid #d1d5db;
    }
    
    .dataTables_wrapper .dataTables_filter input {
        padding: 0.5rem;
        border-radius: 0.5rem;
        border: 1px solid #d1d5db;
        margin-left: 0.5rem;
    }
</style>

<!-- jQuery (required for DataTables) -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<!-- DataTables JS -->
<script type="text/javascript" src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.7/js/dataTables.tailwindcss.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {

    // Render quantities charts
    function renderQuantitiesChart(canvasId, labels, bom3Data, bim3Data, bfstData, lcstData) {
        const el = document.getElementById(canvasId);
        if (!el) {
            console.error('Canvas element not found:', canvasId);
            return;
        }
        
        if (!window.Chart) {
            console.error('Chart.js is not loaded');
            return;
        }
        
        // Destroy existing chart if it exists
        const chartKey = canvasId + 'ChartInstance';
        if (window[chartKey]) {
            console.log('Destroying existing chart:', chartKey);
            try {
                window[chartKey].destroy();
            } catch (e) {
                console.warn('Error destroying chart:', e);
            }
            window[chartKey] = null;
        }
        
        // Ensure data is arrays and convert to numbers
        labels = Array.isArray(labels) ? labels : [];
        bom3Data = Array.isArray(bom3Data) ? bom3Data.map(v => parseFloat(v) || 0) : [];
        bim3Data = Array.isArray(bim3Data) ? bim3Data.map(v => parseFloat(v) || 0) : [];
        bfstData = Array.isArray(bfstData) ? bfstData.map(v => parseFloat(v) || 0) : [];
        lcstData = Array.isArray(lcstData) ? lcstData.map(v => parseFloat(v) || 0) : [];
        
        // Check if we have data
        if (labels.length === 0) {
            console.warn('No data for chart:', canvasId);
            // Show a message in the canvas
            const ctx = el.getContext('2d');
            ctx.clearRect(0, 0, el.width, el.height);
            ctx.font = '16px Arial';
            ctx.fillStyle = '#666';
            ctx.textAlign = 'center';
            ctx.fillText('Aucune donnée disponible', el.width / 2, el.height / 2);
            return;
        }
        
        try {
            const chartInstance = new Chart(el, {
                type: 'bar',
                data: { 
                    labels: labels, 
                    datasets: [
                        { 
                            label: 'Bo m³',
                            data: bom3Data, 
                            backgroundColor: '#3b82f6',
                            borderColor: '#2563eb',
                            borderWidth: 1
                        },
                        { 
                            label: 'Bi m³',
                            data: bim3Data, 
                            backgroundColor: '#22c55e',
                            borderColor: '#16a34a',
                            borderWidth: 1
                        },
                        { 
                            label: 'Bf st',
                            data: bfstData, 
                            backgroundColor: '#f59e0b',
                            borderColor: '#d97706',
                            borderWidth: 1
                        },
                        { 
                            label: 'Liége st',
                            data: lcstData, 
                            backgroundColor: '#8b5cf6',
                            borderColor: '#7c3aed',
                            borderWidth: 1
                        }
                    ] 
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
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + parseFloat(context.parsed.y).toLocaleString('fr-FR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                                }
                            }
                        }
                    }, 
                    scales: { 
                        y: { 
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return parseFloat(value).toLocaleString('fr-FR', {minimumFractionDigits: 0, maximumFractionDigits: 0});
                                }
                            }
                        },
                        x: { 
                            ticks: { maxRotation: 45, minRotation: 45 } 
                        }
                    } 
                }
            });
            
            // Store chart instance
            window[canvasId + 'ChartInstance'] = chartInstance;
        } catch (error) {
            console.error('Error rendering chart:', canvasId, error);
        }
    }

    // Store all chart data - ensure data is properly structured and aligned
    @php
        // Process year data - filter out null years and ensure all data is aligned
        $yearData = $stats['quantities_by_year']->filter(function($item) {
            return !empty($item->year) && $item->year !== null && $item->year !== '';
        })->values();
        
        $yearLabels = [];
        $yearBom3 = [];
        $yearBim3 = [];
        $yearBfst = [];
        $yearLcst = [];
        
        foreach ($yearData as $item) {
            $yearLabels[] = (string)$item->year;
            $yearBom3[] = (float)($item->bom3 ?? 0);
            $yearBim3[] = (float)($item->bim3 ?? 0);
            $yearBfst[] = (float)($item->bfst ?? 0);
            $yearLcst[] = (float)($item->lcst ?? 0);
        }
    @endphp
    const chartData = {
        year: {
            labels: @json($yearLabels),
            bom3: @json($yearBom3),
            bim3: @json($yearBim3),
            bfst: @json($yearBfst),
            lcst: @json($yearLcst)
        },
        province: {
            labels: @json($stats['quantities_by_province']->pluck('province')->toArray()),
            bom3: @json($stats['quantities_by_province']->pluck('bom3')->toArray()),
            bim3: @json($stats['quantities_by_province']->pluck('bim3')->toArray()),
            bfst: @json($stats['quantities_by_province']->pluck('bfst')->toArray()),
            lcst: @json($stats['quantities_by_province']->pluck('lcst')->toArray())
        },
        essence: {
            labels: @json($stats['quantities_by_essence']->pluck('essence')->toArray()),
            bom3: @json($stats['quantities_by_essence']->pluck('bom3')->toArray()),
            bim3: @json($stats['quantities_by_essence']->pluck('bim3')->toArray()),
            bfst: @json($stats['quantities_by_essence']->pluck('bfst')->toArray()),
            lcst: @json($stats['quantities_by_essence']->pluck('lcst')->toArray())
        },
        dref: {
            labels: @json($stats['quantities_by_dref']->pluck('dref')->toArray()),
            bom3: @json($stats['quantities_by_dref']->pluck('bom3')->toArray()),
            bim3: @json($stats['quantities_by_dref']->pluck('bim3')->toArray()),
            bfst: @json($stats['quantities_by_dref']->pluck('bfst')->toArray()),
            lcst: @json($stats['quantities_by_dref']->pluck('lcst')->toArray())
        }
    };

    // Function to update chart based on selection
    function updateQuantitiesChart() {
        const chartType = document.getElementById('volumeChartType').value;
        const data = chartData[chartType];
        
        console.log('Updating chart for type:', chartType);
        console.log('Full data object:', data);
        
        if (!data) {
            console.error('No data object found for chart type:', chartType);
            return;
        }
        
        // Get arrays - they should already be aligned from PHP
        const labels = Array.isArray(data.labels) ? data.labels : [];
        const bom3 = Array.isArray(data.bom3) ? data.bom3.map(v => parseFloat(v) || 0) : [];
        const bim3 = Array.isArray(data.bim3) ? data.bim3.map(v => parseFloat(v) || 0) : [];
        const bfst = Array.isArray(data.bfst) ? data.bfst.map(v => parseFloat(v) || 0) : [];
        const lcst = Array.isArray(data.lcst) ? data.lcst.map(v => parseFloat(v) || 0) : [];
        
        console.log('Labels:', labels);
        console.log('Labels count:', labels.length);
        console.log('Data counts:', { bom3: bom3.length, bim3: bim3.length, bfst: bfst.length, lcst: lcst.length });
        
        if (labels.length === 0) {
            console.warn('No labels found for chart type:', chartType);
            const el = document.getElementById('quantitiesChart');
            if (el) {
                const ctx = el.getContext('2d');
                ctx.clearRect(0, 0, el.width, el.height);
                ctx.font = '16px Arial';
                ctx.fillStyle = '#666';
                ctx.textAlign = 'center';
                ctx.fillText('Aucune donnée disponible pour ce critère', el.width / 2, el.height / 2);
            }
            return;
        }
        
        // Ensure all arrays have the same length
        const maxLength = Math.min(labels.length, bom3.length, bim3.length, bfst.length, lcst.length);
        const finalLabels = labels.slice(0, maxLength);
        const finalBom3 = bom3.slice(0, maxLength);
        const finalBim3 = bim3.slice(0, maxLength);
        const finalBfst = bfst.slice(0, maxLength);
        const finalLcst = lcst.slice(0, maxLength);
        
        console.log('Final data to render:', {
            labels: finalLabels,
            bom3: finalBom3,
            bim3: finalBim3,
            bfst: finalBfst,
            lcst: finalLcst
        });
        
        renderQuantitiesChart('quantitiesChart',
            finalLabels,
            finalBom3,
            finalBim3,
            finalBfst,
            finalLcst
        );
    }

    // Initialize chart with default selection (year)
    // Wait for DOM and Chart.js to be fully ready
    setTimeout(function() {
        const canvas = document.getElementById('quantitiesChart');
        const select = document.getElementById('volumeChartType');
        
        if (canvas && select && window.Chart) {
            console.log('Initializing chart with default selection (year)');
            updateQuantitiesChart();
        } else {
            console.error('Required elements not found:', {
                canvas: !!canvas,
                select: !!select,
                Chart: !!window.Chart
            });
            // Retry after a longer delay
            setTimeout(function() {
                updateQuantitiesChart();
            }, 500);
        }
    }, 200);

    // Update chart when selection changes
    document.getElementById('volumeChartType').addEventListener('change', function() {
        updateQuantitiesChart();
    });

    // Destroy existing DataTable instance if it exists
    if ($.fn.DataTable.isDataTable('#legacyArticlesPreviewTable')) {
        $('#legacyArticlesPreviewTable').DataTable().destroy();
    }

    // Initialize DataTable for preview (client-side)
    $('#legacyArticlesPreviewTable').DataTable({
        processing: false,
        serverSide: false,
        data: @json($tableData),
        columns: [
            { data: 'dref', name: 'dref', orderable: true, searchable: true },
            { data: 'foret', name: 'foret', orderable: true, searchable: true },
            { data: 'province', name: 'province', orderable: true, searchable: true },
            { data: 'date', name: 'date', orderable: true, searchable: false },
            { data: 'essence', name: 'essence', orderable: true, searchable: true },
            { data: 'surface', name: 'surface', orderable: true, searchable: false },
            { data: 'volume', name: 'volume', orderable: true, searchable: false },
            { data: 'ppdh', name: 'ppdh', orderable: true, searchable: false }
        ],
        order: [[3, 'desc']], // Sort by date column
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Tous']],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json',
            processing: 'Traitement en cours...',
            emptyTable: 'Aucune donnée disponible dans le tableau',
            zeroRecords: 'Aucun résultat trouvé',
            loadingRecords: 'Chargement...',
            info: 'Affichage de _START_ à _END_ sur _TOTAL_ entrées',
            infoEmpty: 'Affichage de 0 à 0 sur 0 entrées',
            infoFiltered: '(filtré à partir de _MAX_ entrées au total)',
            search: 'Rechercher:',
            lengthMenu: 'Afficher _MENU_ entrées',
            paginate: {
                first: 'Premier',
                last: 'Dernier',
                next: 'Suivant',
                previous: 'Précédent'
            }
        },
        dom: '<"flex flex-wrap items-center justify-between mb-4"<"flex items-center gap-4"l<"ml-4"f>><"flex items-center gap-2"B>>rtip',
        buttons: [
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded-lg transition-colors duration-200 text-sm',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7]
                }
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                className: 'bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-lg transition-colors duration-200 text-sm',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7]
                }
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Imprimer',
                className: 'bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded-lg transition-colors duration-200 text-sm',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7]
                }
            }
        ],
        columnDefs: [
            {
                targets: [5, 6, 7], // Surface, Volume, Prix columns
                type: 'num',
                render: function(data, type, row) {
                    if (type === 'display' || type === 'filter') {
                        if (data === 'N/A' || data === null || data === '') {
                            return 'N/A';
                        }
                        return parseFloat(data).toLocaleString('fr-FR', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });
                    }
                    return data;
                }
            },
            {
                targets: [3], // Date column
                type: 'string',
                render: function(data, type, row) {
                    return data || 'N/A';
                }
            }
        ],
        responsive: true,
        initComplete: function() {
            // Add custom styling to buttons
            $('.dt-buttons').addClass('mb-4');
            $('.dt-buttons button').addClass('mr-2');
        }
    });

    // Add search input hint
    $(document).on('focus', '.dataTables_filter input', function() {
        if (typeof UXUtils !== 'undefined') {
            UXUtils.showToast('Utilisez Ctrl+K pour rechercher rapidement', 'info', 3000);
        }
    });

    // Add keyboard shortcut Ctrl+K to focus search
    $(document).on('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'k' && !$(e.target).is('input, textarea')) {
            e.preventDefault();
            $('.dataTables_filter input').focus();
        }
    });

    // Custom column filters
    var table = $('#legacyArticlesPreviewTable').DataTable();

    // Filter by Province
    $('#filterProvince').on('change', function() {
        table.column(2).search(this.value).draw();
    });

    // Filter by Essence
    $('#filterEssence').on('change', function() {
        table.column(4).search(this.value).draw();
    });

    // Filter by DREF
    $('#filterDref').on('change', function() {
        table.column(0).search(this.value).draw();
    });

    // Filter by Year - custom search function for date column
    $.fn.dataTable.ext.search.push(
        function(settings, data, dataIndex) {
            var selectedYear = $('#filterYear').val();
            if (!selectedYear) {
                return true; // Show all if no year selected
            }
            
            var dateValue = data[3]; // Date column index
            if (!dateValue || dateValue === 'N/A') {
                return false; // Hide rows with invalid dates
            }
            
            // Extract year from date format "dd/mm/yyyy"
            var dateParts = dateValue.split('/');
            if (dateParts.length === 3) {
                var year = dateParts[2];
                return year === selectedYear;
            }
            
            return false;
        }
    );

    $('#filterYear').on('change', function() {
        table.draw();
    });

    // Reset all filters
    $('#resetFilters').on('click', function() {
        $('#filterProvince').val('');
        $('#filterEssence').val('');
        $('#filterDref').val('');
        $('#filterYear').val('');
        
        // Remove custom year filter
        $.fn.dataTable.ext.search.pop();
        $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex) {
                var selectedYear = $('#filterYear').val();
                if (!selectedYear) {
                    return true;
                }
                var dateValue = data[3];
                if (!dateValue || dateValue === 'N/A') {
                    return false;
                }
                var dateParts = dateValue.split('/');
                if (dateParts.length === 3) {
                    var year = dateParts[2];
                    return year === selectedYear;
                }
                return false;
            }
        );
        
        table.search('').columns().search('').draw();
    });
});
</script>
@endpush
