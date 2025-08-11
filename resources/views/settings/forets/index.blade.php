@extends('layouts.app')

@section('title', 'Forêts')

@section('page-actions')
    <!-- <a href="{{ route('settings.index') }}" class="btn-secondary">
        <i class="material-icons mr-2 text-base">arrow_back</i>
        Retour aux Paramètres
    </a> -->
@endsection

@section('content')
    {{-- Enhanced Statistics Cards --}}
    <!-- <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
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
    </div> -->

    
    {{-- Enhanced Create Form Section --}}
    <div class="card" style="margin-bottom: 1rem;">
        <div class="card-header">
            <h3 class="text-lg font-semibold text-gray-900">Ajouter une nouvelle forêt</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('settings.forets.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="foret" class="form-label">Nom de la forêt</label>
                        <input type="text" class="form-control" id="foret" name="foret" required class="form-input" placeholder="Ex: Forêt de la Mamora">
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
                        <input type="number" class="form-control" id="lat" name="lat" step="any" class="form-input" placeholder="Ex: 34.0209">
                    </div>
                    <div>
                        <label for="log" class="form-label">Longitude</label>
                        <input type="number" class="form-control" id="log" name="log" step="any" class="form-input" placeholder="Ex: -6.8416">
                    </div>
                </div>
                <div class="flex justify-end" style="margin-top: 10px;">
                    <button type="submit" class="btn-primary">
                        <i class="material-icons mr-2 text-base">add</i>
                        Ajouter la forêt
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    {{-- Enhanced Advanced Filter Section --}}
    <div class="card" style="margin-bottom: 1rem;">
        <div id="filter-content" class="card-body">
            <form method="GET" action="{{ route('settings.forets') }}" class="space-y-4">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3 col-6">
                        <label for="search" class="form-label">Rechercher</label>
                        <div class="relative">
                            <i class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</i>
                            <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" class="form-input pl-10 w-full" placeholder="Rechercher dans les forêts...">
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
                        <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}" class="form-input w-full">
                    </div>
                    <div class="col-md-3 col-6">
                        <label for="date_to" class="form-label">Au</label>
                        <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}" class="form-input w-full">
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
                        <!-- <a href="{{ route('settings.forets') }}" class="btn-outline d-flex">
                            <i class="material-icons mr-2 text-xs">restart_alt</i>
                            Réinitialiser
                        </a> -->
                    </div>
                </div>
            </form>
        </div>
    </div>


    {{-- Enhanced Data Table --}}
    <div class="card" style="margin-bottom: 1rem;">
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
                                    <i class="material-icons text-sm">swap_vert</i>
                                </div>
                            </th>
                            <th class="table-header-cell cursor-pointer" onclick="sortTable('foret')">
                                <div class="flex items-center space-x-1">
                                    <span>Forêt</span>
                                    <i class="material-icons text-sm">swap_vert</i>
                                </div>
                            </th>
                            <th class="table-header-cell cursor-pointer" onclick="sortTable('province')">
                                <div class="flex items-center space-x-1">
                                    <span>Province</span>
                                    <i class="material-icons text-sm">swap_vert</i>
                                </div>
                            </th>
                            <th class="table-header-cell">Coordonnées</th>
                            <th class="table-header-cell">Statut</th>
                            <th class="table-header-cell cursor-pointer" onclick="sortTable('created_at')">
                                <div class="flex items-center space-x-1">
                                    <span>Date de création</span>
                                    <i class="material-icons text-sm">swap_vert</i>
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
                                            <i  onclick="editForet({{ $foret->id }})" class="material-icons text-base">edit</i>
                                            <i onclick="deleteForet({{ $foret->id }})" class="material-icons text-base">delete</i>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="table-cell text-center py-8 text-gray-500">
                                    <i class="material-icons text-gray-300 text-6xl">search_off</i>
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

    {{-- Enhanced Import/Export Section --}}
    <div class="card"  style="margin-bottom: 2rem;">
        <div id="import-export-content" class="card-body hidden">
            <div class="d-flex mx-2">  <!-- Added negative margin to compensate for padding -->
                {{-- Export Section --}}
                <div class="col-md-6 col-6 px-2 mb-4 md:mb-0">  <!-- 50% width on medium screens and up -->
                    <div class="h-full p-4  flex flex-col">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="bg-blue-100 p-2 rounded-full">
                                <i class="material-icons text-blue-600">file_download</i>
                </div>
                <div>
                                <h3 class="text-base font-semibold text-gray-800">Export Data</h3>
                                <p class="text-sm text-gray-500">Download forets in Excel format</p>
                </div>
            </div>
                        <form action="{{ route('settings.forets.export') }}" method="GET" class="mt-auto">
                            @foreach(request()->except(['page']) as $key => $value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach
                            <button type="submit" class="w-full btn-primary py-2">
                                <i class="material-icons mr-2 text-base">file_download</i>
                                Export Excel
                            </button>
                        </form>
                    </div>
                </div>
                
                {{-- Import Section --}}
                <div class="wcol-md-6 col-6 px-2">  <!-- 50% width on medium screens and up -->
                    <div class="h-full flex flex-col">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="bg-green-100 p-2 rounded-full">
                                <i class="material-icons text-green-600">cloud_upload</i>
                            </div>
                            <div>
                                <h3 class="text-base font-semibold text-gray-800">Import Data</h3>
                                <p class="text-sm text-gray-500">Upload forets from Excel file</p>
                            </div>
                        </div>
                        <form action="{{ route('excel.import.forets') }}" method="POST" enctype="multipart/form-data" class="mt-auto">
                            @csrf
                            <input type="hidden" name="type" value="forets">
                            <div class="mb-3">
                                <div class="file-upload">
                                    <input type="file" id="forets-file" name="file" accept=".xlsx,.xls,.csv" class="file-input" onchange="updateFileName(this, 'forets-file-name')" required>
                                </div>
                                <div id="forets-file-name" class="text-xs text-gray-500 mt-1 hidden"></div>
                </div>
                            <button type="submit" class="w-full btn-primary py-2">
                                <i class="material-icons mr-2 text-base">cloud_upload</i>
                                Import
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
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
