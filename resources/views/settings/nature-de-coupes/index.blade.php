@extends('layouts.app')

@section('title', 'Gestion des Natures de Coupes')

@section('content')
    <!-- Page Header -->
    <div class="content-header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-0">Gestion des Natures de Coupes</h1>
                <p class="text-muted mb-0">Administrez les différents types de coupes forestières</p>
            </div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createNatureDeCoupeModal">
                <i class="material-icons me-2">add</i>
                Nouvelle Nature de Coupe
            </button>
        </div>
    </div>

    <!-- Statistics Grid -->
    <!-- <x-stats-grid :stats="[
        [
            'title' => 'Total Natures',
            'value' => $natureDeCoupes->total(),
            'icon' => 'material-icons',
            'color' => 'purple'
        ],
        [
            'title' => 'Natures Actives',
            'value' => $natureDeCoupes->where('deleted_at', null)->count(),
            'icon' => 'material-icons',
            'color' => 'green'
        ],
        [
            'title' => 'Ajoutées ce mois',
            'value' => $natureDeCoupes->where('created_at', '>=', now()->subDays(30))->count(),
            'icon' => 'material-icons',
            'color' => 'orange'
        ],
        [
            'title' => 'Types Uniques',
            'value' => $natureDeCoupes->unique('nature_de_coupe')->count(),
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
        <form method="GET" action="{{ route('settings.nature-de-coupes') }}" class="row g-3">
            <div class="col-md-4">
                <x-form.input 
                    name="search" 
                    label="Rechercher" 
                    placeholder="Nature de coupe..."
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

    @php
        $rows = $natureDeCoupes->map(function($natureDeCoupe) {
            return [
                '#' . $natureDeCoupe->id,
                '<span class="fw-medium">' . $natureDeCoupe->nature_de_coupe . '</span>',
                $natureDeCoupe->deleted_at
                    ? '<span class="badge bg-danger">Supprimée</span>'
                    : '<span class="badge bg-success">Active</span>',
                '<small class="text-muted">' . ($natureDeCoupe->created_at?->format('d/m/Y H:i') ?? 'N/A') . '</small>',
                '<div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="editNatureDeCoupe(' . $natureDeCoupe->id . ')">
                        <i class="material-icons">edit</i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteNatureDeCoupe(' . $natureDeCoupe->id . ')">
                        <i class="material-icons">delete</i>
                    </button>
                </div>'
            ];
        })->toArray();
    @endphp

    <!-- Data Table -->
    <x-data-table
        :headers="['ID', 'Nature de Coupe', 'Statut', 'Créé le', 'Actions']"
        :rows="$rows"
        :pagination="$natureDeCoupes->appends(request()->query())->links()"
        searchable="true"
        exportable="true"
    />

    <!-- Import/Export Section -->
    <x-import-export-section
        title="Import/Export des Natures de Coupes"
        :exportRoute="route('settings.nature-de-coupes.export')"
        :importRoute="route('excel.import.nature-de-coupes')"
        exportLabel="Exporter les Natures de Coupes"
        importLabel="Importer des Natures de Coupes"
        exportDescription="Télécharger la liste des natures de coupes au format Excel"
        importDescription="Importer des natures de coupes depuis un fichier Excel"
    />

    <!-- Create Nature De Coupe Modal -->
    <div class="modal fade" id="createNatureDeCoupeModal" tabindex="-1" aria-labelledby="createNatureDeCoupeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createNatureDeCoupeModalLabel">Nouvelle Nature de Coupe</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('settings.nature-de-coupes.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <x-form.input 
                                    name="nature_de_coupe" 
                                    label="Nature de Coupe" 
                                    placeholder="Ex: Coupe rase, Coupe sélective..."
                                    required
                                    icon="content_cut"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="material-icons me-2">add</i>
                            Créer la nature de coupe
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function editNatureDeCoupe(id) {
        // Implement edit functionality
        console.log('Edit nature de coupe:', id);
        // You can open a modal or redirect to edit page
    }

    function deleteNatureDeCoupe(id) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cette nature de coupe ?')) {
            // Implement delete functionality
            console.log('Delete nature de coupe:', id);
            // You can make an AJAX call or redirect to delete route
        }
    }
</script>
@endpush 