@extends('layouts.app')

@section('title', 'Situations Administratives')

@section('page-actions')
    <a href="{{ route('settings.index') }}" class="btn-secondary">
        <i class="material-icons mr-2 text-base">arrow_back</i>
        Retour aux Paramètres
    </a>
@endsection

@section('content')
    <!-- Material Design Stats Cards -->
    <!-- <div class="mdc-layout-grid">
        <div class="mdc-layout-grid__inner">
            <div class="mdc-layout-grid__cell mdc-layout-grid__cell--span-3">
                <div class="mdc-card stats-card">
                    <div class="mdc-card__primary-action">
                        <div class="stats-content">
                            <div class="stats-icon">
                                <i class="material-icons">list</i>
                            </div>
                            <div class="stats-info">
                                <h2 class="stats-number">{{ $situationAdministratives->total() }}</h2>
                                <p class="stats-label">Total Situations</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mdc-layout-grid__cell mdc-layout-grid__cell--span-3">
                <div class="mdc-card stats-card">
                    <div class="mdc-card__primary-action">
                        <div class="stats-content">
                            <div class="stats-icon sold">
                                <i class="material-icons">check_circle</i>
                            </div>
                            <div class="stats-info">
                                <h2 class="stats-number">{{ $situationAdministratives->count() }}</h2>
                                <p class="stats-label">Situations Actives</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mdc-layout-grid__cell mdc-layout-grid__cell--span-3">
                <div class="mdc-card stats-card">
                    <div class="mdc-card__primary-action">
                        <div class="stats-content">
                            <div class="stats-icon unsold">
                                <i class="material-icons">schedule</i>
                            </div>
                            <div class="stats-info">
                                <h2 class="stats-number">{{ $situationAdministratives->where('created_at', '>=', now()->subDays(30))->count() }}</h2>
                                <p class="stats-label">Ajoutées ce mois</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mdc-layout-grid__cell mdc-layout-grid__cell--span-3">
                <div class="mdc-card stats-card">
                    <div class="mdc-card__primary-action">
                        <div class="stats-content">
                            <div class="stats-icon value">
                                <i class="material-icons">attach_money</i>
                            </div>
                            <div class="stats-info">
                                <h2 class="stats-number">{{ $situationAdministratives->count() }}</h2>
                                <p class="stats-label">Communes Uniques</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->

    <!-- Create Form Card -->
    <div class="mdc-card create-form-card" style="margin-bottom: 1rem;">
        <div class="mdc-card__primary">
            <h2 class="mdc-typography--headline6">
                <i class="material-icons">add_circle</i>
                Ajouter une Nouvelle Situation Administrative
            </h2>
            <h3 class="mdc-typography--subtitle2">Créez une nouvelle situation administrative dans le système</h3>
        </div>
        <div class="mdc-card__secondary mdc-typography--body2">
            <form action="{{ route('settings.situation-administratives.store') }}" method="POST">
                @csrf
                <div class="mdc-layout-grid">
                    <div class="mdc-layout-grid__inner">
                        <div class="mdc-layout-grid__cell mdc-layout-grid__cell--span-6">
                            <label class="mdc-text-field mdc-text-field--outlined mdc-text-field--with-leading-icon">
                                <span class="mdc-notched-outline">
                                    <span class="mdc-notched-outline__leading"></span>
                                    <span class="mdc-notched-outline__notch">
                                        <span class="mdc-floating-label" id="new-commune-label">Commune</span>
                                    </span>
                                    <span class="mdc-notched-outline__trailing"></span>
                                </span>
                                <i class="material-icons mdc-text-field__icon mdc-text-field__icon--leading">location_city</i>
                                <input type="text" name="commune" id="new-commune" class="mdc-text-field__input" 
                                       value="{{ old('commune') }}" 
                                       placeholder="Ex: Rabat, Casablanca..." required aria-labelledby="new-commune-label">
                            </label>
                            @error('commune')
                                <div class="mdc-text-field-helper-line">
                                    <div class="mdc-text-field-helper-text mdc-text-field-helper-text--error">{{ $message }}</div>
                                </div>
                            @enderror
                        </div>
                        <div class="mdc-layout-grid__cell mdc-layout-grid__cell--span-6">
                            <label class="mdc-text-field mdc-text-field--outlined mdc-text-field--with-leading-icon">
                                <span class="mdc-notched-outline">
                                    <span class="mdc-notched-outline__leading"></span>
                                    <span class="mdc-notched-outline__notch">
                                        <span class="mdc-floating-label" id="new-province-label">Province</span>
                                    </span>
                                    <span class="mdc-notched-outline__trailing"></span>
                                </span>
                                <i class="material-icons mdc-text-field__icon mdc-text-field__icon--leading">location_city</i>
                                <input type="text" name="province" id="new-province" class="mdc-text-field__input" 
                                       value="{{ old('province') }}" 
                                       placeholder="Ex: Rabat-Salé-Kénitra..." aria-labelledby="new-province-label">
                            </label>
                            @error('province')
                                <div class="mdc-text-field-helper-line">
                                    <div class="mdc-text-field-helper-text mdc-text-field-helper-text--error">{{ $message }}</div>
                                </div>
                            @enderror
                        </div>
                        <div class="mdc-layout-grid__cell mdc-layout-grid__cell--span-12 d-flex align-items-end">
                            <button type="submit" class="mdc-button mdc-button--raised">
                                <span class="mdc-button__ripple"></span>
                                <i class="material-icons mdc-button__icon">save</i>
                                <span class="mdc-button__label">Ajouter la Situation</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Enhanced Advanced Filter Section --}}
    <div class="card" style="margin-bottom: 1rem;">
        <div id="filter-content" class="card-body">
            <form method="GET" action="{{ route('settings.situation-administratives') }}" class="space-y-4">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3 col-6">
                        <label for="commune" class="form-label">Commune</label>
                        <div class="relative">
                            <i class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">location_city</i>
                            <input type="text" class="form-control" name="commune" id="commune" value="{{ request('commune') }}" class="form-input pl-10" placeholder="Ex: Rabat...">
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <label for="province" class="form-label">Province</label>
                        <div class="relative">
                            <i class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">location_city</i>
                            <input type="text" class="form-control" name="province" id="province" value="{{ request('province') }}" class="form-input pl-10" placeholder="Ex: Casablanca...">
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <label for="sort" class="form-label">Trier par</label>
                        <select name="sort" id="sort" class="form-select w-full">
                            <option value="commune_asc" {{ request('sort') == 'commune_asc' ? 'selected' : '' }}>Commune (A-Z)</option>
                            <option value="commune_desc" {{ request('sort') == 'commune_desc' ? 'selected' : '' }}>Commune (Z-A)</option>
                            <option value="created_desc" {{ request('sort') == 'created_desc' ? 'selected' : '' }}>Plus récentes</option>
                            <option value="created_asc" {{ request('sort') == 'created_asc' ? 'selected' : '' }}>Plus anciennes</option>
                        </select>
                    </div>
                    <div class="col-md-3 col-6">
                        <label for="per_page" class="form-label">Par page</label>
                        <select name="per_page" id="per_page" class="form-select w-full">
                            <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100</option>
                        </select>
                    </div>
                    <div class="col-md-3 col-6 d-flex gap-3 align-items-center">
                        <button type="submit" class="btn-primary d-flex">
                            <i class="material-icons mr-2 text-xs">filter_alt</i>
                            Appliquer
                        </button>
                        <!-- <a href="{{ route('settings.situation-administratives') }}" class="btn-outline d-flex">
                            <i class="material-icons mr-2 text-xs">restart_alt</i>
                            Réinitialiser
                        </a> -->
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="mdc-snackbar mdc-snackbar--success">
            <div class="mdc-snackbar__surface">
                <div class="mdc-snackbar__label">{{ session('success') }}</div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mdc-snackbar mdc-snackbar--error">
            <div class="mdc-snackbar__surface">
                <div class="mdc-snackbar__label">{{ session('error') }}</div>
            </div>
        </div>
    @endif

    <!-- Data Table Card -->
    <div class="card" style="margin-bottom: 1rem;">
        <div class="card-header">
            <h5 class="card-title">Liste des Situations Administratives</h5>
            <p class="text-sm text-gray-600">{{ $situationAdministratives->total() }} situation(s) trouvée(s)</p>
        </div>
        <div class="card-body" id="datatable-section">
            @if($situationAdministratives->count() > 0)
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead class="table-header">
                            <tr>
                                <th class="table-header-cell">ID</th>
                                <th class="table-header-cell">Commune</th>
                                <th class="table-header-cell">Province</th>
                                <th class="table-header-cell">Créé le</th>
                                <th class="table-header-cell">Mis à jour le</th>
                                <th class="table-header-cell">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-body">
                            @foreach($situationAdministratives as $situationAdministrative)
                                <tr class="table-row">
                                    <td class="table-cell">{{ $situationAdministrative->id }}</td>
                                    <td class="table-cell">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">{{ $situationAdministrative->commune }}</span>
                                    </td>
                                    <td class="table-cell">{{ $situationAdministrative->province ?: '-' }}</td>
                                    <td class="table-cell">{{ $situationAdministrative->created_at?->format('d/m/Y H:i') }}</td>
                                    <td class="table-cell">{{ $situationAdministrative->updated_at?->format('d/m/Y H:i') }}</td>
                                    <td class="table-cell">
                                        <div class="flex items-center gap-2">
                                                <i onclick="editSituation({{ $situationAdministrative->id }}, '{{ $situationAdministrative->commune }}')" title="Modifier" class="material-icons text-base">edit</i>
                                                <i onclick="deleteSituation({{ $situationAdministrative->id }})" title="Supprimer" class="material-icons text-base">delete</i>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">
                    {{ $situationAdministratives->appends(request()->query())->links() }}
                </div>
            @else
                <div class="empty-state">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="material-icons text-gray-400 text-2xl">location_city</i>
                    </div>
                    <p class="text-gray-500 font-medium">Aucune situation administrative trouvée</p>
                    <p class="text-gray-400 text-sm">Essayez de modifier vos filtres ou ajoutez une nouvelle situation</p>
                </div>
            @endif
        </div>
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
                                <p class="text-sm text-gray-500">Download ituation administrative in Excel format</p>
                </div>
            </div>
                        <form action="{{ route('settings.situation-administratives.export') }}" method="GET" class="mt-auto">
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
                                <p class="text-sm text-gray-500">Upload ituation administrative from Excel file</p>
                            </div>
                        </div>
                        <form action="{{ route('settings.situation-administratives.import') }}" method="POST" enctype="multipart/form-data" class="mt-auto">
                            @csrf
                            <input type="hidden" name="type" value="ituation-administrative">
                            <div class="mb-3">
                                <div class="file-upload">
                                    <input type="file" id="ituation-administrative-file" name="file" accept=".xlsx,.xls,.csv" class="file-input" onchange="updateFileName(this, 'ituation-administrative-file-name')" required>
                                    <!-- <label for="ituation-administrative-file" class="file-label">
                                        <i class="material-icons mr-2 text-base">attach_file</i>
                                        Choose File
                                    </label> -->
                                </div>
                                <div id="ituation-administrative-file-name" class="text-xs text-gray-500 mt-1 hidden"></div>
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

@push('styles')
<style>
.stats-card {
    margin-bottom: 16px;
}

.stats-content {
    display: flex;
    align-items: center;
    padding: 16px;
}

.stats-icon {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 16px;
    background-color: #e3f2fd;
    color: #1976d2;
}

.stats-icon.sold {
    background-color: #e8f5e8;
    color: #388e3c;
}

.stats-icon.unsold {
    background-color: #fff3e0;
    color: #f57c00;
}

.stats-icon.value {
    background-color: #f3e5f5;
    color: #7b1fa2;
}

.stats-number {
    font-size: 2rem;
    font-weight: 500;
    margin: 0;
    color: #212121;
}

.stats-label {
    font-size: 0.875rem;
    color: #757575;
    margin: 4px 0 0 0;
}

.filter-card, .create-form-card, .import-export-card, .data-table-card {
    margin-bottom: 16px;
}

.filter-actions {
    display: flex;
    gap: 8px;
    justify-content: flex-end;
    margin-top: 16px;
}

.export-section, .import-section {
    padding: 16px;
    border: 1px solid #e0e0e0;
    border-radius: 4px;
}

.export-section h4, .import-section h4 {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
}

.import-form {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-top: 12px;
}

.file-input-wrapper {
    display: flex;
    gap: 8px;
    align-items: center;
}

.file-input {
    display: none;
}

.commune-badge {
    background-color: #e8f5e8;
    color: #388e3c;
    padding: 4px 8px;
    border-radius: 4px;
    font-weight: 500;
}

.action-buttons {
    display: flex;
    gap: 4px;
}

.mdc-icon-button {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    border: none;
    background: transparent;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #757575;
    transition: background-color 0.2s;
}

.mdc-icon-button:hover {
    background-color: rgba(0, 0, 0, 0.04);
}

.empty-state {
    text-align: center;
    padding: 32px;
    color: #757575;
}

.empty-state i {
    font-size: 48px;
    margin-bottom: 16px;
}

.pagination-wrapper {
    margin-top: 16px;
    display: flex;
    justify-content: center;
}

.mdc-snackbar {
    position: fixed;
    bottom: 16px;
    right: 16px;
    z-index: 1000;
}

.mdc-snackbar--success .mdc-snackbar__surface {
    background-color: #4caf50;
}

.mdc-snackbar--error .mdc-snackbar__surface {
    background-color: #f44336;
}
</style>
@endpush

@push('scripts')
<script>
function editSituation(id, commune) {
    // Implement edit functionality
    console.log('Edit situation:', id, commune);
}

function deleteSituation(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette situation administrative ?')) {
        // Implement delete functionality
        console.log('Delete situation:', id);
    }
}
</script>
@endpush 