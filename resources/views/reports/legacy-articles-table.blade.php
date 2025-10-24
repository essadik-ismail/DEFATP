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
</script>
@endpush

