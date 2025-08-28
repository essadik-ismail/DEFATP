@extends('layouts.app')

@section('title', 'Articles par Statut de Validation')

@section('content')
<div class="content-card">
    <!-- Header Content -->
    <div class="header-content">
        <div>
            <h1 class="card-title">Articles par Statut de Validation</h1>
            <p class="card-subtitle">Analysez les articles selon leur statut de validation avec des statistiques détaillées</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="filters-form">
        <form method="GET" action="{{ route('reports.articles-by-validation-status') }}" class="filters-grid">
            <div class="form-group">
                <label for="status" class="form-label">Statut de Validation</label>
                <select name="status" id="status" class="form-select">
                    <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>Tous les statuts</option>
                    <option value="validated" {{ request('status') === 'validated' ? 'selected' : '' }}>Validés</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="foret_id" class="form-label">Forêt</label>
                <select name="foret_id" id="foret_id" class="form-select">
                    <option value="">Toutes les forêts</option>
                    @foreach($forets ?? [] as $foret)
                        <option value="{{ $foret->id }}" {{ request('foret_id') == $foret->id ? 'selected' : '' }}>
                            {{ $foret->foret }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <label for="invendu" class="form-label">Statut de Vente</label>
                <select name="invendu" id="invendu" class="form-select">
                    <option value="">Tous les statuts</option>
                    <option value="0" {{ request('invendu') === '0' ? 'selected' : '' }}>Vendus</option>
                    <option value="1" {{ request('invendu') === '1' ? 'selected' : '' }}>Invendus</option>
                </select>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search me-2"></i>Filtrer
                </button>
                <a href="{{ route('reports.articles-by-validation-status') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-2"></i>Réinitialiser
                </a>
            </div>
        </form>
    </div>

    <!-- Statistics Cards -->
    @if(isset($stats))
    <div class="stats-grid">
        <div class="stat-card purple">
            <div class="stat-header">
                <h3 class="stat-title">Total Articles</h3>
                <div class="stat-menu">
                    <i class="fas fa-ellipsis-v"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-info">
                    <h4>{{ $stats['total'] ?? 0 }}</h4>
                    <p>Articles au total</p>
                </div>
                <div class="stat-avatars">
                    <div class="stat-avatar">
                        <i class="fas fa-list"></i>
                    </div>
                </div>
            </div>
            <div class="progress-bar">
                <div class="progress-fill purple" style="width: 100%"></div>
            </div>
        </div>

        <div class="stat-card blue">
            <div class="stat-header">
                <h3 class="stat-title">Vendus</h3>
                <div class="stat-menu">
                    <i class="fas fa-ellipsis-v"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-info">
                    <h4>{{ $stats['vendus'] ?? 0 }}</h4>
                    <p>Articles vendus</p>
                </div>
                <div class="stat-avatars">
                    <div class="stat-avatar">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
            <div class="progress-bar">
                <div class="progress-fill blue" style="width: {{ $stats['total'] > 0 ? ($stats['vendus'] / $stats['total']) * 100 : 0 }}%"></div>
            </div>
        </div>

        <div class="stat-card orange">
            <div class="stat-header">
                <h3 class="stat-title">Invendus</h3>
                <div class="stat-menu">
                    <i class="fas fa-ellipsis-v"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-info">
                    <h4>{{ $stats['invendus'] ?? 0 }}</h4>
                    <p>Articles invendus</p>
                </div>
                <div class="stat-avatars">
                    <div class="stat-avatar">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
            <div class="progress-bar">
                <div class="progress-fill orange" style="width: {{ $stats['total'] > 0 ? ($stats['invendus'] / $stats['total']) * 100 : 0 }}%"></div>
            </div>
        </div>

        <div class="stat-card green">
            <div class="stat-header">
                <h3 class="stat-title">Prix de Vente</h3>
                <div class="stat-menu">
                    <i class="fas fa-ellipsis-v"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-info">
                    <h4>{{ number_format($stats['total_prix_vente'] ?? 0, 0, ',', ' ') }} DH</h4>
                    <p>Total des ventes</p>
                </div>
                <div class="stat-avatars">
                    <div class="stat-avatar">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                </div>
            </div>
            <div class="progress-bar">
                <div class="progress-fill green" style="width: 100%"></div>
            </div>
        </div>
    </div>
    @endif

    <!-- Articles Table -->
    <div class="data-table-section">
        <div class="table-header">
            <h3>Liste des Articles</h3>
            <div class="table-actions">
                <span class="table-count">{{ $articles->count() }} articles trouvés</span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Numéro</th>
                        <th>Date</th>
                        <th>Forêt</th>
                        <th>Essence</th>
                        <th>Exploitant</th>
                        <th>Statut de Validation</th>
                        <th>Statut de Vente</th>
                        <th>Prix de Vente</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($articles as $article)
                    <tr>
                        <td>{{ $article->id }}</td>
                        <td>{{ $article->numero }}</td>
                        <td>{{ $article->date ? \Carbon\Carbon::parse($article->date)->format('d/m/Y') : 'N/A' }}</td>
                        <td>{{ $article->foret->foret ?? 'N/A' }}</td>
                        <td>{{ $article->essence->essence ?? 'N/A' }}</td>
                        <td>
                            @if($article->exploitant)
                                {{ $article->exploitant->nom_complet ?? ($article->exploitant->nom . ' ' . $article->exploitant->prenom) }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            @if($article->valide)
                                <span class="badge bg-success">Validé</span>
                            @else
                                <span class="badge bg-warning">En attente</span>
                            @endif
                        </td>
                        <td>
                            @if($article->invendu)
                                <span class="badge bg-warning">Invendu</span>
                            @else
                                <span class="badge bg-success">Vendu</span>
                            @endif
                        </td>
                        <td>{{ $article->prix_vente ? number_format($article->prix_vente, 0, ',', ' ') . ' DH' : 'N/A' }}</td>
                        <td>
                            <a href="{{ route('articles.show', $article) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('articles.edit', $article) }}" class="btn btn-sm btn-outline-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-4">
                            <div class="empty-state">
                                <i class="fas fa-check-circle fa-3x mb-3"></i>
                                <h5>Aucun article trouvé</h5>
                                <p>Aucun article ne correspond aux critères de recherche sélectionnés.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
