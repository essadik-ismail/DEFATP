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

    <!-- Charts Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Articles by Year Chart -->
        <x-card 
            title="Articles par Année" 
            subtitle="Distribution des articles historiques par année"
            variant="gradient"
            color="blue"
            icon="fas fa-chart-line"
        >
            <div class="mb-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-4 border border-blue-200">
                <canvas id="chartByYear" height="200"></canvas>
            </div>
            <div class="card-actions">
                <x-button href="{{ route('reports.legacy-articles-by-year') }}" variant="primary" icon="fas fa-arrow-right">
                    Voir le rapport détaillé
                </x-button>
            </div>
        </x-card>

        <!-- Articles by Province Chart -->
        <x-card 
            title="Articles par Province" 
            subtitle="Distribution géographique des articles historiques"
            variant="colored"
            color="green"
            icon="fas fa-map"
        >
            <div class="mb-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-4 border border-green-200">
                <canvas id="chartByProvince" height="200"></canvas>
            </div>
            <div class="card-actions">
                <x-button href="{{ route('reports.legacy-articles-by-province') }}" variant="primary" icon="fas fa-arrow-right">
                    Voir le rapport détaillé
                </x-button>
            </div>
        </x-card>

        <!-- Articles by Essence Chart -->
        <x-card 
            title="Articles par Essence" 
            subtitle="Distribution des essences forestières historiques"
            variant="gradient"
            color="purple"
            icon="fas fa-leaf"
        >
            <div class="mb-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-2xl p-4 border border-purple-200">
                <canvas id="chartByEssence" height="200"></canvas>
            </div>
            <div class="card-actions">
                <x-button href="{{ route('reports.legacy-articles-by-essence') }}" variant="primary" icon="fas fa-arrow-right">
                    Voir le rapport détaillé
                </x-button>
            </div>
        </x-card>

        <!-- Articles by DREF Chart -->
        <x-card 
            title="Articles par DREF" 
            subtitle="Distribution par direction régionale"
            variant="colored"
            color="orange"
            icon="fas fa-building"
        >
            <div class="mb-4 bg-gradient-to-r from-orange-50 to-amber-50 rounded-2xl p-4 border border-orange-200">
                <canvas id="chartByDref" height="200"></canvas>
            </div>
        </x-card>
    </div>

    <!-- DataTable Preview Section -->
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 p-8">
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Aperçu des Données</h2>
                    <p class="text-gray-600">Tableau interactif avec recherche, tri et pagination</p>
                </div>
                <x-button href="{{ route('reports.legacy-articles-table') }}" variant="primary" icon="fas fa-table">
                    Voir le tableau complet
                </x-button>
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
                    @forelse($previewData as $article)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $article->dref ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $article->foret ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $article->province ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($article->date && strlen(trim($article->date)) >= 6)
                                @php
                                    $dateStr = trim($article->date);
                                    $formattedDate = 'N/A';
                                    
                                    // Try different date formats
                                    try {
                                        if (preg_match('/^\d{6}$/', $dateStr)) {
                                            // Format: YYMMDD
                                            $formattedDate = \Carbon\Carbon::createFromFormat('ymd', $dateStr)->format('d/m/Y');
                                        } elseif (preg_match('/^\d{8}$/', $dateStr)) {
                                            // Format: YYYYMMDD
                                            $formattedDate = \Carbon\Carbon::createFromFormat('Ymd', $dateStr)->format('d/m/Y');
                                        } elseif (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $dateStr)) {
                                            // Format: DD/MM/YYYY
                                            $formattedDate = \Carbon\Carbon::createFromFormat('d/m/Y', $dateStr)->format('d/m/Y');
                                        } elseif (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateStr)) {
                                            // Format: YYYY-MM-DD
                                            $formattedDate = \Carbon\Carbon::createFromFormat('Y-m-d', $dateStr)->format('d/m/Y');
                                        } else {
                                            // If none match, just show the raw value
                                            $formattedDate = $dateStr;
                                        }
                                    } catch (\Exception $e) {
                                        // If all parsing fails, show the raw value
                                        $formattedDate = $dateStr;
                                    }
                                @endphp
                                {{ $formattedDate }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $article->essence ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $article->surface ? number_format($article->surface, 2) : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @php
                                $totalVolume = ($article->bom3 ?? 0) + ($article->bim3 ?? 0) + ($article->bfst ?? 0) + 
                                             ($article->lcst ?? 0) + ($article->ett ?? 0) + ($article->pst ?? 0);
                            @endphp
                            {{ $totalVolume > 0 ? number_format($totalVolume, 2) : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $article->ppdh ? number_format($article->ppdh, 2) : 'N/A' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                            Aucun article historique trouvé.
                        </td>
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
document.addEventListener('DOMContentLoaded', function () {
    function renderBarChart(canvasId, labels, data, color) {
        const el = document.getElementById(canvasId);
        if (!el || !window.Chart) return;
        new Chart(el, {
            type: 'bar',
            data: { 
                labels, 
                datasets: [{ 
                    data, 
                    backgroundColor: color || '#3b82f6',
                    borderColor: color || '#3b82f6',
                    borderWidth: 1
                }] 
            },
            options: { 
                responsive: true, 
                plugins: { legend: { display: false } }, 
                scales: { 
                    y: { beginAtZero: true },
                    x: { ticks: { maxRotation: 45 } }
                } 
            }
        });
    }

    function renderDoughnut(canvasId, labels, data, colors) {
        const el = document.getElementById(canvasId);
        if (!el || !window.Chart) return;
        new Chart(el, {
            type: 'doughnut',
            data: { 
                labels, 
                datasets: [{ 
                    data, 
                    backgroundColor: colors || ['#22c55e','#f59e0b', '#3b82f6', '#ef4444', '#8b5cf6']
                }] 
            },
            options: { 
                responsive: true,
                plugins: { 
                    legend: { position: 'bottom' },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.parsed + ' articles';
                            }
                        }
                    }
                } 
            }
        });
    }

    // Render charts with data from controller
    renderBarChart('chartByYear', 
        @json($stats['by_year']->pluck('year')->map(function($year) { return '20' . $year; })),
        @json($stats['by_year']->pluck('total')),
        '#3b82f6'
    );
    
    renderBarChart('chartByProvince', 
        @json($stats['by_province']->pluck('province')),
        @json($stats['by_province']->pluck('total')),
        '#22c55e'
    );
    
    renderBarChart('chartByEssence', 
        @json($stats['by_essence']->pluck('essence')),
        @json($stats['by_essence']->pluck('total')),
        '#a855f7'
    );
    
    renderDoughnut('chartByDref', 
        @json($stats['by_dref']->pluck('dref')),
        @json($stats['by_dref']->pluck('total'))
    );

    // Initialize DataTable for preview
    $('#legacyArticlesPreviewTable').DataTable({
        responsive: true,
        pageLength: 10,
        lengthMenu: [[5, 10, 25, 50], [5, 10, 25, 50]],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json'
        },
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded-lg transition-colors duration-200 text-sm'
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                className: 'bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-lg transition-colors duration-200 text-sm'
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Imprimer',
                className: 'bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded-lg transition-colors duration-200 text-sm'
            }
        ],
        columnDefs: [
            {
                targets: [5, 6, 7], // Surface, Volume, Prix columns
                type: 'num'
            },
            {
                targets: [3], // Date column
                type: 'date'
            }
        ],
        order: [[3, 'desc']], // Sort by date descending by default
        initComplete: function() {
            // Add custom styling to buttons
            $('.dt-buttons').addClass('mb-4');
            $('.dt-buttons button').addClass('mr-2');
        }
    });
});
</script>
@endpush
