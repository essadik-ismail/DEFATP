@extends('layouts.app')

@section('title', 'Tableau de Bord - DEFATP')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-50 py-8">
    <div class="container mx-auto px-4">
        <!-- Welcome Guide for New Users -->
        <x-welcome-guide :show="true" />
        
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-4xl font-bold bg-gradient-to-r from-green-600 via-emerald-600 to-teal-600 bg-clip-text text-transparent mb-2">
                        Tableau de Bord
                    </h1>
                    <p class="text-gray-600 text-lg">Vue d'ensemble de votre gestion forestière</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="bg-white/80 backdrop-blur-xl rounded-xl px-4 py-2 border border-gray-200 shadow-sm">
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <i class="fas fa-calendar-alt text-green-600"></i>
                            <span>{{ now()->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions Required Section -->
        @if(isset($actionsRequired) && count($actionsRequired) > 0)
        <div class="mb-8">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-gradient-to-br from-red-500 to-pink-600 shadow-lg">
                    <i class="fas fa-exclamation-circle text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-red-600 to-pink-600">
                        Actions Requises
                    </h2>
                    <p class="text-gray-600">Éléments nécessitant votre attention</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($actionsRequired as $action)
                <div class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:scale-105 cursor-pointer group" onclick="window.location.href='{{ $action['route'] }}'">
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-14 h-14 bg-gradient-to-br {{ $action['color'] }} rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                <i class="fas {{ $action['icon'] }} text-white text-xl"></i>
                            </div>
                            @if($action['priority'] === 'high')
                                <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold animate-pulse">Urgent</span>
                            @elseif($action['priority'] === 'medium')
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">Moyen</span>
                            @else
                                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">Faible</span>
                            @endif
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $action['title'] }}</h3>
                        <p class="text-gray-600 text-sm mb-4">{{ $action['description'] }}</p>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-3xl font-bold bg-gradient-to-r {{ $action['color'] }} bg-clip-text text-transparent">{{ $action['count'] }}</span>
                                <span class="text-sm text-gray-500 ml-1">élément(s)</span>
                            </div>
                            <div class="flex items-center gap-2 text-green-600 font-semibold group-hover:translate-x-1 transition-transform">
                                <span class="text-sm">Voir</span>
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Statistics Cards -->
        <div class="mb-8">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-gradient-to-br from-blue-500 to-indigo-600 shadow-lg">
                    <i class="fas fa-chart-line text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600">
                        Statistiques Générales
                    </h2>
                    <p class="text-gray-600">Vue d'ensemble des données principales</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Total Articles -->
                <div class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 p-6 hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-file-alt text-white text-xl"></i>
                        </div>
                        <div class="text-right">
                            <div class="text-3xl font-bold text-gray-900">{{ number_format($stats['totalArticles']) }}</div>
                            <div class="text-sm text-gray-500">Articles</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 text-sm">
                        <div class="flex-1 bg-gray-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-blue-500 to-cyan-600 h-2 rounded-full" style="width: 100%"></div>
                        </div>
                    </div>
                </div>

                <!-- Articles Vendus -->
                <div class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 p-6 hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-check-circle text-white text-xl"></i>
                        </div>
                        <div class="text-right">
                            <div class="text-3xl font-bold text-gray-900">{{ number_format($stats['vendus']) }}</div>
                            <div class="text-sm text-gray-500">Vendus</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 text-sm">
                        <div class="flex-1 bg-gray-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-green-500 to-emerald-600 h-2 rounded-full transition-all duration-1000" style="width: {{ $stats['vendusPercentage'] }}%"></div>
                        </div>
                        <span class="text-green-600 font-semibold">{{ $stats['vendusPercentage'] }}%</span>
                    </div>
                </div>

                <!-- Articles Invendus -->
                <div class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 p-6 hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-shopping-cart text-white text-xl"></i>
                        </div>
                        <div class="text-right">
                            <div class="text-3xl font-bold text-gray-900">{{ number_format($stats['invendus']) }}</div>
                            <div class="text-sm text-gray-500">Invendus</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 text-sm">
                        <div class="flex-1 bg-gray-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-orange-500 to-red-600 h-2 rounded-full transition-all duration-1000" style="width: {{ $stats['invendusPercentage'] }}%"></div>
                        </div>
                        <span class="text-orange-600 font-semibold">{{ $stats['invendusPercentage'] }}%</span>
                    </div>
                </div>

                <!-- Total Volume -->
                <div class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 p-6 hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-cube text-white text-xl"></i>
                        </div>
                        <div class="text-right">
                            <div class="text-3xl font-bold text-gray-900">{{ number_format($stats['totalVolume'], 2) }}</div>
                            <div class="text-sm text-gray-500">m³</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-purple-600">
                        <i class="fas fa-chart-bar"></i>
                        <span class="font-semibold">Volume total</span>
                    </div>
                </div>
            </div>

            <!-- Financial Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Total Revenue -->
                <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl shadow-xl p-6 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <div class="text-sm font-semibold text-green-100 mb-1">Revenus Totaux</div>
                            <div class="text-4xl font-bold">{{ number_format($stats['totalRevenue'], 2) }} <span class="text-2xl">DH</span></div>
                        </div>
                        <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                            <i class="fas fa-money-bill-wave text-white text-2xl"></i>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 text-green-100 text-sm">
                        <i class="fas fa-arrow-up"></i>
                        <span>Prix de vente total</span>
                    </div>
                </div>

                <!-- Total Retrait -->
                <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl shadow-xl p-6 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <div class="text-sm font-semibold text-blue-100 mb-1">Prix de Retrait</div>
                            <div class="text-4xl font-bold">{{ number_format($stats['totalRetrait'], 2) }} <span class="text-2xl">DH</span></div>
                        </div>
                        <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                            <i class="fas fa-hand-holding-usd text-white text-2xl"></i>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 text-blue-100 text-sm">
                        <i class="fas fa-info-circle"></i>
                        <span>Prix de retrait total</span>
                    </div>
                </div>
            </div>

            <!-- Additional Statistics -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <div class="bg-white/90 backdrop-blur-xl rounded-xl shadow-md border border-white/20 p-4 text-center hover:shadow-lg transition-all">
                    <div class="w-12 h-12 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-tree text-white"></i>
                    </div>
                    <div class="text-2xl font-bold text-gray-900">{{ number_format($stats['totalForests']) }}</div>
                    <div class="text-xs text-gray-500 mt-1">Forêts</div>
                </div>

                <div class="bg-white/90 backdrop-blur-xl rounded-xl shadow-md border border-white/20 p-4 text-center hover:shadow-lg transition-all">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-leaf text-white"></i>
                    </div>
                    <div class="text-2xl font-bold text-gray-900">{{ number_format($stats['totalEssences']) }}</div>
                    <div class="text-xs text-gray-500 mt-1">Essences</div>
                </div>

                <div class="bg-white/90 backdrop-blur-xl rounded-xl shadow-md border border-white/20 p-4 text-center hover:shadow-lg transition-all">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-map-marker-alt text-white"></i>
                    </div>
                    <div class="text-2xl font-bold text-gray-900">{{ number_format($stats['totalLocalisations']) }}</div>
                    <div class="text-xs text-gray-500 mt-1">Localisations</div>
                </div>

                <div class="bg-white/90 backdrop-blur-xl rounded-xl shadow-md border border-white/20 p-4 text-center hover:shadow-lg transition-all">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-user-tie text-white"></i>
                    </div>
                    <div class="text-2xl font-bold text-gray-900">{{ number_format($stats['totalExploitants']) }}</div>
                    <div class="text-xs text-gray-500 mt-1">Exploitants</div>
                </div>

                <div class="bg-white/90 backdrop-blur-xl rounded-xl shadow-md border border-white/20 p-4 text-center hover:shadow-lg transition-all">
                    <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-yellow-600 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-user-check text-white"></i>
                    </div>
                    <div class="text-2xl font-bold text-gray-900">{{ number_format($stats['activeExploitants']) }}</div>
                    <div class="text-xs text-gray-500 mt-1">Actifs</div>
                </div>

                <div class="bg-white/90 backdrop-blur-xl rounded-xl shadow-md border border-white/20 p-4 text-center hover:shadow-lg transition-all">
                    <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-pink-600 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-users text-white"></i>
                    </div>
                    <div class="text-2xl font-bold text-gray-900">{{ number_format($stats['totalUsers']) }}</div>
                    <div class="text-xs text-gray-500 mt-1">Utilisateurs</div>
                </div>
            </div>
        </div>

        <!-- Recent Articles Section -->
        @if(isset($recentArticles) && $recentArticles->count() > 0)
        <div class="mb-8">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-gradient-to-br from-indigo-500 to-purple-600 shadow-lg">
                        <i class="fas fa-clock text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-purple-600">
                            Articles Récents
                        </h2>
                        <p class="text-gray-600">Derniers articles ajoutés</p>
                    </div>
                </div>
                <a href="{{ route('articles.index') }}" class="px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-xl hover:from-indigo-600 hover:to-purple-700 transition-all shadow-lg hover:shadow-xl transform hover:scale-105">
                    <i class="fas fa-arrow-right mr-2"></i>
                    Voir tout
                </a>
            </div>
            <div class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Numéro</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Année</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Forêts</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Essences</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Statut</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($recentArticles as $article)
                            <tr class="hover:bg-gray-50 transition-colors cursor-pointer" onclick="window.location.href='{{ route('articles.show', $article) }}'">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-gray-900">{{ $article->numero ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">{{ $article->annee ?? 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        @forelse($article->forets->take(2) as $foret)
                                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs">{{ $foret->foret }}</span>
                                        @empty
                                            <span class="text-gray-400 text-xs">N/A</span>
                                        @endforelse
                                        @if($article->forets->count() > 2)
                                            <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-xs">+{{ $article->forets->count() - 2 }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        @forelse($article->essences->take(2) as $essence)
                                            <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded text-xs">{{ $essence->essence }}</span>
                                        @empty
                                            <span class="text-gray-400 text-xs">N/A</span>
                                        @endforelse
                                        @if($article->essences->count() > 2)
                                            <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-xs">+{{ $article->essences->count() - 2 }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-600">{{ $article->created_at->format('d/m/Y') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($article->invendu)
                                        <span class="px-3 py-1 bg-orange-100 text-orange-700 rounded-full text-xs font-semibold">Invendu</span>
                                    @else
                                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">Vendu</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        <!-- Quick Actions -->
        <div class="mb-8">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-gradient-to-br from-green-500 to-emerald-600 shadow-lg">
                    <i class="fas fa-bolt text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-green-600 to-emerald-600">
                        Actions Rapides
                    </h2>
                    <p class="text-gray-600">Accès rapide aux fonctionnalités principales</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-lg p-6 text-center border border-white/20 hover:shadow-xl transition-all duration-300 transform hover:scale-105 cursor-pointer group" onclick="window.location.href='{{ route('articles.create') }}'">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg group-hover:scale-110 transition-transform">
                        <i class="fas fa-plus-circle text-white text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Nouvel Article</h3>
                    <p class="text-gray-600 text-sm">Créez un nouvel article forestier</p>
                </div>

                <div class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-lg p-6 text-center border border-white/20 hover:shadow-xl transition-all duration-300 transform hover:scale-105 cursor-pointer group" onclick="window.location.href='{{ route('excel.index') }}'">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg group-hover:scale-110 transition-transform">
                        <i class="fas fa-file-excel text-white text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Import/Export</h3>
                    <p class="text-gray-600 text-sm">Gérez vos données avec Excel</p>
                </div>

                <div class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-lg p-6 text-center border border-white/20 hover:shadow-xl transition-all duration-300 transform hover:scale-105 cursor-pointer group" onclick="window.location.href='{{ route('reports.index') }}'">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg group-hover:scale-110 transition-transform">
                        <i class="fas fa-chart-bar text-white text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Rapports</h3>
                    <p class="text-gray-600 text-sm">Générez des rapports détaillés</p>
                </div>

                <div class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-lg p-6 text-center border border-white/20 hover:shadow-xl transition-all duration-300 transform hover:scale-105 cursor-pointer group" onclick="window.location.href='{{ route('settings.index') }}'">
                    <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-yellow-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg group-hover:scale-110 transition-transform">
                        <i class="fas fa-cog text-white text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Paramètres</h3>
                    <p class="text-gray-600 text-sm">Configurez les données de base</p>
                </div>

                <div class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-lg p-6 text-center border border-white/20 hover:shadow-xl transition-all duration-300 transform hover:scale-105 cursor-pointer group" onclick="window.location.href='{{ route('auth.users.index') }}'">
                    <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-pink-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg group-hover:scale-110 transition-transform">
                        <i class="fas fa-users-cog text-white text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Utilisateurs</h3>
                    <p class="text-gray-600 text-sm">Gérez les comptes utilisateurs</p>
                </div>

                <div class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-lg p-6 text-center border border-white/20 hover:shadow-xl transition-all duration-300 transform hover:scale-105 cursor-pointer group" onclick="window.location.href='{{ route('articles.index') }}'">
                    <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg group-hover:scale-110 transition-transform">
                        <i class="fas fa-search text-white text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Rechercher</h3>
                    <p class="text-gray-600 text-sm">Trouvez rapidement vos articles</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
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
    
    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out;
    }
    
    .stat-card {
        animation: fadeInUp 0.6s ease-out;
        animation-fill-mode: both;
    }
    
    .stat-card:nth-child(1) { animation-delay: 0.1s; }
    .stat-card:nth-child(2) { animation-delay: 0.2s; }
    .stat-card:nth-child(3) { animation-delay: 0.3s; }
    .stat-card:nth-child(4) { animation-delay: 0.4s; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add animation classes
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach(card => {
        card.classList.add('animate-fade-in-up');
    });
    
    // Animate progress bars on scroll
    const observerOptions = {
        threshold: 0.5,
        rootMargin: '0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const progressBar = entry.target.querySelector('.bg-gradient-to-r');
                if (progressBar) {
                    const width = progressBar.style.width;
                    progressBar.style.width = '0%';
                    setTimeout(() => {
                        progressBar.style.width = width;
                    }, 100);
                }
            }
        });
    }, observerOptions);
    
    document.querySelectorAll('.bg-white\\/90').forEach(card => {
        observer.observe(card);
    });
});
</script>
@endpush
@endsection
