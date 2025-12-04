<!-- Province Annual Shares Tab -->
<div class="tab-pane fade {{ $tab === 'province-annual-shares' ? 'show active' : '' }}" id="province-annual-shares" role="tabpanel">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-map-marked-alt text-white text-lg"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-900">Parts Annuelles des Provinces</h3>
                <p class="text-gray-600">Gérez les parts annuelles par province</p>
            </div>
        </div>
        <a href="{{ route('financial-data.province-annual-shares.create') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
            <i class="fas fa-plus"></i>
            <span>Nouvelle Part</span>
        </a>
    </div>
    
    <!-- Search Box -->
    <div class="mb-6">
        <form method="GET" action="{{ route('financial-data.index') }}" class="flex gap-3">
            <input type="hidden" name="tab" value="province-annual-shares">
            <div class="flex-1 relative">
                <input type="text" 
                       name="province_search" 
                       class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                       placeholder="Rechercher par année ou province..." 
                       value="{{ request('province_search') }}">
                <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                    <i class="fas fa-search"></i>
                </div>
            </div>
            <button type="submit" 
                    class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-300">
                <i class="fas fa-search"></i>
            </button>
            @if(request('province_search'))
                <a href="{{ route('financial-data.index', ['tab' => 'province-annual-shares']) }}" 
                   class="px-4 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300">
                    <i class="fas fa-times"></i>
                </a>
            @endif
        </form>
    </div>
    
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table id="provinceAnnualSharesTable" class="w-full">
                <thead class="bg-gradient-to-r from-gray-50 to-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Année</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Situation Administrative</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Liège</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Bols/Charbon/Tanin</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Alfa</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Produits Divers</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Intérêts Retard</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Province</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($provinceAnnualShares as $share)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $share->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <span class="font-semibold">{{ $share->year }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($share->situationAdministrative)
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs">
                                        {{ $share->situationAdministrative->province ?? 'N/A' }}
                                    </span>
                                </div>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ number_format($share->llege ?? 0, 2) }} DH
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ number_format($share->bols_charbon_tanin ?? 0, 2) }} DH
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ number_format($share->alfa ?? 0, 2) }} DH
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ number_format($share->produits_divers ?? 0, 2) }} DH
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ number_format($share->interets_retard ?? 0, 2) }} DH
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded">
                                {{ number_format($share->total_province ?? 0, 2) }} DH
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('financial-data.province-annual-shares.edit', $share) }}" 
                                   class="inline-flex items-center gap-1 px-3 py-2 bg-gradient-to-r from-yellow-500 to-orange-500 text-white rounded-lg hover:from-yellow-600 hover:to-orange-600 transition-all duration-300 transform hover:scale-105 shadow-sm"
                                   title="Modifier">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>
                                <form action="{{ route('financial-data.province-annual-shares.destroy', $share) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center gap-1 px-3 py-2 bg-gradient-to-r from-red-500 to-pink-500 text-white rounded-lg hover:from-red-600 hover:to-pink-600 transition-all duration-300 transform hover:scale-105 shadow-sm"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette part annuelle ?')"
                                            title="Supprimer">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-map-marked-alt text-2xl text-gray-400"></i>
                                </div>
                                <p class="text-lg font-medium">Aucune part annuelle trouvée</p>
                                <p class="text-sm">Commencez par ajouter une nouvelle part annuelle</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($provinceAnnualShares->hasPages())
        <div class="mt-6 flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Affichage de {{ $provinceAnnualShares->firstItem() }} à {{ $provinceAnnualShares->lastItem() }} sur {{ $provinceAnnualShares->total() }} résultats
            </div>
            <div class="flex items-center gap-2">
                {{ $provinceAnnualShares->appends(array_merge(request()->query(), ['tab' => 'province-annual-shares']))->links() }}
            </div>
        </div>
    @endif
</div>


