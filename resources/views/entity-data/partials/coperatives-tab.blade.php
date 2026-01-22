<!-- Coopératives Tab (Contracts) -->
<div class="tab-pane fade" id="coperatives" role="tabpanel">
    <!-- Filters and Search Area -->
    <x-filters-card 
        title="Filtres et Recherche"
        icon="fas fa-filter"
        :action="route('entity-data.index')"
        formId="coperativesFilterForm"
        class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6"
    >
        <input type="hidden" name="tab" value="coperatives">
        <x-form-input
            type="text"
            name="coperative_search"
            label="Recherche"
            :value="request('coperative_search')"
            placeholder="Rechercher une coopérative..."
        />
        <input type="hidden" name="per_page" value="{{ request('per_page', 15) }}">
    </x-filters-card>

    <!-- Coopératives Data Table -->
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #059669, #047857);">
                    <i class="fas fa-table text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold bg-clip-text text-transparent" style="background: linear-gradient(to right, #059669, #047857); -webkit-background-clip: text; background-clip: text;">
                        Liste des Coopératives
                    </h2>
                    <p class="text-green-600">Affichage de {{ $coperatives->firstItem() ?? 0 }} à {{ $coperatives->lastItem() ?? 0 }} sur {{ $coperatives->total() }} coopératives</p>
                </div>
            </div>
            <a href="{{ route('contracts.coperatives.create') }}" 
               class="inline-flex items-center gap-3 px-6 py-3 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300"
               style="background: linear-gradient(135deg, #059669, #047857);">
                <i class="fas fa-plus"></i>
                <span>Nouvelle Coopérative</span>
            </a>
        </div>

        <!-- Per Page Selector -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div class="flex items-center gap-4">
                <label for="coperativesPerPageSelect" class="text-sm font-semibold text-green-700">Coopératives par page:</label>
                <select class="form-input px-4 py-2 border border-green-300 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 hover:border-green-400" 
                        id="coperativesPerPageSelect" onchange="changePerPage('coperatives', this.value)">
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                    <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                </select>
            </div>
        </div>
            
        <!-- Data Table -->
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table id="coperativesTable" class="w-full">
                <thead class="bg-gradient-to-r from-gray-50 to-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-green-800 uppercase tracking-wider relative">
                            <div class="flex items-center justify-between">
                                <span>ID</span>
                                <button class="filter-btn ml-2 text-green-400 hover:text-green-600" data-column="0" title="Filtrer">
                                    <i class="fas fa-filter text-xs"></i>
                                </button>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-green-800 uppercase tracking-wider relative">
                            <div class="flex items-center justify-between">
                                <span>Nom</span>
                                <button class="filter-btn ml-2 text-green-400 hover:text-green-600" data-column="1" title="Filtrer">
                                    <i class="fas fa-filter text-xs"></i>
                                </button>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-green-800 uppercase tracking-wider relative">
                            <div class="flex items-center justify-between">
                                <span>Vocation</span>
                                <button class="filter-btn ml-2 text-green-400 hover:text-green-600" data-column="2" title="Filtrer">
                                    <i class="fas fa-filter text-xs"></i>
                                </button>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-green-800 uppercase tracking-wider relative">
                            <div class="flex items-center justify-between">
                                <span>Nombre de Membres</span>
                                <button class="filter-btn ml-2 text-green-400 hover:text-green-600" data-column="3" title="Filtrer">
                                    <i class="fas fa-filter text-xs"></i>
                                </button>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-green-800 uppercase tracking-wider relative">
                            <div class="flex items-center justify-between">
                                <span>Nombre de Coopératives</span>
                                <button class="filter-btn ml-2 text-green-400 hover:text-green-600" data-column="4" title="Filtrer">
                                    <i class="fas fa-filter text-xs"></i>
                                </button>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-green-800 uppercase tracking-wider relative">
                            <div class="flex items-center justify-between">
                                <span>Date de Création</span>
                                <button class="filter-btn ml-2 text-green-400 hover:text-green-600" data-column="5" title="Filtrer">
                                    <i class="fas fa-filter text-xs"></i>
                                </button>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-green-800 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($coperatives as $coperative)
                    <tr class="hover:bg-green-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-900">
                            {{ $coperative->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-green-900">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-users-cog text-blue-600 text-sm"></i>
                                </div>
                                <span class="font-medium">{{ $coperative->nom ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-green-900">
                            @if($coperative->vocation)
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    <i class="fas fa-briefcase mr-1"></i>
                                    {{ $coperative->vocation->name }}
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-green-900">
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">{{ $coperative->nombre_membres ?? 0 }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-green-900">
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">{{ $coperative->nombre_coperatives ?? 0 }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-700">
                            {{ $coperative->created_at ? $coperative->created_at->format('d/m/Y') : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center justify-center gap-1">
                                <a href="{{ route('contracts.coperatives.edit', $coperative) }}" 
                                   class="inline-flex items-center justify-center w-8 h-8 bg-orange-100 hover:bg-orange-200 text-orange-600 rounded-lg transition-colors duration-200" 
                                   title="Modifier">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>
                                <form action="{{ route('contracts.coperatives.destroy', $coperative) }}" 
                                      method="POST" 
                                      style="display: contents;"
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette coopérative ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center justify-center w-8 h-8 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg transition-colors duration-200"
                                            title="Supprimer">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-8">
                            <div class="text-gray-500">
                                <i class="fas fa-users-cog text-4xl mb-2 d-block"></i>
                                <p class="h5 mb-2">Aucune coopérative trouvée</p>
                                <p class="text-muted mb-3">Aucune coopérative ne correspond à vos critères de recherche</p>
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
                    <div class="text-sm text-green-600">
                        Affichage de {{ $coperatives->firstItem() ?? 0 }} à {{ $coperatives->lastItem() ?? 0 }} 
                        sur {{ $coperatives->total() }} coopératives
                    </div>
                    <div class="pagination-controls">
                        {{ $coperatives->appends(array_merge(request()->query(), ['tab' => 'coperatives']))->links() }}
                    </div>
                    <div class="text-sm text-green-500">
                        {{ $coperatives->perPage() }} par page
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
