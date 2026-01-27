<!-- Prestations Tab -->
<div class="tab-pane fade" id="prestations" role="tabpanel">
    <!-- Filters and Search Area -->
    <x-filters-card 
        title="Filtres et Recherche"
        icon="fas fa-filter"
        :action="route('entity-data.index')"
        formId="prestationsFilterForm"
        class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6"
    >
        <input type="hidden" name="tab" value="prestations">
        <x-form-input
            type="text"
            name="prestation_search"
            label="Recherche"
            :value="request('prestation_search')"
            placeholder="Rechercher une prestation..."
        />
        <input type="hidden" name="per_page" value="{{ request('per_page', 15) }}">
    </x-filters-card>

    <!-- Prestations Data Table -->
    <div class="rounded-2xl border max-w-full overflow-hidden" style="background: #FFFFFF; border-color: rgba(154,179,163,0.4); box-shadow: 0 2px 8px rgba(0,0,0,0.04), 0 1px 2px rgba(0,0,0,0.03);">
        <!-- Per Page Selector -->
        <div class="px-5 py-3 border-b flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2" style="border-color: rgba(154,179,163,0.4);">
            <div class="flex items-center gap-2">
                <label for="prestationsPerPageSelect" class="text-xs font-medium" style="color: #6B7C72;">Prestations par page:</label>
                <select class="form-input px-2 py-1 border rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-offset-0"
                        style="border-color: rgba(154,179,163,0.5);"
                        id="prestationsPerPageSelect" onchange="changePerPage('prestations', this.value)">
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                    <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                </select>
            </div>
        </div>
            
        <!-- Data Table -->
        <div class="overflow-x-auto max-w-full" style="-webkit-overflow-scrolling: touch;">
            <table id="prestationsTable" class="w-full divide-y divide-gray-200">
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
                                <span>Nom</span>
                                <button class="filter-btn ml-1 text-gray-400 hover:text-gray-600" data-column="1" title="Filtrer">
                                    <i class="fas fa-filter text-xs"></i>
                                </button>
                            </div>
                        </th>
                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider relative">
                            <div class="flex items-center justify-between">
                                <span>Contrats</span>
                                <button class="filter-btn ml-1 text-gray-400 hover:text-gray-600" data-column="2" title="Filtrer">
                                    <i class="fas fa-filter text-xs"></i>
                                </button>
                            </div>
                        </th>
                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider relative">
                            <div class="flex items-center justify-between">
                                <span>Avenants</span>
                                <button class="filter-btn ml-1 text-gray-400 hover:text-gray-600" data-column="3" title="Filtrer">
                                    <i class="fas fa-filter text-xs"></i>
                                </button>
                            </div>
                        </th>
                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider relative">
                            <div class="flex items-center justify-between">
                                <span>Date de Création</span>
                                <button class="filter-btn ml-1 text-gray-400 hover:text-gray-600" data-column="4" title="Filtrer">
                                    <i class="fas fa-filter text-xs"></i>
                                </button>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($prestations as $prestation)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-3 py-2 whitespace-nowrap text-xs font-medium text-gray-900">
                            {{ $prestation->id }}
                        </td>
                        <td class="px-3 py-2 whitespace-nowrap text-xs text-gray-900">
                            <span class="font-medium">{{ $prestation->name }}</span>
                        </td>
                        <td class="px-3 py-2 whitespace-nowrap text-xs text-gray-900">
                            @if($prestation->contracts->count() > 0)
                                <div class="flex flex-col gap-1">
                                    @foreach($prestation->contracts->take(3) as $contract)
                                        <a href="{{ route('contracts.show', $contract) }}" class="text-blue-600 hover:text-blue-800">
                                            Contrat #{{ $contract->contarct ?? $contract->id }} ({{ $contract->pivot->quantity }})
                                        </a>
                                    @endforeach
                                    @if($prestation->contracts->count() > 3)
                                        <span class="text-xs text-blue-500">+{{ $prestation->contracts->count() - 3 }} autres</span>
                                    @endif
                                </div>
                            @else
                                <span class="text-muted text-xs">-</span>
                            @endif
                        </td>
                        <td class="px-3 py-2 whitespace-nowrap text-xs text-gray-900">
                            @if($prestation->avenants->count() > 0)
                                <div class="flex flex-col gap-1">
                                    @foreach($prestation->avenants->take(3) as $avenant)
                                        <span class="text-blue-600">
                                            Avenant #{{ $avenant->id }} ({{ $avenant->pivot->quantity }})
                                        </span>
                                    @endforeach
                                    @if($prestation->avenants->count() > 3)
                                        <span class="text-xs text-blue-500">+{{ $prestation->avenants->count() - 3 }} autres</span>
                                    @endif
                                </div>
                            @else
                                <span class="text-muted text-xs">-</span>
                            @endif
                        </td>
                        <td class="px-3 py-2 whitespace-nowrap text-xs text-gray-900">
                            {{ $prestation->created_at ? $prestation->created_at->format('d/m/Y') : 'N/A' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-6">
                            <div class="text-gray-500">
                                <i class="fas fa-tasks text-4xl mb-2 d-block"></i>
                                <p class="h5 mb-2">Aucune prestation trouvée</p>
                                <p class="text-muted mb-3">Aucune prestation ne correspond à vos critères de recherche</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
                
        @if($prestations->hasPages())
            <div class="bg-gray-50 px-4 py-2 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-2">
                    <div class="text-xs text-gray-600">
                        Affichage de {{ $prestations->firstItem() ?? 0 }} à {{ $prestations->lastItem() ?? 0 }} 
                        sur {{ $prestations->total() }} prestations
                    </div>
                    <div class="pagination-controls">
                        {{ $prestations->appends(array_merge(request()->query(), ['tab' => 'prestations']))->links() }}
                    </div>
                    <div class="text-xs text-gray-500">
                        {{ $prestations->perPage() }} par page
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
