@extends('layouts.app')

@section('title', 'Gestion des Natures de Coupe')

@section('content')
    <!-- Page Header -->
    <div class="content-header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-0">Gestion des Natures de Coupe</h1>
                <p class="text-muted mb-0">Administrez les types de coupe forestière</p>
            </div>
            <a href="{{ route('settings.nature-de-coupes.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>
                Nouvelle Nature de Coupe
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
                <i class="fas fa-download me-2"></i>Import/Export des Natures de Coupe
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="d-grid">
                        <a href="{{ route('settings.nature-de-coupes.export') }}" class="btn btn-success">
                            <i class="fas fa-download me-2"></i>Exporter les Natures de Coupe
                        </a>
                        <small class="text-muted mt-1">Télécharger la liste des natures de coupe au format Excel</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-grid">
                        <a href="{{ route('excel.import.nature-de-coupes') }}" class="btn btn-info">
                            <i class="fas fa-upload me-2"></i>Importer des Natures de Coupe
                        </a>
                        <small class="text-muted mt-1">Importer des natures de coupe depuis un fichier Excel</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Simple Data Display -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-list me-2"></i>Liste des Natures de Coupe
            </h5>
        </div>
        <div class="card-body">
            @if($natureDeCoupes->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nature de Coupe</th>
                                <th>Statut</th>
                                <th>Créé le</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($natureDeCoupes as $nature)
                                <tr>
                                    <td>{{ $nature->id }}</td>
                                    <td>{{ $nature->nature_de_coupe }}</td>
                                    <td>
                                        @if($nature->deleted_at)
                                            <span class="badge bg-danger">Supprimée</span>
                                        @else
                                            <span class="badge bg-success">Active</span>
                                        @endif
                                    </td>
                                    <td>{{ $nature->created_at?->format('d/m/Y H:i') ?? 'N/A' }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('settings.nature-de-coupes.edit', $nature) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('settings.nature-de-coupes.destroy', $nature) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette nature de coupe ?')">
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
                
                @if($natureDeCoupes->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $natureDeCoupes->appends(request()->query())->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-4">
                    <i class="fas fa-cut text-muted" style="font-size: 3rem;"></i>
                    <p class="h5 mt-3 text-muted">Aucune nature de coupe trouvée</p>
                    <p class="text-muted">Commencez par créer votre première nature de coupe</p>
                    <a href="{{ route('settings.nature-de-coupes.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Créer la Première Nature de Coupe
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection 