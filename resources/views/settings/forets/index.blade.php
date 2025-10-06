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
            <div class="d-flex gap-2">
                <a href="{{ route('settings.forets.map') }}" class="btn btn-info">
                    <i class="fas fa-map-marked-alt me-2"></i>
                    Carte des Forêts
                </a>
                <a href="{{ route('settings.forets.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    Nouvelle Forêt
                </a>
            </div>
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
                <i class="fas fa-download me-2"></i>Import/Export des Forêts
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="d-grid">
                        <a href="{{ route('settings.forets.export') }}" class="btn btn-success">
                            <i class="fas fa-download me-2"></i>Exporter les Forêts
                        </a>
                        <small class="text-muted mt-1">Télécharger la liste des forêts au format Excel</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-grid">
                        <a href="{{ route('excel.import.forets') }}" class="btn btn-info">
                            <i class="fas fa-upload me-2"></i>Importer des Forêts
                        </a>
                        <small class="text-muted mt-1">Importer des forêts depuis un fichier Excel</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Simple Data Display -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-list me-2"></i>Liste des Forêts
            </h5>
        </div>
        <div class="card-body">
            @if($forets->count() > 0)
                @php
                    $headers = ['ID', 'Nom de la Forêt', 'Statut', 'Créé le', 'Actions'];
                    $rows = [];
                @endphp
                @foreach($forets as $foret)
                    @php
                        $statusBadge = $foret->deleted_at
                            ? '<span class="badge bg-danger">Supprimée</span>'
                            : '<span class="badge bg-success">Active</span>';
                        $actionsHtml = '<div class="d-flex gap-2">'
                            . '<a href="' . e(route('settings.forets.edit', $foret)) . '" class="btn btn-sm btn-warning" title="Modifier">'
                            . '<i class="fas fa-edit"></i>'
                            . '</a>'
                            . '<form action="' . e(route('settings.forets.destroy', $foret)) . '" method="POST" class="d-inline" onsubmit="return confirm(\'Êtes-vous sûr de vouloir supprimer cette forêt ?\')">'
                            . csrf_field() . method_field('DELETE')
                            . '<button type="submit" class="btn btn-sm btn-danger" title="Supprimer">'
                            . '<i class="fas fa-trash"></i>'
                            . '</button>'
                            . '</form>'
                            . '</div>';
                        $rows[] = [
                            '<span class="badge bg-secondary">' . e($foret->id) . '</span>',
                            e($foret->foret),
                            $statusBadge,
                            '<small class="text-muted">' . e($foret->created_at?->format('d/m/Y H:i') ?? 'N/A') . '</small>',
                            $actionsHtml,
                        ];
                    @endphp
                @endforeach
                <x-data-table :headers="$headers" :rows="$rows" :pagination="$forets->appends(request()->query())->links()" />
            @else
                <div class="text-center py-4">
                    <i class="fas fa-tree text-muted" style="font-size: 3rem;"></i>
                    <p class="h5 mt-3 text-muted">Aucune forêt trouvée</p>
                    <p class="text-muted">Commencez par créer votre première forêt</p>
                    <a href="{{ route('settings.forets.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Créer la Première Forêt
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
