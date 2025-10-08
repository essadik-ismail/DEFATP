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

    <!-- Charts -->
    @if(isset($stats))
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20 mb-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-3">Vendus vs Invendus</h3>
                <canvas id="chartVendusInvendusEssence"></canvas>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-3">Revenus (DH)</h3>
                <canvas id="chartRevenusEssence"></canvas>
            </div>
        </div>
        
        <!-- Essence Chart -->
        @if(isset($essenceStats) && $essenceStats->count() > 0)
        <div class="mt-8">
            <h3 class="text-lg font-bold text-gray-800 mb-3">Articles par Essence</h3>
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-2xl p-6 border border-purple-200">
                <canvas id="chartEssences" height="400"></canvas>
            </div>
        </div>
        @endif
    </div>
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const vendus = Number(@json($stats['vendus'] ?? 0));
        const invendus = Number(@json($stats['invendus'] ?? 0));
        const totalRevenue = Number(@json($stats['total_prix_vente'] ?? 0));

        // Vendus vs Invendus Chart
        const c1 = document.getElementById('chartVendusInvendusEssence');
        if (c1 && window.Chart) {
            new Chart(c1, { 
                type: 'doughnut', 
                data: { 
                    labels: ['Vendus','Invendus'], 
                    datasets: [{ 
                        data: [vendus, invendus], 
                        backgroundColor: ['#22c55e','#f59e0b'], 
                        borderWidth: 0 
                    }] 
                }, 
                options: { 
                    plugins: { 
                        legend: { position: 'bottom' } 
                    } 
                } 
            });
        }

        // Revenue Chart
        const c2 = document.getElementById('chartRevenusEssence');
        if (c2 && window.Chart) {
            new Chart(c2, { 
                type: 'bar', 
                data: { 
                    labels: ['Total Revenus'], 
                    datasets: [{ 
                        label: 'DH', 
                        data: [totalRevenue], 
                        backgroundColor: '#3b82f6' 
                    }] 
                }, 
                options: { 
                    scales: { 
                        y: { beginAtZero: true } 
                    } 
                } 
            });
        }

        // Essence Chart
        @if(isset($essenceStats) && $essenceStats->count() > 0)
        const essenceStats = @json($essenceStats);
        const c3 = document.getElementById('chartEssences');
        if (c3 && window.Chart && essenceStats) {
            const labels = essenceStats.map(item => item.label);
            const data = essenceStats.map(item => item.total);
            
            new Chart(c3, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Nombre d\'articles',
                        data: data,
                        backgroundColor: [
                            '#8b5cf6', '#a855f7', '#c084fc', '#d8b4fe', '#e9d5ff',
                            '#f3e8ff', '#ddd6fe', '#c4b5fd', '#a78bfa', '#8b5cf6'
                        ],
                        borderColor: [
                            '#7c3aed', '#9333ea', '#a855f7', '#c084fc', '#d8b4fe',
                            '#e9d5ff', '#ddd6fe', '#c4b5fd', '#a78bfa', '#8b5cf6'
                        ],
                        borderWidth: 1,
                        borderRadius: 8,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: 'Distribution des articles par essence forestière',
                            font: {
                                size: 16,
                                weight: 'bold'
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                maxRotation: 45,
                                minRotation: 0
                            }
                        }
                    },
                    animation: {
                        duration: 2000,
                        easing: 'easeInOutQuart'
                    }
                }
            });
        }
        @endif
    });
    </script>
    @endpush
    @endif

    @php
        $headers = ['ID', 'Année', 'Numéro', 'Date', 'Statut', 'Forêts', 'Essences', 'Exploitant', 'Prix Retrait', 'Prix Vente', 'Actions'];
        $rows = $articles->map(function ($article) {
            $status = $article->invendu
                ? '<span class="badge bg-warning-soft text-warning-700 px-2 py-1 rounded">Invendu</span>'
                : '<span class="badge bg-success-soft text-success-700 px-2 py-1 rounded">Vendu</span>';

            $forets = method_exists($article, 'forets') && $article->forets && $article->forets->count()
                ? $article->forets->map(fn($f) => '<span class="badge bg-emerald-100 text-emerald-800 rounded px-2 py-0.5">'.e($f->foret).'</span>')->implode(' ')
                : e(optional($article->foret)->foret ?: 'N/A');

            $essences = method_exists($article, 'essences') && $article->essences && $article->essences->count()
                ? $article->essences->map(fn($e) => '<span class="badge bg-purple-100 text-purple-800 rounded px-2 py-0.5">'.e($e->essence).'</span>')->implode(' ')
                : e(optional($article->essence)->essence ?: 'N/A');

            $exploitant = $article->exploitant ? e($article->exploitant->nom_complet ?? trim(($article->exploitant->nom ?? '').' '.($article->exploitant->prenom ?? ''))) : 'N/A';

            $actions = '<div class="flex gap-2">'
                .'<a href="'.route('articles.show', $article->id).'" class="btn btn-sm btn-outline-primary" title="Voir"><i class="fas fa-eye"></i></a>'
                .'<a href="'.route('articles.edit', $article->id).'" class="btn btn-sm btn-outline-secondary" title="Modifier"><i class="fas fa-edit"></i></a>'
                .'</div>';

            return [
                e($article->id),
                e($article->annee),
                e($article->numero),
                e(optional($article->date_adjudication)->format('d/m/Y') ?: 'N/A'),
                $status,
                $forets,
                $essences,
                $exploitant,
                e(number_format($article->prix_de_retrait ?? 0, 0, ',', ' ')).' FCFA',
                $article->prix_vente ? e(number_format($article->prix_vente, 0, ',', ' ')).' FCFA' : 'N/A',
                $actions,
            ];
        });
    @endphp

    <x-data-table :headers="$headers" :rows="$rows" :pagination="$articles->links()" searchable="true" />
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
