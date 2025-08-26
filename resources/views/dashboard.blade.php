@extends('layouts.app')

@section('title', 'Tableau de Bord - SylvaNet')

@section('content')


<!-- Main Content Area -->
<div class="main-content">
    <!-- Welcome Guide for New Users -->
    <x-welcome-guide :show="true" />
    
    <!-- Welcome Card -->
    <div class="welcome-section mb-8">
        <div class="glassmorphism-card p-8 text-center">
            <div class="welcome-icon mb-4">
                <i class="fas fa-tree text-6xl text-green-600"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Bienvenue sur SylvaNet</h1>
            <p class="text-xl text-gray-600 mb-6">Votre plateforme de gestion forestière intelligente</p>
            <div class="flex justify-center space-x-4">
                <a href="{{ route('articles.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Nouvel Article
                </a>
                <a href="{{ route('articles.index') }}" class="btn btn-outline">
                    <i class="fas fa-list me-2"></i>Voir les Articles
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Overview -->
    <div class="stats-section mb-8">
        <h2 class="section-title mb-6">Vue d'Ensemble</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Articles Overview -->
            <div class="glassmorphism-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="stats-icon bg-blue-100 text-blue-600">
                        <i class="fas fa-file-alt text-2xl"></i>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold text-gray-900">{{ $stats['totalArticles'] ?? 0 }}</div>
                        <div class="text-sm text-gray-600">Total Articles</div>
                    </div>
                </div>
                <div class="flex items-center text-sm">
                    <span class="text-green-600 font-medium">{{ $stats['validatedArticles'] ?? 0 }} validés</span>
                    <span class="mx-2">•</span>
                    <span class="text-orange-600">{{ $stats['pendingArticles'] ?? 0 }} en attente</span>
                </div>
            </div>

            <!-- Financial Overview -->
            <div class="glassmorphism-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="stats-icon bg-green-100 text-green-600">
                        <i class="fas fa-coins text-2xl"></i>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold text-gray-900">{{ number_format($stats['totalRevenue'] ?? 0, 0) }} DH</div>
                        <div class="text-sm text-gray-600">Chiffre d'Affaires</div>
                    </div>
                </div>
                <div class="flex items-center text-sm">
                    <span class="text-green-600 font-medium">{{ $stats['soldArticles'] ?? 0 }} vendus</span>
                    <span class="mx-2">•</span>
                    <span class="text-blue-600">{{ $stats['totalVolume'] ?? 0 }} m³</span>
                </div>
            </div>

            <!-- Forest Overview -->
            <div class="glassmorphism-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="stats-icon bg-purple-100 text-purple-600">
                        <i class="fas fa-tree text-2xl"></i>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold text-gray-900">{{ $stats['totalForests'] ?? 0 }}</div>
                        <div class="text-sm text-gray-600">Forêts Gérées</div>
                    </div>
                </div>
                <div class="flex items-center text-sm">
                    <span class="text-green-600 font-medium">{{ $stats['totalEssences'] ?? 0 }} essences</span>
                    <span class="mx-2">•</span>
                    <span class="text-blue-600">{{ $stats['totalLocalisations'] ?? 0 }} zones</span>
                </div>
            </div>

            <!-- Operators Overview -->
            <div class="glassmorphism-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="stats-icon bg-orange-100 text-orange-600">
                        <i class="fas fa-users text-2xl"></i>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold text-gray-900">{{ $stats['totalExploitants'] ?? 0 }}</div>
                        <div class="text-sm text-gray-600">Exploitants</div>
                    </div>
                </div>
                <div class="flex items-center text-sm">
                    <span class="text-green-600 font-medium">{{ $stats['activeExploitants'] ?? 0 }} actifs</span>
                    <span class="mx-2">•</span>
                    <span class="text-blue-600">{{ $stats['totalUsers'] ?? 0 }} utilisateurs</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions-section mb-8">
        <h2 class="section-title mb-6">Actions Rapides</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="glassmorphism-card p-6 text-center hover-lift cursor-pointer" onclick="window.location.href='{{ route('articles.create') }}'">
                <div class="quick-action-icon bg-blue-100 text-blue-600 mb-4">
                    <i class="fas fa-plus-circle text-3xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Nouvel Article</h3>
                <p class="text-gray-600">Créez un nouvel article forestier en quelques étapes simples</p>
            </div>

            <div class="glassmorphism-card p-6 text-center hover-lift cursor-pointer" onclick="window.location.href='{{ route('excel.index') }}'">
                <div class="quick-action-icon bg-green-100 text-green-600 mb-4">
                    <i class="fas fa-file-excel text-3xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Import/Export</h3>
                <p class="text-gray-600">Gérez vos données avec Excel pour une manipulation en masse</p>
            </div>

            <div class="glassmorphism-card p-6 text-center hover-lift cursor-pointer" onclick="window.location.href='{{ route('reports.index') }}'">
                <div class="quick-action-icon bg-purple-100 text-purple-600 mb-4">
                    <i class="fas fa-chart-bar text-3xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Rapports</h3>
                <p class="text-gray-600">Générez des rapports détaillés sur vos activités forestières</p>
            </div>

            <div class="glassmorphism-card p-6 text-center hover-lift cursor-pointer" onclick="window.location.href='{{ route('settings.index') }}'">
                <div class="quick-action-icon bg-orange-100 text-orange-600 mb-4">
                    <i class="fas fa-cog text-3xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Paramètres</h3>
                <p class="text-gray-600">Configurez les données de base : forêts, essences, localisations</p>
            </div>

            <div class="glassmorphism-card p-6 text-center hover-lift cursor-pointer" onclick="window.location.href='{{ route('auth.users.index') }}'">
                <div class="quick-action-icon bg-red-100 text-red-600 mb-4">
                    <i class="fas fa-users-cog text-3xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Utilisateurs</h3>
                <p class="text-gray-600">Gérez les comptes utilisateurs et les permissions</p>
            </div>

            <div class="glassmorphism-card p-6 text-center hover-lift cursor-pointer" onclick="window.location.href='{{ route('articles.index') }}'">
                <div class="quick-action-icon bg-indigo-100 text-indigo-600 mb-4">
                    <i class="fas fa-search text-3xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Rechercher</h3>
                <p class="text-gray-600">Trouvez rapidement vos articles avec des filtres avancés</p>
            </div>
        </div>
    </div>



    <!-- Quick Tips -->
    <div class="quick-tips-section">
        <h2 class="section-title mb-6">Conseils Rapides</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="glassmorphism-card p-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-lightbulb text-blue-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="font-medium text-gray-900 mb-2">Utilisez les Filtres</h3>
                        <p class="text-gray-600">Les filtres vous aident à trouver rapidement les articles selon différents critères : forêt, essence, date, etc.</p>
                    </div>
                </div>
            </div>

            <div class="glassmorphism-card p-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-download text-green-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="font-medium text-gray-900 mb-2">Exportez vos Données</h3>
                        <p class="text-gray-600">Utilisez la fonction d'export Excel pour sauvegarder et analyser vos données hors ligne.</p>
                    </div>
                </div>
            </div>

            <div class="glassmorphism-card p-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-chart-line text-purple-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="font-medium text-gray-900 mb-2">Suivez vos Statistiques</h3>
                        <p class="text-gray-600">Consultez régulièrement les rapports pour suivre l'évolution de vos activités forestières.</p>
                    </div>
                </div>
            </div>

            <div class="glassmorphism-card p-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-save text-orange-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="font-medium text-gray-900 mb-2">Sauvegardez Régulièrement</h3>
                        <p class="text-gray-600">N'oubliez pas de sauvegarder vos données et de valider vos articles avant la vente.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add click handlers for quick action cards
    document.querySelectorAll('.quick-action-icon').forEach(icon => {
        icon.parentElement.addEventListener('click', function() {
            const action = this.querySelector('h3').textContent;
            console.log('Quick action clicked:', action);
        });
    });
    
    // Add hover effects for cards
    document.querySelectorAll('.glassmorphism-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});
</script>
@endpush 