@extends('layouts.app')

@section('title', 'Tableau de Bord')

@section('content')
    <!-- Welcome Header -->
    <div class="welcome-header">
        <div class="welcome-content">
            <div class="welcome-text">
                <h1 class="welcome-title">Bonjour, {{ auth()->user()->name }} 👋</h1>
                <p class="welcome-subtitle">Bienvenue sur votre tableau de bord SylvaNet</p>
                <p class="welcome-date">Aujourd'hui nous sommes {{ now()->format('d/m/Y') }}</p>
            </div>
            <div class="welcome-actions">
                <a href="{{ route('articles.create') }}" class="welcome-btn primary">
                    <i class="fas fa-plus"></i>
                    <span>Nouvel Article</span>
                </a>
                <a href="{{ route('reports.index') }}" class="welcome-btn secondary">
                    <i class="fas fa-chart-line"></i>
                    <span>Voir Rapports</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Enhanced Statistics Overview Cards -->
    <div class="stats-section">
        <div class="stats-header">
            <h2 class="section-title">Vue d'ensemble</h2>
            <p class="section-subtitle">Statistiques clés de votre système</p>
        </div>
        <div class="stats-grid">
            <div class="stat-card purple">
                <div class="stat-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ \App\Models\Article::count() }}</div>
                    <div class="stat-label">Articles Totaux</div>
                </div>
                <div class="stat-trend positive">
                    <i class="fas fa-arrow-up"></i>
                    <span>+12%</span>
                </div>
            </div>

            <div class="stat-card blue">
                <div class="stat-icon">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ \App\Models\Exploitant::count() }}</div>
                    <div class="stat-label">Exploitants</div>
                </div>
                <div class="stat-trend positive">
                    <i class="fas fa-arrow-up"></i>
                    <span>+8%</span>
                </div>
            </div>

            <div class="stat-card green">
                <div class="stat-icon">
                    <i class="fas fa-seedling"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ \App\Models\Essence::count() }}</div>
                    <div class="stat-label">Essences</div>
                </div>
                <div class="stat-trend neutral">
                    <i class="fas fa-minus"></i>
                    <span>Stable</span>
                </div>
            </div>

            <div class="stat-card orange">
                <div class="stat-icon">
                    <i class="fas fa-mountain"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ \App\Models\Foret::count() }}</div>
                    <div class="stat-label">Forêts</div>
                </div>
                <div class="stat-trend positive">
                    <i class="fas fa-arrow-up"></i>
                    <span>+5%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Dashboard Grid -->
    <div class="dashboard-main">
        <div class="dashboard-left">
            <!-- Recent Articles -->
            <div class="dashboard-card">
                <div class="card-header">
                    <div class="card-title-section">
                        <h3 class="card-title">Articles Récents</h3>
                        <p class="card-subtitle">Derniers articles ajoutés au système</p>
                    </div>
                    <a href="{{ route('articles.index') }}" class="card-action">
                        <i class="fas fa-external-link-alt"></i>
                        <span>Voir tout</span>
                    </a>
                </div>
                <div class="card-body">
                    @php
                        $recentArticles = \App\Models\Article::with(['essence', 'foret'])
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
                                    <div class="activity-actions">
                                        <a href="{{ route('articles.show', $article) }}" class="action-link">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-inbox"></i>
                            </div>
                            <h4 class="empty-title">Aucun article récent</h4>
                            <p class="empty-description">Commencez par ajouter votre premier article</p>
                            <a href="{{ route('articles.create') }}" class="empty-action">
                                <i class="fas fa-plus"></i>
                                <span>Ajouter un article</span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- System Status -->
            <div class="dashboard-card">
                <div class="card-header">
                    <div class="card-title-section">
                        <h3 class="card-title">Statut du Système</h3>
                        <p class="card-subtitle">État des services et composants</p>
                    </div>
                    <div class="status-overview">
                        <span class="status-badge online">Tous les services opérationnels</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="status-grid">
                        <div class="status-item">
                            <div class="status-indicator online"></div>
                            <div class="status-info">
                                <h6 class="status-title">Base de Données</h6>
                                <p class="status-description">Connectée et opérationnelle</p>
                            </div>
                        </div>
                        
                        <div class="status-item">
                            <div class="status-indicator online"></div>
                            <div class="status-info">
                                <h6 class="status-title">Système de Fichiers</h6>
                                <p class="status-description">Accès en lecture/écriture</p>
                            </div>
                        </div>
                        
                        <div class="status-item">
                            <div class="status-indicator online"></div>
                            <div class="status-info">
                                <h6 class="status-title">Cache</h6>
                                <p class="status-description">Fonctionnel</p>
                            </div>
                        </div>
                        
                        <div class="status-item">
                            <div class="status-indicator online"></div>
                            <div class="status-info">
                                <h6 class="status-title">Sessions</h6>
                                <p class="status-description">Gestion active</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="dashboard-right">
            <!-- Quick Actions -->
            <div class="dashboard-card">
                <div class="card-header">
                    <div class="card-title-section">
                        <h3 class="card-title">Actions Rapides</h3>
                        <p class="card-subtitle">Accès rapide aux fonctionnalités</p>
                    </div>
                </div>
                <div class="card-body">
                    <div class="quick-actions">
                        <a href="{{ route('articles.create') }}" class="quick-action-item">
                            <div class="quick-action-icon">
                                <i class="fas fa-plus"></i>
                            </div>
                            <div class="quick-action-content">
                                <span class="quick-action-title">Nouvel Article</span>
                                <span class="quick-action-desc">Créer un nouvel article</span>
                            </div>
                        </a>
                        
                        <a href="{{ route('reports.index') }}" class="quick-action-item">
                            <div class="quick-action-icon">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <div class="quick-action-content">
                                <span class="quick-action-title">Rapports</span>
                                <span class="quick-action-desc">Consulter les rapports</span>
                            </div>
                        </a>
                        
                        <a href="{{ route('settings.index') }}" class="quick-action-item">
                            <div class="quick-action-icon">
                                <i class="fas fa-cog"></i>
                            </div>
                            <div class="quick-action-content">
                                <span class="quick-action-title">Paramètres</span>
                                <span class="quick-action-desc">Configurer le système</span>
                            </div>
                        </a>
                        
                        <a href="{{ route('auth.users.index') }}" class="quick-action-item">
                            <div class="quick-action-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="quick-action-content">
                                <span class="quick-action-title">Utilisateurs</span>
                                <span class="quick-action-desc">Gérer les utilisateurs</span>
                            </div>
                        </a>

                        <a href="{{ route('excel.index') }}" class="quick-action-item">
                            <div class="quick-action-icon">
                                <i class="fas fa-file-excel"></i>
                            </div>
                            <div class="quick-action-content">
                                <span class="quick-action-title">Import/Export</span>
                                <span class="quick-action-desc">Gérer les données</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Summary -->
            <div class="dashboard-card">
                <div class="card-header">
                    <div class="card-title-section">
                        <h3 class="card-title">Activité Récente</h3>
                        <p class="card-subtitle">Résumé des dernières actions</p>
                    </div>
                </div>
                <div class="card-body">
                    <div class="activity-summary">
                        <div class="summary-item">
                            <div class="summary-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="summary-content">
                                <span class="summary-value">5</span>
                                <span class="summary-label">Actions aujourd'hui</span>
                            </div>
                        </div>
                        <div class="summary-item">
                            <div class="summary-icon">
                                <i class="fas fa-calendar-week"></i>
                            </div>
                            <div class="summary-content">
                                <span class="summary-value">23</span>
                                <span class="summary-label">Cette semaine</span>
                            </div>
                        </div>
                        <div class="summary-item">
                            <div class="summary-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div class="summary-content">
                                <span class="summary-value">89</span>
                                <span class="summary-label">Ce mois</span>
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
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
            position: relative;
            box-shadow: 
                0 6px 24px rgba(0, 0, 0, 0.1),
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
            border-radius: 20px;
            box-shadow: 
                0 6px 24px rgba(0, 0, 0, 0.1),
                0 2px 8px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .dashboard-card:hover {
            transform: translateY(-4px);
            box-shadow: 
                0 16px 48px rgba(0, 0, 0, 0.15),
                0 4px 16px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            padding: 1.5rem;
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
            padding: 1.5rem;
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
            padding: 1.25rem;
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
            gap: 0.75rem;
        }

        .quick-action-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
            padding: 1.5rem 1.25rem;
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
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
        }

        .status-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.25rem;
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

        .welcome-header {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            padding: 2.5rem 1.5rem;
            border-radius: 20px;
            margin-bottom: 1.5rem;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
        }

        .welcome-content {
            flex: 1;
            color: white;
        }

        .welcome-text h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .welcome-subtitle {
            font-size: 1.125rem;
            margin-bottom: 1rem;
        }

        .welcome-date {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .welcome-actions {
            display: flex;
            gap: 1rem;
        }

        .welcome-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            padding: 0.875rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.95rem;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .welcome-btn.primary {
            background: white;
            color: var(--primary-color);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .welcome-btn.primary:hover {
            background: #f3f4f6;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
        }

        .welcome-btn.secondary {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .welcome-btn.secondary:hover {
            background: rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.4);
        }

        .stats-section {
            margin-bottom: 1.5rem;
        }

        .stats-header {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .section-title {
            font-size: 1.875rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .section-subtitle {
            font-size: 1rem;
            color: var(--text-secondary);
            margin-bottom: 1.5rem;
        }

        .dashboard-main {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 1.5rem;
        }

        .dashboard-left {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .dashboard-right {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .card-title-section {
            flex: 1;
        }

        .card-action {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 12px;
            background: rgba(248, 250, 252, 0.8);
            color: var(--primary-color);
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .card-action:hover {
            background: rgba(255, 255, 255, 0.9);
            border-color: var(--primary-color);
            transform: translateX(4px);
            text-decoration: none;
            color: var(--primary-color);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .card-action i {
            font-size: 0.875rem;
        }

        .activity-summary {
            display: flex;
            justify-content: space-around;
            background: rgba(248, 250, 252, 0.8);
            border-radius: 16px;
            padding: 1.25rem;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .summary-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
        }

        .summary-icon {
            width: 48px;
            height: 48px;
            border-radius: 16px;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(74, 124, 89, 0.3);
        }

        .summary-icon i {
            color: white;
            font-size: 1.5rem;
        }

        .summary-content {
            text-align: center;
        }

        .summary-value {
            font-size: 1.875rem;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1;
        }

        .summary-label {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .activity-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .action-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 12px;
            background: rgba(248, 250, 252, 0.8);
            color: var(--primary-color);
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .action-link:hover {
            background: rgba(255, 255, 255, 0.9);
            border-color: var(--primary-color);
            transform: translateX(4px);
            text-decoration: none;
            color: var(--primary-color);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .action-link i {
            font-size: 0.875rem;
        }

        .empty-action {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 12px;
            background: rgba(248, 250, 252, 0.8);
            color: var(--primary-color);
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .empty-action:hover {
            background: rgba(255, 255, 255, 0.9);
            border-color: var(--primary-color);
            transform: translateX(4px);
            text-decoration: none;
            color: var(--primary-color);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .empty-action i {
            font-size: 0.875rem;
        }

        .status-overview {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .status-badge {
            padding: 0.375rem 0.75rem;
            border-radius: 10px;
            font-weight: 700;
        }

        .status-badge.online {
            background: rgba(34, 197, 94, 0.2);
            color: #22c55e;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }

        .status-badge.offline {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .quick-action-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.5rem;
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

        .quick-action-content {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .quick-action-title {
            font-weight: 600;
            font-size: 1rem;
            color: var(--text-primary);
        }

        .quick-action-desc {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        @media (max-width: 1024px) {
            .dashboard-main {
                grid-template-columns: 1fr;
            }
            
            .dashboard-left,
            .dashboard-right {
                grid-column: 1 / -1;
            }
            
            .stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }
            
            .quick-actions {
                grid-template-columns: 1fr;
            }
            
            .status-grid {
                grid-template-columns: 1fr;
            }
            
            .stat-card {
                padding: 1.5rem;
            }
            
            .card-header {
                padding: 1.25rem;
            }
            
            .card-body {
                padding: 1.25rem;
            }

            .welcome-header {
                flex-direction: column;
                text-align: center;
                padding: 2rem 1.5rem;
            }

            .welcome-actions {
                flex-direction: column;
                width: 100%;
            }

            .welcome-btn {
                width: 100%;
            }

            .stats-header {
                align-items: center;
            }

            .section-title {
                font-size: 1.5rem;
            }

            .section-subtitle {
                text-align: center;
            }

            .activity-summary {
                flex-direction: column;
                gap: 1rem;
                padding: 1.5rem;
            }

            .summary-item {
                flex-direction: row;
                align-items: center;
                gap: 1rem;
            }

            .summary-icon {
                width: 40px;
                height: 40px;
            }

            .summary-value {
                font-size: 1.5rem;
            }

            .summary-label {
                font-size: 0.8rem;
            }

            .welcome-title {
                font-size: 2rem;
            }

            .welcome-subtitle {
                font-size: 1rem;
            }

            .welcome-date {
                font-size: 0.8rem;
            }

            .dashboard-card {
                margin-bottom: 1rem;
            }

            .card-header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }

            .card-action {
                align-self: flex-start;
            }

            .activity-item {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .activity-actions {
                justify-content: center;
            }

            .quick-action-item {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }

            .quick-action-content {
                align-items: center;
            }
        }

        @media (max-width: 480px) {
            .welcome-header {
                padding: 1.5rem 1rem;
            }

            .welcome-title {
                font-size: 1.75rem;
            }

            .welcome-subtitle {
                font-size: 0.9rem;
            }

            .welcome-date {
                font-size: 0.75rem;
            }

            .stats-grid {
                gap: 0.5rem;
            }

            .stat-card {
                padding: 1.25rem;
            }

            .dashboard-main {
                gap: 0.75rem;
            }

            .dashboard-card {
                margin-bottom: 0.5rem;
            }

            .card-header {
                padding: 1rem;
            }

            .card-body {
                padding: 1rem;
            }

            .activity-item {
                padding: 0.875rem;
            }

            .quick-action-item {
                padding: 0.875rem;
            }

            .status-grid {
                gap: 0.75rem;
            }

            .status-item {
                padding: 0.875rem;
            }

            .activity-summary {
                padding: 0.875rem;
            }

            .summary-item {
                gap: 0.5rem;
            }

            .summary-icon {
                width: 32px;
                height: 32px;
            }

            .summary-value {
                font-size: 1.125rem;
            }

            .summary-label {
                font-size: 0.75rem;
            }
        }

        /* Dark Mode Enhancements */
        .dark-mode .welcome-header {
            background: linear-gradient(135deg, var(--primary-dark), var(--accent-color));
        }

        .dark-mode .dashboard-card {
            background: rgba(31, 41, 55, 0.95);
            border-color: rgba(75, 85, 99, 0.3);
        }

        .dark-mode .card-header {
            background: linear-gradient(135deg, rgba(31, 41, 55, 0.8) 0%, rgba(55, 65, 81, 0.9) 100%);
        }

        .dark-mode .activity-item {
            background: rgba(55, 65, 81, 0.8);
            border-color: rgba(75, 85, 99, 0.3);
        }

        .dark-mode .quick-action-item {
            background: rgba(55, 65, 81, 0.8);
            border-color: rgba(75, 85, 99, 0.3);
        }

        .dark-mode .status-item {
            background: rgba(55, 65, 81, 0.8);
            border-color: rgba(75, 85, 99, 0.3);
        }

        .dark-mode .activity-summary {
            background: rgba(55, 65, 81, 0.8);
            border-color: rgba(75, 85, 99, 0.3);
        }

        .dark-mode .card-action {
            background: rgba(55, 65, 81, 0.8);
            color: var(--primary-color);
            border-color: rgba(75, 85, 99, 0.3);
        }

        .dark-mode .action-link {
            background: rgba(55, 65, 81, 0.8);
            color: var(--primary-color);
            border-color: rgba(75, 85, 99, 0.3);
        }

        .dark-mode .empty-action {
            background: rgba(55, 65, 81, 0.8);
            color: var(--primary-color);
            border-color: rgba(75, 85, 99, 0.3);
        }

        /* Animation Enhancements */
        .welcome-header {
            animation: slideInDown 0.8s ease-out;
        }

        .stats-section {
            animation: slideInUp 0.8s ease-out 0.2s both;
        }

        .dashboard-main {
            animation: slideInUp 0.8s ease-out 0.4s both;
        }

        .dashboard-card {
            animation: fadeInUp 0.6s ease-out;
        }

        .dashboard-card:nth-child(1) { animation-delay: 0.1s; }
        .dashboard-card:nth-child(2) { animation-delay: 0.2s; }
        .dashboard-card:nth-child(3) { animation-delay: 0.3s; }
        .dashboard-card:nth-child(4) { animation-delay: 0.4s; }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Hover Effects */
        .stat-card:hover {
            transform: translateY(-8px) scale(1.02);
        }

        .dashboard-card:hover {
            transform: translateY(-4px);
        }

        .activity-item:hover {
            transform: translateX(6px);
        }

        .quick-action-item:hover {
            transform: translateY(-6px);
        }

        .status-item:hover {
            transform: translateY(-3px);
        }

        /* Focus States for Accessibility */
        .welcome-btn:focus,
        .card-action:focus,
        .action-link:focus,
        .empty-action:focus,
        .quick-action-item:focus {
            outline: 2px solid var(--primary-color);
            outline-offset: 2px;
        }

        /* Loading States */
        .loading {
            opacity: 0.7;
            pointer-events: none;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin: -10px 0 0 -10px;
            border: 2px solid var(--primary-color);
            border-top-color: transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
@endpush 