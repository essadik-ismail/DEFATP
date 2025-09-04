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
            <!-- Search and Filter Section -->
            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" class="form-control" id="searchInput" placeholder="Rechercher dans les articles..." autocomplete="off" aria-label="Rechercher dans les articles">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex gap-2">
                        <select class="form-select" id="statusFilter" aria-label="Filtrer par statut">
                            <option value="">Tous les statuts</option>
                            <option value="validated">Validés</option>
                            <option value="pending">En attente</option>
                        </select>
                        <select class="form-select" id="typeFilter" aria-label="Filtrer par type">
                            <option value="">Tous les types</option>
                            <option value="adjudication">Adjudication</option>
                            <option value="appel_doffre">Appel d'Offre</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="d-flex align-items-center gap-2">
                        <label for="perPageSelect" class="form-label mb-0">Articles par page:</label>
                        <select class="form-select form-select-sm" id="perPageSelect" style="width: auto;" onchange="changePerPage()">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                            <option value="15" {{ request('per_page') == 15 ? 'selected' : '' }}>15</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-secondary" onclick="refreshTable()">
                            <i class="fas fa-sync-alt"></i> Actualiser
                        </button>
                        <button type="button" class="btn btn-outline-success" onclick="exportAllArticles()">
                            <i class="fas fa-download"></i> Exporter Tout
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="table-responsive position-relative" style="overflow-x: auto;">
                <div class="table-scroll-indicator"></div>
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
                                    <div class="btn-group" role="group">
                                        <!-- Quick Actions -->
                                        <a href="{{ route('articles.show', $article) }}" class="btn btn-sm btn-info" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('articles.edit', $article) }}" class="btn btn-sm btn-warning" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <!-- Dropdown for Additional Actions -->
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                                <span class="visually-hidden">Toggle Dropdown</span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item" href="#" onclick="duplicateArticle({{ $article->id }})">
                                                        <i class="fas fa-copy text-primary me-2"></i>Dupliquer
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="#" onclick="exportArticle({{ $article->id }})">
                                                        <i class="fas fa-download text-success me-2"></i>Exporter
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="#" onclick="printArticle({{ $article->id }})">
                                                        <i class="fas fa-print text-secondary me-2"></i>Imprimer
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="#" onclick="shareArticle({{ $article->id }})">
                                                        <i class="fas fa-share-alt text-info me-2"></i>Partager
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <a class="dropdown-item" href="#" onclick="toggleValidation({{ $article->id }}, {{ $article->is_validated ? 'false' : 'true' }})">
                                                        <i class="fas {{ $article->is_validated ? 'fa-times-circle text-warning' : 'fa-check-circle text-success' }} me-2"></i>
                                                        {{ $article->is_validated ? 'Dévalider' : 'Valider' }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="#" onclick="archiveArticle({{ $article->id }})">
                                                        <i class="fas fa-archive text-muted me-2"></i>Archiver
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('articles.destroy', $article) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?')">
                                                            <i class="fas fa-trash me-2"></i>Supprimer
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
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
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="pagination-info">
                            <small class="text-muted">
                                Affichage de {{ $articles->firstItem() ?? 0 }} à {{ $articles->lastItem() ?? 0 }} 
                                sur {{ $articles->total() }} articles
                            </small>
                        </div>
                        <div class="pagination-controls">
                            {{ $articles->appends(request()->query())->links() }}
                        </div>
                        <div class="pagination-per-page">
                            <small class="text-muted">
                                {{ $articles->perPage() }} par page
                            </small>
                        </div>
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

@push('styles')
<style>
/* Enhanced table styling */
.table-responsive {
    border-radius: 8px;
    overflow: auto;
    max-height: 70vh;
}

.table-container {
    min-width: 100%;
    overflow: auto;
}

.table {
    margin-bottom: 0;
}

.table th {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
    color: #495057;
    padding: 12px 8px;
}

.table td {
    padding: 12px 8px;
    vertical-align: middle;
}

.table tbody tr:hover {
    background-color: #f8f9fa !important;
    transition: background-color 0.2s ease;
}

/* Dropdown actions styling */
.dropdown-menu {
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    border: 1px solid #dee2e6;
}

.dropdown-item {
    padding: 8px 16px;
    transition: background-color 0.2s ease;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
}

.dropdown-item.text-danger:hover {
    background-color: #f8d7da;
}

/* Search and filter styling */
.input-group-text {
    background-color: #f8f9fa;
    border-color: #dee2e6;
}

.form-select {
    border-radius: 6px;
}

/* Pagination styling */
.pagination-info, .pagination-per-page {
    color: #6c757d;
}

.pagination-controls .pagination {
    margin: 0;
}

.pagination-controls .page-link {
    border-radius: 6px;
    margin: 0 2px;
    border: 1px solid #dee2e6;
}

.pagination-controls .page-link:hover {
    background-color: #e9ecef;
    border-color: #dee2e6;
}

.pagination-controls .page-item.active .page-link {
    background-color: #007bff;
    border-color: #007bff;
}

/* Badge styling */
.badge {
    font-size: 0.75em;
    padding: 4px 8px;
    border-radius: 12px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .pagination-info, .pagination-per-page {
        display: none;
    }
    
    .dropdown-menu {
        position: fixed !important;
        top: 50% !important;
        left: 50% !important;
        transform: translate(-50%, -50%) !important;
        width: 90vw;
        max-width: 300px;
    }
}

/* Table overflow and scrolling enhancements */
.table-responsive::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

.table-responsive::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.table-responsive::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
}

.table-responsive::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

.table-responsive::-webkit-scrollbar-corner {
    background: #f1f1f1;
}

/* Ensure table headers stay visible during scroll */
.table thead th {
    position: sticky;
    top: 0;
    z-index: 10;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

/* Horizontal scroll indicator */
.table-scroll-indicator {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, transparent, #007bff, transparent);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.table-responsive:hover .table-scroll-indicator {
    opacity: 1;
}

/* Enhanced table overflow handling */
.table-responsive {
    box-shadow: inset 0 0 10px rgba(0,0,0,0.1);
}

.table {
    min-width: 800px; /* Ensure minimum width for readability */
}

/* Responsive table behavior */
@media (max-width: 1200px) {
    .table-responsive {
        max-height: 60vh;
    }
}

@media (max-width: 768px) {
    .table-responsive {
        max-height: 50vh;
    }
    
    .table {
        min-width: 600px;
    }
}

/* Smooth scrolling behavior */
.table-responsive {
    scroll-behavior: smooth;
}

/* Table row hover effects with overflow */
.table tbody tr {
    transition: all 0.2s ease;
}

.table tbody tr:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

/* Ensure actions column is always visible */
.table td:last-child {
    position: sticky;
    right: 0;
    background: white;
    z-index: 5;
    box-shadow: -2px 0 5px rgba(0,0,0,0.1);
}

.table th:last-child {
    position: sticky;
    right: 0;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    z-index: 15;
    box-shadow: -2px 0 5px rgba(0,0,0,0.1);
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, page initialized');
    initializeTableFilters();
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

// Articles table functionality
function initializeTableFilters() {
    // Add row highlighting on hover
    const tableRows = document.querySelectorAll('tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.backgroundColor = '#f8f9fa';
        });
        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
        });
    });
    
    // Initialize table scrolling functionality
    initializeTableScrolling();
}

function initializeTableScrolling() {
    const tableContainer = document.querySelector('.table-responsive');
    const scrollIndicator = document.querySelector('.table-scroll-indicator');
    
    if (tableContainer && scrollIndicator) {
        // Show scroll indicators based on scroll position
        tableContainer.addEventListener('scroll', function() {
            const { scrollTop, scrollLeft, scrollHeight, scrollWidth, clientHeight, clientWidth } = this;
            
            // Vertical scroll indicator
            if (scrollTop > 0) {
                scrollIndicator.style.opacity = '1';
            } else {
                scrollIndicator.style.opacity = '0';
            }
            
            // Horizontal scroll indicator
            if (scrollLeft > 0) {
                scrollIndicator.style.opacity = '1';
            }
            
            // Show scroll to top button if scrolled down
            if (scrollTop > 100) {
                showScrollToTopButton();
            } else {
                hideScrollToTopButton();
            }
        });
        
        // Add smooth scrolling to top functionality
        addScrollToTopButton();
    }
}

function addScrollToTopButton() {
    // Remove existing button if any
    const existingButton = document.querySelector('.scroll-to-top-btn');
    if (existingButton) {
        existingButton.remove();
    }
    
    // Create scroll to top button
    const scrollButton = document.createElement('button');
    scrollButton.className = 'scroll-to-top-btn btn btn-primary btn-sm position-fixed';
    scrollButton.innerHTML = '<i class="fas fa-arrow-up"></i>';
    scrollButton.style.cssText = `
        bottom: 20px;
        right: 20px;
        z-index: 1000;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    `;
    
    scrollButton.addEventListener('click', function() {
        const tableContainer = document.querySelector('.table-responsive');
        if (tableContainer) {
            tableContainer.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }
    });
    
    document.body.appendChild(scrollButton);
}

function showScrollToTopButton() {
    const scrollButton = document.querySelector('.scroll-to-top-btn');
    if (scrollButton) {
        scrollButton.style.display = 'block';
    }
}

function hideScrollToTopButton() {
    const scrollButton = document.querySelector('.scroll-to-top-btn');
    if (scrollButton) {
        scrollButton.style.display = 'none';
    }
}

function filterTable() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    const typeFilter = document.getElementById('typeFilter').value;
    
    const tableRows = document.querySelectorAll('tbody tr');
    
    tableRows.forEach(row => {
        if (row.cells.length < 12) return; // Skip if not a valid article row
        
        const text = row.textContent.toLowerCase();
        const statusCell = row.cells[10]; // Status column
        const typeCell = row.cells[9]; // Type column
        
        let showRow = true;
        
        // Search filter
        if (searchTerm && !text.includes(searchTerm)) {
            showRow = false;
        }
        
        // Status filter
        if (statusFilter) {
            const statusText = statusCell.textContent.toLowerCase();
            if (statusFilter === 'validated' && !statusText.includes('validé')) {
                showRow = false;
            } else if (statusFilter === 'pending' && !statusText.includes('attente')) {
                showRow = false;
            }
        }
        
        // Type filter
        if (typeFilter) {
            const typeText = typeCell.textContent.toLowerCase();
            if (typeFilter === 'adjudication' && !typeText.includes('adjudication')) {
                showRow = false;
            } else if (typeFilter === 'appel_doffre' && !typeText.includes('appel d\'offre')) {
                showRow = false;
            }
        }
        
        row.style.display = showRow ? '' : 'none';
    });
    
    updateRowCount();
}

function updateRowCount() {
    const visibleRows = document.querySelectorAll('tbody tr:not([style*="display: none"])');
    const totalRows = document.querySelectorAll('tbody tr').length;
    
    // Update pagination info if it exists
    const paginationInfo = document.querySelector('.pagination-info small');
    if (paginationInfo) {
        paginationInfo.textContent = `${visibleRows.length} articles affichés sur ${totalRows} au total`;
    }
}

function duplicateArticle(articleId) {
    UXUtils.confirm('Voulez-vous dupliquer cet article ?', {
        title: 'Dupliquer l\'article',
        confirmText: 'Dupliquer',
        cancelText: 'Annuler',
        type: 'info',
        icon: 'fas fa-copy'
    }).then(confirmed => {
        if (confirmed) {
            UXUtils.showInfo('Redirection vers la page de création...');
            // Redirect to create page with article data
            window.location.href = `/articles/create?duplicate=${articleId}`;
        }
    });
}

function exportArticle(articleId) {
    // Show loading state
    const button = event.target.closest('.dropdown-item');
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Export en cours...';
    
    // Make export request
    fetch(`/articles/export?article_id=${articleId}`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (response.ok) {
            return response.blob();
        }
        throw new Error('Export failed');
    })
    .then(blob => {
        // Create download link
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `article_${articleId}_${new Date().toISOString().split('T')[0]}.csv`;
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
        document.body.removeChild(a);
        
        // Reset button and show success
        button.innerHTML = originalText;
        UXUtils.showSuccess('Article exporté avec succès !');
    })
    .catch(error => {
        console.error('Export error:', error);
        UXUtils.showError('Erreur lors de l\'export de l\'article');
        button.innerHTML = originalText;
    });
}

// Add keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + F to focus search
    if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
        e.preventDefault();
        document.getElementById('searchInput').focus();
    }
    
    // Escape to clear search
    if (e.key === 'Escape') {
        document.getElementById('searchInput').value = '';
        filterTable();
    }
});

function changePerPage() {
    const perPage = document.getElementById('perPageSelect').value;
    const currentUrl = new URL(window.location);
    currentUrl.searchParams.set('per_page', perPage);
    currentUrl.searchParams.delete('page'); // Reset to first page
    window.location.href = currentUrl.toString();
}

function refreshTable() {
    window.location.reload();
}

function exportAllArticles() {
    // Show loading state
    const button = event.target.closest('button');
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Export en cours...';
    button.disabled = true;
    
    // Get current filters
    const searchTerm = document.getElementById('searchInput').value;
    const statusFilter = document.getElementById('statusFilter').value;
    const typeFilter = document.getElementById('typeFilter').value;
    
    // Build export URL with filters
    let exportUrl = '/articles/export';
    const params = new URLSearchParams();
    
    if (searchTerm) params.append('search', searchTerm);
    if (statusFilter) params.append('status', statusFilter);
    if (typeFilter) params.append('type', typeFilter);
    
    if (params.toString()) {
        exportUrl += '?' + params.toString();
    }
    
    // Make export request
    fetch(exportUrl, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (response.ok) {
            return response.blob();
        }
        throw new Error('Export failed');
    })
    .then(blob => {
        // Create download link
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `articles_export_${new Date().toISOString().split('T')[0]}.csv`;
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
        document.body.removeChild(a);
        
        // Reset button
        button.innerHTML = originalText;
        button.disabled = false;
    })
    .catch(error => {
        console.error('Export error:', error);
        alert('Erreur lors de l\'export');
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

// Additional action functions
function printArticle(articleId) {
    const printWindow = window.open(`/articles/${articleId}?print=1`, '_blank');
    printWindow.onload = function() {
        printWindow.print();
    };
}

function shareArticle(articleId) {
    if (navigator.share) {
        navigator.share({
            title: 'Article Forestier',
            text: 'Consultez cet article forestier',
            url: `${window.location.origin}/articles/${articleId}`
        });
    } else {
        // Fallback: copy to clipboard
        const url = `${window.location.origin}/articles/${articleId}`;
        navigator.clipboard.writeText(url).then(() => {
            alert('Lien copié dans le presse-papiers !');
        });
    }
}

function toggleValidation(articleId, newStatus) {
    const action = newStatus ? 'valider' : 'dévalider';
    if (confirm(`Voulez-vous ${action} cet article ?`)) {
        fetch(`/articles/${articleId}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                is_validated: newStatus
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erreur lors de la modification du statut');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur lors de la modification du statut');
        });
    }
}

function archiveArticle(articleId) {
    if (confirm('Voulez-vous archiver cet article ?')) {
        fetch(`/articles/${articleId}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                is_deleted: true
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erreur lors de l\'archivage');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur lors de l\'archivage');
        });
    }
}

// Enhanced UX features
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const typeFilter = document.getElementById('typeFilter');
    const table = document.querySelector('.table');
    
    // Debounced search function
    const debouncedSearch = UXUtils.debounce(function() {
        filterTable();
    }, 300);
    
    // Enhanced search with debouncing
    searchInput.addEventListener('input', debouncedSearch);
    
    // Enhanced filter handling
    statusFilter.addEventListener('change', function() {
        filterTable();
        UXUtils.showToast(`Filtre par statut: ${this.value || 'Tous'}`, 'info', 2000);
    });
    
    typeFilter.addEventListener('change', function() {
        filterTable();
        UXUtils.showToast(`Filtre par type: ${this.value || 'Tous'}`, 'info', 2000);
    });
    
    // Enhanced table interactions
    if (table) {
        // Add loading states to action buttons
        table.querySelectorAll('.btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                if (this.classList.contains('btn-danger') || this.classList.contains('btn-warning')) {
                    UXUtils.setLoading(this, true);
                }
            });
        });
        
        // Enhanced row hover effects
        table.querySelectorAll('tbody tr').forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.backgroundColor = 'rgba(5, 150, 105, 0.05)';
            });
            
            row.addEventListener('mouseleave', function() {
                this.style.backgroundColor = '';
            });
        });
    }
    
    // Enhanced category cards
    document.querySelectorAll('.category-card').forEach(card => {
        card.addEventListener('click', function() {
            this.style.transform = 'scale(0.98)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
    });
    
    // Add keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + K to focus search
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            searchInput.focus();
        }
        
        // Ctrl/Cmd + N to create new article
        if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
            e.preventDefault();
            window.location.href = '{{ route("articles.create") }}';
        }
    });
    
    // Add search input hint
    searchInput.addEventListener('focus', function() {
        UXUtils.showToast('Utilisez Ctrl+K pour rechercher rapidement', 'info', 3000);
    });
    
    // Enhanced pagination
    document.querySelectorAll('.pagination .page-link').forEach(link => {
        link.addEventListener('click', function(e) {
            const btn = this;
            UXUtils.setLoading(btn, true);
        });
    });
    
    // Auto-refresh data every 5 minutes
    setInterval(function() {
        // Check if user is active
        if (document.visibilityState === 'visible') {
            // Auto-refresh logic could go here
            console.log('Auto-refreshing data...');
        }
    }, 300000); // 5 minutes
});
</script>
@endpush
