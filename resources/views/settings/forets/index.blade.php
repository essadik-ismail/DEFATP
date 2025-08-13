@extends('layouts.app')

@section('title', 'Gestion des Forêts')

@section('content')
    <!-- Page Header -->
    <div class="content-header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-0">Gestion des Forêts</h1>
                <p class="text-muted mb-0">Administrez les forêts et leurs informations géographiques</p>
            </div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createForetModal">
                <i class="material-icons me-2">add</i>
                Nouvelle Forêt
            </button>
        </div>
    </div>

    <!-- Statistics Grid -->
    <!-- <x-stats-grid :stats="[
        [
            'title' => 'Total Forêts',
            'value' => $stats['total'] ?? 0,
            'icon' => 'material-icons',
            'color' => 'purple'
        ],
        [
            'title' => 'Forêts Actives',
            'value' => $stats['active'] ?? 0,
            'icon' => 'material-icons',
            'color' => 'green'
        ],
        [
            'title' => 'Ajoutées ce mois',
            'value' => $stats['recent'] ?? 0,
            'icon' => 'material-icons',
            'color' => 'orange'
        ],
        [
            'title' => 'Types Uniques',
            'value' => $stats['unique'] ?? 0,
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
        <form method="GET" action="{{ route('settings.forets') }}" class="row g-3">
            <div class="col-md-3">
                <x-form.input 
                    name="search" 
                    label="Rechercher" 
                    placeholder="Nom de la forêt..."
                    value="{{ request('search') }}"
                    icon="search"
                />
            </div>
            <div class="col-md-3">
                <x-form.select 
                    name="province" 
                    label="Province"
                    :options="[
                        '' => 'Toutes les provinces',
                        'Casablanca-Settat' => 'Casablanca-Settat',
                        'Rabat-Salé-Kénitra' => 'Rabat-Salé-Kénitra',
                        'Marrakech-Safi' => 'Marrakech-Safi',
                        'Fès-Meknès' => 'Fès-Meknès',
                        'Tanger-Tétouan-Al Hoceima' => 'Tanger-Tétouan-Al Hoceima',
                        'Oriental' => 'Oriental',
                        'Souss-Massa' => 'Souss-Massa',
                        'Drâa-Tafilalet' => 'Drâa-Tafilalet',
                        'Béni Mellal-Khénifra' => 'Béni Mellal-Khénifra',
                        'Guelmim-Oued Noun' => 'Guelmim-Oued Noun',
                        'Laâyoune-Sakia El Hamra' => 'Laâyoune-Sakia El Hamra',
                        'Dakhla-Oued Ed-Dahab' => 'Dakhla-Oued Ed-Dahab'
                    ]"
                    selected="{{ request('province') }}"
                />
            </div>
            <div class="col-md-2">
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
        :headers="['ID', 'Forêt', 'Province', 'Coordonnées', 'Statut', 'Créé le', 'Actions']"
        :total="$forets->total()"
        :pagination="$forets->appends(request()->query())->links()"
        emptyMessage="Aucune forêt trouvée"
        emptySubmessage="Commencez par ajouter votre première forêt"
    >
        @foreach($forets as $foret)
            <tr class="table-row">
                <td class="table-cell">#{{ $foret->id }}</td>
                <td class="table-cell">
                    <span class="fw-medium">{{ $foret->foret }}</span>
                </td>
                <td class="table-cell">
                    <span class="badge bg-secondary">{{ $foret->province }}</span>
                </td>
                <td class="table-cell">
                    @if($foret->lat && $foret->log)
                        <small class="text-muted">{{ $foret->lat }}, {{ $foret->log }}</small>
                    @else
                        <span class="text-muted">Non définies</span>
                    @endif
                </td>
                <td class="table-cell">
                    @if($foret->is_deleted)
                        <span class="badge bg-danger">Supprimée</span>
                    @else
                        <span class="badge bg-success">Active</span>
                    @endif
                </td>
                <td class="table-cell">
                    <small class="text-muted">{{ $foret->created_at?->format('d/m/Y H:i') }}</small>
                </td>
                <td class="table-cell">
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="editForet({{ $foret->id }})">
                            <i class="material-icons">edit</i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteForet({{ $foret->id }})">
                            <i class="material-icons">delete</i>
                        </button>
                    </div>
                </td>
            </tr>
        @endforeach
    </x-data-table>

    <!-- Import/Export Section -->
    <x-import-export-section
        title="Import/Export des Forêts"
        :exportRoute="route('settings.forets.export')"
        :importRoute="route('excel.import.forets')"
        exportLabel="Exporter les Forêts"
        importLabel="Importer des Forêts"
        exportDescription="Télécharger la liste des forêts au format Excel"
        importDescription="Importer des forêts depuis un fichier Excel"
    />

    <!-- Create Foret Modal -->
    <div class="modal fade" id="createForetModal" tabindex="-1" aria-labelledby="createForetModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createForetModalLabel">Nouvelle Forêt</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('settings.forets.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <x-form.input 
                                    name="foret" 
                                    label="Nom de la forêt" 
                                    placeholder="Ex: Forêt de la Mamora"
                                    required
                                />
                            </div>
                            <div class="col-12">
                                <x-form.select 
                                    name="province" 
                                    label="Province"
                                    :options="[
                                        '' => 'Sélectionner une province',
                                        'Casablanca-Settat' => 'Casablanca-Settat',
                                        'Rabat-Salé-Kénitra' => 'Rabat-Salé-Kénitra',
                                        'Marrakech-Safi' => 'Marrakech-Safi',
                                        'Fès-Meknès' => 'Fès-Meknès',
                                        'Tanger-Tétouan-Al Hoceima' => 'Tanger-Tétouan-Al Hoceima',
                                        'Oriental' => 'Oriental',
                                        'Souss-Massa' => 'Souss-Massa',
                                        'Drâa-Tafilalet' => 'Drâa-Tafilalet',
                                        'Béni Mellal-Khénifra' => 'Béni Mellal-Khénifra',
                                        'Guelmim-Oued Noun' => 'Guelmim-Oued Noun',
                                        'Laâyoune-Sakia El Hamra' => 'Laâyoune-Sakia El Hamra',
                                        'Dakhla-Oued Ed-Dahab' => 'Dakhla-Oued Ed-Dahab'
                                    ]"
                                    required
                                />
                            </div>
                            <div class="col-6">
                                <x-form.input 
                                    name="lat" 
                                    label="Latitude" 
                                    type="number"
                                    step="any"
                                    placeholder="Ex: 34.0209"
                                />
                            </div>
                            <div class="col-6">
                                <x-form.input 
                                    name="log" 
                                    label="Longitude" 
                                    type="number"
                                    step="any"
                                    placeholder="Ex: -6.8416"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="material-icons me-2">add</i>
                            Créer la forêt
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function editForet(id) {
        // Implement edit functionality
        console.log('Edit foret:', id);
        // You can open a modal or redirect to edit page
    }

    function deleteForet(id) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cette forêt ?')) {
            // Implement delete functionality
            console.log('Delete foret:', id);
            // You can make an AJAX call or redirect to delete route
        }
    }
</script>
@endpush
