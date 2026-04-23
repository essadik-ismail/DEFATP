<!-- Situations Administratives Tab (Articles) -->
<div class="tab-pane fade" id="situations" role="tabpanel">
    <!-- Situations Administratives Data Table -->
    <div class="rounded-2xl border max-w-full overflow-hidden" style="background: #FFFFFF; border-color: rgba(154,179,163,0.4); box-shadow: 0 2px 8px rgba(0,0,0,0.04), 0 1px 2px rgba(0,0,0,0.03);">
        <!-- Per Page Selector -->
        <div class="px-5 py-3 border-b flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2" style="border-color: rgba(154,179,163,0.4);">
            <div class="flex items-center gap-2">
                <span class="text-xs font-medium" style="color: #6B7C72;">Total: {{ $situationsAdministratives->count() }} situations</span>
            </div>
            <a href="{{ route('settings.situation-administratives.create') }}" 
               class="btn-primary inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium">
                <i class="fas fa-plus"></i>
                <span>Nouvelle Situation</span>
            </a>
        </div>
            
        <!-- Data Table -->
        <div class="overflow-x-auto max-w-full" style="-webkit-overflow-scrolling: touch;">
            <table id="situationsTable" class="w-full divide-y divide-gray-200">
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
                                <span>Commune</span>
                                <button class="filter-btn ml-1 text-gray-400 hover:text-gray-600" data-column="1" title="Filtrer">
                                    <i class="fas fa-filter text-xs"></i>
                                </button>
                            </div>
                        </th>
                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider relative">
                            <div class="flex items-center justify-between">
                                <span>Province</span>
                                <button class="filter-btn ml-1 text-gray-400 hover:text-gray-600" data-column="2" title="Filtrer">
                                    <i class="fas fa-filter text-xs"></i>
                                </button>
                            </div>
                        </th>
                        <th class="px-3 py-2 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($situationsAdministratives as $situation)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-3 py-2 whitespace-nowrap text-xs font-medium text-gray-900">
                            {{ $situation->id }}
                        </td>
                        <td class="px-3 py-2 whitespace-nowrap text-xs text-gray-900">
                            <span class="font-medium">{{ $situation->commune }}</span>
                        </td>
                        <td class="px-3 py-2 whitespace-nowrap text-xs text-gray-900">
                            {{ $situation->province }}
                        </td>
                        <td class="px-3 py-2 whitespace-nowrap text-xs text-gray-900">
                            <div class="flex items-center justify-center gap-0.5">
                                <a href="{{ route('settings.situation-administratives.edit', $situation) }}" 
                                   class="inline-flex items-center justify-center w-7 h-7 bg-orange-100 hover:bg-orange-200 text-orange-600 rounded transition-colors duration-200" 
                                   title="Modifier">
                                    <i class="fas fa-edit text-xs"></i>
                                </a>
                                <form action="{{ route('settings.situation-administratives.destroy', $situation) }}" method="POST" style="display: contents;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center justify-center w-7 h-7 bg-red-100 hover:bg-red-200 text-red-600 rounded transition-colors duration-200"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette situation administrative ?')"
                                            title="Supprimer">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
