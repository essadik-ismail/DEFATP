@extends('layouts.app')

@section('title', 'Gestion des Situations Administratives')

@section('content')
    <!-- Page Header -->
    <div class="content-header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-0">Gestion des Situations Administratives</h1>
                <p class="text-muted mb-0">Administrez les situations administratives des parcelles forestières</p>
            </div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createSituationAdministrativeModal">
                <i class="material-icons me-2">add</i>
                Nouvelle Situation Administrative
            </button>
        </div>
    </div>

    <!-- Statistics Grid -->
    <!-- <x-stats-grid :stats="[
        [
            'title' => 'Total Situations',
            'value' => $situationAdministratives->total(),
            'icon' => 'material-icons',
            'color' => 'purple'
        ],
        [
            'title' => 'Situations Actives',
            'value' => $situationAdministratives->count(),
            'icon' => 'material-icons',
            'color' => 'green'
        ],
        [
            'title' => 'Ajoutées ce mois',
            'value' => $situationAdministratives->where('created_at', '>=', now()->subDays(30))->count(),
            'icon' => 'material-icons',
            'color' => 'orange'
        ],
        [
            'title' => 'Communes Uniques',
            'value' => $situationAdministratives->count(),
            'icon' => 'material-icons',
            'color' => 'blue'
        ]
    ]" /> -->

    <!-- Filter Section -->
    <x-filter-section 
        title="Filtres de recherche"
        collapsible="true"
        collapsed="false"
    >
        <form method="GET" action="{{ route('settings.situation-administratives') }}" class="row g-3">
            <div class="col-md-4">
                <x-form.input 
                    name="search" 
                    label="Rechercher" 
                    placeholder="Commune, cercle, province..."
                    value="{{ request('search') }}"
                    icon="search"
                />
            </div>
            <div class="col-md-3">
                <x-form.select 
                    name="status" 
                    label="Statut"
                    :options="[
                        '' => 'Tous',
                        'active' => 'Actives',
                        'deleted' => 'Supprimées'
                    ]"
                    selected="{{ request('status') }}"
                />
            </div>
            <div class="col-md-3">
                <x-form.select 
                    name="per_page" 
                    label="Par page"
                    :options="[
                        '10' => '10',
                        '15' => '15',
                        '25' => '25',
                        '50' => '50',
                        '100' => '100'
                    ]"
                    selected="{{ request('per_page', 15) }}"
                />
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="material-icons me-2">filter_alt</i>
                    Filtrer
                </button>
            </div>
        </form>
    </x-filter-section>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <x-alert type="success" :message="session('success')" />
    @endif

    @if(session('error'))
        <x-alert type="danger" :message="session('error')" />
    @endif

    <!-- Data Table -->
    <x-data-table
        :headers="['ID', 'Commune', 'Cercle', 'Province', 'Statut', 'Créé le', 'Actions']"
        :total="$situationAdministratives->total()"
        :pagination="$situationAdministratives->appends(request()->query())->links()"
        emptyMessage="Aucune situation administrative trouvée"
        emptySubmessage="Commencez par ajouter votre première situation administrative"
    >
        @foreach($situationAdministratives as $situationAdministrative)
            <tr class="table-row">
                <td class="table-cell">#{{ $situationAdministrative->id }}</td>
                <td class="table-cell">
                    <span class="fw-medium">{{ $situationAdministrative->commune }}</span>
                </td>
                <td class="table-cell">
                    <span class="text-body">{{ $situationAdministrative->cercle }}</span>
                </td>
                <td class="table-cell">
                    <span class="text-body">{{ $situationAdministrative->province }}</span>
                </td>
                <td class="table-cell">
                    @if($situationAdministrative->deleted_at)
                        <span class="badge bg-danger">Supprimée</span>
                    @else
                        <span class="badge bg-success">Active</span>
                    @endif
                </td>
                <td class="table-cell">
                    <small class="text-muted">{{ $situationAdministrative->created_at?->format('d/m/Y H:i') }}</small>
                </td>
                <td class="table-cell">
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="editSituationAdministrative({{ $situationAdministrative->id }})">
                            <i class="material-icons">edit</i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteSituationAdministrative({{ $situationAdministrative->id }})">
                            <i class="material-icons">delete</i>
                        </button>
                    </div>
                </td>
            </tr>
        @endforeach
    </x-data-table>

    <!-- Import/Export Section -->
    <x-import-export-section
        title="Import/Export des Situations Administratives"
        :exportRoute="route('settings.situation-administratives.export')"
        :importRoute="route('excel.import.situation-administratives')"
        exportLabel="Exporter les Situations Administratives"
        importLabel="Importer des Situations Administratives"
        exportDescription="Télécharger la liste des situations administratives au format Excel"
        importDescription="Importer des situations administratives depuis un fichier Excel"
    />

    <!-- Create Situation Administrative Modal -->
    <div class="modal fade" id="createSituationAdministrativeModal" tabindex="-1" aria-labelledby="createSituationAdministrativeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createSituationAdministrativeModalLabel">Nouvelle Situation Administrative</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('settings.situation-administratives.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <x-form.input 
                                    name="commune" 
                                    label="Commune" 
                                    placeholder="Ex: Commune de Rabat"
                                    required
                                    icon="location_city"
                                />
                            </div>
                            <div class="col-12">
                                <x-form.input 
                                    name="cercle" 
                                    label="Cercle" 
                                    placeholder="Ex: Cercle de Rabat"
                                    required
                                    icon="location_on"
                                />
                            </div>
                            <div class="col-12">
                                <x-form.input 
                                    name="province" 
                                    label="Province" 
                                    placeholder="Ex: Province de Rabat"
                                    required
                                    icon="map"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="material-icons me-2">add</i>
                            Créer la situation administrative
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function editSituationAdministrative(id) {
        // Implement edit functionality
        console.log('Edit situation administrative:', id);
        // You can open a modal or redirect to edit page
    }

    function deleteSituationAdministrative(id) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cette situation administrative ?')) {
            // Implement delete functionality
            console.log('Delete situation administrative:', id);
            // You can make an AJAX call or redirect to delete route
        }
    }
</script>
@endpush 