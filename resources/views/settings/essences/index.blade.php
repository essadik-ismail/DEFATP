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
            <a href="{{ route('settings.essences.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>
                Nouvelle Essence
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
                <i class="fas fa-download me-2"></i>Import/Export des Essences
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="d-grid">
                        <a href="{{ route('settings.essences.export') }}" class="btn btn-success">
                            <i class="fas fa-download me-2"></i>Exporter les Essences
                        </a>
                        <small class="text-muted mt-1">Télécharger la liste des essences au format Excel</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-grid">
                        <a href="{{ route('excel.import.essences') }}" class="btn btn-info">
                            <i class="fas fa-upload me-2"></i>Importer des Essences
                        </a>
                        <small class="text-muted mt-1">Importer des essences depuis un fichier Excel</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Simple Data Display -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-list me-2"></i>Liste des Essences
            </h5>
        </div>
        <div class="card-body">
            @if($essences->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom de l'Essence</th>
                                <th>Statut</th>
                                <th>Créé le</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($essences as $essence)
                                <tr>
                                    <td>{{ $essence->id }}</td>
                                    <td>{{ $essence->essence }}</td>
                                    <td>
                                        @if($essence->deleted_at)
                                            <span class="badge bg-danger">Supprimée</span>
                                        @else
                                            <span class="badge bg-success">Active</span>
                                        @endif
                                    </td>
                                    <td>{{ $essence->created_at?->format('d/m/Y H:i') ?? 'N/A' }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('settings.essences.edit', $essence) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('settings.essences.destroy', $essence) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette essence ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if($essences->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $essences->appends(request()->query())->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-4">
                    <i class="fas fa-leaf text-muted" style="font-size: 3rem;"></i>
                    <p class="h5 mt-3 text-muted">Aucune essence trouvée</p>
                    <p class="text-muted">Commencez par créer votre première essence</p>
                    <a href="{{ route('settings.essences.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Créer la Première Essence
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection 