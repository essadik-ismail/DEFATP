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
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #059669, #047857);">
                    <i class="fas fa-table text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold bg-clip-text text-transparent" style="background: linear-gradient(to right, #059669, #047857); -webkit-background-clip: text; background-clip: text;">
                        Liste des Prestations
                    </h2>
                    <p class="text-green-600">Affichage de {{ isset($prestations) && method_exists($prestations, 'firstItem') ? ($prestations->firstItem() ?? 0) : 0 }} à {{ isset($prestations) && method_exists($prestations, 'lastItem') ? ($prestations->lastItem() ?? 0) : 0 }} sur {{ isset($prestations) && method_exists($prestations, 'total') ? $prestations->total() : 0 }} prestations</p>
                </div>
            </div>
        </div>

        <!-- Per Page Selector -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div class="flex items-center gap-4">
                <label for="prestationsPerPageSelect" class="text-sm font-semibold text-green-700">Prestations par page:</label>
                <select class="form-input px-4 py-2 border border-green-300 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 hover:border-green-400" 
                        id="prestationsPerPageSelect" onchange="changePerPage('prestations', this.value)">
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
                <table id="prestationsTable" class="w-full">
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
                                <span>Contrats</span>
                                <button class="filter-btn ml-2 text-green-400 hover:text-green-600" data-column="2" title="Filtrer">
                                    <i class="fas fa-filter text-xs"></i>
                                </button>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-green-800 uppercase tracking-wider relative">
                            <div class="flex items-center justify-between">
                                <span>Avenants</span>
                                <button class="filter-btn ml-2 text-green-400 hover:text-green-600" data-column="3" title="Filtrer">
                                    <i class="fas fa-filter text-xs"></i>
                                </button>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-green-800 uppercase tracking-wider relative">
                            <div class="flex items-center justify-between">
                                <span>Date de Création</span>
                                <button class="filter-btn ml-2 text-green-400 hover:text-green-600" data-column="4" title="Filtrer">
                                    <i class="fas fa-filter text-xs"></i>
                                </button>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($prestations as $prestation)
                    <tr class="hover:bg-green-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-900">
                            {{ $prestation->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-green-900">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-sky-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-tasks text-sky-600 text-sm"></i>
                                </div>
                                <span class="font-medium">{{ $prestation->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-green-900">
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
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-green-900">
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
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-700">
                            {{ $prestation->created_at ? $prestation->created_at->format('d/m/Y') : 'N/A' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-8">
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
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="text-sm text-green-600">
                        Affichage de {{ $prestations->firstItem() ?? 0 }} à {{ $prestations->lastItem() ?? 0 }} 
                        sur {{ $prestations->total() }} prestations
                    </div>
                    <div class="pagination-controls">
                        {{ $prestations->appends(array_merge(request()->query(), ['tab' => 'prestations']))->links() }}
                    </div>
                    <div class="text-sm text-green-500">
                        {{ $prestations->perPage() }} par page
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
