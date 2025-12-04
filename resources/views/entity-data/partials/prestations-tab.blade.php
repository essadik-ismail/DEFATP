<!-- Prestations Tab -->
<div class="tab-pane fade" id="prestations" role="tabpanel">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-sky-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-tasks text-white text-lg"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-900">Liste des Prestations</h3>
                <p class="text-gray-600">Visualisez les prestations utilisées dans les contrats et avenants</p>
            </div>
        </div>
    </div>
    
    <!-- Search Box -->
    <div class="mb-6">
        <form method="GET" action="{{ route('entity-data.index') }}" class="flex gap-3">
            <input type="hidden" name="tab" value="prestations">
            <div class="flex-1 relative">
                <input type="text" 
                       name="prestation_search" 
                       class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 hover:border-gray-400" 
                       placeholder="Rechercher une prestation..." 
                       value="{{ request('prestation_search') }}">
                <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                    <i class="fas fa-search"></i>
                </div>
            </div>
            <button type="submit" 
                    class="px-6 py-3 bg-gradient-to-r from-sky-600 to-blue-600 text-white rounded-xl hover:from-sky-700 hover:to-blue-700 transition-all duration-300">
                <i class="fas fa-search"></i>
            </button>
            @if(request('prestation_search'))
                <a href="{{ route('entity-data.index', ['tab' => 'prestations']) }}" 
                   class="px-4 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300">
                    <i class="fas fa-times"></i>
                </a>
            @endif
        </form>
    </div>
    
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table id="prestationsTable" class="w-full">
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
                                <span>Nom</span>
                                <button class="filter-btn ml-2 text-gray-400 hover:text-gray-600" data-column="1" title="Filtrer">
                                    <i class="fas fa-filter text-xs"></i>
                                </button>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider relative">
                            <div class="flex items-center justify-between">
                                <span>Contrats</span>
                                <button class="filter-btn ml-2 text-gray-400 hover:text-gray-600" data-column="2" title="Filtrer">
                                    <i class="fas fa-filter text-xs"></i>
                                </button>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider relative">
                            <div class="flex items-center justify-between">
                                <span>Avenants</span>
                                <button class="filter-btn ml-2 text-gray-400 hover:text-gray-600" data-column="3" title="Filtrer">
                                    <i class="fas fa-filter text-xs"></i>
                                </button>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider relative">
                            <div class="flex items-center justify-between">
                                <span>Date de Création</span>
                                <button class="filter-btn ml-2 text-gray-400 hover:text-gray-600" data-column="4" title="Filtrer">
                                    <i class="fas fa-filter text-xs"></i>
                                </button>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($prestations as $prestation)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $prestation->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-sky-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-tasks text-sky-600 text-sm"></i>
                                </div>
                                <span class="font-medium">{{ $prestation->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($prestation->contracts->count() > 0)
                                <div class="flex flex-col gap-1">
                                    @foreach($prestation->contracts->take(3) as $contract)
                                        <a href="{{ route('contracts.show', $contract) }}" class="text-indigo-600 hover:text-indigo-800">
                                            Contrat #{{ $contract->contarct ?? $contract->id }} ({{ $contract->pivot->quantity }})
                                        </a>
                                    @endforeach
                                    @if($prestation->contracts->count() > 3)
                                        <span class="text-xs text-gray-500">+{{ $prestation->contracts->count() - 3 }} autres</span>
                                    @endif
                                </div>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($prestation->avenants->count() > 0)
                                <div class="flex flex-col gap-1">
                                    @foreach($prestation->avenants->take(3) as $avenant)
                                        <span class="text-indigo-600">
                                            Avenant #{{ $avenant->id }} ({{ $avenant->pivot->quantity }})
                                        </span>
                                    @endforeach
                                    @if($prestation->avenants->count() > 3)
                                        <span class="text-xs text-gray-500">+{{ $prestation->avenants->count() - 3 }} autres</span>
                                    @endif
                                </div>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $prestation->created_at ? $prestation->created_at->format('d/m/Y') : 'N/A' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-tasks text-2xl text-gray-400"></i>
                                </div>
                                <p class="text-lg font-medium">Aucune prestation trouvée</p>
                                <p class="text-sm">Les prestations sont créées depuis les formulaires de contrats et d'avenants</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($prestations->hasPages())
        <div class="mt-6 flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Affichage de {{ $prestations->firstItem() }} à {{ $prestations->lastItem() }} sur {{ $prestations->total() }} résultats
            </div>
            <div class="flex items-center gap-2">
                {{ $prestations->appends(array_merge(request()->query(), ['tab' => 'prestations']))->links() }}
            </div>
        </div>
    @endif
</div>


