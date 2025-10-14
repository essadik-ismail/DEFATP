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

    <!-- Charts -->
    @if(isset($stats))
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20 mb-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-3">Vendus vs Invendus</h3>
                <canvas id="chartVendusInvendusValidation"></canvas>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-3">Revenus (DH)</h3>
                <canvas id="chartRevenusValidation"></canvas>
            </div>
        </div>
    </div>
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const vendus = Number(@json($stats['vendus'] ?? 0));
        const invendus = Number(@json($stats['invendus'] ?? 0));
        const totalRevenue = Number(@json($stats['total_prix_vente'] ?? 0));

        const c1 = document.getElementById('chartVendusInvendusValidation');
        if (c1 && window.Chart) {
            new Chart(c1, { type: 'doughnut', data: { labels: ['Vendus','Invendus'], datasets: [{ data: [vendus, invendus], backgroundColor: ['#22c55e','#f59e0b'], borderWidth: 0 }] }, options: { plugins: { legend: { position: 'bottom' } } } });
        }
        const c2 = document.getElementById('chartRevenusValidation');
        if (c2 && window.Chart) {
            new Chart(c2, { type: 'bar', data: { labels: ['Total Revenus'], datasets: [{ label: 'DH', data: [totalRevenue], backgroundColor: '#3b82f6' }] }, options: { scales: { y: { beginAtZero: true } } } });
        }
    });
    </script>
    @endpush
    @endif

    @php
        $headers = ['ID', 'Numéro', 'Date', 'Forêts', 'Essences', 'Exploitant', 'Validation', 'Vente', 'Prix de Vente', 'Actions'];
        $rows = $articles->map(function ($article) {
            $date = optional($article->date_adjudication)->format('d/m/Y') ?: 'N/A';
            $forets = method_exists($article, 'forets') && $article->forets && $article->forets->count()
                ? $article->forets->map(fn($f) => '<span class="badge bg-emerald-100 text-emerald-800 rounded px-2 py-0.5">'.e($f->foret).'</span>')->implode(' ')
                : 'N/A';
            $essences = method_exists($article, 'essences') && $article->essences && $article->essences->count()
                ? $article->essences->map(fn($e) => '<span class="badge bg-purple-100 text-purple-800 rounded px-2 py-0.5">'.e($e->essence).'</span>')->implode(' ')
                : 'N/A';
            $exploitant = $article->exploitant ? e($article->exploitant->nom_complet ?? trim(($article->exploitant->nom ?? '').' '.($article->exploitant->prenom ?? ''))) : 'N/A';
            $validation = $article->valide
                ? '<span class="badge bg-success-soft text-success-700 px-2 py-1 rounded">Validé</span>'
                : '<span class="badge bg-warning-soft text-warning-700 px-2 py-1 rounded">En attente</span>';
            $vente = $article->invendu
                ? '<span class="badge bg-warning-soft text-warning-700 px-2 py-1 rounded">Invendu</span>'
                : '<span class="badge bg-success-soft text-success-700 px-2 py-1 rounded">Vendu</span>';
            $actions = '<div class="flex gap-2">'
                .'<a href="'.route('articles.show', $article).'" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>'
                .'<a href="'.route('articles.edit', $article).'" class="btn btn-sm btn-outline-warning"><i class="fas fa-edit"></i></a>'
                .'</div>';
            return [
                e($article->id),
                e($article->numero),
                e($date),
                $forets,
                $essences,
                $exploitant,
                $validation,
                $vente,
                $article->prix_vente ? e(number_format($article->prix_vente, 0, ',', ' ')).' DH' : 'N/A',
                $actions,
            ];
        });
    @endphp

    <x-data-table :headers="$headers" :rows="$rows" :pagination="$articles->links()" searchable="true" />
</div>
@endsection
