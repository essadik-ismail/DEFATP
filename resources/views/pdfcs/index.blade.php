@extends('layouts.app')

@section('title', 'PDFCs - DEFATP')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #10b981, #059669);">
                <i class="fas fa-project-diagram text-white text-2xl"></i>
            </div>
            <div>
                <h1 class="text-4xl font-bold bg-clip-text text-transparent" style="background: linear-gradient(to right, #10b981, #059669); -webkit-background-clip: text; background-clip: text;">
                    PDFCs
                </h1>
                <p class="text-gray-600 text-lg mt-2">Gérez et consultez tous les PDFCs</p>
            </div>
        </div>
    </div>

    <!-- PDFCs Data Table -->
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-table text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Liste des PDFCs</h2>
                    <p class="text-gray-600">Gérez et consultez tous les PDFCs</p>
                </div>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('pdfcs.create') }}" 
                   class="inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-plus"></i>
                    <span class="font-semibold">Nouveau PDFC</span>
                </a>
            </div>
        </div>
        
        <!-- Search and Filter Section -->
        <div class="bg-gradient-to-r from-gray-50 to-slate-50 rounded-2xl p-6 border border-gray-200 mb-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-gradient-to-br from-gray-500 to-slate-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-search text-white"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Recherche et Filtres</h3>
            </div>
            <form method="GET" action="{{ route('pdfcs.index') }}" id="filterForm">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="form-group">
                        <label for="searchInput" class="block text-sm font-semibold text-gray-700 mb-2">
                            Rechercher
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   class="form-input w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 hover:border-gray-400" 
                                   name="search" 
                                   id="searchInput" 
                                   placeholder="Rechercher dans les PDFCs..." 
                                   value="{{ request('search') }}"
                                   onkeyup="debounceFilter()">
                            <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <i class="fas fa-search"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="start_date" class="block text-sm font-semibold text-gray-700 mb-2">
                            Date de début
                            <i class="fas fa-question-circle mx-1 text-gray-400" title="Format: jj/mm/aaaa (ex: 01/01/2024)"></i>
                        </label>
                        <input type="date" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 hover:border-gray-400" 
                               name="start_date" 
                               id="start_date" 
                               value="{{ request('start_date') }}"
                               placeholder="jj/mm/aaaa"
                               onchange="submitFilter()">
                    </div>
                    <div class="form-group">
                        <label for="end_date" class="block text-sm font-semibold text-gray-700 mb-2">
                            Date de fin
                            <i class="fas fa-question-circle mx-1 text-gray-400" title="Format: jj/mm/aaaa (ex: 01/01/2024)"></i>
                        </label>
                        <input type="date" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 hover:border-gray-400" 
                               name="end_date" 
                               id="end_date" 
                               value="{{ request('end_date') }}"
                               placeholder="jj/mm/aaaa"
                               onchange="submitFilter()">
                    </div>
                    <div class="form-group">
                        <label for="statusFilter" class="block text-sm font-semibold text-gray-700 mb-2">
                            Statut
                        </label>
                        <div class="flex gap-2">
                            <select class="form-input flex-1 px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 hover:border-gray-400" 
                                    name="status" id="statusFilter" onchange="submitFilter()">
                                <option value="">Tous les statuts</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actifs</option>
                                <option value="deleted" {{ request('status') == 'deleted' ? 'selected' : '' }}>Supprimés</option>
                                <option value="recent" {{ request('status') == 'recent' ? 'selected' : '' }}>Récents (30 jours)</option>
                            </select>
                            <button type="button" 
                                    class="px-4 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300"
                                    onclick="clearFilters()" 
                                    title="Effacer les filtres">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Hidden fields to preserve pagination -->
                @if(request('per_page'))
                    <input type="hidden" name="per_page" value="{{ request('per_page') }}">
                @endif
            </form>
        </div>

        <!-- Data Table -->
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-gray-50 to-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date de Début</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date de Fin</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">État</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date de Création</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($pdfcs as $pdfc)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $pdfc->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $pdfc->date_de_début ? $pdfc->date_de_début->format('d/m/Y') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $pdfc->date_de_fin ? $pdfc->date_de_fin->format('d/m/Y') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($pdfc->etat)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ $pdfc->etat }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $pdfc->created_at ? $pdfc->created_at->format('d/m/Y') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-1">
                                        <!-- View Action -->
                                        <a href="{{ route('pdfcs.show', $pdfc) }}" 
                                           class="inline-flex items-center justify-center w-8 h-8 bg-blue-100 hover:bg-blue-200 text-blue-600 rounded-lg transition-colors duration-200" 
                                           title="Voir les détails">
                                            <i class="fas fa-eye text-sm"></i>
                                        </a>
                                        
                                        <!-- Edit Action -->
                                        <a href="{{ route('pdfcs.edit', $pdfc) }}" 
                                           class="inline-flex items-center justify-center w-8 h-8 bg-orange-100 hover:bg-orange-200 text-orange-600 rounded-lg transition-colors duration-200" 
                                           title="Modifier le PDFC">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>
                                        
                                        <!-- Delete Action -->
                                        <form action="{{ route('pdfcs.destroy', $pdfc) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce PDFC ?')"
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg transition-colors duration-200" 
                                                    title="Supprimer le PDFC">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-8">
                                    <div class="text-muted">
                                        <i class="fas fa-project-diagram text-4xl mb-2 d-block"></i>
                                        <p class="h5 mb-2">Aucun PDFC trouvé</p>
                                        <p class="text-muted mb-3">Commencez par créer votre premier PDFC</p>
                                        <a href="{{ route('pdfcs.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>Créer le Premier PDFC
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($pdfcs->hasPages())
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                        <div class="text-sm text-gray-600">
                            <i class="fas fa-info-circle mr-1"></i>
                            Affichage de {{ $pdfcs->firstItem() ?? 0 }} à {{ $pdfcs->lastItem() ?? 0 }} 
                            sur {{ $pdfcs->total() }} PDFCs
                            @if(request()->hasAny(['search', 'status', 'start_date', 'end_date']))
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 ml-2">
                                    <i class="fas fa-filter mr-1"></i>Filtrés
                                </span>
                            @endif
                        </div>
                        <div class="pagination-controls">
                            {{ $pdfcs->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
                        <div class="flex items-center gap-2">
                            <label for="perPageSelect" class="text-sm text-gray-600">Par page:</label>
                            <select class="form-input px-3 py-1 border border-gray-300 rounded-lg text-sm" 
                                    id="perPageSelect" onchange="changePerPage(this.value)">
                                <option value="10" {{ request('per_page', 15) == 10 ? 'selected' : '' }}>10</option>
                                <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15</option>
                                <option value="25" {{ request('per_page', 15) == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page', 15) == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page', 15) == 100 ? 'selected' : '' }}>100</option>
                            </select>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Debounced search function
    let searchTimeout;
    function debounceFilter() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            submitFilter();
        }, 500);
    }

    // Submit filter form
    function submitFilter() {
        document.getElementById('filterForm').submit();
    }

    // Clear all filters
    function clearFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('start_date').value = '';
        document.getElementById('end_date').value = '';
        document.getElementById('statusFilter').value = '';
        submitFilter();
    }

    // Per page selector functionality
    function changePerPage(perPage) {
        const url = new URL(window.location);
        url.searchParams.set('per_page', perPage);
        url.searchParams.delete('page');
        window.location.href = url.toString();
    }
</script>
@endpush

