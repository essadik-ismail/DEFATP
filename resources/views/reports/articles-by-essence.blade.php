@extends('layouts.app')

@section('title', 'Articles par Essence')

@section('content')
<div class="content-card">
    <!-- Header Content -->
    <div class="header-content">
        <div>
            <h1 class="card-title">Articles par Essence</h1>
            <p class="card-subtitle">Analysez les articles par essence avec des statistiques détaillées</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="filters-form">
        <form method="GET" action="{{ route('reports.articles-by-essence') }}" class="filters-grid">
            <div class="form-group">
                <label for="essence_id" class="form-label">Essence</label>
                <select name="essence_id" id="essence_id" class="form-select">
                    <option value="">Toutes les essences</option>
                    @foreach($essences as $essence)
                        <option value="{{ $essence->id }}" {{ request('essence_id') == $essence->id ? 'selected' : '' }}>
                            {{ $essence->essence }}
                        </option>
                    @endforeach
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
                <label for="invendu" class="form-label">Statut</label>
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
                <a href="{{ route('reports.articles-by-essence') }}" class="btn btn-outline-secondary">
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

        <div class="stat-card purple">
            <div class="stat-header">
                <h3 class="stat-title">Prix Total</h3>
                <div class="stat-menu">
                    <i class="fas fa-ellipsis-v"></i>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-info">
                    <h4>{{ number_format($stats['total_prix_vente'] ?? 0, 0, ',', ' ') }}</h4>
                    <p>FCFA total</p>
                </div>
                <div class="stat-avatars">
                    <div class="stat-avatar">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                </div>
            </div>
            <div class="progress-bar">
                <div class="progress-fill purple" style="width: 100%"></div>
            </div>
        </div>
    </div>
    @endif

    <!-- Articles Table -->
    <div class="content-card">
        <div class="header-content">
            <h2 class="card-title">Articles ({{ $articles->count() }})</h2>
        </div>
        
        @if($articles->count() > 0)
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th class="table-id">ID</th>
                            <th>Année</th>
                            <th>Numéro</th>
                            <th>Date</th>
                            <th>Statut</th>
                            <th>Forêt</th>
                            <th>Essence</th>
                            <th>Exploitant</th>
                            <th>Prix Retrait</th>
                            <th>Prix Vente</th>
                            <th class="table-actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($articles as $article)
                            <tr>
                                <td class="table-id">{{ $article->id }}</td>
                                <td>{{ $article->annee }}</td>
                                <td>{{ $article->numero }}</td>
                                <td class="table-date">{{ $article->date ? $article->date->format('d/m/Y') : 'N/A' }}</td>
                                <td>
                                    @if($article->invendu)
                                        <span class="status-badge warning">Invendu</span>
                                    @else
                                        <span class="status-badge success">Vendu</span>
                                    @endif
                                </td>
                                <td>{{ $article->foret->foret ?? 'N/A' }}</td>
                                <td>{{ $article->essence->essence ?? 'N/A' }}</td>
                                <td>{{ $article->exploitant ? $article->exploitant->nom . ' ' . $article->exploitant->prenom : 'N/A' }}</td>
                                <td>{{ number_format($article->prix_retrait, 0, ',', ' ') }} FCFA</td>
                                <td>{{ $article->prix_vente ? number_format($article->prix_vente, 0, ',', ' ') . ' FCFA' : 'N/A' }}</td>
                                <td class="table-actions">
                                    <div class="action-buttons">
                                        <a href="{{ route('articles.show', $article->id) }}" class="btn btn-sm btn-outline-primary" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('articles.edit', $article->id) }}" class="btn btn-sm btn-outline-secondary" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-inbox"></i>
                </div>
                <h3>Aucun article trouvé</h3>
                <p>Aucun article ne correspond aux critères de recherche.</p>
            </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    .filters-form {
        background: white;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        border: 1px solid var(--google-border);
    }

    .filters-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        align-items: end;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        align-items: end;
    }

    .table-container {
        overflow-x: auto;
        border-radius: 8px;
        border: 1px solid var(--google-border);
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
    }

    .data-table th {
        background: var(--google-light-gray);
        padding: 16px 12px;
        text-align: left;
        font-weight: 600;
        color: var(--google-text);
        border-bottom: 1px solid var(--google-border);
    }

    .data-table td {
        padding: 16px 12px;
        border-bottom: 1px solid var(--google-border);
        color: var(--google-text);
    }

    .data-table tr:hover {
        background: var(--google-light-gray);
    }

    .table-id {
        font-weight: 600;
        color: var(--google-gray);
    }

    .table-date {
        color: var(--google-gray);
        font-size: 14px;
    }

    .table-actions {
        width: 120px;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
    }

    .status-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-badge.success {
        background: rgba(52, 168, 83, 0.1);
        color: #137333;
    }

    .status-badge.warning {
        background: rgba(251, 188, 4, 0.1);
        color: #b06000;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--google-gray);
    }

    .empty-icon {
        font-size: 48px;
        margin-bottom: 16px;
        opacity: 0.5;
    }

    .empty-state h3 {
        margin: 0 0 8px 0;
        color: var(--google-text);
    }

    .empty-state p {
        margin: 0;
        font-size: 16px;
    }
</style>
@endpush
@endsection
