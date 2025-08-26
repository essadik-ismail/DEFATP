@extends('layouts.app')

@section('title', 'Articles Forestiers - SylvaNet')

@section('content')

<!-- Main Content Area -->
<div class="main-content">

    <!-- Category Cards (Top Row) -->
    <div class="category-cards">
        <div class="category-card">
            <div class="category-icon design">
                <i class="fas fa-tree"></i>
            </div>
            <div class="category-info">
                <h3>Forêts</h3>
                <p>{{ \App\Models\Foret::count() }} forêts</p>
                <span class="category-size">{{ \App\Models\Foret::count() * 2 }} ha</span>
            </div>
            <div class="category-options">
                <i class="fas fa-ellipsis-v"></i>
            </div>
        </div>
        
        <div class="category-card">
            <div class="category-icon documents">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="category-info">
                <h3>Articles</h3>
                <p>{{ $articles->total() }} articles</p>
                <span class="category-size">{{ number_format($articles->sum('bo_m3') + $articles->sum('bi_m3'), 2) }} m³</span>
            </div>
            <div class="category-options">
                <i class="fas fa-ellipsis-v"></i>
            </div>
        </div>
        
        <div class="category-card">
            <div class="category-icon music">
                <i class="fas fa-leaf"></i>
            </div>
            <div class="category-info">
                <h3>Essences</h3>
                <p>{{ \App\Models\Essence::count() }} essences</p>
                <span class="category-size">{{ \App\Models\Essence::count() * 5 }} types</span>
            </div>
            <div class="category-options">
                <i class="fas fa-ellipsis-v"></i>
            </div>
        </div>
        
        <div class="category-card">
            <div class="category-icon images">
                <i class="fas fa-map-marker-alt"></i>
            </div>
            <div class="category-info">
                <h3>Localisations</h3>
                <p>{{ \App\Models\Localisation::count() }} zones</p>
                <span class="category-size">{{ \App\Models\Localisation::count() }} codes</span>
            </div>
            <div class="category-options">
                <i class="fas fa-ellipsis-v"></i>
            </div>
        </div>
    </div>

    <!-- Articles Data Table -->
    <div class="entity-data-card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-file-alt me-2 text-primary"></i>Articles
            </h3>
            <a href="{{ route('articles.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-2"></i>Nouvel Article
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Année</th>
                            <th>Numéro</th>
                            <th>Date d'Adjudication</th>
                            <th>Forêt</th>
                            <th>Essence</th>
                            <th>Localisation</th>
                            <th>Prix de Retrait</th>
                            <th>Prix de Vente</th>
                            <th>Type</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($articles as $article)
                            <tr>
                                <td>{{ $article->id }}</td>
                                <td>
                                    <span class="badge bg-primary">{{ $article->annee ?? '-' }}</span>
                                </td>
                                <td>
                                    @if($article->numero)
                                        <span class="badge bg-secondary">{{ $article->numero }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($article->date_adjudication)
                                        {{ $article->date_adjudication->format('d/m/Y') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($article->foret)
                                        {{ $article->foret->foret }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($article->essence)
                                        {{ $article->essence->essence }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($article->localisation)
                                        <span title="{{ $article->localisation->CODE }}">
                                            {{ Str::limit($article->localisation->CODE, 20) }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($article->prix_de_retrait)
                                        <span class="badge bg-warning text-dark">
                                            {{ number_format($article->prix_de_retrait, 2) }} DH
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($article->prix_vente)
                                        <span class="badge bg-success">
                                            {{ number_format($article->prix_vente, 2) }} DH
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($article->type)
                                        <span class="badge {{ $article->type == 'appel_doffre' ? 'bg-info' : 'bg-primary' }}">
                                            {{ $article->type == 'appel_doffre' ? 'Appel d\'Offre' : 'Adjudication' }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($article->is_validated)
                                        <span class="badge bg-success">
                                            <i class="fas fa-check me-1"></i>Validé
                                        </span>
                                    @else
                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-clock me-1"></i>En attente
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('articles.show', $article) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('articles.edit', $article) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('articles.destroy', $article) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-file-alt text-4xl mb-2 d-block"></i>
                                            <p class="h5 mb-2">Aucun article créé</p>
                                            <p class="text-muted mb-3">Commencez par créer votre premier article forestier</p>
                                            <a href="{{ route('articles.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus me-2"></i>Créer le Premier Article
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($articles->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $articles->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Create Cards -->
    <!-- <div class="quick-create-section">
        <h2 class="section-title">Création Rapide</h2>
        <div class="quick-create-grid">
            <a href="{{ route('settings.essences') }}" class="quick-create-card">
                <div class="quick-create-icon essence">
                    <i class="fas fa-leaf"></i>
                </div>
                <h4>Nouvelle Essence</h4>
                <p>Ajouter un type d'arbre</p>
            </a>
            
            <a href="{{ route('settings.forets') }}" class="quick-create-card">
                <div class="quick-create-icon foret">
                    <i class="fas fa-tree"></i>
                </div>
                <h4>Nouvelle Forêt</h4>
                <p>Ajouter une zone forestière</p>
            </a>
            
            <a href="{{ route('settings.localisations') }}" class="quick-create-card">
                <div class="quick-create-icon localisation">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h4>Nouvelle Localisation</h4>
                <p>Ajouter une zone géographique</p>
            </a>
            
            <a href="{{ route('settings.situation-administratives') }}" class="quick-create-card">
                <div class="quick-create-icon situation">
                    <i class="fas fa-building"></i>
                </div>
                <h4>Nouvelle Situation</h4>
                <p>Ajouter une situation administrative</p>
            </a>
            
            <a href="{{ route('settings.exploitants') }}" class="quick-create-card">
                <div class="quick-create-icon exploitant">
                    <i class="fas fa-user-tie"></i>
                </div>
                <h4>Nouvel Exploitant</h4>
                <p>Ajouter un opérateur</p>
            </a>
            
            <a href="{{ route('settings.nature-de-coupes') }}" class="quick-create-card">
                <div class="quick-create-icon nature">
                    <i class="fas fa-cut"></i>
                </div>
                <h4>Nouvelle Nature</h4>
                <p>Ajouter un type de coupe</p>
            </a>
        </div>
    </div> -->

    <!-- Data Tables for All Entities -->
    <div class="entities-data-section">
        <h2 class="section-title">Données des Entités</h2>
        
        <!-- Tabs Section -->
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" id="entitiesTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="essences-tab" data-bs-toggle="tab" data-bs-target="#essences" type="button" role="tab" aria-controls="essences" aria-selected="true">
                            <i class="fas fa-leaf me-2"></i>Essences
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="forets-tab" data-bs-toggle="tab" data-bs-target="#forets" type="button" role="tab" aria-controls="forets" aria-selected="false">
                            <i class="fas fa-tree me-2"></i>Forêts
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="localisations-tab" data-bs-toggle="tab" data-bs-target="#localisations" type="button" role="tab" aria-controls="localisations" aria-selected="false">
                            <i class="fas fa-map-marker-alt me-2"></i>Localisations
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="situations-tab" data-bs-toggle="tab" data-bs-target="#situations" type="button" role="tab" aria-controls="situations" aria-selected="false">
                            <i class="fas fa-building me-2"></i>Situations Administratives
                        </button>
                    </li>
                    <!-- <li class="nav-item" role="presentation">
                        <button class="nav-link" id="exploitants-tab" data-bs-toggle="tab" data-bs-target="#exploitants" type="button" role="tab" aria-controls="exploitants" aria-selected="false">
                            <i class="fas fa-user-tie me-2"></i>Exploitants
                        </button>
                    </li> -->
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="natures-coupe-tab" data-bs-toggle="tab" data-bs-target="#natures-coupe" type="button" role="tab" aria-controls="natures-coupe" aria-selected="false">
                            <i class="fas fa-cut me-2"></i>Natures de Coupe
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="entitiesTabContent">
                    <!-- Essences Tab -->
                    <div class="tab-pane fade show active" id="essences" role="tabpanel" aria-labelledby="essences-tab">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5>Liste des Essences</h5>
                            <a href="{{ route('settings.essences.create') }}" class="btn btn-success btn-sm">
                                <i class="fas fa-plus me-2"></i>Nouvelle Essence
                            </a>
                        </div>
                        
                        <!-- Search Box -->
                        <div class="mb-3">
                            <form method="GET" action="{{ route('articles.index') }}" class="d-flex gap-2">
                                <input type="text" name="essence_search" class="form-control" 
                                       placeholder="Rechercher une essence..." 
                                       value="{{ request('essence_search') }}">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                                @if(request('essence_search'))
                                    <a href="{{ route('articles.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                            </form>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nom de l'Essence</th>
                                        <th>Date de Création</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($essences as $essence)
                                    <tr>
                                        <td>{{ $essence->id }}</td>
                                        <td>{{ $essence->essence }}</td>
                                        <td>{{ $essence->created_at->format('d/m/Y') }}</td>
                                        <td>
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
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">
                                            <i class="fas fa-leaf fa-2x mb-2"></i>
                                            <p>Aucune essence trouvée</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        @if($essences->hasPages())
                            <div class="d-flex justify-content-center mt-3">
                                {{ $essences->appends(request()->query())->links() }}
                            </div>
                        @endif
                    </div>

                    <!-- Forêts Tab -->
                    <div class="tab-pane fade" id="forets" role="tabpanel" aria-labelledby="forets-tab">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5>Liste des Forêts</h5>
                            <a href="{{ route('settings.forets.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus me-2"></i>Nouvelle Forêt
                            </a>
                        </div>
                        
                        <!-- Search Box -->
                        <div class="mb-3">
                            <form method="GET" action="{{ route('articles.index') }}" class="d-flex gap-2">
                                <input type="text" name="foret_search" class="form-control" 
                                       placeholder="Rechercher une forêt..." 
                                       value="{{ request('foret_search') }}">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                                @if(request('foret_search'))
                                    <a href="{{ route('articles.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                            </form>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nom de la Forêt</th>
                                        <th>Date de Création</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($forets as $foret)
                                    <tr>
                                        <td>{{ $foret->id }}</td>
                                        <td>{{ $foret->foret }}</td>
                                        <td>{{ $foret->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            <a href="{{ route('settings.forets.edit', $foret) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('settings.forets.destroy', $foret) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette forêt ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">
                                            <i class="fas fa-tree fa-2x mb-2"></i>
                                            <p>Aucune forêt trouvée</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        @if($forets->hasPages())
                            <div class="d-flex justify-content-center mt-3">
                                {{ $forets->appends(request()->query())->links() }}
                            </div>
                        @endif
                    </div>

                    <!-- Localisations Tab -->
                    <div class="tab-pane fade" id="localisations" role="tabpanel" aria-labelledby="localisations-tab">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5>Liste des Localisations</h5>
                            <div>
                                <a href="{{ route('settings.localisations.create') }}" class="btn btn-info btn-sm me-2">
                                    <i class="fas fa-plus me-2"></i>Nouvelle Localisation
                                </a>
                                <a href="{{ route('settings.localisations.export') }}" class="btn btn-info btn-sm me-2">
                                    <i class="fas fa-download me-2"></i>Exporter
                                </a>
                                <button class="btn btn-success btn-sm" onclick="document.getElementById('importLocalisationForm').click()">
                                    <i class="fas fa-upload me-2"></i>Importer
                                </button>
                                <input type="file" id="importLocalisationForm" style="display: none;" accept=".xlsx,.xls,.csv" onchange="importLocalisations(this)">
                            </div>
                        </div>
                        
                        <!-- Search Box -->
                        <div class="mb-3">
                            <form method="GET" action="{{ route('articles.index') }}" class="d-flex gap-2">
                                <input type="text" name="localisation_search" class="form-control" 
                                       placeholder="Rechercher par Code, DRANEF ou Entité..." 
                                       value="{{ request('localisation_search') }}">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                                @if(request('localisation_search'))
                                    <a href="{{ route('articles.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                            </form>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Code</th>
                                        <th>DRANEF</th>
                                        <th>Entité</th>
                                        <th>Date de Création</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($localisations as $localisation)
                                    <tr>
                                        <td>{{ $localisation->id }}</td>
                                        <td>{{ $localisation->CODE }}</td>
                                        <td>{{ $localisation->DRANEF }}</td>
                                        <td>{{ $localisation->ENTITE }}</td>
                                        <td>{{ $localisation->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">
                                            <i class="fas fa-map-marker-alt fa-2x mb-2"></i>
                                            <p>Aucune localisation trouvée</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        @if($localisations->hasPages())
                            <div class="d-flex justify-content-center mt-3">
                                {{ $localisations->appends(request()->query())->links() }}
                            </div>
                        @endif
                    </div>

                    <!-- Situations Administratives Tab -->
                    <div class="tab-pane fade" id="situations" role="tabpanel" aria-labelledby="situations-tab">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5>Liste des Situations Administratives</h5>
                            <a href="{{ route('settings.situation-administratives.create') }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-plus me-2"></i>Nouvelle Situation
                            </a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Commune</th>
                                        <th>Province</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(\App\Models\SituationAdministrative::all() as $situation)
                                    <tr>
                                        <td>{{ $situation->id }}</td>
                                        <td>{{ $situation->commune }}</td>
                                        <td>{{ $situation->province }}</td>
                                        <td>
                                            <a href="{{ route('settings.situation-administratives.edit', $situation) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('settings.situation-administratives.destroy', $situation) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette situation administrative ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Exploitants Tab -->
                    <!-- <div class="tab-pane fade" id="exploitants" role="tabpanel" aria-labelledby="exploitants-tab">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5>Liste des Exploitants</h5>
                            <a href="{{ route('settings.exploitants') }}" class="btn btn-danger btn-sm">
                                <i class="fas fa-plus me-2"></i>Nouvel Exploitant
                            </a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nom</th>
                                        <th>Date de Création</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(\App\Models\Exploitant::all() as $exploitant)
                                    <tr>
                                        <td>{{ $exploitant->id }}</td>
                                        <td>{{ $exploitant->nom_complet }}</td>
                                        <td>{{ $exploitant->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            <a href="{{ route('settings.exploitants.edit', $exploitant) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('settings.exploitants.destroy', $exploitant) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet exploitant ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div> -->

                    <!-- Natures de Coupe Tab -->
                    <div class="tab-pane fade" id="natures-coupe" role="tabpanel" aria-labelledby="natures-coupe-tab">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5>Liste des Natures de Coupe</h5>
                            <a href="{{ route('settings.nature-de-coupes.create') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-plus me-2"></i>Nouvelle Nature
                            </a>
                        </div>
                        
                        <!-- Search Box -->
                        <div class="mb-3">
                            <form method="GET" action="{{ route('articles.index') }}" class="d-flex gap-2">
                                <input type="text" name="nature_search" class="form-control" 
                                       placeholder="Rechercher une nature de coupe..." 
                                       value="{{ request('nature_search') }}">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                                @if(request('nature_search'))
                                    <a href="{{ route('articles.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                            </form>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nature</th>
                                        <th>Date de Création</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($natureDeCoupes as $nature)
                                    <tr>
                                        <td>{{ $nature->id }}</td>
                                        <td>{{ $nature->nature_de_coupe }}</td>
                                        <td>{{ $nature->created_at->format('d/m/Y') }}</td>
                                        <td>
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
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">
                                            <i class="fas fa-cut fa-2x mb-2"></i>
                                            <p>Aucune nature de coupe trouvée</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        @if($natureDeCoupes->hasPages())
                            <div class="d-flex justify-content-center mt-3">
                                {{ $natureDeCoupes->appends(request()->query())->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Article Modal -->
<div class="modal fade" id="createArticleModal" tabindex="-1" aria-labelledby="createArticleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createArticleModalLabel">
                    <i class="fas fa-plus me-2"></i>Créer un Nouvel Article
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('articles.store') }}" method="POST" id="createArticleForm">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="annee" class="form-label">Année *</label>
                                <input type="number" class="form-control" id="annee" name="annee" value="{{ date('Y') }}" min="2000" max="2100" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="numero" class="form-label">Numéro d'Article *</label>
                                <input type="text" class="form-control" id="numero" name="numero" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="date_adjudication" class="form-label">Date d'Adjudication *</label>
                                <input type="date" class="form-control" id="date_adjudication" name="date_adjudication" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="parcelle" class="form-label">Parcelle</label>
                                <input type="number" class="form-control" id="parcelle" name="parcelle" min="0">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="foret_id" class="form-label">Forêt *</label>
                                <select class="form-select" id="foret_id" name="foret_id" required>
                                    <option value="">Sélectionner une forêt</option>
                                    @foreach(\App\Models\Foret::all() as $foret)
                                        <option value="{{ $foret->id }}">{{ $foret->foret }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="essence_id" class="form-label">Essence *</label>
                                <select class="form-select" id="essence_id" name="essence_id" required>
                                    <option value="">Sélectionner une essence</option>
                                    @foreach(\App\Models\Essence::all() as $essence)
                                        <option value="{{ $essence->id }}">{{ $essence->essence }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="localisation_id" class="form-label">Localisation *</label>
                                <select class="form-select" id="localisation_id" name="localisation_id" required>
                                    <option value="">Sélectionner une localisation</option>
                                    @foreach(\App\Models\Localisation::all() as $localisation)
                                        <option value="{{ $localisation->id }}">{{ $localisation->CODE }} - {{ $localisation->DRANEF }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="situation_administrative_id" class="form-label">Situation Administrative *</label>
                                <select class="form-select" id="situation_administrative_id" name="situation_administrative_id" required>
                                    <option value="">Sélectionner une situation</option>
                                    @foreach(\App\Models\SituationAdministrative::all() as $situation)
                                        <option value="{{ $situation->id }}">{{ $situation->commune }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exploitant_id" class="form-label">Exploitant *</label>
                                <select class="form-select" id="exploitant_id" name="exploitant_id" required>
                                    <option value="">Sélectionner un exploitant</option>
                                    @foreach(\App\Models\Exploitant::all() as $exploitant)
                                        <option value="{{ $exploitant->id }}">{{ $exploitant->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nature_de_coupe_id" class="form-label">Nature de Coupe *</label>
                                <select class="form-select" id="nature_de_coupe_id" name="nature_de_coupe_id" required>
                                    <option value="">Sélectionner une nature</option>
                                    @foreach(\App\Models\NatureDeCoupe::all() as $nature)
                                        <option value="{{ $nature->id }}">{{ $nature->nature }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="bo_m3" class="form-label">BO (m³)</label>
                                <input type="number" class="form-control" id="bo_m3" name="bo_m3" step="0.01" min="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="bi_m3" class="form-label">BI (m³)</label>
                                <input type="number" class="form-control" id="bi_m3" name="bi_m3" step="0.01" min="0">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="prix_de_retrait" class="form-label">Prix de Retrait (DH) *</label>
                                <input type="number" class="form-control" id="prix_de_retrait" name="prix_de_retrait" step="0.01" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="prix_vente" class="form-label">Prix de Vente (DH) *</label>
                                <input type="number" class="form-control" id="prix_vente" name="prix_vente" step="0.01" min="0" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="type" class="form-label">Type *</label>
                                <select class="form-select" id="type" name="type" required>
                                    <option value="">Sélectionner le type</option>
                                    <option value="adjudication">Adjudication</option>
                                    <option value="appel_doffre">Appel d'Offre</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="bf_st" class="form-label">BF/ST</label>
                                <input type="number" class="form-control" id="bf_st" name="bf_st" min="0">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="observations" class="form-label">Observations</label>
                                <textarea class="form-control" id="observations" name="observations" rows="3"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="is_validated" id="is_validated" value="1">
                        <label class="form-check-label" for="is_validated">
                            Article validé
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" form="createArticleForm" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Créer l'Article
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Create Essence Modal -->
<div class="modal fade" id="createEssenceModal" tabindex="-1" aria-labelledby="createEssenceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createEssenceModalLabel">
                    <i class="fas fa-leaf me-2"></i>Nouvelle Essence
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createEssenceForm">
                    <div class="mb-3">
                        <label for="essence_name" class="form-label">Nom de l'Essence *</label>
                        <input type="text" class="form-control" id="essence_name" name="essence" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" form="createEssenceForm" class="btn btn-success">
                    <i class="fas fa-save me-2"></i>Créer
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Create Foret Modal -->
<div class="modal fade" id="createForetModal" tabindex="-1" aria-labelledby="createForetModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createForetModalLabel">
                    <i class="fas fa-tree me-2"></i>Nouvelle Forêt
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createForetForm">
                    <div class="mb-3">
                        <label for="foret_name" class="form-label">Nom de la Forêt *</label>
                        <input type="text" class="form-control" id="foret_name" name="foret" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" form="createForetForm" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Créer
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Create Localisation Modal -->
<div class="modal fade" id="createLocalisationModal" tabindex="-1" aria-labelledby="createLocalisationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createLocalisationModalLabel">
                    <i class="fas fa-map-marker-alt me-2"></i>Nouvelle Localisation
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createLocalisationForm">
                    <div class="mb-3">
                        <label for="localisation_code" class="form-label">Code *</label>
                        <input type="text" class="form-control" id="localisation_code" name="CODE" required>
                    </div>
                    <div class="mb-3">
                        <label for="localisation_dranef" class="form-label">DRANEF *</label>
                        <input type="text" class="form-control" id="localisation_dranef" name="DRANEF" required>
                    </div>
                    <div class="mb-3">
                        <label for="localisation_entite" class="form-label">Entité *</label>
                        <input type="text" class="form-control" id="localisation_entite" name="ENTITE" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" form="createLocalisationForm" class="btn btn-info">
                    <i class="fas fa-save me-2"></i>Créer
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Create Situation Administrative Modal -->
<div class="modal fade" id="createSituationModal" tabindex="-1" aria-labelledby="createSituationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createSituationModalLabel">
                    <i class="fas fa-building me-2"></i>Nouvelle Situation Administrative
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createSituationForm">
                    <div class="mb-3">
                        <label for="situation_commune" class="form-label">Commune *</label>
                        <input type="text" class="form-control" id="situation_commune" name="commune" required>
                    </div>
                    <div class="mb-3">
                        <label for="situation_province" class="form-label">Province *</label>
                        <input type="text" class="form-control" id="situation_province" name="province" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" form="createSituationForm" class="btn btn-warning">
                    <i class="fas fa-save me-2"></i>Créer
                </button>
            </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Exploitant Modal -->
<div class="modal fade" id="createExploitantModal" tabindex="-1" aria-labelledby="createExploitantModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createExploitantModalLabel">
                    <i class="fas fa-user-tie me-2"></i>Nouvel Exploitant
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createExploitantForm">
                    <div class="mb-3">
                        <label for="exploitant_nom" class="form-label">Nom Complet *</label>
                        <input type="text" class="form-control" id="exploitant_nom" name="nom_complet" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" form="createExploitantForm" class="btn btn-danger">
                    <i class="fas fa-save me-2"></i>Créer
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Create Nature de Coupe Modal -->
<div class="modal fade" id="createNatureCoupeModal" tabindex="-1" aria-labelledby="createNatureCoupeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createNatureCoupeModalLabel">
                    <i class="fas fa-cut me-2"></i>Nouvelle Nature de Coupe
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createNatureCoupeForm">
                    <div class="mb-3">
                        <label for="nature_coupe_name" class="form-label">Nature de Coupe *</label>
                        <input type="text" class="form-control" id="nature_coupe_name" name="nature_de_coupe" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" form="createNatureCoupeForm" class="btn btn-secondary">
                    <i class="fas fa-save me-2"></i>Créer
                </button>
            </div>
        </div>
    </div>

    <!-- Tabs Section with Related Data Tables -->
    <div class="tabs-section">
        <h2 class="section-title">Données Associées aux Articles</h2>
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" id="relatedDataTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="forets-tab" data-bs-toggle="tab" data-bs-target="#forets" type="button" role="tab" aria-controls="forets" aria-selected="true">
                            <i class="fas fa-tree me-2"></i>Forêts
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="essences-tab" data-bs-toggle="tab" data-bs-target="#essences" type="button" role="tab" aria-controls="essences" aria-selected="false">
                            <i class="fas fa-leaf me-2"></i>Essences
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="localisations-tab" data-bs-toggle="tab" data-bs-target="#localisations" type="button" role="tab" aria-controls="localisations" aria-selected="false">
                            <i class="fas fa-map-marker-alt me-2"></i>Localisations
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="situations-tab" data-bs-toggle="tab" data-bs-target="#situations" type="button" role="tab" aria-controls="situations" aria-selected="false">
                            <i class="fas fa-building me-2"></i>Situations Administratives
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="exploitants-tab" data-bs-toggle="tab" data-bs-target="#exploitants" type="button" role="tab" aria-controls="exploitants" aria-selected="false">
                            <i class="fas fa-user-tie me-2"></i>Exploitants
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="natures-coupe-tab" data-bs-toggle="tab" data-bs-target="#natures-coupe" type="button" role="tab" aria-controls="natures-coupe" aria-selected="false">
                            <i class="fas fa-cut me-2"></i>Natures de Coupe
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="relatedDataTabContent">
                    <!-- Forêts Tab -->
                    <div class="tab-pane fade show active" id="forets" role="tabpanel" aria-labelledby="forets-tab">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5>Liste des Forêts</h5>
                            <a href="{{ route('settings.forets') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus me-2"></i>Nouvelle Forêt
                            </a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nom de la Forêt</th>
                                        <th>Date de Création</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(\App\Models\Foret::all() as $foret)
                                    <tr>
                                        <td>{{ $foret->id }}</td>
                                        <td>{{ $foret->foret }}</td>
                                        <td>{{ $foret->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-warning" onclick="editForet({{ $foret->id }})">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="deleteForet({{ $foret->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Essences Tab -->
                    <div class="tab-pane fade" id="essences" role="tabpanel" aria-labelledby="essences-tab">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5>Liste des Essences</h5>
                            <a href="{{ route('settings.essences') }}" class="btn btn-success btn-sm">
                                <i class="fas fa-plus me-2"></i>Nouvelle Essence
                            </a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nom de l'Essence</th>
                                        <th>Date de Création</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(\App\Models\Essence::all() as $essence)
                                    <tr>
                                        <td>{{ $essence->id }}</td>
                                        <td>{{ $essence->essence }}</td>
                                        <td>{{ $essence->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-warning" onclick="editEssence({{ $essence->id }})">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="deleteEssence({{ $essence->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Localisations Tab -->
                    <div class="tab-pane fade" id="localisations" role="tabpanel" aria-labelledby="localisations-tab">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5>Liste des Localisations</h5>
                            <a href="{{ route('settings.localisations') }}" class="btn btn-info btn-sm">
                                <i class="fas fa-plus me-2"></i>Nouvelle Localisation
                            </a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Code</th>
                                        <th>DRANEF</th>
                                        <th>Entité</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(\App\Models\Localisation::all() as $localisation)
                                    <tr>
                                        <td>{{ $localisation->id }}</td>
                                        <td>{{ $localisation->CODE }}</td>
                                        <td>{{ $localisation->DRANEF }}</td>
                                        <td>{{ $localisation->ENTITE }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-warning" onclick="editLocalisation({{ $localisation->id }})">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="deleteLocalisation({{ $localisation->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Situations Administratives Tab -->
                    <div class="tab-pane fade" id="situations" role="tabpanel" aria-labelledby="situations-tab">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5>Liste des Situations Administratives</h5>
                            <a href="{{ route('settings.situation-administratives') }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-plus me-2"></i>Nouvelle Situation
                            </a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Commune</th>
                                        <th>Province</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(\App\Models\SituationAdministrative::all() as $situation)
                                    <tr>
                                        <td>{{ $situation->id }}</td>
                                        <td>{{ $situation->commune }}</td>
                                        <td>{{ $situation->province }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-warning" onclick="editSituation({{ $situation->id }})">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="deleteSituation({{ $situation->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Exploitants Tab -->
                    <div class="tab-pane fade" id="exploitants" role="tabpanel" aria-labelledby="exploitants-tab">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5>Liste des Exploitants</h5>
                            <a href="{{ route('settings.exploitants') }}" class="btn btn-danger btn-sm">
                                <i class="fas fa-plus me-2"></i>Nouvel Exploitant
                            </a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nom</th>
                                        <th>Date de Création</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(\App\Models\Exploitant::all() as $exploitant)
                                    <tr>
                                        <td>{{ $exploitant->id }}</td>
                                        <td>{{ $exploitant->nom_complet }}</td>
                                        <td>{{ $exploitant->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-warning" onclick="editExploitant({{ $exploitant->id }})">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="deleteExploitant({{ $exploitant->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Natures de Coupe Tab -->
                    <div class="tab-pane fade" id="natures-coupe" role="tabpanel" aria-labelledby="natures-coupe-tab">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5>Liste des Natures de Coupe</h5>
                            <a href="{{ route('settings.nature-de-coupes') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-plus me-2"></i>Nouvelle Nature
                            </a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nature</th>
                                        <th>Date de Création</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(\App\Models\NatureDeCoupe::all() as $nature)
                                    <tr>
                                        <td>{{ $nature->id }}</td>
                                        <td>{{ $nature->nature_de_coupe }}</td>
                                        <td>{{ $nature->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-warning" onclick="editNatureCoupe({{ $nature->id }})">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="deleteNatureCoupe({{ $nature->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, page initialized');
});

// Function to handle localisation import
function importLocalisations(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const formData = new FormData();
        formData.append('file', file);
        
        fetch('{{ route("settings.localisations.import") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Import réussi ! ' + data.message);
                location.reload();
            } else {
                alert('Erreur lors de l\'import : ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur lors de l\'import');
        });
        
        // Reset input
        input.value = '';
    }
}
</script>
@endpush
