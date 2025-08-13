@extends('layouts.app')

@section('title', 'Gestion des Essences')

@section('content')
    <!-- Page Header -->
    <div class="content-header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-0">Gestion des Essences</h1>
                <p class="text-muted mb-0">Administrez les essences forestières</p>
            </div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createEssenceModal">
                <i class="material-icons me-2">add</i>
                Nouvelle Essence
            </button>
        </div>
    </div>

    <!-- Statistics Grid -->
    <!-- <x-stats-grid :stats="[
        [
            'title' => 'Total Essences',
            'value' => $essences->total(),
            'icon' => 'material-icons',
            'color' => 'purple'
        ],
        [
            'title' => 'Essences Actives',
            'value' => $essences->where('deleted_at', null)->count(),
            'icon' => 'material-icons',
            'color' => 'green'
        ],
        [
            'title' => 'Ajoutées ce mois',
            'value' => $essences->where('created_at', '>=', now()->subDays(30))->count(),
            'icon' => 'material-icons',
            'color' => 'orange'
        ],
        [
            'title' => 'Types Uniques',
            'value' => $essences->unique('essence')->count(),
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
        <form method="GET" action="{{ route('settings.essences') }}" class="row g-3">
            <div class="col-md-4">
                <x-form.input 
                    name="search" 
                    label="Rechercher" 
                    placeholder="Nom de l'essence..."
                    value="{{ request('search') }}"
                    icon="search"
                />
            </div>
            <div class="col-md-3">
                <x-form.select 
                    name="sort" 
                    label="Trier par"
                    :options="[
                        '' => 'Sélectionner',
                        'essence' => 'Nom',
                        'created_at' => 'Date de création',
                        'updated_at' => 'Date de modification'
                    ]"
                    selected="{{ request('sort') }}"
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
    @php
        $pagination = $essences->appends(request()->query())->links();
    @endphp
    
    <x-data-table
        :headers="['ID', 'Nom de l\'Essence', 'Statut', 'Créé le', 'Actions']"
        :total="$essences->total()"
        :pagination="$pagination"
        emptyMessage="Aucune essence trouvée"
        emptySubmessage="Commencez par ajouter votre première essence"
    >
        @foreach($essences as $essence)
            <tr class="table-row">
                <td class="table-cell">#{{ $essence->id }}</td>
                <td class="table-cell">
                    <span class="fw-medium">{{ $essence->essence }}</span>
                </td>
                <td class="table-cell">
                    @if($essence->deleted_at)
                        <span class="badge bg-danger">Supprimée</span>
                    @else
                        <span class="badge bg-success">Active</span>
                    @endif
                </td>
                <td class="table-cell">
                    <small class="text-muted">{{ $essence->created_at?->format('d/m/Y H:i') }}</small>
                </td>
                <td class="table-cell">
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="editEssence({{ $essence->id }})">
                            <i class="material-icons">edit</i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteEssence({{ $essence->id }})">
                            <i class="material-icons">delete</i>
                        </button>
                    </div>
                </td>
            </tr>
        @endforeach
    </x-data-table>

    <!-- Import/Export Section -->
    <x-import-export-section
        title="Import/Export des Essences"
        :exportRoute="route('settings.essences.export')"
        :importRoute="route('excel.import.essences')"
        exportLabel="Exporter les Essences"
        importLabel="Importer des Essences"
        exportDescription="Télécharger la liste des essences au format Excel"
        importDescription="Importer des essences depuis un fichier Excel"
    />

    <!-- Create Essence Modal -->
    <div class="modal fade" id="createEssenceModal" tabindex="-1" aria-labelledby="createEssenceModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createEssenceModalLabel">Nouvelle Essence</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('settings.essences.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <x-form.input 
                                    name="essence" 
                                    label="Nom de l'essence" 
                                    placeholder="Ex: Chêne, Pin, Eucalyptus..."
                                    required
                                    icon="forest"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="material-icons me-2">add</i>
                            Créer l'essence
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function editEssence(id) {
        // Implement edit functionality
        console.log('Edit essence:', id);
        // You can open a modal or redirect to edit page
    }

    function deleteEssence(id) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cette essence ?')) {
            // Implement delete functionality
            console.log('Delete essence:', id);
            // You can make an AJAX call or redirect to delete route
        }
    }
</script>
@endpush 