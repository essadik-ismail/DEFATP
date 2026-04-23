<!-- Forêts Tab (Articles) -->
<div class="tab-pane fade" id="forets" role="tabpanel">
    <!-- Filters and Search Area -->
    <x-filters-card 
        title="Filtres et Recherche"
        icon="fas fa-filter"
        :action="route('entity-data.index')"
        formId="foretsFilterForm"
        class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6"
    >
        <input type="hidden" name="tab" value="forets">
        <x-form-input
            type="text"
            name="foret_search"
            label="Recherche"
            :value="request('foret_search')"
            placeholder="Rechercher une forêt..."
        />
        <input type="hidden" name="per_page" value="{{ request('per_page', 15) }}">
    </x-filters-card>

    <!-- Forêts Data Table -->
    <div class="rounded-2xl border max-w-full overflow-hidden" style="background: #FFFFFF; border-color: rgba(154,179,163,0.4); box-shadow: 0 2px 8px rgba(0,0,0,0.04), 0 1px 2px rgba(0,0,0,0.03);">
        <!-- Per Page Selector -->
        <div class="px-5 py-3 border-b flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2" style="border-color: rgba(154,179,163,0.4);">
            <div class="flex items-center gap-2">
                <label for="foretsPerPageSelect" class="text-xs font-medium" style="color: #6B7C72;">Forêts par page:</label>
                <select class="form-input px-2 py-1 border rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-offset-0"
                        style="border-color: rgba(154,179,163,0.5);"
                        id="foretsPerPageSelect" onchange="changePerPage('forets', this.value)">
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                    <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                </select>
            </div>
            <a href="{{ route('settings.forets.create') }}" 
               class="btn-primary inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium">
                <i class="fas fa-plus"></i>
                <span>Nouvelle Forêt</span>
            </a>
        </div>

        <!-- Data Table -->
        <div class="overflow-x-auto max-w-full" style="-webkit-overflow-scrolling: touch;">
            <table id="foretsTable" class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider relative">
                            <div class="flex items-center justify-between">
                                <span>ID</span>
                                <button class="filter-btn ml-2 text-gray-400 hover:text-gray-600" data-column="0" title="Filtrer">
                                    <i class="fas fa-filter text-xs"></i>
                                </button>
                            </div>
                        </th>
                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider relative">
                            <div class="flex items-center justify-between">
                                <span>Nom de la Forêt</span>
                                <button class="filter-btn ml-1 text-gray-400 hover:text-gray-600" data-column="1" title="Filtrer">
                                    <i class="fas fa-filter text-xs"></i>
                                </button>
                            </div>
                        </th>
                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider relative">
                            <div class="flex items-center justify-between">
                                <span>Date de Création</span>
                                <button class="filter-btn ml-1 text-gray-400 hover:text-gray-600" data-column="2" title="Filtrer">
                                    <i class="fas fa-filter text-xs"></i>
                                </button>
                            </div>
                        </th>
                        <th class="px-3 py-2 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($forets as $foret)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-3 py-2 whitespace-nowrap text-xs font-medium text-gray-900">
                            {{ $foret->id }}
                        </td>
                        <td class="px-3 py-2 whitespace-nowrap text-xs text-gray-900">
                            <span class="font-medium">{{ $foret->foret }}</span>
                        </td>
                        <td class="px-3 py-2 whitespace-nowrap text-xs text-gray-900">
                            {{ $foret->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-3 py-2 whitespace-nowrap text-xs text-gray-900">
                            <div class="flex items-center justify-center gap-0.5">
                                <a href="{{ route('settings.forets.edit', $foret) }}" 
                                   class="inline-flex items-center justify-center w-7 h-7 bg-orange-100 hover:bg-orange-200 text-orange-600 rounded transition-colors duration-200" 
                                   title="Modifier">
                                    <i class="fas fa-edit text-xs"></i>
                                </a>
                                <form action="{{ route('settings.forets.destroy', $foret) }}" method="POST" style="display: contents;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center justify-center w-7 h-7 bg-red-100 hover:bg-red-200 text-red-600 rounded transition-colors duration-200"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette forêt ?')"
                                            title="Supprimer">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-6">
                            <div class="text-gray-500">
                                <i class="fas fa-tree text-4xl mb-2 d-block"></i>
                                <p class="h5 mb-2">Aucune forêt trouvée</p>
                                <p class="text-muted mb-3">Aucune forêt ne correspond à vos critères de recherche</p>
                                <a href="{{ route('settings.forets.create') }}" class="btn-primary">
                                    <i class="fas fa-plus me-2"></i>Créer la Première Forêt
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
                
        @if($forets->hasPages())
            <div class="bg-gray-50 px-4 py-2 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-2">
                    <div class="text-xs text-gray-600">
                        Affichage de {{ $forets->firstItem() ?? 0 }} à {{ $forets->lastItem() ?? 0 }} 
                        sur {{ $forets->total() }} forêts
                    </div>
                    <div class="pagination-controls">
                        {{ $forets->appends(array_merge(request()->query(), ['tab' => 'forets']))->links() }}
                    </div>
                    <div class="text-xs text-gray-500">
                        {{ $forets->perPage() }} par page
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

