@extends('layouts.app')

@section('title', 'Forêts')

@section('page-actions')
    <a href="{{ route('settings.index') }}" class="btn-secondary">
        <i class="material-icons mr-2 text-base">arrow_back</i>
        Retour aux Paramètres
    </a>
@endsection

@section('content')
    {{-- Enhanced Statistics Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="stats-card">
            <div class="stats-icon bg-blue-100 text-blue-600">
                <i class="material-icons text-blue-600">park</i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600">Total Forêts</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
            </div>
        </div>

        <div class="stats-card">
            <div class="stats-icon bg-green-100 text-green-600">
                <i class="material-icons text-green-600">check_circle</i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600">Forêts Actives</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['active'] }}</p>
            </div>
        </div>

        <div class="stats-card">
            <div class="stats-icon bg-orange-100 text-orange-600">
                <i class="material-icons text-orange-600">schedule</i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600">Ajoutées ce mois</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['recent'] }}</p>
            </div>
        </div>

        <div class="stats-card">
            <div class="stats-icon bg-purple-100 text-purple-600">
                <i class="material-icons text-purple-600">category</i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600">Types Uniques</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['unique'] }}</p>
            </div>
        </div>
    </div>

    {{-- Enhanced Advanced Filter Section --}}
    <div class="card mb-8">
        <div id="filter-content" class="card-body">
            <form method="GET" action="{{ route('settings.forets') }}" class="space-y-4">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3 col-6">
                        <label for="search" class="form-label">Rechercher</label>
                        <div class="relative">
                            <i class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</i>
                            <input type="text" id="search" name="search" value="{{ request('search') }}" class="form-input pl-10 w-full" placeholder="Rechercher dans les forêts...">
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <label for="province" class="form-label">Province</label>
                        <select id="province" name="province" class="form-select w-full">
                            <option value="">Toutes</option>
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
                    <div class="col-md-3 col-6">
                        <label for="status" class="form-label">Statut</label>
                        <select id="status" name="status" class="form-select w-full">
                            <option value="">Tous</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actives</option>
                            <option value="deleted" {{ request('status') == 'deleted' ? 'selected' : '' }}>Supprimées</option>
                            <option value="recent" {{ request('status') == 'recent' ? 'selected' : '' }}>Récentes</option>
                        </select>
                    </div>
                    <div class="col-md-3 col-6">
                        <label for="date_from" class="form-label">Du</label>
                        <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}" class="form-input w-full">
                    </div>
                    <div class="col-md-3 col-6">
                        <label for="date_to" class="form-label">Au</label>
                        <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}" class="form-input w-full">
                    </div>
                    <div class="col-md-3 col-6">
                        <label for="sort" class="form-label">Trier par</label>
                        <select id="sort" name="sort" class="form-select w-full">
                            <option value="foret" {{ request('sort') == 'foret' ? 'selected' : '' }}>Forêt</option>
                            <option value="id" {{ request('sort') == 'id' ? 'selected' : '' }}>ID</option>
                            <option value="province" {{ request('sort') == 'province' ? 'selected' : '' }}>Province</option>
                            <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Créé le</option>
                            <option value="updated_at" {{ request('sort') == 'updated_at' ? 'selected' : '' }}>Modifié le</option>
                        </select>
                    </div>
                    <div class="col-md-3 col-6">
                        <label for="per_page" class="form-label">Par page</label>
                        <select id="per_page" name="per_page" class="form-select w-full">
                            <option value="10" {{ request('per_page', 15) == 10 ? 'selected' : '' }}>10</option>
                            <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15</option>
                            <option value="25" {{ request('per_page', 15) == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page', 15) == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page', 15) == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </div>
                    <div class="col-md-3 col-6 d-flex gap-3 align-items-center">
                        <button type="submit" class="btn-primary d-flex">
                            <i class="material-icons mr-2 text-xs">filter_alt</i>
                            Appliquer
                        </button>
                        <a href="{{ route('settings.forets') }}" class="btn-outline d-flex">
                            <i class="material-icons mr-2 text-xs">restart_alt</i>
                            Réinitialiser
                        </a>
                    </div>
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
            <form action="{{ route('settings.forets.export') }}" method="GET" class="mt-4">
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
            <form action="{{ route('excel.import-all') }}" method="POST" enctype="multipart/form-data" class="mt-4">
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
                                        <svg onclick="deleteForet({{ $foret->id }})" title="Supprimer" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
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
