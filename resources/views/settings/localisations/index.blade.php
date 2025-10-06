@extends('layouts.app')

@section('title', 'Gestion des Localisations')

@section('content')
    <!-- Page Header -->
    <div class="content-header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-0">Gestion des Localisations</h1>
                <p class="text-muted mb-0">Administrez les zones géographiques forestières</p>
            </div>
            <a href="{{ route('settings.localisations.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>
                Nouvelle Localisation
            </a>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Import/Export Section -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-download me-2"></i>Import/Export des Localisations
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="d-grid">
                        <a href="{{ route('settings.localisations.export') }}" class="btn btn-success">
                            <i class="fas fa-download me-2"></i>Exporter les Localisations
                        </a>
                        <small class="text-muted mt-1">Télécharger la liste des localisations au format Excel</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-grid">
                        <a href="{{ route('excel.import.localisations') }}" class="btn btn-info">
                            <i class="fas fa-upload me-2"></i>Importer des Localisations
                        </a>
                        <small class="text-muted mt-1">Importer des localisations depuis un fichier Excel</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Simple Data Display -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-list me-2"></i>Liste des Localisations
            </h5>
        </div>
        <div class="card-body">
            @if($localisations->count() > 0)
                @php
                    $headers = ['ID', 'Code', 'DRANEF', 'Entité', 'Statut', 'Créé le', 'Actions'];
                    $rows = [];
                @endphp
                @foreach($localisations as $localisation)
                    @php
                        $statusBadge = $localisation->deleted_at
                            ? '<span class="badge bg-danger">Supprimée</span>'
                            : '<span class="badge bg-success">Active</span>';
                        $actionsHtml = '<div class="d-flex gap-2">'
                            . '<a href="' . e(route('settings.localisations.edit', $localisation)) . '" class="btn btn-sm btn-warning" title="Modifier">'
                            . '<i class="fas fa-edit"></i>'
                            . '</a>'
                            . '<form action="' . e(route('settings.localisations.destroy', $localisation)) . '" method="POST" class="d-inline" onsubmit="return confirm(\'Êtes-vous sûr de vouloir supprimer cette localisation ?\')">'
                            . csrf_field() . method_field('DELETE')
                            . '<button type="submit" class="btn btn-sm btn-danger" title="Supprimer">'
                            . '<i class="fas fa-trash"></i>'
                            . '</button>'
                            . '</form>'
                            . '</div>';
                        $rows[] = [
                            '<span class="badge bg-secondary">' . e($localisation->id) . '</span>',
                            e($localisation->CODE),
                            e($localisation->DRANEF),
                            e($localisation->ENTITE),
                            $statusBadge,
                            '<small class="text-muted">' . e($localisation->created_at?->format('d/m/Y H:i') ?? 'N/A') . '</small>',
                            $actionsHtml,
                        ];
                    @endphp
                @endforeach
                <x-data-table :headers="$headers" :rows="$rows" :pagination="$localisations->appends(request()->query())->links()" />
            @else
                <div class="text-center py-4">
                    <i class="fas fa-map-marker-alt text-muted" style="font-size: 3rem;"></i>
                    <p class="h5 mt-3 text-muted">Aucune localisation trouvée</p>
                    <p class="text-muted">Commencez par créer votre première localisation</p>
                    <a href="{{ route('settings.localisations.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Créer la Première Localisation
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
