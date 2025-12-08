@extends('layouts.app')

@section('title', 'Recette - DEFATP')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #7c3aed, #6366f1);">
                    <i class="fas fa-money-bill-wave text-white text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-4xl font-bold bg-clip-text text-transparent" style="background: linear-gradient(to right, #7c3aed, #6366f1); -webkit-background-clip: text; background-clip: text;">
                        Recette
                    </h1>
                    <p class="text-gray-600 text-lg mt-2">Gérez les résumés nationaux financiers</p>
                </div>
            </div>
            <a href="{{ route('financial-data.create') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                <i class="fas fa-plus"></i>
                <span>Nouveau Résumé</span>
            </a>
        </div>
    </div>

    <!-- Search Box -->
    <div class="mb-6">
        <form method="GET" action="{{ route('financial-data.index') }}" class="flex gap-3">
            <div class="flex-1 relative">
                <input type="text" 
                       name="search" 
                       class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 hover:border-gray-400" 
                       placeholder="Rechercher par année, mois, situation administrative ou localisation..." 
                       value="{{ request('search') }}">
                <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                    <i class="fas fa-search"></i>
                </div>
            </div>
            <button type="submit" 
                    class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-300">
                <i class="fas fa-search"></i>
            </button>
            @if(request('search'))
                <a href="{{ route('financial-data.index') }}" 
                   class="px-4 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300">
                    <i class="fas fa-times"></i>
                </a>
            @endif
        </form>
    </div>

    <!-- National Summaries Table -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table id="nationalSummariesTable" class="w-full">
                <thead class="bg-gradient-to-r from-gray-50 to-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Année</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Mois</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Situation Administrative</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Localisation</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Budget Général Total</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Général</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($nationalSummaries as $summary)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $summary->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $summary->year }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $summary->month ? \Carbon\Carbon::create()->month($summary->month)->locale('fr')->monthName : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $summary->situationAdministrative ? $summary->situationAdministrative->commune . ' - ' . $summary->situationAdministrative->province : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $summary->localisation ? $summary->localisation->CODE . ' - ' . $summary->localisation->ENTITE : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ number_format($summary->budget_general_total, 2) }} DH
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                            {{ number_format($summary->total_general, 2) }} DH
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('financial-data.edit', $summary) }}" 
                                   class="inline-flex items-center gap-1 px-3 py-2 bg-gradient-to-r from-blue-500 to-cyan-500 text-white rounded-lg hover:from-blue-600 hover:to-cyan-600 transition-all duration-300 transform hover:scale-105 shadow-sm"
                                   title="Modifier">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>
                                <form action="{{ route('financial-data.destroy', $summary) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center gap-1 px-3 py-2 bg-gradient-to-r from-red-500 to-pink-500 text-white rounded-lg hover:from-red-600 hover:to-pink-600 transition-all duration-300 transform hover:scale-105 shadow-sm"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce résumé national ?')"
                                            title="Supprimer">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-flag text-2xl text-gray-400"></i>
                                </div>
                                <p class="text-lg font-medium">Aucun résumé national trouvé</p>
                                <p class="text-sm">Commencez par ajouter un nouveau résumé national</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($nationalSummaries->hasPages())
        <div class="mt-6 flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Affichage de {{ $nationalSummaries->firstItem() }} à {{ $nationalSummaries->lastItem() }} sur {{ $nationalSummaries->total() }} résultats
            </div>
            <div class="flex items-center gap-2">
                {{ $nationalSummaries->appends(request()->query())->links() }}
            </div>
        </div>
    @endif
</div>

<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/dataTables.tailwindcss.min.css">

<!-- jQuery (required for DataTables) -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<!-- DataTables JS -->
<script type="text/javascript" src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.7/js/dataTables.tailwindcss.min.js"></script>

<script>
$(document).ready(function() {
    const table = $('#nationalSummariesTable');
    const tbodyRows = table.find('tbody tr');
    
    // Check if table has data rows (not just empty state with colspan)
    const hasDataRows = tbodyRows.length > 0 && 
                       !tbodyRows.first().find('td[colspan]').length;
    
    // If table only has empty state with colspan, remove it
    if (!hasDataRows && tbodyRows.length > 0) {
        const emptyRow = tbodyRows.first();
        if (emptyRow.find('td[colspan]').length) {
            emptyRow.remove();
        }
    }
    
    // Initialize DataTables
    if (hasDataRows || tbodyRows.length === 0) {
        $('#nationalSummariesTable').DataTable({
            processing: false,
            serverSide: false,
            order: [[0, 'desc']],
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Tous']],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json',
                emptyTable: 'Aucune donnée disponible dans le tableau'
            }
        });
    }
});
</script>
@endsection
