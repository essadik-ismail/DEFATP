@extends('layouts.app')

@section('title', 'Localisations')

@section('page-actions')
    <div class="flex items-center gap-3">
        <a href="{{ route('settings.localisations.export') }}" class="btn-white">
            <i class="material-icons mr-2 text-base">file_download</i>
            Exporter
        </a>
        <a href="{{ route('settings.index') }}" class="btn-secondary">
            <i class="material-icons mr-2 text-base">arrow_back</i>
            Retour aux Paramètres
        </a>
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
                                <h2 class="stats-number">{{ $localisations->total() }}</h2>
                                <p class="stats-label">Total Localisations</p>
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
                                <h2 class="stats-number">{{ $localisations->count() }}</h2>
                                <p class="stats-label">Localisations Actives</p>
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
                                <h2 class="stats-number">{{ $localisations->where('created_at', '>=', now()->subDays(30))->count() }}</h2>
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
                                <h2 class="stats-number">{{ $localisations->count() }}</h2>
                                <p class="stats-label">Codes Uniques</p>
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
            <form method="GET" action="{{ route('settings.localisations') }}" class="space-y-4">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3 col-6">
                        <label for="code" class="form-label">Code</label>
                        <div class="relative">
                            <i class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">code</i>
                            <input type="text" name="code" id="code" value="{{ request('code') }}" class="form-input pl-10" placeholder="Ex: L001">
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <label for="sort" class="form-label">Trier par</label>
                        <select name="sort" id="sort" class="form-select w-full">
                            <option value="code_asc" {{ request('sort') == 'code_asc' ? 'selected' : '' }}>Code (A-Z)</option>
                            <option value="code_desc" {{ request('sort') == 'code_desc' ? 'selected' : '' }}>Code (Z-A)</option>
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
                    <div class="col-md-3 col-6">
                        <label for="status" class="form-label">Statut</label>
                        <select name="status" id="status" class="form-select w-full">
                            <option value="">Tous les statuts</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actives</option>
                            <option value="recent" {{ request('status') == 'recent' ? 'selected' : '' }}>Récentes</option>
                        </select>
                    </div>
                    <div class="col-md-3 col-6 d-flex gap-3 align-items-center">
                        <button type="submit" class="btn-primary d-flex">
                            <i class="material-icons mr-2 text-xs">filter_alt</i>
                            Appliquer
                        </button>
                        <a href="{{ route('settings.localisations') }}" class="btn-outline d-flex">
                            <i class="material-icons mr-2 text-xs">restart_alt</i>
                            Réinitialiser
                        </a>
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
                    <p class="text-sm text-gray-600">Gérez vos données de localisations</p>
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
                    <p class="text-gray-600 mb-4">Téléchargez toutes les localisations au format Excel</p>
                    <a href="{{ route('settings.localisations.export') }}" class="btn-white">
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
                    <p class="text-gray-600 mb-4">Importez des localisations depuis un fichier Excel</p>
                    <form action="{{ route('settings.localisations.import') }}" method="POST" enctype="multipart/form-data" class="import-form">
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
            <h5 class="card-title">Liste des Localisations</h5>
            <p class="text-sm text-gray-600">{{ $localisations->total() }} localisation(s) trouvée(s)</p>
        </div>
        <div class="card-body" id="datatable-section">
            @if($localisations->count() > 0)
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead class="table-header">
                            <tr>
                                <th class="table-header-cell">ID</th>
                                <th class="table-header-cell">Code</th>
                                <th class="table-header-cell">Créé le</th>
                                <th class="table-header-cell">Mis à jour le</th>
                            </tr>
                        </thead>
                        <tbody class="table-body">
                            @foreach($localisations as $localisation)
                                <tr class="table-row">
                                    <td class="table-cell">{{ $localisation->id }}</td>
                                    <td class="table-cell">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">{{ $localisation->CODE }}</span>
                                    </td>
                                    <td class="table-cell">{{ $localisation->created_at?->format('d/m/Y H:i') }}</td>
                                    <td class="table-cell">{{ $localisation->updated_at?->format('d/m/Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">
                    {{ $localisations->appends(request()->query())->links() }}
                </div>
            @else
                <div class="empty-state">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="material-icons text-gray-400 text-2xl">location_off</i>
                    </div>
                    <p class="text-gray-500 font-medium">Aucune localisation trouvée</p>
                    <p class="text-gray-400 text-sm">Essayez de modifier vos filtres ou importez des localisations</p>
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

.filter-card, .import-export-card, .data-table-card {
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

.code-badge {
    background-color: #e3f2fd;
    color: #1976d2;
    padding: 4px 8px;
    border-radius: 4px;
    font-family: monospace;
    font-weight: 500;
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
