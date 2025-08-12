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
        <x-card title="Articles par Année" subtitle="Analysez les articles groupés par année avec des statistiques détaillées" collapsible="false">
            <div class="card-stats">
                <span class="stat-item">
                    <i class="fas fa-chart-line"></i>
                    Statistiques annuelles
                </span>
            </div>
            <div class="card-actions">
                <x-button href="{{ route('reports.articles-by-year') }}" variant="primary" icon="fas fa-arrow-right">
                    Voir le rapport
                </x-button>
            </div>
        </x-card>

        <!-- Articles by Forest -->
        <x-card title="Articles par Forêt" subtitle="Consultez les articles organisés par forêt avec analyses détaillées" collapsible="false">
            <div class="card-stats">
                <span class="stat-item">
                    <i class="fas fa-map-marker-alt"></i>
                    Analyse géographique
                </span>
            </div>
            <div class="card-actions">
                <x-button href="{{ route('reports.articles-by-foret') }}" variant="primary" icon="fas fa-arrow-right">
                    Voir le rapport
                </x-button>
            </div>
        </x-card>

        <!-- Articles by Essence -->
        <x-card title="Articles par Essence" subtitle="Analysez les articles selon les types d'essences forestières" collapsible="false">
            <div class="card-stats">
                <span class="stat-item">
                    <i class="fas fa-leaf"></i>
                    Analyse botanique
                </span>
            </div>
            <div class="card-actions">
                <x-button href="{{ route('reports.articles-by-essence') }}" variant="primary" icon="fas fa-arrow-right">
                    Voir le rapport
                </x-button>
            </div>
        </x-card>

        <!-- Articles by Exploitant -->
        <x-card title="Articles par Exploitant" subtitle="Consultez les articles associés à chaque exploitant forestier" collapsible="false">
            <div class="card-stats">
                <span class="stat-item">
                    <i class="fas fa-users"></i>
                    Analyse par exploitant
                </span>
            </div>
            <div class="card-actions">
                <x-button href="{{ route('reports.articles-by-exploitant') }}" variant="primary" icon="fas fa-arrow-right">
                    Voir le rapport
                </x-button>
            </div>
        </x-card>

        <!-- Articles by Nature de Coupe -->
        <x-card title="Articles par Nature de Coupe" subtitle="Analysez les articles selon les méthodes d'exploitation" collapsible="false">
            <div class="card-stats">
                <span class="stat-item">
                    <i class="fas fa-cut"></i>
                    Analyse des méthodes
                </span>
            </div>
            <div class="card-actions">
                <x-button href="{{ route('reports.articles-by-nature-de-coupe') }}" variant="primary" icon="fas fa-arrow-right">
                    Voir le rapport
                </x-button>
            </div>
        </x-card>

        <!-- Articles by Localisation -->
        <x-card title="Articles par Localisation" subtitle="Analysez les articles selon leur emplacement géographique" collapsible="false">
            <div class="card-stats">
                <span class="stat-item">
                    <i class="fas fa-map"></i>
                    Analyse spatiale
                </span>
            </div>
            <div class="card-actions">
                <x-button href="{{ route('reports.articles-by-localisation') }}" variant="primary" icon="fas fa-arrow-right">
                    Voir le rapport
                </x-button>
            </div>
        </x-card>



        <!-- Articles by Validation Status -->
        <x-card title="Articles par Statut de Validation" subtitle="Analysez les articles selon leur statut de validation" collapsible="false">
            <div class="card-stats">
                <span class="stat-item">
                    <i class="fas fa-check-circle"></i>
                    Analyse des validations
                </span>
            </div>
            <div class="card-actions">
                <x-button href="{{ route('reports.articles-by-validation-status') }}" variant="primary" icon="fas fa-arrow-right">
                    Voir le rapport
                </x-button>
            </div>
        </x-card>
    </div>
</div>
@endsection
