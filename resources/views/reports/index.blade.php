@extends('layouts.app')

@section('title', 'Rapports')

@section('content')
<div class="content-card">
    <!-- Header Content -->
    <div class="header-content">
        <div>
            <h1 class="card-title">Rapports</h1>
            <p class="card-subtitle">Générez et consultez différents types de rapports pour analyser vos données</p>
        </div>
    </div>

    <!-- Reports Grid -->
    <div class="settings-grid">
        <!-- Articles by Year -->
        <div class="settings-card">
            <div class="card-icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="card-content">
                <h3>Articles par Année</h3>
                <p>Analysez les articles groupés par année avec des statistiques détaillées</p>
                <div class="card-stats">
                    <span class="stat-item">
                        <i class="fas fa-chart-line"></i>
                        Statistiques annuelles
                    </span>
                </div>
            </div>
            <div class="card-actions">
                <a href="{{ route('reports.articles-by-year') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-right me-2"></i>Voir le rapport
                </a>
            </div>
        </div>

        <!-- Articles by Forest -->
        <div class="settings-card">
            <div class="card-icon">
                <i class="fas fa-tree"></i>
            </div>
            <div class="card-content">
                <h3>Articles par Forêt</h3>
                <p>Consultez les articles organisés par forêt avec analyses détaillées</p>
                <div class="card-stats">
                    <span class="stat-item">
                        <i class="fas fa-map-marker-alt"></i>
                        Analyse géographique
                    </span>
                </div>
            </div>
            <div class="card-actions">
                <a href="{{ route('reports.articles-by-foret') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-right me-2"></i>Voir le rapport
                </a>
            </div>
        </div>

        <!-- Articles by Essence -->
        <div class="settings-card">
            <div class="card-icon">
                <i class="fas fa-seedling"></i>
            </div>
            <div class="card-content">
                <h3>Articles par Essence</h3>
                <p>Analysez les articles selon les types d'essences forestières</p>
                <div class="card-stats">
                    <span class="stat-item">
                        <i class="fas fa-leaf"></i>
                        Analyse botanique
                    </span>
                </div>
            </div>
            <div class="card-actions">
                <a href="{{ route('reports.articles-by-essence') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-right me-2"></i>Voir le rapport
                </a>
            </div>
        </div>

        <!-- Articles by Exploitant -->
        <div class="settings-card">
            <div class="card-icon">
                <i class="fas fa-user-tie"></i>
            </div>
            <div class="card-content">
                <h3>Articles par Exploitant</h3>
                <p>Consultez les articles associés à chaque exploitant forestier</p>
                <div class="card-stats">
                    <span class="stat-item">
                        <i class="fas fa-users"></i>
                        Analyse par exploitant
                    </span>
                </div>
            </div>
            <div class="card-actions">
                <a href="{{ route('reports.articles-by-exploitant') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-right me-2"></i>Voir le rapport
                </a>
            </div>
        </div>

        <!-- Unsold Articles -->
        <div class="settings-card">
            <div class="card-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="card-content">
                <h3>Articles Invendus</h3>
                <p>Liste complète des articles qui n'ont pas encore été vendus</p>
                <div class="card-stats">
                    <span class="stat-item">
                        <i class="fas fa-exclamation-triangle"></i>
                        Articles en attente
                    </span>
                </div>
            </div>
            <div class="card-actions">
                <a href="{{ route('reports.invendus') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-right me-2"></i>Voir le rapport
                </a>
            </div>
        </div>

        <!-- Sold Articles -->
        <div class="settings-card">
            <div class="card-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="card-content">
                <h3>Articles Vendus</h3>
                <p>Analyse des articles qui ont été vendus avec statistiques de vente</p>
                <div class="card-stats">
                    <span class="stat-item">
                        <i class="fas fa-chart-bar"></i>
                        Statistiques de vente
                    </span>
                </div>
            </div>
            <div class="card-actions">
                <a href="{{ route('reports.vendus') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-right me-2"></i>Voir le rapport
                </a>
            </div>
        </div>

        <!-- Summary Report -->
        <div class="settings-card">
            <div class="card-icon">
                <i class="fas fa-chart-pie"></i>
            </div>
            <div class="card-content">
                <h3>Rapport de Synthèse</h3>
                <p>Vue d'ensemble complète avec toutes les statistiques importantes</p>
                <div class="card-stats">
                    <span class="stat-item">
                        <i class="fas fa-tachometer-alt"></i>
                        Vue d'ensemble
                    </span>
                </div>
            </div>
            <div class="card-actions">
                <a href="{{ route('reports.summary') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-right me-2"></i>Voir le rapport
                </a>
            </div>
        </div>

        <!-- Export Complete Report -->
        <div class="settings-card">
            <div class="card-icon">
                <i class="fas fa-download"></i>
            </div>
            <div class="card-content">
                <h3>Export Complet</h3>
                <p>Téléchargez un rapport complet au format CSV avec toutes les données</p>
                <div class="card-stats">
                    <span class="stat-item">
                        <i class="fas fa-file-csv"></i>
                        Export CSV
                    </span>
                </div>
            </div>
            <div class="card-actions">
                <a href="{{ route('reports.export-summary') }}" class="btn btn-primary">
                    <i class="fas fa-download me-2"></i>Télécharger
                </a>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .settings-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 24px;
        margin-top: 24px;
    }

    .settings-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        border: 1px solid var(--google-border);
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .settings-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(135deg, #1e293b 0%, #4a7c59 50%, #e67e22 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .settings-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 32px rgba(0,0,0,0.12);
    }

    .settings-card:hover::before {
        opacity: 1;
    }

    .card-icon {
        width: 60px;
        height: 60px;
        border-radius: 16px;
        background: linear-gradient(135deg, #1e293b 0%, #4a7c59 50%, #e67e22 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        color: white;
        font-size: 24px;
        box-shadow: 0 4px 16px rgba(30, 41, 59, 0.3);
    }

    .card-content h3 {
        margin: 0 0 12px 0;
        font-size: 20px;
        font-weight: 600;
        color: var(--google-text);
    }

    .card-content p {
        margin: 0 0 16px 0;
        color: var(--google-gray);
        line-height: 1.5;
    }

    .card-stats {
        margin-bottom: 20px;
    }

    .stat-item {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: var(--google-light-gray);
        border-radius: 20px;
        font-size: 14px;
        color: var(--google-text);
        font-weight: 500;
    }

    .stat-item i {
        background: linear-gradient(135deg, #1e293b 0%, #4a7c59 50%, #e67e22 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .card-actions {
        display: flex;
        gap: 12px;
    }

    .card-actions .btn {
        flex: 1;
        justify-content: center;
    }
</style>
@endpush
@endsection
