<!-- Regional Budgets Tab -->
<div class="tab-pane fade {{ $tab === 'regional-budgets' ? 'show active' : '' }}" id="regional-budgets" role="tabpanel">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-coins text-white text-lg"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-900">Budgets Régionaux</h3>
                <p class="text-gray-600">Gérez les budgets régionaux</p>
            </div>
        </div>
        <a href="{{ route('financial-data.regional-budgets.create') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-lg hover:from-purple-700 hover:to-pink-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
            <i class="fas fa-plus"></i>
            <span>Nouveau Budget</span>
        </a>
    </div>
    
    <!-- Search Box -->
    <div class="mb-6">
        <form method="GET" action="{{ route('financial-data.index') }}" class="flex gap-3">
            <input type="hidden" name="tab" value="regional-budgets">
            <div class="flex-1 relative">
                <input type="text" 
                       name="regional_search" 
                       class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400" 
                       placeholder="Rechercher par année ou région..." 
                       value="{{ request('regional_search') }}">
                <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                    <i class="fas fa-search"></i>
                </div>
            </div>
            <button type="submit" 
                    class="px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-300">
                <i class="fas fa-search"></i>
            </button>
            @if(request('regional_search'))
                <a href="{{ route('financial-data.index', ['tab' => 'regional-budgets']) }}" 
                   class="px-4 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300">
                    <i class="fas fa-times"></i>
                </a>
            @endif
        </form>
    </div>
    
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table id="regionalBudgetsTable" class="w-full">
                <thead class="bg-gradient-to-r from-gray-50 to-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Année</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Situation Administrative</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Taxe Adjudication 1/6</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Taxe Reconnaissance</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">TA Saisie Caution</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Budget FMF</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Taxe Mise en Charge</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($regionalBudgets as $budget)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $budget->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <span class="font-semibold">{{ $budget->year }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($budget->situationAdministrative)
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded text-xs">
                                        {{ $budget->situationAdministrative->region ?? 'N/A' }}
                                    </span>
                                </div>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ number_format($budget->taxe_adjudication_1_6 ?? 0, 2) }} DH
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ number_format($budget->taxe_reconnaissance_interets ?? 0, 2) }} DH
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ number_format($budget->ta_saisie_caution ?? 0, 2) }} DH
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ number_format($budget->budget_fmf ?? 0, 2) }} DH
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ number_format($budget->taxe_mise_en_charge ?? 0, 2) }} DH
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('financial-data.regional-budgets.edit', $budget) }}" 
                                   class="inline-flex items-center gap-1 px-3 py-2 bg-gradient-to-r from-yellow-500 to-orange-500 text-white rounded-lg hover:from-yellow-600 hover:to-orange-600 transition-all duration-300 transform hover:scale-105 shadow-sm"
                                   title="Modifier">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>
                                <form action="{{ route('financial-data.regional-budgets.destroy', $budget) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center gap-1 px-3 py-2 bg-gradient-to-r from-red-500 to-pink-500 text-white rounded-lg hover:from-red-600 hover:to-pink-600 transition-all duration-300 transform hover:scale-105 shadow-sm"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce budget régional ?')"
                                            title="Supprimer">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-coins text-2xl text-gray-400"></i>
                                </div>
                                <p class="text-lg font-medium">Aucun budget régional trouvé</p>
                                <p class="text-sm">Commencez par ajouter un nouveau budget régional</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($regionalBudgets->hasPages())
        <div class="mt-6 flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Affichage de {{ $regionalBudgets->firstItem() }} à {{ $regionalBudgets->lastItem() }} sur {{ $regionalBudgets->total() }} résultats
            </div>
            <div class="flex items-center gap-2">
                {{ $regionalBudgets->appends(array_merge(request()->query(), ['tab' => 'regional-budgets']))->links() }}
            </div>
        </div>
    @endif
</div>


