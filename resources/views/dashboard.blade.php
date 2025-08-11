@extends('layouts.app')

@section('title', 'Tableau de Bord')
@section('page-title', 'Bonjour, ' . auth()->user()->name)

@section('page-actions')
    <button class="btn-primary" onclick="window.location.href='{{ route('articles.create') }}'">
        <i class="fas fa-plus mr-2"></i>
        Nouvel Article
    </button>
@endsection

@section('content')
    <!-- Enhanced Statistics Overview Cards -->
    <div class="stats-grid mx-8">
        <div class="stat-card purple">
            <div class="stat-content">
                <div class="stat-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="stat-info">
                    <h3 class="stat-number">{{ \App\Models\Article::count() }}</h3>
                    <p class="stat-label">Articles Totaux</p>
                </div>
                <div class="stat-trend positive">
                    <i class="fas fa-trending-up"></i>
                    <span>+12%</span>
                </div>
            </div>
        </div>

        <div class="stat-card blue">
            <div class="stat-content">
                <div class="stat-icon">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div class="stat-info">
                    <h3 class="stat-number">{{ \App\Models\Exploitant::count() }}</h3>
                    <p class="stat-label">Exploitants</p>
                </div>
                <div class="stat-trend positive">
                    <i class="fas fa-trending-up"></i>
                    <span>+8%</span>
                </div>
            </div>
        </div>

        <div class="stat-card green">
            <div class="stat-content">
                <div class="stat-icon">
                    <i class="fas fa-seedling"></i>
                </div>
                <div class="stat-info">
                    <h3 class="stat-number">{{ \App\Models\Essence::count() }}</h3>
                    <p class="stat-label">Essences</p>
                </div>
                <div class="stat-trend neutral">
                    <i class="fas fa-minus"></i>
                    <span>0%</span>
                </div>
            </div>
        </div>

        <div class="stat-card orange">
            <div class="stat-content">
                <div class="stat-icon">
                    <i class="fas fa-mountain"></i>
                </div>
                <div class="stat-info">
                    <h3 class="stat-number">{{ \App\Models\Foret::count() }}</h3>
                    <p class="stat-label">Forêts</p>
                </div>
                <div class="stat-trend positive">
                    <i class="fas fa-trending-up"></i>
                    <span>+5%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities and Quick Actions -->
    <div class="dashboard-grid mx-8">
        <!-- Recent Articles -->
        <!-- <div class="dashboard-card">
            <div class="card-header">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-clock text-white"></i>
                    </div>
                    <div>
                        <h5 class="card-title">Articles Récents</h5>
                        <p class="text-sm text-gray-600">Derniers articles ajoutés</p>
                    </div>
                </div>
                <button class="btn-primary" onclick="window.location.href='{{ route('articles.index') }}'">
                    <i class="fas fa-eye mr-2"></i>
                    Voir tout
                </button>
            </div>
            <div class="card-body">
                @php
                    $recentArticles = \App\Models\Article::with(['sessionAdjudication', 'essence', 'foret'])
                        ->latest()
                        ->take(5)
                        ->get();
                @endphp
                
                @if($recentArticles->count() > 0)
                    <div class="activity-list">
                        @foreach($recentArticles as $article)
                            <div class="activity-item">
                                <div class="activity-icon">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <div class="activity-content">
                                    <h6 class="activity-title">{{ $article->numero }}</h6>
                                    <p class="activity-description">
                                        {{ $article->essence->essence ?? 'N/A' }} - {{ $article->foret->foret ?? 'N/A' }}
                                    </p>
                                    <span class="activity-time">{{ $article->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-inbox text-gray-400 text-2xl"></i>
                        </div>
                        <p class="text-gray-500 font-medium">Aucun article récent</p>
                        <p class="text-gray-400 text-sm">Commencez par ajouter votre premier article</p>
                    </div>
                @endif
            </div>
        </div> -->

        <!-- Quick Actions -->
        <!-- <div class="dashboard-card">
            <div class="card-header">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-bolt text-white"></i>
                    </div>
                    <div>
                        <h5 class="card-title">Actions Rapides</h5>
                        <p class="text-sm text-gray-600">Accès rapide aux fonctionnalités</p>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="quick-actions">
                    <a href="{{ route('articles.create') }}" class="quick-action-item">
                        <div class="quick-action-icon">
                            <i class="fas fa-plus"></i>
                        </div>
                        <span>Nouvel Article</span>
                    </a>
                    
                    <a href="{{ route('reports.articles-by-year') }}" class="quick-action-item">
                        <div class="quick-action-icon">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <span>Rapports</span>
                    </a>
                    
                    <a href="{{ route('settings.index') }}" class="quick-action-item">
                        <div class="quick-action-icon">
                            <i class="fas fa-cog"></i>
                        </div>
                        <span>Paramètres</span>
                    </a>
                    
                    <a href="{{ route('auth.users.index') }}" class="quick-action-item">
                        <div class="quick-action-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <span>Utilisateurs</span>
                    </a>
                </div>
            </div>
        </div> -->
    </div>

    <!-- System Status -->
    <!-- <div class="dashboard-card">
        <div class="card-header">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-violet-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-server text-white"></i>
                </div>
                <div>
                    <h5 class="card-title">Statut du Système</h5>
                    <p class="text-sm text-gray-600">État des services</p>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="status-grid">
                <div class="status-item">
                    <div class="status-indicator online"></div>
                    <div class="status-info">
                        <h6>Base de données</h6>
                        <p>Opérationnel</p>
                    </div>
                </div>
                
                <div class="status-item">
                    <div class="status-indicator online"></div>
                    <div class="status-info">
                        <h6>Authentification</h6>
                        <p>Opérationnel</p>
                    </div>
                </div>
                
                <div class="status-item">
                    <div class="status-indicator online"></div>
                    <div class="status-info">
                        <h6>Stockage</h6>
                        <p>Opérationnel</p>
                    </div>
                </div>
                
                <div class="status-item">
                    <div class="status-indicator online"></div>
                    <div class="status-info">
                        <h6>API</h6>
                        <p>Opérationnel</p>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
@endsection

@push('styles')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 24px;
        padding: 2.5rem;
        border: 1px solid rgba(255, 255, 255, 0.3);
        position: relative;
        overflow: hidden;
        box-shadow: 
            0 8px 32px rgba(0, 0, 0, 0.1),
            0 2px 8px rgba(0, 0, 0, 0.05);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        animation: fadeInUp 0.6s ease-out;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, var(--purple-color), var(--accent-color));
        opacity: 0;
        transition: opacity 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-12px) scale(1.03);
        box-shadow: 
            0 20px 60px rgba(0, 0, 0, 0.15),
            0 8px 24px rgba(0, 0, 0, 0.1);
    }

    .stat-card:hover::before {
        opacity: 1;
    }

    .stat-card.purple::before {
        background: linear-gradient(90deg, #7c3aed, #8b5cf6);
    }

    .stat-card.blue::before {
        background: linear-gradient(90deg, #2563eb, #3b82f6);
    }

    .stat-card.orange::before {
        background: linear-gradient(90deg, #ea580c, #f97316);
    }

    .stat-card.green::before {
        background: linear-gradient(90deg, #059669, #10b981);
    }

    .stat-content {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        position: relative;
    }

    .stat-icon {
        width: 64px;
        height: 64px;
        border-radius: 20px;
        background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 8px 24px rgba(74, 124, 89, 0.3);
        transition: all 0.3s ease;
    }

    .stat-card:hover .stat-icon {
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 12px 32px rgba(74, 124, 89, 0.4);
    }

    .stat-icon i {
        color: white;
        font-size: 1.5rem;
    }

    .stat-info {
        flex: 1;
        min-width: 0;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 0.5rem 0;
        line-height: 1;
        background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .stat-label {
        color: var(--text-secondary);
        font-size: 1rem;
        margin: 0;
        font-weight: 500;
    }

    .stat-trend {
        position: absolute;
        top: 1.5rem;
        right: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        font-weight: 600;
        padding: 0.5rem 1rem;
        border-radius: 12px;
        backdrop-filter: blur(10px);
    }

    .stat-trend.positive {
        background: rgba(34, 197, 94, 0.1);
        color: #22c55e;
        border: 1px solid rgba(34, 197, 94, 0.2);
    }

    .stat-trend.negative {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    .stat-trend.neutral {
        background: rgba(107, 114, 128, 0.1);
        color: #6b7280;
        border: 1px solid rgba(107, 114, 128, 0.2);
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .dashboard-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 24px;
        box-shadow: 
            0 8px 32px rgba(0, 0, 0, 0.1),
            0 2px 8px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.3);
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .dashboard-card:hover {
        transform: translateY(-4px);
        box-shadow: 
            0 16px 48px rgba(0, 0, 0, 0.15),
            0 4px 16px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        padding: 2rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: linear-gradient(135deg, rgba(248, 250, 252, 0.8) 0%, rgba(255, 255, 255, 0.9) 100%);
    }

    .card-title {
        margin: 0;
        font-weight: 600;
        color: var(--text-primary);
        font-size: 1.25rem;
    }

    .card-body {
        padding: 2rem;
    }

    .activity-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .activity-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.5rem;
        border-radius: 16px;
        background: rgba(248, 250, 252, 0.8);
        transition: all 0.3s ease;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .activity-item:hover {
        background: rgba(255, 255, 255, 0.9);
        transform: translateX(8px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
    }

    .activity-icon {
        width: 48px;
        height: 48px;
        border-radius: 16px;
        background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(74, 124, 89, 0.3);
    }

    .activity-icon i {
        color: white;
        font-size: 1.125rem;
    }

    .activity-content {
        flex: 1;
        min-width: 0;
    }

    .activity-title {
        margin: 0 0 0.5rem 0;
        font-weight: 600;
        color: var(--text-primary);
        font-size: 1rem;
    }

    .activity-description {
        margin: 0 0 0.5rem 0;
        color: var(--text-secondary);
        font-size: 0.875rem;
    }

    .activity-time {
        color: var(--text-muted);
        font-size: 0.75rem;
        font-weight: 500;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: var(--text-secondary);
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .quick-actions {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    .quick-action-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
        padding: 2rem 1.5rem;
        border-radius: 16px;
        background: rgba(248, 250, 252, 0.8);
        text-decoration: none;
        color: var(--text-primary);
        transition: all 0.3s ease;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .quick-action-item:hover {
        background: rgba(255, 255, 255, 0.9);
        border-color: var(--primary-color);
        transform: translateY(-4px);
        text-decoration: none;
        color: var(--text-primary);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
    }

    .quick-action-icon {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(74, 124, 89, 0.3);
        transition: all 0.3s ease;
    }

    .quick-action-item:hover .quick-action-icon {
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 8px 20px rgba(74, 124, 89, 0.4);
    }

    .quick-action-icon i {
        color: white;
        font-size: 1.25rem;
    }

    .quick-action-item span {
        font-weight: 500;
        font-size: 0.875rem;
        text-align: center;
    }

    .status-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
    }

    .status-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.5rem;
        border-radius: 16px;
        background: rgba(248, 250, 252, 0.8);
        border: 1px solid rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .status-item:hover {
        background: rgba(255, 255, 255, 0.9);
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
    }

    .status-indicator {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        flex-shrink: 0;
        position: relative;
    }

    .status-indicator.online {
        background: #22c55e;
        box-shadow: 0 0 0 6px rgba(34, 197, 94, 0.2);
        animation: pulse 2s infinite;
    }

    .status-indicator.offline {
        background: #ef4444;
        box-shadow: 0 0 0 6px rgba(239, 68, 68, 0.2);
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(34, 197, 94, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(34, 197, 94, 0);
        }
    }

    .status-info h6 {
        margin: 0 0 0.25rem 0;
        font-weight: 600;
        color: var(--text-primary);
        font-size: 1rem;
    }

    .status-info p {
        margin: 0;
        color: var(--text-secondary);
        font-size: 0.875rem;
        font-weight: 500;
    }

    @media (max-width: 1024px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
        }
        
        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        }
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .quick-actions {
            grid-template-columns: 1fr;
        }
        
        .status-grid {
            grid-template-columns: 1fr;
        }
        
        .stat-card {
            padding: 2rem;
        }
        
        .card-header {
            padding: 1.5rem;
        }
        
        .card-body {
            padding: 1.5rem;
        }
    }
</style>
@endpush 