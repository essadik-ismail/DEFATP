@extends('layouts.app')

@section('title', 'Paramètres - Gestion Forestière')
@section('page-title', 'Paramètres')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center">
                <i class="fas fa-cog text-white text-2xl"></i>
            </div>
            <div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                    Paramètres
                </h1>
                <p class="text-gray-600 text-lg mt-2">Gérez les données de base du système forestier</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Essences Card -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-leaf text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Essences</h3>
                    <p class="text-gray-600 text-sm">Types d'arbres forestiers</p>
                </div>
            </div>
            <div class="mb-4">
                <div class="text-3xl font-bold text-gray-900">{{ \App\Models\Essence::count() }}</div>
                <div class="text-sm text-gray-600">essences</div>
            </div>
            <a href="{{ route('settings.essences') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-300">
                <i class="fas fa-cog"></i>
                <span>Gérer</span>
            </a>
        </div>
        
        <!-- Forêts Card -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-tree text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Forêts</h3>
                    <p class="text-gray-600 text-sm">Zones forestières</p>
                </div>
            </div>
            <div class="mb-4">
                <div class="text-3xl font-bold text-gray-900">{{ \App\Models\Foret::count() }}</div>
                <div class="text-sm text-gray-600">forêts</div>
            </div>
            <a href="{{ route('settings.forets') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-300">
                <i class="fas fa-cog"></i>
                <span>Gérer</span>
            </a>
        </div>
        
        <!-- Nature de Coupes Card -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-yellow-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-cut text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Nature de Coupes</h3>
                    <p class="text-gray-600 text-sm">Méthodes d'exploitation</p>
                </div>
            </div>
            <div class="mb-4">
                <div class="text-3xl font-bold text-gray-900">{{ \App\Models\NatureDeCoupe::count() }}</div>
                <div class="text-sm text-gray-600">types</div>
            </div>
            <a href="{{ route('settings.nature-de-coupes') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-orange-600 to-yellow-600 text-white rounded-lg hover:from-orange-700 hover:to-yellow-700 transition-all duration-300">
                <i class="fas fa-cog"></i>
                <span>Gérer</span>
            </a>
        </div>
        
        <!-- Situations Administratives Card -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-building text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Situations Administratives</h3>
                    <p class="text-gray-600 text-sm">Communes & Provinces</p>
                </div>
            </div>
            <div class="mb-4">
                <div class="text-3xl font-bold text-gray-900">{{ \App\Models\SituationAdministrative::count() }}</div>
                <div class="text-sm text-gray-600">situations</div>
            </div>
            <a href="{{ route('settings.situation-administratives') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-lg hover:from-purple-700 hover:to-pink-700 transition-all duration-300">
                <i class="fas fa-cog"></i>
                <span>Gérer</span>
            </a>
        </div>
        
        <!-- Exploitants Card -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-user-tie text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Exploitants</h3>
                    <p class="text-gray-600 text-sm">Gestion des exploitants</p>
                </div>
            </div>
            <div class="mb-4">
                <div class="text-3xl font-bold text-gray-900">{{ \App\Models\Exploitant::count() }}</div>
                <div class="text-sm text-gray-600">exploitants</div>
            </div>
            <a href="{{ route('exploitants.index') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-lg hover:from-indigo-700 hover:to-blue-700 transition-all duration-300">
                <i class="fas fa-cog"></i>
                <span>Gérer</span>
            </a>
        </div>
        
        <!-- Localisations Card -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-map-marker-alt text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Localisations</h3>
                    <p class="text-gray-600 text-sm">Gestion des localisations</p>
                </div>
            </div>
            <div class="mb-4">
                <div class="text-3xl font-bold text-gray-900">{{ \App\Models\Localisation::count() }}</div>
                <div class="text-sm text-gray-600">localisations</div>
            </div>
            <a href="{{ route('settings.localisations') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-teal-600 to-cyan-600 text-white rounded-lg hover:from-teal-700 hover:to-cyan-700 transition-all duration-300">
                <i class="fas fa-cog"></i>
                <span>Gérer</span>
            </a>
        </div>
        
        <!-- Import/Export Card -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-gray-500 to-slate-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-download text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Import/Export</h3>
                    <p class="text-gray-600 text-sm">Gestion des données</p>
                </div>
            </div>
            <div class="mb-4">
                <div class="text-3xl font-bold text-gray-900">-</div>
                <div class="text-sm text-gray-600">fonctionnalités</div>
            </div>
            <a href="{{ route('excel.index') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-gray-600 to-slate-600 text-white rounded-lg hover:from-gray-700 hover:to-slate-700 transition-all duration-300">
                <i class="fas fa-cog"></i>
                <span>Gérer</span>
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