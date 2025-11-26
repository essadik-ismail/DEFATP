@extends('layouts.app')

@section('title', 'ODFs - DEFATP')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #8b5cf6, #7c3aed);">
                <i class="fas fa-users text-white text-2xl"></i>
            </div>
            <div>
                <h1 class="text-4xl font-bold bg-clip-text text-transparent" style="background: linear-gradient(to right, #8b5cf6, #7c3aed); -webkit-background-clip: text; background-clip: text;">
                Organisation développement forestier
                </h1>
                <p class="text-gray-600 text-lg mt-2">Gérez et consultez toutes les Organisation développement forestier (ODF)</p>
            </div>
        </div>
    </div>

    <!-- ODFs Data Table -->
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-table text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Liste des ODF</h2>
                    <p class="text-gray-600">Gérez et consultez toutes les ODF</p>
                </div>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('odfs.create') }}" 
                   class="inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-xl hover:from-purple-700 hover:to-indigo-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-plus"></i>
                    <span class="font-semibold">Nouvelle ODF</span>
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
            <form method="GET" action="{{ route('odfs.index') }}" id="filterForm" onsubmit="resetPage(); return true;">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                    <div class="form-group">
                        <label for="searchInput" class="block text-sm font-semibold text-gray-700 mb-2">
                            Rechercher
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   class="form-input w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400" 
                                   name="search" 
                                   id="searchInput" 
                                   placeholder="Rechercher dans les ODFs..." 
                                   value="{{ request('search') }}">
                            <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <i class="fas fa-search"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="odf_entite_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-building text-purple-500 mr-1"></i>ODF Entité
                        </label>
                        <select name="odf_entite_id" 
                                id="odf_entite_id"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400">
                            <option value="">Toutes les ODF Entités</option>
                            @foreach($odfEntites as $entite)
                                <option value="{{ $entite->id }}" {{ request('odf_entite_id') == $entite->id ? 'selected' : '' }}>
                                    {{ $entite->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="constitution" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-check-circle text-green-500 mr-1"></i>Constitution
                        </label>
                        <select name="constitution" 
                                id="constitution"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400">
                            <option value="">Tous les statuts</option>
                            <option value="1" {{ request('constitution') == '1' ? 'selected' : '' }}>Constitué</option>
                            <option value="0" {{ request('constitution') == '0' ? 'selected' : '' }}>Non constitué</option>
                        </select>
                    </div>

                <!-- Date filters -->              
                <div class="form-group">
                    <label for="start_date" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calendar-plus text-blue-500 mr-1"></i>
                            Date de début
                            <i class="fas fa-question-circle mx-1 text-gray-400" title="Format: jj/mm/aaaa (ex: 01/01/2024)"></i>
                        </label>
                        <input type="date" 
                               name="start_date" 
                               id="start_date" 
                               value="{{ request('start_date') }}"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400"
                               placeholder="jj/mm/aaaa">
                    </div>
                    
                    <div class="form-group">
                        <label for="end_date" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calendar-minus text-blue-500 mr-1"></i>
                            Date de fin
                            <i class="fas fa-question-circle mx-1 text-gray-400" title="Format: jj/mm/aaaa (ex: 31/12/2024)"></i>
                        </label>
                        <input type="date" 
                               name="end_date" 
                               id="end_date" 
                               value="{{ request('end_date') }}"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400"
                               placeholder="jj/mm/aaaa">
                    </div>


                    
                </div>
                
                <!-- Filter Action Buttons -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <div class="flex gap-3">
                        <button type="submit" 
                                class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-xl hover:from-purple-700 hover:to-indigo-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                            <i class="fas fa-filter"></i>
                            <span class="font-semibold">Appliquer les filtres</span>
                        </button>
                        <button type="button" 
                                class="inline-flex items-center gap-2 px-4 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300"
                                onclick="clearFilters()" 
                                title="Effacer les filtres">
                            <i class="fas fa-times"></i>
                            <span>Effacer les filtres</span>
                        </button>
                    </div>
                </div>
                
                <!-- Hidden field to preserve per_page setting -->
                @if(request('per_page'))
                    <input type="hidden" name="per_page" value="{{ request('per_page') }}">
                @endif
            </form>
        </div>

        <!-- Data Table -->
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table id="odfsIndexTable" class="w-full">
                    <thead class="bg-gradient-to-r from-gray-50 to-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider relative">
                                <div class="flex items-center justify-between">
                                    <span>ID</span>
                                    <button class="filter-btn ml-2 text-gray-400 hover:text-gray-600" data-column="0" title="Filtrer">
                                        <i class="fas fa-filter text-xs"></i>
                                    </button>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider relative">
                                <div class="flex items-center justify-between">
                                    <span>ODF Entité</span>
                                    <button class="filter-btn ml-2 text-gray-400 hover:text-gray-600" data-column="1" title="Filtrer">
                                        <i class="fas fa-filter text-xs"></i>
                                    </button>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider relative">
                                <div class="flex items-center justify-between">
                                    <span>Constitution</span>
                                    <button class="filter-btn ml-2 text-gray-400 hover:text-gray-600" data-column="2" title="Filtrer">
                                        <i class="fas fa-filter text-xs"></i>
                                    </button>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider relative">
                                <div class="flex items-center justify-between">
                                    <span>Date de Dépôt</span>
                                    <button class="filter-btn ml-2 text-gray-400 hover:text-gray-600" data-column="3" title="Filtrer">
                                        <i class="fas fa-filter text-xs"></i>
                                    </button>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($odfs as $odf)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $odf->id }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    @if($odf->odfEntite)
                                        <div class="space-y-1">
                                            <div class="flex items-center gap-2">
                                                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-building text-purple-600 text-sm"></i>
                                                </div>
                                                <span class="font-medium">{{ $odf->odfEntite->name }}</span>
                                            </div>
                                            @if($odf->odfEntite->localisation)
                                                <div class="flex items-center gap-2 ml-10 text-xs text-gray-600">
                                                    <i class="fas fa-map-marker-alt text-blue-500"></i>
                                                    <span>{{ $odf->odfEntite->localisation->DRANEF }} - {{ $odf->odfEntite->localisation->DPANEF }} - {{ $odf->odfEntite->localisation->ENTITE }}</span>
                                                </div>
                                            @endif
                                            @if($odf->odfEntite->situationAdministrative)
                                                <div class="flex items-center gap-2 ml-10 text-xs text-gray-600">
                                                    <i class="fas fa-building text-emerald-500"></i>
                                                    <span>{{ $odf->odfEntite->situationAdministrative->commune }}@if($odf->odfEntite->situationAdministrative->province) - {{ $odf->odfEntite->situationAdministrative->province }}@endif</span>
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-gray-400">N/A</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($odf->constitution)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Constitué
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i>
                                            Non constitué
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($odf->date_depot_odf)
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-calendar text-blue-500 text-xs"></i>
                                            <span>{{ \Carbon\Carbon::parse($odf->date_depot_odf)->format('d/m/Y') }}</span>
                                        </div>
                                    @else
                                        <span class="text-gray-400">N/A</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-1">
                                        <!-- View Action (only show if constitution is true) -->
                                        @if($odf->constitution)
                                        <a href="{{ route('odfs.show', $odf) }}" 
                                           class="inline-flex items-center justify-center w-8 h-8 bg-blue-100 hover:bg-blue-200 text-blue-600 rounded-lg transition-colors duration-200" 
                                           title="Voir les détails">
                                            <i class="fas fa-eye text-sm"></i>
                                        </a>
                                        @endif
                                        
                                        <!-- Edit Action -->
                                        <a href="{{ route('odfs.edit', $odf) }}" 
                                           class="inline-flex items-center justify-center w-8 h-8 bg-orange-100 hover:bg-orange-200 text-orange-600 rounded-lg transition-colors duration-200" 
                                           title="Modifier l'ODF">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>
                                        
                                        <!-- Delete Action (only show if constitution is false) -->
                                        @if(!$odf->constitution)
                                        <form action="{{ route('odfs.destroy', $odf) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette ODF ?')"
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg transition-colors duration-200" 
                                                    title="Supprimer l'ODF">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-8">
                                    <div class="text-muted">
                                        <i class="fas fa-users text-4xl mb-2 d-block"></i>
                                        <p class="h5 mb-2">Aucune ODF trouvée</p>
                                        <p class="text-muted mb-3">Commencez par créer votre première ODF</p>
                                        <a href="{{ route('odfs.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>Créer la Première ODF
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($odfs->hasPages())
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                        <div class="text-sm text-gray-600">
                            <i class="fas fa-info-circle mr-1"></i>
                            Affichage de {{ $odfs->firstItem() ?? 0 }} à {{ $odfs->lastItem() ?? 0 }} 
                            sur {{ $odfs->total() }} ODFs
                            @if(request()->has('search'))
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 ml-2">
                                    <i class="fas fa-filter mr-1"></i>Filtrés
                                </span>
                            @endif
                        </div>
                        <div class="pagination-controls">
                            {{ $odfs->appends(request()->query())->links('pagination::bootstrap-4') }}
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
    // Reset page to 1 when applying filters
    function resetPage() {
        // Remove any existing page parameter from the form
        const form = document.getElementById('filterForm');
        const pageInput = form.querySelector('input[name="page"]');
        if (pageInput) {
            pageInput.remove();
        }
    }
    
    // Clear all filters
    function clearFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('start_date').value = '';
        document.getElementById('end_date').value = '';
        document.getElementById('odf_entite_id').value = '';
        document.getElementById('constitution').value = '';
        // Remove page parameter when clearing
        const form = document.getElementById('filterForm');
        const pageInput = form.querySelector('input[name="page"]');
        if (pageInput) {
            pageInput.remove();
        }
        form.submit();
    }
    
    // Allow Enter key to submit form in search input
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    resetPage();
                    document.getElementById('filterForm').submit();
                }
            });
        }
    });

    // Per page selector functionality
    function changePerPage(perPage) {
        const url = new URL(window.location);
        url.searchParams.set('per_page', perPage);
        url.searchParams.delete('page');
        window.location.href = url.toString();
    }
</script>
@endpush

