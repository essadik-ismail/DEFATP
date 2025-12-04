<!-- Monthly Revenues Tab -->
<div class="tab-pane fade {{ $tab === 'monthly-revenues' ? 'show active' : '' }}" id="monthly-revenues" role="tabpanel">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-calendar-alt text-white text-lg"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-900">Revenus Mensuels</h3>
                <p class="text-gray-600">Gérez les revenus mensuels</p>
            </div>
        </div>
        <a href="{{ route('financial-data.monthly-revenues.create') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
            <i class="fas fa-plus"></i>
            <span>Nouveau Revenu</span>
        </a>
    </div>
    
    <!-- Search Box -->
    <div class="mb-6">
        <form method="GET" action="{{ route('financial-data.index') }}" class="flex gap-3">
            <input type="hidden" name="tab" value="monthly-revenues">
            <div class="flex-1 relative">
                <input type="text" 
                       name="monthly_search" 
                       class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 hover:border-gray-400" 
                       placeholder="Rechercher par année, mois ou région..." 
                       value="{{ request('monthly_search') }}">
                <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                    <i class="fas fa-search"></i>
                </div>
            </div>
            <button type="submit" 
                    class="px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-300">
                <i class="fas fa-search"></i>
            </button>
            @if(request('monthly_search'))
                <a href="{{ route('financial-data.index', ['tab' => 'monthly-revenues']) }}" 
                   class="px-4 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300">
                    <i class="fas fa-times"></i>
                </a>
            @endif
        </form>
    </div>
    
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table id="monthlyRevenuesTable" class="w-full">
                <thead class="bg-gradient-to-r from-gray-50 to-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Année</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Mois</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Situation Administrative</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Liège</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Bols/Charbon/Tanin</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Alfa</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Produits Divers</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Part Province</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($monthlyRevenues as $revenue)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $revenue->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <span class="font-semibold">{{ $revenue->year }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-semibold">
                                {{ \Carbon\Carbon::create()->month($revenue->month)->locale('fr')->monthName }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($revenue->situationAdministrative)
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs">
                                        {{ $revenue->situationAdministrative->region ?? 'N/A' }}
                                    </span>
                                </div>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ number_format($revenue->llege ?? 0, 2) }} DH
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ number_format($revenue->bols_charbon_tanin ?? 0, 2) }} DH
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ number_format($revenue->alfa ?? 0, 2) }} DH
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ number_format($revenue->produits_divers ?? 0, 2) }} DH
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded">
                                {{ number_format($revenue->total_part_province ?? 0, 2) }} DH
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('financial-data.monthly-revenues.edit', $revenue) }}" 
                                   class="inline-flex items-center gap-1 px-3 py-2 bg-gradient-to-r from-yellow-500 to-orange-500 text-white rounded-lg hover:from-yellow-600 hover:to-orange-600 transition-all duration-300 transform hover:scale-105 shadow-sm"
                                   title="Modifier">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>
                                <form action="{{ route('financial-data.monthly-revenues.destroy', $revenue) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center gap-1 px-3 py-2 bg-gradient-to-r from-red-500 to-pink-500 text-white rounded-lg hover:from-red-600 hover:to-pink-600 transition-all duration-300 transform hover:scale-105 shadow-sm"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce revenu mensuel ?')"
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
                                    <i class="fas fa-calendar-alt text-2xl text-gray-400"></i>
                                </div>
                                <p class="text-lg font-medium">Aucun revenu mensuel trouvé</p>
                                <p class="text-sm">Commencez par ajouter un nouveau revenu mensuel</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($monthlyRevenues->hasPages())
        <div class="mt-6 flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Affichage de {{ $monthlyRevenues->firstItem() }} à {{ $monthlyRevenues->lastItem() }} sur {{ $monthlyRevenues->total() }} résultats
            </div>
            <div class="flex items-center gap-2">
                {{ $monthlyRevenues->appends(array_merge(request()->query(), ['tab' => 'monthly-revenues']))->links() }}
            </div>
        </div>
    @endif
</div>


