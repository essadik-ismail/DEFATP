@extends('layouts.app')

@section('title', 'Gestion des Localisations')

@section('content')
    <!-- Page Header -->
    <div class="content-header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-0">Gestion des Localisations</h1>
                <p class="text-muted mb-0">Administrez les localisations et leurs codes géographiques</p>
            </div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createLocalisationModal">
                <i class="material-icons me-2">add</i>
                Nouvelle Localisation
            </button>
        </div>
    </div>

    <!-- Statistics Grid -->
    <!-- <x-stats-grid :stats="[
        [
            'title' => 'Total Localisations',
            'value' => $localisations->total(),
            'icon' => 'material-icons',
            'color' => 'purple'
        ],
        [
            'title' => 'Localisations Actives',
            'value' => $localisations->count(),
            'icon' => 'material-icons',
            'color' => 'green'
        ],
        [
            'title' => 'Ajoutées ce mois',
            'value' => $localisations->where('created_at', '>=', now()->subDays(30))->count(),
            'icon' => 'material-icons',
            'color' => 'orange'
        ],
        [
            'title' => 'Codes Uniques',
            'value' => $localisations->count(),
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
        <form method="GET" action="{{ route('settings.localisations') }}" class="row g-3">
            <div class="col-md-3">
                <x-form.input 
                    name="code" 
                    label="Code" 
                    placeholder="Ex: L001"
                    value="{{ request('code') }}"
                    icon="code"
                />
            </div>
            <div class="col-md-3">
                <x-form.input 
                    name="search" 
                    label="Rechercher" 
                    placeholder="DRANEF, DPANEF, ENTITE..."
                    value="{{ request('search') }}"
                    icon="search"
                />
            </div>
            <div class="col-md-2">
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
        :headers="['Code', 'DRANEF', 'DPANEF', 'ENTITE', 'Créé le', 'Actions']"
        :total="$localisations->total()"
        :pagination="$localisations->appends(request()->query())->links()"
        emptyMessage="Aucune localisation trouvée"
        emptySubmessage="Commencez par ajouter votre première localisation"
    >
        @foreach($localisations as $localisation)
            <tr class="table-row">
                <td class="table-cell">
                    <span class="badge bg-primary">{{ $localisation->CODE }}</span>
                </td>
                <td class="table-cell">
                    <span class="text-body">{{ $localisation->DRANEF }}</span>
                </td>
                <td class="table-cell">
                    <span class="text-body">{{ $localisation->DPANEF }}</span>
                </td>
                <td class="table-cell">
                    <span class="text-body">{{ $localisation->ENTITE }}</span>
                </td>
                <td class="table-cell">
                    <small class="text-muted">{{ $localisation->created_at?->format('d/m/Y H:i') }}</small>
                </td>
                <td class="table-cell">
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="editLocalisation({{ $localisation->id }})">
                            <i class="material-icons">edit</i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteLocalisation({{ $localisation->id }})">
                            <i class="material-icons">delete</i>
                        </button>
                    </div>
                </td>
            </tr>
        @endforeach
    </x-data-table>

    <!-- Import/Export Section -->
    <x-import-export-section
        title="Import/Export des Localisations"
        :exportRoute="route('settings.localisations.export')"
        :importRoute="route('excel.import.localisations')"
        exportLabel="Exporter les Localisations"
        importLabel="Importer des Localisations"
        exportDescription="Télécharger la liste des localisations au format Excel"
        importDescription="Importer des localisations depuis un fichier Excel"
    />

    <!-- Create Localisation Modal -->
    <div class="modal fade" id="createLocalisationModal" tabindex="-1" aria-labelledby="createLocalisationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createLocalisationModalLabel">Nouvelle Localisation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('settings.localisations.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <x-form.input 
                                    name="CODE" 
                                    label="Code" 
                                    placeholder="Ex: L001"
                                    required
                                    icon="code"
                                />
                            </div>
                            <div class="col-12">
                                <x-form.input 
                                    name="DRANEF" 
                                    label="DRANEF" 
                                    placeholder="Direction Régionale des Eaux et Forêts"
                                    required
                                    icon="location_on"
                                />
                            </div>
                            <div class="col-12">
                                <x-form.input 
                                    name="DPANEF" 
                                    label="DPANEF" 
                                    placeholder="Direction Provinciale des Eaux et Forêts"
                                    required
                                    icon="location_city"
                                />
                            </div>
                            <div class="col-12">
                                <x-form.input 
                                    name="ENTITE" 
                                    label="ENTITE" 
                                    placeholder="Entité administrative"
                                    required
                                    icon="business"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="material-icons me-2">add</i>
                            Créer la localisation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function editLocalisation(id) {
        // Implement edit functionality
        console.log('Edit localisation:', id);
        // You can open a modal or redirect to edit page
    }

    function deleteLocalisation(id) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cette localisation ?')) {
            // Implement delete functionality
            console.log('Delete localisation:', id);
            // You can make an AJAX call or redirect to delete route
        }
    }
</script>
@endpush
