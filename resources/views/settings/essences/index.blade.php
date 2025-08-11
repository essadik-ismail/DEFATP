@extends('layouts.app')

@section('title', 'Essences')

@section('page-actions')
    <a href="{{ route('settings.index') }}" class="btn-secondary">
        <i class="material-icons mr-2 text-base">arrow_back</i>
        Retour aux Paramètres
    </a>
@endsection

@section('content')
    {{-- Enhanced Statistics Cards --}}
    <!-- <div class="flex flex-col md:flex-row md:flex-wrap gap-6 mb-8">
        <div class="stats-card w-full md:basis-1/2 lg:basis-1/4">
            <div class="stats-icon bg-blue-100 text-blue-600">
                <i class="material-icons text-blue-600">description</i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600">Total Essences</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
            </div>
        </div>

        <div class="stats-card w-full md:basis-1/2 lg:basis-1/4">
            <div class="stats-icon bg-green-100 text-green-600">
                <i class="material-icons text-green-600">check_circle</i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600">Essences Actives</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['active'] }}</p>
            </div>
        </div>

        <div class="stats-card w-full md:basis-1/2 lg:basis-1/4">
            <div class="stats-icon bg-orange-100 text-orange-600">
                <i class="material-icons text-orange-600">schedule</i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600">Ajoutées ce mois</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['recent'] }}</p>
            </div>
        </div>

        <div class="stats-card w-full md:basis-1/2 lg:basis-1/4">
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
    <div class="card mb-6">
        <div class="card-header">
            <h3 class="text-lg font-semibold text-gray-900">Ajouter une nouvelle essence</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('settings.essences.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="essence" class="form-label">Nom de l'essence</label>
                        <input type="text" id="essence" name="essence" required class="form-input" placeholder="Ex: Chêne, Pin, Eucalyptus...">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="btn-primary">
                            <i class="material-icons mr-2 text-base">add</i>
                            Ajouter l'essence
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Enhanced Advanced Filter Section --}}
    <div class="card mb-6">
        <!-- <div class="card-header">
            <button type="button" onclick="toggleFilters()" class="flex items-center justify-between w-full text-left">
                <h3 class="text-lg font-semibold text-gray-900">Filtres Avancés</h3>
                <i id="filter-icon" class="material-icons text-gray-500 transform transition-transform duration-200">expand_more</i>
            </button>
        </div> -->
        <div id="filter-content" class="card-body hidden">
            <form method="GET" action="{{ route('settings.essences') }}" class="space-y-4">
                <div class="row g-3 align-items-end"> <!-- Changed to Bootstrap-like row with gutters -->
                    <!-- Search -->
                    <div class="col-md-3 col-6">
                        <label for="search" class="form-label">Rechercher</label>
                        <div class="relative">
                            <i class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</i>
                            <input type="text" id="search" name="search" value="{{ request('search') }}" class="form-input pl-10 w-full" placeholder="Rechercher dans les essences...">
                        </div>
                    </div>


                    <!-- Status -->
                    <div class="col-md-3 col-6">
                        <label for="status" class="form-label">Statut</label>
                        <select id="status" name="status" class="form-select w-full">
                            <option value="">Tous</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actives</option>
                            <option value="deleted" {{ request('status') == 'deleted' ? 'selected' : '' }}>Supprimées</option>
                            <option value="recent" {{ request('status') == 'recent' ? 'selected' : '' }}>Récentes</option>
                        </select>
                    </div>

                    <!-- Date From -->
                    <div class="col-md-3 col-6">
                        <label for="date_from" class="form-label">Du</label>
                        <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}" class="form-input w-full">
                    </div>

                    <!-- Date To -->
                    <div class="col-md-3 col-6">
                        <label for="date_to" class="form-label">Au</label>
                        <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}" class="form-input w-full">
                    </div>

                    <!-- Sort By -->
                    <div class="col-md-3 col-6">
                        <label for="sort" class="form-label">Trier par</label>
                        <select id="sort" name="sort" class="form-select w-full">
                            <option value="essence" {{ request('sort') == 'essence' ? 'selected' : '' }}>Essence</option>
                            <option value="id" {{ request('sort') == 'id' ? 'selected' : '' }}>ID</option>
                            <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Créé le</option>
                            <option value="updated_at" {{ request('sort') == 'updated_at' ? 'selected' : '' }}>Modifié le</option>
                        </select>
                    </div>

                    <!-- Direction -->
                    <div class="col-md-3 col-6">
                        <label for="direction" class="form-label">Direction</label>
                        <select id="direction" name="direction" class="form-select w-full">
                            <option value="asc" {{ request('direction') == 'asc' ? 'selected' : '' }}>Asc</option>
                            <option value="desc" {{ request('direction') == 'desc' ? 'selected' : '' }}>Desc</option>
                        </select>
                    </div>

                    <!-- Per Page -->
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


                    <!-- Buttons -->
                    <div class="col-md-3 col-6 d-flex gap-3 align-items-center">
                        <button type="submit" class="btn-primary d-flex">
                            <i class="material-icons mr-2 text-xs">filter_alt</i>
                            Appliquer
                        </button>
                        <a href="{{ route('settings.essences') }}" class=" btn-primary d-flex underline">
                            <i class="material-icons mr-2 text-xs underline">restart_alt</i>
                            Annuler
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>




    <div class="mb-6"></div>
    {{-- Enhanced Data Table --}}
    <div class="card mb-6">
        <div class="card-header">
            <h3 class="text-lg font-semibold text-gray-900">Liste des essences</h3>
            <p class="text-sm text-gray-600">Affichage de {{ $essences->firstItem() ?? 0 }} à {{ $essences->lastItem() ?? 0 }} sur {{ $essences->total() }} résultats</p>
        </div>
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead class="table-header">
                        <tr>
                            <th class="table-header-cell cursor-pointer" onclick="sortTable('id')">
                                <div class="flex items-center space-x-1">
                                    <span>ID</span>
                                    <i class="material-icons text-base sort-icon">unfold_more</i>
                                </div>
                            </th>
                            <th class="table-header-cell cursor-pointer" onclick="sortTable('essence')">
                                <div class="flex items-center space-x-1">
                                    <span>Essence</span>
                                    <i class="material-icons text-base sort-icon">unfold_more</i>
                                </div>
                            </th>
                            <th class="table-header-cell">Statut</th>
                            <th class="table-header-cell cursor-pointer" onclick="sortTable('created_at')">
                                <div class="flex items-center space-x-1">
                                    <span>Date de création</span>
                                    <i class="material-icons text-base sort-icon">unfold_more</i>
                                </div>
                            </th>
                            <th class="table-header-cell cursor-pointer" onclick="sortTable('updated_at')">
                                <div class="flex items-center space-x-1">
                                    <span>Dernière modification</span>
                                    <i class="material-icons text-base sort-icon">unfold_more</i>
                                </div>
                            </th>
                            <th class="table-header-cell">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-body">
                        @forelse($essences as $essence)
                            <tr class="table-row hover:bg-gray-50">
                                <td class="table-cell text-center">#{{ $essence->id }}</td>
                                <td class="table-cell font-medium">{{ $essence->essence }}</td>
                                <td class="table-cell">
                                    @if($essence->is_deleted)
                                        <span class="badge badge-danger">Supprimée</span>
                                    @else
                                        <span class="badge badge-success">Active</span>
                                    @endif
                                </td>
                                <td class="table-cell text-center">{{ $essence->created_at?->format('d/m/Y H:i') }}</td>
                                <td class="table-cell text-center">{{ $essence->updated_at?->format('d/m/Y H:i') }}</td>
                                <td class="table-cell">
                                    <div class="flex items-center space-x-2">
                                        <button onclick="editEssence({{ $essence->id }})" class="icon-button icon-button-primary" title="Modifier">
                                            <i class="material-icons text-base">edit</i>
                                        </button>
                                        <button onclick="deleteEssence({{ $essence->id }})" class="icon-button icon-button-danger" title="Supprimer">
                                            <i class="material-icons text-base">delete</i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="table-cell text-center py-8 text-gray-500">
                                    <i class="material-icons text-gray-300 text-6xl">category</i>
                                    <p class="text-lg font-medium">Aucune essence trouvée</p>
                                    <p class="text-sm">Commencez par ajouter votre première essence</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        {{-- Laravel Pagination --}}
        @if($essences->hasPages())
            <div class="card-body border-t border-gray-200">
                {{ $essences->appends(request()->except('page'))->links() }}
            </div>
        @endif
    </div>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div id="success-message" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg-lg z-50 fade-in">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div id="error-message" class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 fade-in">
            {{ session('error') }}
        </div>
    @endif

    {{-- Enhanced Import/Export Section --}}
    <div class="card mt-15">
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
                                <p class="text-sm text-gray-500">Download essences in Excel format</p>
                            </div>
                        </div>
                        <form action="{{ route('settings.essences.export') }}" method="GET" class="mt-auto">
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
                                <p class="text-sm text-gray-500">Upload essences from Excel file</p>
                            </div>
                        </div>
                        <form action="{{ route('excel.import-all') }}" method="POST" enctype="multipart/form-data" class="mt-auto">
                            @csrf
                            <input type="hidden" name="type" value="essences">
                            <div class="mb-3">
                                <div class="file-upload">
                                    <input type="file" id="essences-file" name="file" accept=".xlsx,.xls,.csv" class="file-input" onchange="updateFileName(this, 'essences-file-name')" required>
                                    <!-- <label for="essences-file" class="file-label">
                                        <i class="material-icons mr-2 text-base">attach_file</i>
                                        Choose File
                                    </label> -->
                                </div>
                                <div id="essences-file-name" class="text-xs text-gray-500 mt-1 hidden"></div>
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

    function editEssence(id) {
        // Implement edit functionality
        console.log('Edit essence:', id);
    }

    function deleteEssence(id) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cette essence ?')) {
            // Implement delete functionality
            console.log('Delete essence:', id);
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