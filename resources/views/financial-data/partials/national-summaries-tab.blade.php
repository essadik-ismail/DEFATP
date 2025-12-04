<!-- National Summaries Tab -->
<div class="tab-pane fade {{ $tab === 'national-summaries' ? 'show active' : '' }}" id="national-summaries" role="tabpanel">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-flag text-white text-lg"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-900">Résumés Nationaux</h3>
                <p class="text-gray-600">Gérez les résumés nationaux</p>
            </div>
        </div>
        <a href="{{ route('financial-data.national-summaries.create') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-orange-600 to-red-600 text-white rounded-lg hover:from-orange-700 hover:to-red-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
            <i class="fas fa-plus"></i>
            <span>Nouveau Résumé</span>
        </a>
    </div>
    
    <!-- Search Box -->
    <div class="mb-6">
        <form method="GET" action="{{ route('financial-data.index') }}" class="flex gap-3">
            <input type="hidden" name="tab" value="national-summaries">
            <div class="flex-1 relative">
                <input type="text" 
                       name="national_search" 
                       class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 hover:border-gray-400" 
                       placeholder="Rechercher par année..." 
                       value="{{ request('national_search') }}">
                <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                    <i class="fas fa-search"></i>
                </div>
            </div>
            <button type="submit" 
                    class="px-6 py-3 bg-gradient-to-r from-orange-600 to-red-600 text-white rounded-xl hover:from-orange-700 hover:to-red-700 transition-all duration-300">
                <i class="fas fa-search"></i>
            </button>
            @if(request('national_search'))
                <a href="{{ route('financial-data.index', ['tab' => 'national-summaries']) }}" 
                   class="px-4 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300">
                    <i class="fas fa-times"></i>
                </a>
            @endif
        </form>
    </div>
    
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table id="nationalSummariesTable" class="w-full">
                <thead class="bg-gradient-to-r from-gray-50 to-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Année</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Budget Général Total</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Part État</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">CAS FMF Total</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Communes Total</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Provinces Total</th>
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
                            <span class="font-semibold text-lg">{{ $summary->year }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ number_format($summary->budget_general_total ?? 0, 2) }} DH
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded">
                                {{ number_format($summary->part_etat ?? 0, 2) }} DH
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ number_format($summary->cas_fmf_total ?? 0, 2) }} DH
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded">
                                {{ number_format($summary->communes_total ?? 0, 2) }} DH
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded">
                                {{ number_format($summary->provinces_total ?? 0, 2) }} DH
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                            <span class="px-3 py-1 bg-gradient-to-r from-orange-400 to-red-500 text-white rounded-lg">
                                {{ number_format($summary->total_general ?? 0, 2) }} DH
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('financial-data.national-summaries.edit', $summary) }}" 
                                   class="inline-flex items-center gap-1 px-3 py-2 bg-gradient-to-r from-yellow-500 to-orange-500 text-white rounded-lg hover:from-yellow-600 hover:to-orange-600 transition-all duration-300 transform hover:scale-105 shadow-sm"
                                   title="Modifier">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>
                                <form action="{{ route('financial-data.national-summaries.destroy', $summary) }}" method="POST" class="inline">
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
                        <td colspan="9" class="px-6 py-12 text-center text-gray-500">
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
                {{ $nationalSummaries->appends(array_merge(request()->query(), ['tab' => 'national-summaries']))->links() }}
            </div>
        </div>
    @endif
</div>


