@extends('layouts.app')

@section('title', 'Situations Administratives')

@section('page-actions')
    <div class="page-actions">
        <button class="mdc-button mdc-button--outlined" onclick="window.location.href='{{ route('settings.index') }}'">
            <span class="mdc-button__ripple"></span>
            <i class="material-icons mdc-button__icon">arrow_back</i>
            <span class="mdc-button__label">Retour aux Paramètres</span>
        </button>
    </div>
@endsection

@section('content')
    <!-- Material Design Stats Cards -->
    <div class="mdc-layout-grid">
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
    </div>

    <!-- Material Design Filter Card -->
    <div class="mdc-card filter-card">
        <div class="mdc-card__primary">
            <h2 class="mdc-typography--headline6">
                <i class="material-icons">filter_list</i>
                Filtres de Recherche
            </h2>
            <h3 class="mdc-typography--subtitle2">Affinez votre recherche de situations administratives</h3>
        </div>
        <div class="mdc-card__secondary mdc-typography--body2">
            <form method="GET" action="{{ route('settings.situation-administratives') }}" class="filter-form">
                <div class="mdc-layout-grid">
                    <div class="mdc-layout-grid__inner">
                        <div class="mdc-layout-grid__cell mdc-layout-grid__cell--span-3">
                            <label class="mdc-text-field mdc-text-field--outlined mdc-text-field--with-leading-icon">
                                <span class="mdc-notched-outline">
                                    <span class="mdc-notched-outline__leading"></span>
                                    <span class="mdc-notched-outline__notch">
                                        <span class="mdc-floating-label" id="commune-label">Commune</span>
                                    </span>
                                    <span class="mdc-notched-outline__trailing"></span>
                                </span>
                                <i class="material-icons mdc-text-field__icon mdc-text-field__icon--leading">location_city</i>
                                <input type="text" name="commune" id="commune" class="mdc-text-field__input" 
                                       value="{{ request('commune') }}" 
                                       placeholder="Ex: Rabat..." aria-labelledby="commune-label">
                            </label>
                        </div>
                        
                        <div class="mdc-layout-grid__cell mdc-layout-grid__cell--span-3">
                            <label class="mdc-text-field mdc-text-field--outlined mdc-text-field--with-leading-icon">
                                <span class="mdc-notched-outline">
                                    <span class="mdc-notched-outline__leading"></span>
                                    <span class="mdc-notched-outline__notch">
                                        <span class="mdc-floating-label" id="province-label">Province</span>
                                    </span>
                                    <span class="mdc-notched-outline__trailing"></span>
                                </span>
                                <i class="material-icons mdc-text-field__icon mdc-text-field__icon--leading">location_city</i>
                                <input type="text" name="province" id="province" class="mdc-text-field__input" 
                                       value="{{ request('province') }}" 
                                       placeholder="Ex: Casablanca..." aria-labelledby="province-label">
                            </label>
                        </div>
                        
                        <div class="mdc-layout-grid__cell mdc-layout-grid__cell--span-3">
                            <label class="mdc-select mdc-select--outlined">
                                <span class="mdc-notched-outline">
                                    <span class="mdc-notched-outline__leading"></span>
                                    <span class="mdc-notched-outline__notch">
                                        <span class="mdc-floating-label">Tri</span>
                                    </span>
                                    <span class="mdc-notched-outline__trailing"></span>
                                </span>
                                <i class="material-icons mdc-select__icon">sort</i>
                                <select name="sort" id="sort" class="mdc-select__anchor">
                                    <option value="commune_asc" {{ request('sort') == 'commune_asc' ? 'selected' : '' }}>Commune (A-Z)</option>
                                    <option value="commune_desc" {{ request('sort') == 'commune_desc' ? 'selected' : '' }}>Commune (Z-A)</option>
                                    <option value="created_desc" {{ request('sort') == 'created_desc' ? 'selected' : '' }}>Plus récentes</option>
                                    <option value="created_asc" {{ request('sort') == 'created_asc' ? 'selected' : '' }}>Plus anciennes</option>
                                </select>
                            </label>
                        </div>
                        
                        <div class="mdc-layout-grid__cell mdc-layout-grid__cell--span-3">
                            <label class="mdc-select mdc-select--outlined">
                                <span class="mdc-notched-outline">
                                    <span class="mdc-notched-outline__leading"></span>
                                    <span class="mdc-notched-outline__notch">
                                        <span class="mdc-floating-label">Affichage</span>
                                    </span>
                                    <span class="mdc-notched-outline__trailing"></span>
                                </span>
                                <i class="material-icons mdc-select__icon">view_list</i>
                                <select name="per_page" id="per_page" class="mdc-select__anchor">
                                    <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>10 par page</option>
                                    <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25 par page</option>
                                    <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50 par page</option>
                                    <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100 par page</option>
                                </select>
                            </label>
                        </div>
                        
                        <div class="mdc-layout-grid__cell mdc-layout-grid__cell--span-9">
                            <div class="filter-actions">
                                <button type="submit" class="mdc-button mdc-button--raised">
                                    <span class="mdc-button__ripple"></span>
                                    <i class="material-icons mdc-button__icon">search</i>
                                    <span class="mdc-button__label">Appliquer les filtres</span>
                                </button>
                                <button type="button" class="mdc-button mdc-button--outlined" onclick="window.location.href='{{ route('settings.situation-administratives') }}'">
                                    <span class="mdc-button__ripple"></span>
                                    <i class="material-icons mdc-button__icon">clear</i>
                                    <span class="mdc-button__label">Réinitialiser</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Create Form Card -->
    <div class="mdc-card create-form-card">
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

    <!-- Import/Export Card -->
    <div class="mdc-card import-export-card">
        <div class="mdc-card__primary">
            <h2 class="mdc-typography--headline6">
                <i class="material-icons">file_upload</i>
                Import/Export
            </h2>
            <h3 class="mdc-typography--subtitle2">Gérez vos données de situations administratives</h3>
        </div>
        <div class="mdc-card__secondary mdc-typography--body2">
            <div class="mdc-layout-grid">
                <div class="mdc-layout-grid__inner">
                    <div class="mdc-layout-grid__cell mdc-layout-grid__cell--span-6">
                        <div class="export-section">
                            <h4 class="mdc-typography--subtitle1">
                                <i class="material-icons">download</i>
                                Exporter les données
                            </h4>
                            <p class="mdc-typography--body2">Téléchargez toutes les situations administratives au format Excel</p>
                            <a href="{{ route('settings.situation-administratives.export') }}" class="mdc-button mdc-button--raised">
                                <span class="mdc-button__ripple"></span>
                                <i class="material-icons mdc-button__icon">file_download</i>
                                <span class="mdc-button__label">Exporter (.xlsx)</span>
                            </a>
                        </div>
                    </div>
                    
                    <div class="mdc-layout-grid__cell mdc-layout-grid__cell--span-6">
                        <div class="import-section">
                            <h4 class="mdc-typography--subtitle1">
                                <i class="material-icons">upload</i>
                                Importer des données
                            </h4>
                            <p class="mdc-typography--body2">Importez des situations administratives depuis un fichier Excel</p>
                            <form action="{{ route('settings.situation-administratives.import') }}" method="POST" enctype="multipart/form-data" class="import-form">
                                @csrf
                                <div class="file-input-wrapper">
                                    <input type="file" name="file" id="file" accept=".xlsx,.xls,.csv" required class="file-input">
                                    <label for="file" class="mdc-button mdc-button--outlined">
                                        <span class="mdc-button__ripple"></span>
                                        <i class="material-icons mdc-button__icon">attach_file</i>
                                        <span class="mdc-button__label">Choisir un fichier</span>
                                    </label>
                                </div>
                                <button type="submit" class="mdc-button mdc-button--raised">
                                    <span class="mdc-button__ripple"></span>
                                    <i class="material-icons mdc-button__icon">cloud_upload</i>
                                    <span class="mdc-button__label">Importer</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
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
    <div class="mdc-card data-table-card">
        <div class="mdc-card__primary">
            <h2 class="mdc-typography--headline6">
                <i class="material-icons">table_chart</i>
                Liste des Situations Administratives
            </h2>
            <h3 class="mdc-typography--subtitle2">{{ $situationAdministratives->total() }} situation(s) trouvée(s)</h3>
        </div>
        <div class="mdc-card__secondary">
            <div class="table-container">
                <table class="mdc-data-table__table">
                    <thead>
                        <tr class="mdc-data-table__header-row">
                            <th class="mdc-data-table__header-cell" role="columnheader" scope="col">
                                <div class="mdc-data-table__header-cell-wrapper">
                                    <div class="mdc-data-table__header-cell-label">ID</div>
                                </div>
                            </th>
                            <th class="mdc-data-table__header-cell" role="columnheader" scope="col">
                                <div class="mdc-data-table__header-cell-wrapper">
                                    <div class="mdc-data-table__header-cell-label">Commune</div>
                                </div>
                            </th>
                            <th class="mdc-data-table__header-cell" role="columnheader" scope="col">
                                <div class="mdc-data-table__header-cell-wrapper">
                                    <div class="mdc-data-table__header-cell-label">Province</div>
                                </div>
                            </th>
                            <th class="mdc-data-table__header-cell" role="columnheader" scope="col">
                                <div class="mdc-data-table__header-cell-wrapper">
                                    <div class="mdc-data-table__header-cell-label">Créé le</div>
                                </div>
                            </th>
                            <th class="mdc-data-table__header-cell" role="columnheader" scope="col">
                                <div class="mdc-data-table__header-cell-wrapper">
                                    <div class="mdc-data-table__header-cell-label">Mis à jour le</div>
                                </div>
                            </th>
                            <th class="mdc-data-table__header-cell" role="columnheader" scope="col">
                                <div class="mdc-data-table__header-cell-wrapper">
                                    <div class="mdc-data-table__header-cell-label">Actions</div>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="mdc-data-table__content">
                        @forelse($situationAdministratives as $situationAdministrative)
                            <tr class="mdc-data-table__row">
                                <td class="mdc-data-table__cell">{{ $situationAdministrative->id }}</td>
                                <td class="mdc-data-table__cell">
                                    <span class="commune-badge">{{ $situationAdministrative->commune }}</span>
                                </td>
                                <td class="mdc-data-table__cell">{{ $situationAdministrative->province ?: '-' }}</td>
                                <td class="mdc-data-table__cell">{{ $situationAdministrative->created_at?->format('d/m/Y H:i') }}</td>
                                <td class="mdc-data-table__cell">{{ $situationAdministrative->updated_at?->format('d/m/Y H:i') }}</td>
                                <td class="mdc-data-table__cell">
                                    <div class="action-buttons">
                                        <button class="mdc-icon-button material-icons" onclick="editSituation({{ $situationAdministrative->id }}, '{{ $situationAdministrative->commune }}')" title="Modifier">
                                            edit
                                        </button>
                                        <button class="mdc-icon-button material-icons" onclick="deleteSituation({{ $situationAdministrative->id }})" title="Supprimer">
                                            delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="mdc-data-table__row">
                                <td colspan="6" class="mdc-data-table__cell text-center">
                                    <div class="empty-state">
                                        <i class="material-icons">location_city</i>
                                        <p>Aucune situation administrative trouvée.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($situationAdministratives->hasPages())
                <div class="pagination-wrapper">
                    {{ $situationAdministratives->appends(request()->query())->links() }}
                </div>
            @endif
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