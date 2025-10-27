@extends('layouts.app')

@section('title', 'Tableau des Articles Historiques - Rapports')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Content -->
    <div class="mb-8">
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 p-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-table text-white text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold bg-gradient-to-r from-amber-600 to-orange-600 bg-clip-text text-transparent">
                            Tableau des Articles Historiques
                        </h1>
                        <p class="text-gray-600 text-lg mt-2">Consultez et analysez les données détaillées des articles forestiers</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <x-button href="{{ route('reports.legacy-articles') }}" variant="secondary" icon="fas fa-chart-bar">
                        Tableau de bord
                    </x-button>
                    <x-button href="{{ route('reports.legacy-articles-by-year') }}" variant="primary" icon="fas fa-calendar">
                        Par année
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

    <!-- Advanced Filters Section -->
    <div class="bg-gradient-to-r from-gray-50 to-slate-50 rounded-2xl p-6 border border-gray-200 mb-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 bg-gradient-to-br from-gray-500 to-slate-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-filter text-white"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900">Filtres Avancés</h3>
            <button 
                type="button" 
                onclick="toggleAdvancedFilters()" 
                class="ml-auto text-sm text-amber-600 hover:text-amber-700 font-medium"
            >
                <i class="fas fa-chevron-down" id="filterToggleIcon"></i>
                Filtres avancés
            </button>
        </div>
        
        <form method="GET" action="{{ route('reports.legacy-articles-table') }}" id="filterForm">
            <!-- Basic Filters -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-search text-blue-500 mr-2"></i>
                        Recherche globale
                    </label>
                    <input 
                        type="text" 
                        id="search" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="DREF, Forêt, Province, Essence, Acheteur..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors"
                    >
                </div>
                
                <div>
                    <label for="year" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar text-green-500 mr-2"></i>
                        Année
                    </label>
                    <select 
                        id="year" 
                        name="year" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors"
                    >
                        <option value="">Toutes les années</option>
                        @foreach($years as $year)
                            <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="province" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
                        Province
                    </label>
                    <select 
                        id="province" 
                        name="province" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors"
                    >
                        <option value="">Toutes les provinces</option>
                        @foreach($provinces as $province)
                            <option value="{{ $province }}" {{ request('province') == $province ? 'selected' : '' }}>
                                {{ $province }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="essence" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-leaf text-green-500 mr-2"></i>
                        Essence
                    </label>
                    <select 
                        id="essence" 
                        name="essence" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors"
                    >
                        <option value="">Toutes les essences</option>
                        @foreach($essences as $essence)
                            <option value="{{ $essence }}" {{ request('essence') == $essence ? 'selected' : '' }}>
                                {{ $essence }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <!-- Advanced Filters (Collapsible) -->
            <div id="advancedFilters" class="hidden">
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-4">
                    <div>
                        <label for="dref" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-tag text-purple-500 mr-2"></i>
                            DREF
                        </label>
                        <input 
                            type="text" 
                            id="dref" 
                            name="dref" 
                            value="{{ request('dref') }}"
                            placeholder="Filtrer par DREF..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors"
                        >
                    </div>
                    
                    <div>
                        <label for="foret" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-tree text-green-500 mr-2"></i>
                            Forêt
                        </label>
                        <input 
                            type="text" 
                            id="foret" 
                            name="foret" 
                            value="{{ request('foret') }}"
                            placeholder="Filtrer par forêt..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors"
                        >
                    </div>
                    
                    <div>
                        <label for="min_volume" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-cube text-blue-500 mr-2"></i>
                            Volume Min (m³)
                        </label>
                        <input 
                            type="number" 
                            id="min_volume" 
                            name="min_volume" 
                            value="{{ request('min_volume') }}"
                            placeholder="0"
                            step="0.01"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors"
                        >
                    </div>
                    
                    <div>
                        <label for="max_volume" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-cube text-blue-500 mr-2"></i>
                            Volume Max (m³)
                        </label>
                        <input 
                            type="number" 
                            id="max_volume" 
                            name="max_volume" 
                            value="{{ request('max_volume') }}"
                            placeholder="999999"
                            step="0.01"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors"
                        >
                    </div>
                    
                    <div>
                        <label for="min_price" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-coins text-yellow-500 mr-2"></i>
                            Prix Min (DH)
                        </label>
                        <input 
                            type="number" 
                            id="min_price" 
                            name="min_price" 
                            value="{{ request('min_price') }}"
                            placeholder="0"
                            step="0.01"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors"
                        >
                    </div>
                    
                    <div>
                        <label for="max_price" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-coins text-yellow-500 mr-2"></i>
                            Prix Max (DH)
                        </label>
                        <input 
                            type="number" 
                            id="max_price" 
                            name="max_price" 
                            value="{{ request('max_price') }}"
                            placeholder="999999"
                            step="0.01"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors"
                        >
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label for="min_surface" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-expand-arrows-alt text-indigo-500 mr-2"></i>
                            Surface Min (ha)
                        </label>
                        <input 
                            type="number" 
                            id="min_surface" 
                            name="min_surface" 
                            value="{{ request('min_surface') }}"
                            placeholder="0"
                            step="0.01"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors"
                        >
                    </div>
                    
                    <div>
                        <label for="max_surface" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-expand-arrows-alt text-indigo-500 mr-2"></i>
                            Surface Max (ha)
                        </label>
                        <input 
                            type="number" 
                            id="max_surface" 
                            name="max_surface" 
                            value="{{ request('max_surface') }}"
                            placeholder="999999"
                            step="0.01"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors"
                        >
                    </div>
                    
                    <div>
                        <label for="per_page" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-list text-gray-500 mr-2"></i>
                            Résultats par page
                        </label>
                        <select 
                            id="per_page" 
                            name="per_page" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors"
                        >
                            <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>10</option>
                            <option value="20" {{ request('per_page') == '20' ? 'selected' : '' }}>20</option>
                            <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex items-center gap-3">
                <button 
                    type="submit" 
                    class="px-6 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transition-colors flex items-center gap-2"
                >
                    <i class="fas fa-filter"></i>
                    Appliquer les filtres
                </button>
                
                <a 
                    href="{{ route('reports.legacy-articles-table') }}" 
                    class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors flex items-center gap-2"
                >
                    <i class="fas fa-times"></i>
                    Effacer tous les filtres
                </a>
                
                <button 
                    type="button" 
                    onclick="exportToExcel()" 
                    class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors flex items-center gap-2"
                >
                    <i class="fas fa-file-excel"></i>
                    Exporter Excel
                </button>
            </div>
        </form>
    </div>

    <!-- DataTable Card -->
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 p-8">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Articles Historiques</h2>
            <p class="text-gray-600">Tableau interactif avec recherche, tri et pagination</p>
        </div>

        <!-- DataTable -->
        <div class="overflow-x-auto">
            <table id="legacyArticlesTable" class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DREF</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Forêt</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Province</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Essence</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Intervention</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Surface (ha)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">BOM3</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">BIM3</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">BFST</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acheteur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix (DH)</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($articles as $article)
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
                            {{ $article->intervent ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $article->surface ? number_format($article->surface, 2) : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $article->bom3 ? number_format($article->bom3, 2) : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $article->bim3 ? number_format($article->bim3, 2) : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $article->bfst ? number_format($article->bfst, 2) : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $article->acheteur ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $article->ppdh ? number_format($article->ppdh, 2) : 'N/A' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="12" class="px-6 py-4 text-center text-sm text-gray-500">
                            Aucun article historique trouvé.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($articles->hasPages())
        <div class="mt-6">
            {{ $articles->links() }}
        </div>
        @endif
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Initialize DataTable
    $('#legacyArticlesTable').DataTable({
        responsive: true,
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Tous"]],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json'
        },
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors duration-200'
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                className: 'bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors duration-200'
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Imprimer',
                className: 'bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors duration-200'
            }
        ],
        columnDefs: [
            {
                targets: [6, 7, 8, 9, 11], // Surface, BOM3, BIM3, BFST, Prix columns
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

// Toggle advanced filters
function toggleAdvancedFilters() {
    const advancedFilters = document.getElementById('advancedFilters');
    const toggleIcon = document.getElementById('filterToggleIcon');
    
    if (advancedFilters.classList.contains('hidden')) {
        advancedFilters.classList.remove('hidden');
        toggleIcon.classList.remove('fa-chevron-down');
        toggleIcon.classList.add('fa-chevron-up');
    } else {
        advancedFilters.classList.add('hidden');
        toggleIcon.classList.remove('fa-chevron-up');
        toggleIcon.classList.add('fa-chevron-down');
    }
}

// Export to Excel function
function exportToExcel() {
    // Get current filter parameters
    const form = document.getElementById('filterForm');
    const formData = new FormData(form);
    const params = new URLSearchParams();
    
    // Add all form data to URL parameters
    for (let [key, value] of formData.entries()) {
        if (value) {
            params.append(key, value);
        }
    }
    
    // Add export parameter
    params.append('export', 'excel');
    
    // Create download link
    const url = '{{ route("reports.legacy-articles-table") }}?' + params.toString();
    window.open(url, '_blank');
}

// Auto-submit form on filter change
document.addEventListener('DOMContentLoaded', function() {
    const filterInputs = document.querySelectorAll('#filterForm input, #filterForm select');
    
    filterInputs.forEach(input => {
        // For select elements, submit immediately on change
        if (input.tagName === 'SELECT') {
            input.addEventListener('change', function() {
                document.getElementById('filterForm').submit();
            });
        }
        
        // For text inputs, add debounced search
        if (input.type === 'text' || input.type === 'number') {
            let timeout;
            input.addEventListener('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    document.getElementById('filterForm').submit();
                }, 500); // 500ms delay
            });
        }
    });
});
</script>
@endpush

