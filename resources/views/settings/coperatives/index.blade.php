@extends('layouts.app')

@section('title', 'Coopératives - DEFATP')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #3b82f6, #2563eb);">
                <i class="fas fa-users-cog text-white text-2xl"></i>
            </div>
            <div>
                <h1 class="text-4xl font-bold bg-clip-text text-transparent" style="background: linear-gradient(to right, #3b82f6, #2563eb); -webkit-background-clip: text; background-clip: text;">
                    Coopératives
                </h1>
                <p class="text-gray-600 text-lg mt-2">Gérez et consultez toutes les coopératives</p>
            </div>
        </div>
    </div>

    <!-- Coperatives Data Table -->
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-table text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Liste des Coopératives</h2>
                    <p class="text-gray-600">Gérez et consultez toutes les coopératives</p>
                </div>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('contracts.coperatives.create') }}" 
                   class="inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-plus"></i>
                    <span class="font-semibold">Nouvelle Coopérative</span>
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
            <form method="GET" action="{{ route('coperatives.index') }}" id="filterForm">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="form-group">
                        <label for="searchInput" class="block text-sm font-semibold text-gray-700 mb-2">
                            Rechercher
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   class="form-input w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                                   name="search" 
                                   id="searchInput" 
                                   placeholder="Rechercher dans les coopératives..." 
                                   value="{{ request('search') }}"
                                   onkeyup="debounceFilter()">
                            <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <i class="fas fa-search"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="vocationFilter" class="block text-sm font-semibold text-gray-700 mb-2">
                            Vocation
                        </label>
                        <select class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                                name="vocation_id" id="vocationFilter" onchange="submitFilter()">
                            <option value="">Toutes les vocations</option>
                            @foreach($vocations as $vocation)
                                <option value="{{ $vocation->id }}" {{ request('vocation_id') == $vocation->id ? 'selected' : '' }}>
                                    {{ $vocation->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="statusFilter" class="block text-sm font-semibold text-gray-700 mb-2">
                            Statut
                        </label>
                        <div class="flex gap-2">
                            <select class="form-input flex-1 px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                                    name="status" id="statusFilter" onchange="submitFilter()">
                                <option value="">Tous les statuts</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actives</option>
                                <option value="deleted" {{ request('status') == 'deleted' ? 'selected' : '' }}>Supprimées</option>
                                <option value="recent" {{ request('status') == 'recent' ? 'selected' : '' }}>Récentes</option>
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
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nom</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Vocation</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nombre de Membres</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nombre de Coopératives</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date de Création</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($coperatives as $coperative)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $coperative->id }}</td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-users-cog text-blue-600 text-sm"></i>
                                        </div>
                                        <span class="font-medium">{{ $coperative->nom ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td>
                                    @if($coperative->vocation)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            <i class="fas fa-briefcase mr-1"></i>
                                            {{ $coperative->vocation->name }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $coperative->nombre_membres ?? 0 }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $coperative->nombre_coperatives ?? 0 }}</span>
                                </td>
                                <td>
                                    @if($coperative->created_at)
                                        {{ $coperative->created_at->format('d/m/Y') }}
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex items-center gap-1">
                                        <!-- Edit Action -->
                                        <a href="{{ route('contracts.coperatives.edit', $coperative) }}" 
                                           class="inline-flex items-center justify-center w-8 h-8 bg-orange-100 hover:bg-orange-200 text-orange-600 rounded-lg transition-colors duration-200" 
                                           title="Modifier la coopérative">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>
                                        
                                        <!-- Delete Action -->
                                        <form action="{{ route('contracts.coperatives.destroy', $coperative) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette coopérative ?')"
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg transition-colors duration-200" 
                                                    title="Supprimer la coopérative">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-users-cog text-4xl mb-2 d-block"></i>
                                        <p class="h5 mb-2">Aucune coopérative trouvée</p>
                                        <p class="text-muted mb-3">Commencez par créer votre première coopérative</p>
                                        <a href="{{ route('contracts.coperatives.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>Créer la Première Coopérative
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
        @if($coperatives->hasPages())
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="text-sm text-gray-600">
                        <i class="fas fa-info-circle mr-1"></i>
                        Affichage de {{ $coperatives->firstItem() ?? 0 }} à {{ $coperatives->lastItem() ?? 0 }} 
                        sur {{ $coperatives->total() }} coopératives
                        @if(request()->hasAny(['search', 'vocation_id', 'status']))
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 ml-2">
                                <i class="fas fa-filter mr-1"></i>Filtrés
                            </span>
                        @endif
                    </div>
                    <div class="pagination-controls">
                        {{ $coperatives->appends(request()->query())->links('pagination::bootstrap-4') }}
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
        }, 500); // Wait 500ms after user stops typing
    }

    // Submit filter form
    function submitFilter() {
        document.getElementById('filterForm').submit();
    }

    // Clear all filters
    function clearFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('vocationFilter').value = '';
        document.getElementById('statusFilter').value = '';
        submitFilter();
    }

    // Per page selector functionality
    function changePerPage(perPage) {
        const url = new URL(window.location);
        url.searchParams.set('per_page', perPage);
        url.searchParams.delete('page'); // Reset to first page when changing per page
        window.location.href = url.toString();
    }

    // Add keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + F to focus search
        if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
            e.preventDefault();
            document.getElementById('searchInput').focus();
        }
        
        // Escape to clear search
        if (e.key === 'Escape') {
            clearFilters();
        }
    });
</script>
@endpush

@push('styles')
<style>
    /* Enhanced table styling */
    .table-responsive {
        border-radius: 8px;
        overflow: auto;
        max-height: 70vh;
    }

    .table-container {
        min-width: 100%;
        overflow: auto;
    }

    /* Table overflow and scrolling enhancements */
    .table-responsive::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 4px;
    }

    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }

    /* Ensure table headers stay visible during scroll */
    .table thead th {
        position: sticky;
        top: 0;
        z-index: 10;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }

    /* Table row hover effects */
    .table tbody tr {
        transition: all 0.2s ease;
    }

    .table tbody tr:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    /* Enhanced Pagination Styling */
    .pagination-controls .pagination {
        margin: 0;
        justify-content: center;
    }

    .pagination .page-link {
        border-radius: 6px;
        margin: 0 2px;
        border: 1px solid #dee2e6;
        color: #495057;
        transition: all 0.2s ease;
    }

    .pagination .page-link:hover {
        background-color: #e9ecef;
        border-color: #adb5bd;
        transform: translateY(-1px);
    }

    .pagination .page-item.active .page-link {
        background-color: #007bff;
        border-color: #007bff;
        box-shadow: 0 2px 4px rgba(0, 123, 255, 0.3);
    }
</style>
@endpush

