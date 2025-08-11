@extends('layouts.app')

@section('title', 'Sessions d\'Adjudication')

@section('page-actions')
    <a href="{{ route('settings.index') }}" class="btn-secondary">
        <i class="material-icons mr-2 text-base">arrow_back</i>
        Retour aux Paramètres
    </a>
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
                                <h2 class="stats-number">{{ $sessionAdjudications->total() }}</h2>
                                <p class="stats-label">Total Sessions</p>
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
                                <h2 class="stats-number">{{ $sessionAdjudications->count() }}</h2>
                                <p class="stats-label">Sessions Actives</p>
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
                                <h2 class="stats-number">{{ $sessionAdjudications->where('created_at', '>=', now()->subDays(30))->count() }}</h2>
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
                                <h2 class="stats-number">{{ $sessionAdjudications->count() }}</h2>
                                <p class="stats-label">Années Uniques</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Enhanced Advanced Filter Section --}}
    <div class="card mb-8">
        <div id="filter-content" class="card-body">
            <form method="GET" action="{{ route('settings.session-adjudications') }}" class="space-y-4">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3 col-6">
                        <label for="annee" class="form-label">Année</label>
                        <div class="relative">
                            <i class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">calendar_today</i>
                            <input type="text" name="annee" id="annee" value="{{ request('annee') }}" class="form-input pl-10" placeholder="Ex: 2024...">
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <label for="session" class="form-label">Session</label>
                        <div class="relative">
                            <i class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">event</i>
                            <input type="text" name="session" id="session" value="{{ request('session') }}" class="form-input pl-10" placeholder="Ex: Session 1...">
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <label for="sort" class="form-label">Trier par</label>
                        <select name="sort" id="sort" class="form-select w-full">
                            <option value="annee_desc" {{ request('sort') == 'annee_desc' ? 'selected' : '' }}>Année (Décroissant)</option>
                            <option value="annee_asc" {{ request('sort') == 'annee_asc' ? 'selected' : '' }}>Année (Croissant)</option>
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
                        <a href="{{ route('settings.session-adjudications') }}" class="btn-outline d-flex">
                            <i class="material-icons mr-2 text-xs">restart_alt</i>
                            Réinitialiser
                        </a>
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
                Ajouter une Nouvelle Session d'Adjudication
            </h2>
            <h3 class="mdc-typography--subtitle2">Créez une nouvelle session d'adjudication dans le système</h3>
        </div>
        <div class="mdc-card__secondary mdc-typography--body2">
            <form action="{{ route('settings.session-adjudications.store') }}" method="POST">
                @csrf
                <div class="mdc-layout-grid">
                    <div class="mdc-layout-grid__inner">
                        <div class="mdc-layout-grid__cell mdc-layout-grid__cell--span-6">
                            <label class="mdc-text-field mdc-text-field--outlined mdc-text-field--with-leading-icon">
                                <span class="mdc-notched-outline">
                                    <span class="mdc-notched-outline__leading"></span>
                                    <span class="mdc-notched-outline__notch">
                                        <span class="mdc-floating-label" id="new-annee-label">Année</span>
                                    </span>
                                    <span class="mdc-notched-outline__trailing"></span>
                                </span>
                                <i class="material-icons mdc-text-field__icon mdc-text-field__icon--leading">calendar_today</i>
                                <input type="number" name="annee" id="new-annee" class="mdc-text-field__input" 
                                       value="{{ old('annee') }}" 
                                       placeholder="Ex: 2024..." required aria-labelledby="new-annee-label">
                            </label>
                            @error('annee')
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
                                        <span class="mdc-floating-label" id="new-session-label">Session</span>
                                    </span>
                                    <span class="mdc-notched-outline__trailing"></span>
                                </span>
                                <i class="material-icons mdc-text-field__icon mdc-text-field__icon--leading">event</i>
                                <input type="text" name="session" id="new-session" class="mdc-text-field__input" 
                                       value="{{ old('session') }}" 
                                       placeholder="Ex: Session 1..." required aria-labelledby="new-session-label">
                            </label>
                            @error('session')
                                <div class="mdc-text-field-helper-line">
                                    <div class="mdc-text-field-helper-text mdc-text-field-helper-text--error">{{ $message }}</div>
                                </div>
                            @enderror
                        </div>
                        <div class="mdc-layout-grid__cell mdc-layout-grid__cell--span-12 d-flex align-items-end">
                            <button type="submit" class="mdc-button mdc-button--raised">
                                <span class="mdc-button__ripple"></span>
                                <i class="material-icons mdc-button__icon">save</i>
                                <span class="mdc-button__label">Ajouter la Session</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Import/Export Card -->
    <div class="card my-8">
        <div class="card-header">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center">
                    <i class="material-icons text-green-600">cloud_upload</i>
                </div>
                <div>
                    <h5 class="card-title">Import/Export</h5>
                    <p class="text-sm text-gray-600">Gérez vos données de sessions d'adjudication</p>
                </div>
            </div>
        </div>
        <div class="card-body" id="import-export-section">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="export-section">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center">
                            <i class="material-icons text-blue-600 text-sm">file_download</i>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900">Exporter les données</h4>
                    </div>
                    <p class="text-gray-600 mb-4">Téléchargez toutes les sessions d'adjudication au format Excel</p>
                    <a href="{{ route('settings.session-adjudications.export') }}" class="btn-white">
                        <i class="material-icons mr-2 text-base">file_download</i>
                        Exporter (.xlsx)
                    </a>
                </div>
                
                <div class="import-section">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 bg-green-50 rounded-lg flex items-center justify-center">
                            <i class="material-icons text-green-600 text-sm">cloud_upload</i>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900">Importer des données</h4>
                    </div>
                    <p class="text-gray-600 mb-4">Importez des sessions d'adjudication depuis un fichier Excel</p>
                    <form action="{{ route('settings.session-adjudications.import') }}" method="POST" enctype="multipart/form-data" class="import-form">
                        @csrf
                        <div class="flex items-center gap-4">
                            <input type="file" name="file" id="file" accept=".xlsx,.xls,.csv" required 
                                   class="block w-full form-control">
                            <button type="submit" class="btn-white">
                                <i class="material-icons mr-2 text-base">cloud_upload</i>
                                Importer
                            </button>
                        </div>
                    </form>
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
    <div class="card" style="margin-bottom: 1rem;">
        <div class="card-header">
            <h5 class="card-title">Liste des Sessions d'Adjudication</h5>
            <p class="text-sm text-gray-600">{{ $sessionAdjudications->total() }} session(s) trouvée(s)</p>
        </div>
        <div class="card-body" id="datatable-section">
            @if($sessionAdjudications->count() > 0)
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead class="table-header">
                            <tr>
                                <th class="table-header-cell">ID</th>
                                <th class="table-header-cell">Année</th>
                                <th class="table-header-cell">Session</th>
                                <th class="table-header-cell">Créé le</th>
                                <th class="table-header-cell">Mis à jour le</th>
                                <th class="table-header-cell">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-body">
                            @foreach($sessionAdjudications as $sessionAdjudication)
                                <tr class="table-row">
                                    <td class="table-cell">{{ $sessionAdjudication->id }}</td>
                                    <td class="table-cell">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">{{ $sessionAdjudication->annee }}</span>
                                    </td>
                                    <td class="table-cell">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">{{ $sessionAdjudication->session }}</span>
                                    </td>
                                    <td class="table-cell">{{ $sessionAdjudication->created_at?->format('d/m/Y H:i') }}</td>
                                    <td class="table-cell">{{ $sessionAdjudication->updated_at?->format('d/m/Y H:i') }}</td>
                                    <td class="table-cell">
                                        <div class="flex items-center gap-2">
                                            <button class="icon-button icon-button-primary" onclick="editSession({{ $sessionAdjudication->id }}, '{{ $sessionAdjudication->annee }}', '{{ $sessionAdjudication->session }}')" title="Modifier">
                                                <i class="material-icons text-base">edit</i>
                                            </button>
                                            <button class="icon-button icon-button-danger" onclick="deleteSession({{ $sessionAdjudication->id }})" title="Supprimer">
                                                <i class="material-icons text-base">delete</i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">
                    {{ $sessionAdjudications->appends(request()->query())->links() }}
                </div>
            @else
                <div class="empty-state">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="material-icons text-gray-400 text-2xl">event</i>
                    </div>
                    <p class="text-gray-500 font-medium">Aucune session d'adjudication trouvée</p>
                    <p class="text-gray-400 text-sm">Essayez de modifier vos filtres ou ajoutez une nouvelle session</p>
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

.annee-badge {
    background-color: #e3f2fd;
    color: #1976d2;
    padding: 4px 8px;
    border-radius: 4px;
    font-weight: 500;
}

.session-badge {
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
function editSession(id, annee, session) {
    // Implement edit functionality
    console.log('Edit session:', id, annee, session);
}

function deleteSession(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette session d\'adjudication ?')) {
        // Implement delete functionality
        console.log('Delete session:', id);
    }
}
</script>
@endpush 