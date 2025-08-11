@extends('layouts.app')

@section('title', 'Forêts')

@section('page-actions')
    <a href="{{ route('settings.index') }}" class="btn-secondary">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Retour aux Paramètres
    </a>
@endsection

@section('content')
    {{-- Enhanced Statistics Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="stats-card">
            <div class="stats-icon bg-blue-100 text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600">Total Forêts</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
            </div>
        </div>

        <div class="stats-card">
            <div class="stats-icon bg-green-100 text-green-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600">Forêts Actives</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['active'] }}</p>
            </div>
        </div>

        <div class="stats-card">
            <div class="stats-icon bg-orange-100 text-orange-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600">Ajoutées ce mois</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['recent'] }}</p>
            </div>
        </div>

        <div class="stats-card">
            <div class="stats-icon bg-purple-100 text-purple-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600">Types Uniques</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['unique'] }}</p>
            </div>
        </div>
    </div>

    {{-- Enhanced Advanced Filter Section --}}
    <div class="card mb-6">
        <div class="card-header">
            <button type="button" onclick="toggleFilters()" class="flex items-center justify-between w-full text-left">
                <h3 class="text-lg font-semibold text-gray-900">Filtres Avancés</h3>
                <svg id="filter-icon" class="w-5 h-5 text-gray-500 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
        </div>
        <div id="filter-content" class="card-body hidden">
            <form method="GET" action="{{ route('settings.forets') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label for="search" class="form-label">Rechercher</label>
                        <input type="text" id="search" name="search" value="{{ request('search') }}" 
                               class="form-input" placeholder="Rechercher dans les forêts...">
                    </div>
                    
                    <div>
                        <label for="province" class="form-label">Province</label>
                        <select id="province" name="province" class="form-select">
                            <option value="">Toutes les provinces</option>
                            <option value="Casablanca-Settat" {{ request('province') == 'Casablanca-Settat' ? 'selected' : '' }}>Casablanca-Settat</option>
                            <option value="Rabat-Salé-Kénitra" {{ request('province') == 'Rabat-Salé-Kénitra' ? 'selected' : '' }}>Rabat-Salé-Kénitra</option>
                            <option value="Marrakech-Safi" {{ request('province') == 'Marrakech-Safi' ? 'selected' : '' }}>Marrakech-Safi</option>
                            <option value="Fès-Meknès" {{ request('province') == 'Fès-Meknès' ? 'selected' : '' }}>Fès-Meknès</option>
                            <option value="Tanger-Tétouan-Al Hoceima" {{ request('province') == 'Tanger-Tétouan-Al Hoceima' ? 'selected' : '' }}>Tanger-Tétouan-Al Hoceima</option>
                            <option value="Oriental" {{ request('province') == 'Oriental' ? 'selected' : '' }}>Oriental</option>
                            <option value="Souss-Massa" {{ request('province') == 'Souss-Massa' ? 'selected' : '' }}>Souss-Massa</option>
                            <option value="Drâa-Tafilalet" {{ request('province') == 'Drâa-Tafilalet' ? 'selected' : '' }}>Drâa-Tafilalet</option>
                            <option value="Béni Mellal-Khénifra" {{ request('province') == 'Béni Mellal-Khénifra' ? 'selected' : '' }}>Béni Mellal-Khénifra</option>
                            <option value="Guelmim-Oued Noun" {{ request('province') == 'Guelmim-Oued Noun' ? 'selected' : '' }}>Guelmim-Oued Noun</option>
                            <option value="Laâyoune-Sakia El Hamra" {{ request('province') == 'Laâyoune-Sakia El Hamra' ? 'selected' : '' }}>Laâyoune-Sakia El Hamra</option>
                            <option value="Dakhla-Oued Ed-Dahab" {{ request('province') == 'Dakhla-Oued Ed-Dahab' ? 'selected' : '' }}>Dakhla-Oued Ed-Dahab</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="status" class="form-label">Statut</label>
                        <select id="status" name="status" class="form-select">
                            <option value="">Tous les statuts</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actives</option>
                            <option value="deleted" {{ request('status') == 'deleted' ? 'selected' : '' }}>Supprimées</option>
                            <option value="recent" {{ request('status') == 'recent' ? 'selected' : '' }}>Récentes</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="date_from" class="form-label">Date de début</label>
                        <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}" class="form-input">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="date_to" class="form-label">Date de fin</label>
                        <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}" class="form-input">
                    </div>
                    
                    <div>
                        <label for="sort" class="form-label">Trier par</label>
                        <select id="sort" name="sort" class="form-select">
                            <option value="foret" {{ request('sort') == 'foret' ? 'selected' : '' }}>Forêt</option>
                            <option value="id" {{ request('sort') == 'id' ? 'selected' : '' }}>ID</option>
                            <option value="province" {{ request('sort') == 'province' ? 'selected' : '' }}>Province</option>
                            <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Date de création</option>
                            <option value="updated_at" {{ request('sort') == 'updated_at' ? 'selected' : '' }}>Date de modification</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="per_page" class="form-label">Par page</label>
                        <select id="per_page" name="per_page" class="form-select">
                            <option value="10" {{ request('per_page', 15) == 10 ? 'selected' : '' }}>10</option>
                            <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15</option>
                            <option value="25" {{ request('per_page', 15) == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page', 15) == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page', 15) == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex flex-wrap gap-3">
                    <button type="submit" class="btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Appliquer les filtres
                    </button>
                    <a href="{{ route('settings.forets') }}" class="btn-secondary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Réinitialiser
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Enhanced Import/Export Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- Export Section --}}
        <div class="import-export-card bg-gradient-to-r from-blue-500 to-blue-600">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-white">Exporter les données</h3>
                    <p class="text-blue-100">Téléchargez toutes les forêts au format Excel</p>
                </div>
                <svg class="w-8 h-8 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <form action="{{ route('settings.export.forets') }}" method="GET" class="mt-4">
                @foreach(request()->except(['page']) as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach
                <button type="submit" class="w-full btn-white">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Exporter Excel
                </button>
            </form>
        </div>

        {{-- Import Section --}}
        <div class="import-export-card bg-gradient-to-r from-green-500 to-green-600">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-white">Importer des données</h3>
                    <p class="text-green-100">Ajoutez des forêts depuis un fichier Excel</p>
                </div>
                <svg class="w-8 h-8 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                </svg>
            </div>
            <form action="{{ route('excel.import') }}" method="POST" enctype="multipart/form-data" class="mt-4">
                @csrf
                <input type="hidden" name="type" value="forets">
                <div class="file-upload">
                    <input type="file" id="forets-file" name="file" accept=".xlsx,.xls,.csv" class="file-input" onchange="updateFileName(this, 'forets-file-name')" required>
                    <label for="forets-file" class="file-label">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        Choisir un fichier
                    </label>
                </div>
                <div id="forets-file-name" class="text-sm text-green-100 mt-2 hidden"></div>
                <button type="submit" class="w-full btn-white mt-3">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    Importer
                </button>
            </form>
        </div>
    </div>

    {{-- Enhanced Create Form Section --}}
    <div class="card mb-6">
        <div class="card-header">
            <h3 class="text-lg font-semibold text-gray-900">Ajouter une nouvelle forêt</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('settings.forets.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="foret" class="form-label">Nom de la forêt</label>
                        <input type="text" id="foret" name="foret" required class="form-input" placeholder="Ex: Forêt de la Mamora">
                    </div>
                    <div>
                        <label for="province" class="form-label">Province</label>
                        <select id="province" name="province" required class="form-select">
                            <option value="">Sélectionner une province</option>
                            <option value="Casablanca-Settat">Casablanca-Settat</option>
                            <option value="Rabat-Salé-Kénitra">Rabat-Salé-Kénitra</option>
                            <option value="Marrakech-Safi">Marrakech-Safi</option>
                            <option value="Fès-Meknès">Fès-Meknès</option>
                            <option value="Tanger-Tétouan-Al Hoceima">Tanger-Tétouan-Al Hoceima</option>
                            <option value="Oriental">Oriental</option>
                            <option value="Souss-Massa">Souss-Massa</option>
                            <option value="Drâa-Tafilalet">Drâa-Tafilalet</option>
                            <option value="Béni Mellal-Khénifra">Béni Mellal-Khénifra</option>
                            <option value="Guelmim-Oued Noun">Guelmim-Oued Noun</option>
                            <option value="Laâyoune-Sakia El Hamra">Laâyoune-Sakia El Hamra</option>
                            <option value="Dakhla-Oued Ed-Dahab">Dakhla-Oued Ed-Dahab</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="lat" class="form-label">Latitude</label>
                        <input type="number" id="lat" name="lat" step="any" class="form-input" placeholder="Ex: 34.0209">
                    </div>
                    <div>
                        <label for="log" class="form-label">Longitude</label>
                        <input type="number" id="log" name="log" step="any" class="form-input" placeholder="Ex: -6.8416">
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Ajouter la forêt
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Enhanced Data Table --}}
    <div class="card">
        <div class="card-header">
            <h3 class="text-lg font-semibold text-gray-900">Liste des forêts</h3>
            <p class="text-sm text-gray-600">Affichage de {{ $forets->firstItem() ?? 0 }} à {{ $forets->lastItem() ?? 0 }} sur {{ $forets->total() }} résultats</p>
        </div>
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead class="table-header">
                        <tr>
                            <th class="table-header-cell cursor-pointer" onclick="sortTable('id')">
                                <div class="flex items-center space-x-1">
                                    <span>ID</span>
                                    <svg class="w-4 h-4 sort-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                    </svg>
                                </div>
                            </th>
                            <th class="table-header-cell cursor-pointer" onclick="sortTable('foret')">
                                <div class="flex items-center space-x-1">
                                    <span>Forêt</span>
                                    <svg class="w-4 h-4 sort-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                    </svg>
                                </div>
                            </th>
                            <th class="table-header-cell cursor-pointer" onclick="sortTable('province')">
                                <div class="flex items-center space-x-1">
                                    <span>Province</span>
                                    <svg class="w-4 h-4 sort-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                    </svg>
                                </div>
                            </th>
                            <th class="table-header-cell">Coordonnées</th>
                            <th class="table-header-cell">Statut</th>
                            <th class="table-header-cell cursor-pointer" onclick="sortTable('created_at')">
                                <div class="flex items-center space-x-1">
                                    <span>Date de création</span>
                                    <svg class="w-4 h-4 sort-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                    </svg>
                                </div>
                            </th>
                            <th class="table-header-cell">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-body">
                        @forelse($forets as $foret)
                            <tr class="table-row hover:bg-gray-50">
                                <td class="table-cell text-center">#{{ $foret->id }}</td>
                                <td class="table-cell font-medium">{{ $foret->foret }}</td>
                                <td class="table-cell">
                                    <span class="badge badge-secondary">{{ $foret->province }}</span>
                                </td>
                                <td class="table-cell text-sm">
                                    @if($foret->lat && $foret->log)
                                        <span class="text-gray-600">{{ $foret->lat }}, {{ $foret->log }}</span>
                                    @else
                                        <span class="text-gray-400">Non définies</span>
                                    @endif
                                </td>
                                <td class="table-cell">
                                    @if($foret->is_deleted)
                                        <span class="badge badge-danger">Supprimée</span>
                                    @else
                                        <span class="badge badge-success">Active</span>
                                    @endif
                                </td>
                                <td class="table-cell text-center">{{ $foret->created_at?->format('d/m/Y H:i') }}</td>
                                <td class="table-cell">
                                    <div class="flex items-center space-x-2">
                                        <button onclick="editForet({{ $foret->id }})" class="icon-button icon-button-primary" title="Modifier">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </button>
                                        <button onclick="deleteForet({{ $foret->id }})" class="icon-button icon-button-danger" title="Supprimer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="table-cell text-center py-8 text-gray-500">
                                    <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <p class="text-lg font-medium">Aucune forêt trouvée</p>
                                    <p class="text-sm">Commencez par ajouter votre première forêt</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        {{-- Laravel Pagination --}}
        @if($forets->hasPages())
            <div class="card-body border-t border-gray-200">
                {{ $forets->appends(request()->except('page'))->links() }}
            </div>
        @endif
    </div>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div id="success-message" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 fade-in">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div id="error-message" class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 fade-in">
            {{ session('error') }}
        </div>
    @endif
@endsection

@push('scripts')
<script>
    // Global functions for backward compatibility
    function toggleFilters() {
        const content = document.getElementById('filter-content');
        const icon = document.getElementById('filter-icon');
        content.classList.toggle('hidden');
        icon.classList.toggle('rotate-180');
    }

    function updateFileName(input, displayId) {
        const display = document.getElementById(displayId);
        if (input.files && input.files[0]) {
            display.textContent = input.files[0].name;
            display.classList.remove('hidden');
        } else {
            display.classList.add('hidden');
        }
    }

    function sortTable(column) {
        const url = new URL(window.location);
        const currentSort = url.searchParams.get('sort');
        const currentDirection = url.searchParams.get('direction');
        
        if (currentSort === column) {
            url.searchParams.set('direction', currentDirection === 'asc' ? 'desc' : 'asc');
        } else {
            url.searchParams.set('sort', column);
            url.searchParams.set('direction', 'asc');
        }
        
        window.location.href = url.toString();
    }

    function refreshTable() {
        window.location.reload();
    }

    function editForet(id) {
        // Implement edit functionality
        console.log('Edit foret:', id);
    }

    function deleteForet(id) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cette forêt ?')) {
            // Implement delete functionality
            console.log('Delete foret:', id);
        }
    }

    // Auto-hide notifications
    document.addEventListener('DOMContentLoaded', function() {
        const successMessage = document.getElementById('success-message');
        const errorMessage = document.getElementById('error-message');
        
        if (successMessage) {
            setTimeout(() => successMessage.remove(), 5000);
        }
        if (errorMessage) {
            setTimeout(() => errorMessage.remove(), 5000);
        }
    });
</script>
@endpush
