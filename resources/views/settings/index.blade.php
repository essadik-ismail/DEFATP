@extends('layouts.app')

@section('title', 'Paramètres - Gestion Forestière')
@section('page-title', 'Paramètres')

@section('content')
    <div class="settings-grid">
        <div class="settings-card">
            <div class="card-icon">
                <i class="fas fa-seedling"></i>
            </div>
            <div class="card-content">
                <h4>Essences</h4>
                <p>Types d'arbres forestiers</p>
                <div class="card-stats">
                    <span class="stat-number">{{ \App\Models\Essence::count() }}</span>
                    <span class="stat-label">essences</span>
                </div>
            </div>
            <div class="card-actions">
                <a href="{{ route('settings.essences') }}" class="btn btn-primary">
                    <i class="fas fa-cog me-2"></i>Gérer
                </a>
            </div>
        </div>
        
        <div class="settings-card">
            <div class="card-icon">
                <i class="fas fa-tree"></i>
            </div>
            <div class="card-content">
                <h4>Forêts</h4>
                <p>Zones forestières</p>
                <div class="card-stats">
                    <span class="stat-number">{{ \App\Models\Foret::count() }}</span>
                    <span class="stat-label">forêts</span>
                </div>
            </div>
            <div class="card-actions">
                <a href="{{ route('settings.forets') }}" class="btn btn-primary">
                    <i class="fas fa-cog me-2"></i>Gérer
                </a>
            </div>
        </div>
        
        <div class="settings-card">
            <div class="card-icon">
                <i class="fas fa-cut"></i>
            </div>
            <div class="card-content">
                <h4>Nature de Coupes</h4>
                <p>Méthodes d'exploitation</p>
                <div class="card-stats">
                    <span class="stat-number">{{ \App\Models\NatureDeCoupe::count() }}</span>
                    <span class="stat-label">types</span>
                </div>
            </div>
            <div class="card-actions">
                <a href="{{ route('settings.nature-de-coupes') }}" class="btn btn-primary">
                    <i class="fas fa-cog me-2"></i>Gérer
                </a>
            </div>
        </div>
        
        <div class="settings-card">
            <div class="card-icon">
                <i class="fas fa-map-marker-alt"></i>
            </div>
            <div class="card-content">
                <h4>Situations Administratives</h4>
                <p>Communes & Provinces</p>
                <div class="card-stats">
                    <span class="stat-number">{{ \App\Models\SituationAdministrative::count() }}</span>
                    <span class="stat-label">situations</span>
                </div>
            </div>
            <div class="card-actions">
                <a href="{{ route('settings.situation-administratives') }}" class="btn btn-primary">
                    <i class="fas fa-cog me-2"></i>Gérer
                </a>
            </div>
        </div>
        
        <div class="settings-card">
            <div class="card-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="card-content">
                <h4>Exploitants</h4>
                <p>Gestion des exploitants</p>
                <div class="card-stats">
                    <span class="stat-number">{{ \App\Models\Exploitant::count() }}</span>
                    <span class="stat-label">exploitants</span>
                </div>
            </div>
            <div class="card-actions">
                <a href="{{ route('settings.exploitants') }}" class="btn btn-primary">
                    <i class="fas fa-cog me-2"></i>Gérer
                </a>
            </div>
        </div>
        
        <div class="settings-card">
            <div class="card-icon">
                <i class="fas fa-gavel"></i>
            </div>
            <div class="card-content">
                <h4>Sessions d'Adjudication</h4>
                <p>Gestion des sessions</p>
                <div class="card-stats">
                    <span class="stat-number">{{ \App\Models\SessionAdjudication::count() }}</span>
                    <span class="stat-label">sessions</span>
                </div>
            </div>
            <div class="card-actions">
                <a href="{{ route('settings.session-adjudications') }}" class="btn btn-primary">
                    <i class="fas fa-cog me-2"></i>Gérer
                </a>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .settings-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        padding: 1rem 0;
    }

    .settings-card {
        background: var(--card-bg);
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .settings-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(135deg, #1e293b 0%, #4a7c59 50%, #e67e22 100%);
    }

    .settings-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }

    .card-icon {
        width: 60px;
        height: 60px;
        border-radius: 16px;
        background: linear-gradient(135deg, #1e293b 0%, #4a7c59 50%, #e67e22 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.5rem;
    }

    .card-icon i {
        color: white;
        font-size: 1.5rem;
    }

    .card-content {
        flex: 1;
    }

    .card-content h4 {
        margin: 0 0 0.5rem 0;
        font-weight: 700;
        color: var(--text-primary);
        font-size: 1.25rem;
    }

    .card-content p {
        margin: 0 0 1rem 0;
        color: var(--text-secondary);
        font-size: 0.875rem;
    }

    .card-stats {
        display: flex;
        align-items: baseline;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        background: linear-gradient(135deg, #1e293b 0%, #4a7c59 50%, #e67e22 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        line-height: 1;
    }

    .stat-label {
        color: var(--text-secondary);
        font-size: 0.875rem;
        font-weight: 500;
    }

    .card-actions {
        margin-top: auto;
    }

    .card-actions .btn {
        width: 100%;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    @media (max-width: 768px) {
        .settings-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        
        .settings-card {
            padding: 1.5rem;
        }
        
        .card-icon {
            width: 50px;
            height: 50px;
        }
        
        .card-icon i {
            font-size: 1.25rem;
        }
        
        .card-content h4 {
            font-size: 1.125rem;
        }
        
        .stat-number {
            font-size: 1.75rem;
        }
    }
</style>
@endpush 