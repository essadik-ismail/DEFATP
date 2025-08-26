@extends('layouts.app')

@section('title', 'Détails de l\'Article - SylvaNet')

@section('content')
<!-- Content Header -->
<div class="content-header">
    <div class="header-content">
        <div class="greeting-section">
            <div class="greeting">
                <h1 class="section-title">Article #{{ $article->numero ?? $article->id }}</h1>
                <p class="text-muted">Détails complets de l'article forestier</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('articles.edit', $article) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-2"></i>Modifier
                </a>
                <a href="{{ route('articles.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Article Details -->
        <div class="card glassmorphism-card mb-4">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-info-circle me-2"></i>Informations Générales
                </h5>
                <p class="card-subtitle">Détails principaux de l'article</p>
            </div>
            <div class="card-body">
                <div class="article-details">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="detail-item">
                                <label class="detail-label">Numéro d'Article</label>
                                <span class="detail-value">{{ $article->numero ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-item">
                                <label class="detail-label">Année</label>
                                <span class="detail-value">{{ $article->annee ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="detail-item">
                                <label class="detail-label">Essence</label>
                                <span class="detail-value">{{ $article->essence->essence ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-item">
                                <label class="detail-label">Forêt</label>
                                <span class="detail-value">{{ $article->foret->foret ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="detail-item">
                                <label class="detail-label">Localisation</label>
                                <span class="detail-value">{{ $article->localisation->CODE ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-item">
                                <label class="detail-label">Date d'Adjudication</label>
                                <span class="detail-value">
                                    {{ $article->date_adjudication ? $article->date_adjudication->format('d/m/Y') : 'N/A' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="detail-item">
                                <label class="detail-label">Prix de Retrait</label>
                                <span class="detail-value">
                                    {{ $article->prix_de_retrait ? number_format($article->prix_de_retrait, 2) . ' DH' : 'N/A' }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-item">
                                <label class="detail-label">Prix de Vente</label>
                                <span class="detail-value">
                                    {{ $article->prix_vente ? number_format($article->prix_vente, 2) . ' DH' : 'N/A' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="detail-item">
                                <label class="detail-label">Type</label>
                                <span class="detail-value">
                                    @if($article->type == 'appel_doffre')
                                        <span class="badge bg-info">Appel d'Offre</span>
                                    @elseif($article->type == 'adjudication')
                                        <span class="badge bg-primary">Adjudication</span>
                                    @else
                                        N/A
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-item">
                                <label class="detail-label">Statut</label>
                                <span class="detail-value">
                                    @if($article->is_validated)
                                        <span class="badge bg-success"><i class="fas fa-check me-1"></i>Validé</span>
                                    @else
                                        <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>En attente</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Technical Details -->
        <div class="card glassmorphism-card mb-4">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-ruler me-2"></i>Détails Techniques
                </h5>
                <p class="card-subtitle">Mesures et spécifications</p>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="detail-item">
                            <label class="detail-label">Volume</label>
                            <span class="detail-value">{{ $article->volume ? number_format($article->volume, 2) . ' m³' : 'N/A' }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="detail-item">
                            <label class="detail-label">Poids</label>
                            <span class="detail-value">{{ $article->poids ? number_format($article->poids, 2) . ' tonnes' : 'N/A' }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="detail-item">
                            <label class="detail-label">Parcelle</label>
                            <span class="detail-value">{{ $article->parcelle ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="detail-item">
                            <label class="detail-label">BF/ST</label>
                            <span class="detail-value">{{ $article->bf_st ?? 'N/A' }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-item">
                            <label class="detail-label">Liège</label>
                            <span class="detail-value">{{ $article->liege ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="card glassmorphism-card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-plus-circle me-2"></i>Informations Supplémentaires
                </h5>
                <p class="card-subtitle">Détails additionnels</p>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="detail-item">
                            <label class="detail-label">Situation Administrative</label>
                            <span class="detail-value">{{ $article->situationAdministrative->commune ?? 'N/A' }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-item">
                            <label class="detail-label">Nature de Coupe</label>
                            <span class="detail-value">{{ $article->natureDeCoupe->nature ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="detail-item">
                            <label class="detail-label">Exploitant</label>
                            <span class="detail-value">{{ $article->exploitant->nom ?? 'N/A' }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-item">
                            <label class="detail-label">Date de Création</label>
                            <span class="detail-value">{{ $article->created_at ? $article->created_at->format('d/m/Y H:i') : 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                @if($article->observation)
                <div class="row">
                    <div class="col-12">
                        <div class="detail-item">
                            <label class="detail-label">Observations</label>
                            <span class="detail-value">{{ $article->observation }}</span>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Quick Actions -->
        <div class="card glassmorphism-card mb-4">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-bolt me-2"></i>Actions Rapides
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('articles.edit', $article) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Modifier l'Article
                    </a>
                    <a href="{{ route('articles.index') }}" class="btn btn-secondary">
                        <i class="fas fa-list me-2"></i>Voir Tous les Articles
                    </a>
                    <form action="{{ route('articles.destroy', $article) }}" method="POST" class="d-grid" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-2"></i>Supprimer l'Article
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Article Summary -->
        <div class="card glassmorphism-card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-chart-pie me-2"></i>Résumé de l'Article
                </h5>
            </div>
            <div class="card-body">
                <div class="summary-item">
                    <div class="summary-icon">
                        <i class="fas fa-tree text-success"></i>
                    </div>
                    <div class="summary-info">
                        <div class="summary-label">Essence</div>
                        <div class="summary-value">{{ $article->essence->essence ?? 'N/A' }}</div>
                    </div>
                </div>

                <div class="summary-item">
                    <div class="summary-icon">
                        <i class="fas fa-map-marker-alt text-info"></i>
                    </div>
                    <div class="summary-info">
                        <div class="summary-label">Localisation</div>
                        <div class="summary-value">{{ $article->localisation->CODE ?? 'N/A' }}</div>
                    </div>
                </div>

                <div class="summary-item">
                    <div class="summary-icon">
                        <i class="fas fa-ruler text-warning"></i>
                    </div>
                    <div class="summary-info">
                        <div class="summary-label">Volume</div>
                        <div class="summary-value">{{ $article->volume ? number_format($article->volume, 2) . ' m³' : 'N/A' }}</div>
                    </div>
                </div>

                <div class="summary-item">
                    <div class="summary-icon">
                        <i class="fas fa-money-bill text-success"></i>
                    </div>
                    <div class="summary-info">
                        <div class="summary-label">Prix de Vente</div>
                        <div class="summary-value">{{ $article->prix_vente ? number_format($article->prix_vente, 2) . ' DH' : 'N/A' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
