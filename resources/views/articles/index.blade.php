@extends('layouts.app')

@section('title', 'Articles Forestiers - DEFATP')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center">
                <i class="fas fa-file-alt text-white text-2xl"></i>
            </div>
            <div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                    Articles Forestiers
                </h1>
                <p class="text-gray-600 text-lg mt-2">Gérez et consultez tous les articles forestiers du système</p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Articles Overview Card -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-file-alt text-white text-xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-900">
                        Articles
                        @if(request()->hasAny(['start_date', 'end_date', 'search', 'type', 'status', 'year']))
                            <span class="text-xs text-blue-600 font-normal">(filtrés)</span>
                        @endif
                    </h3>
                    <p class="text-gray-600 text-sm">{{ $articles->total() }} total</p>
                    <div class="flex items-center text-xs text-gray-500 mt-1">
                        <span class="text-green-600 font-medium">{{ $stats['sold_articles'] }} vendus</span>
                        <span class="mx-1">•</span>
                        <span class="text-orange-600">{{ $stats['unsold_articles'] }} invendus</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Financial Overview Card -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-coins text-white text-xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-900">
                        Revenus
                        @if(request()->hasAny(['start_date', 'end_date', 'search', 'type', 'status', 'year']))
                            <span class="text-xs text-blue-600 font-normal">(filtrés)</span>
                        @endif
                    </h3>
                    <p class="text-gray-600 text-sm">{{ number_format($stats['total_revenue'], 0) }} DH</p>
                    <div class="flex items-center text-xs text-gray-500 mt-1">
                        <span class="text-blue-600">{{ number_format($stats['total_retrait'], 0) }} DH retrait</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Volume Overview Card -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-yellow-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-cube text-white text-xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-900">
                        Volume
                        @if(request()->hasAny(['start_date', 'end_date', 'search', 'type', 'status', 'year']))
                            <span class="text-xs text-blue-600 font-normal">(filtré)</span>
                        @endif
                    </h3>
                    <p class="text-gray-600 text-sm">{{ number_format($stats['total_volume'], 2) }} m³</p>
                    <div class="flex items-center text-xs text-gray-500 mt-1">
                        <span class="text-blue-600">Bois total</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- System Overview Card -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-database text-white text-xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-900">Système</h3>
                    <p class="text-gray-600 text-sm">{{ $stats['total_forets'] }} forêts</p>
                    <div class="flex items-center text-xs text-gray-500 mt-1">
                        <span class="text-blue-600">{{ $stats['total_essences'] }} essences</span>
                        <span class="mx-1">•</span>
                        <span class="text-green-600">{{ $stats['total_exploitants'] }} exploitants</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Date Filter Section -->
    <div class="mb-8">
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Filtre par Période</h2>
                    <p class="text-gray-600">Sélectionnez une période pour filtrer les articles et statistiques</p>
                </div>
            </div>
            
            <form method="GET" action="{{ route('articles.index') }}" id="dateFilterForm" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="form-group">
                        <label for="start_date" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calendar-plus text-purple-500 mr-2"></i>
                            Date de Début
                        </label>
                        <input type="date" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400" 
                               id="start_date" 
                               name="start_date" 
                               value="{{ request('start_date', now()->startOfMonth()->format('Y-m-d')) }}">
                    </div>
                    
                    <div class="form-group">
                        <label for="end_date" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calendar-minus text-purple-500 mr-2"></i>
                            Date de Fin
                        </label>
                        <input type="date" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400" 
                               id="end_date" 
                               name="end_date" 
                               value="{{ request('end_date', now()->format('Y-m-d')) }}">
                    </div>
                    
                    <div class="form-group flex items-end">
                        <div class="flex gap-3 w-full">
                            <button type="submit" 
                                    class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                                <i class="fas fa-filter"></i>
                                <span>Filtrer</span>
                            </button>
                            <button type="button" 
                                    onclick="resetDateFilter()"
                                    class="px-4 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300"
                                    title="Réinitialiser">
                                <i class="fas fa-undo"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                @if(request('start_date') || request('end_date'))
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4 border border-blue-200">
                        <div class="flex items-center gap-2 text-blue-700">
                            <i class="fas fa-info-circle"></i>
                            <span class="font-semibold">Période sélectionnée :</span>
                            <span>{{ request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') : 'Début' }}</span>
                            <span>→</span>
                            <span>{{ request('end_date') ? \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') : 'Fin' }}</span>
                        </div>
                    </div>
                @endif
                
                <!-- Preserve other filters -->
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                @if(request('type'))
                    <input type="hidden" name="type" value="{{ request('type') }}">
                @endif
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                @if(request('year'))
                    <input type="hidden" name="year" value="{{ request('year') }}">
                @endif
                @if(request('per_page'))
                    <input type="hidden" name="per_page" value="{{ request('per_page') }}">
                @endif
            </form>
        </div>
    </div>

    <!-- Articles by Type Statistics -->
    <div class="mb-8">
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-chart-pie text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">
                        Répartition par Type
                        @if(request()->hasAny(['start_date', 'end_date', 'search', 'type', 'status', 'year']))
                            <span class="text-lg text-blue-600 font-normal">(Filtres actifs)</span>
                        @endif
                    </h2>
                    <p class="text-gray-600">Distribution des articles selon leur type</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4 border border-blue-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold text-blue-900">Appels d'Offre</h3>
                            <p class="text-2xl font-bold text-blue-600">{{ $stats['articles_by_type']['appel_doffre'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-gavel text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-4 border border-green-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold text-green-900">Adjudications</h3>
                            <p class="text-2xl font-bold text-green-600">{{ $stats['articles_by_type']['adjudication'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-hammer text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-4 border border-purple-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold text-purple-900">Marchés Négociés</h3>
                            <p class="text-2xl font-bold text-purple-600">{{ $stats['articles_by_type']['marche_negocié'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-handshake text-purple-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Articles Data Table -->
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-table text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Liste des Articles</h2>
                    <p class="text-gray-600">Gérez et consultez tous les articles forestiers</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('articles.create.simple') }}" 
                   class="inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-br from-blue-500 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-file-excel"></i>
                    <span class="font-semibold">Création Simple</span>
                </a>
                <a href="{{ route('articles.create') }}" 
                   class="inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-br from-green-500 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-plus"></i>
                    <span class="font-semibold">Nouvel Article</span>
                </a>
            </div>
        </div>
        <!-- Search and Filter Section -->
        <div class="bg-gradient-to-r from-gray-50 to-slate-50 rounded-2xl p-6 border border-gray-200 mb-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-gray-500 to-slate-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-search text-white"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Recherche et Filtres Avancés</h3>
            </div>
            
            <form method="GET" action="{{ route('articles.index') }}" id="filterForm">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                    <div class="form-group">
                        <label for="search" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-search text-blue-500 mr-1"></i>Recherche
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   class="form-input w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                                   name="search" 
                                   id="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Numéro, année, forêt, essence...">
                            <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <i class="fas fa-search"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="type" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-tag text-green-500 mr-1"></i>Type
                        </label>
                        <select class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 hover:border-gray-400" 
                                name="type" id="type">
                            <option value="">Tous les types</option>
                            <option value="appel_doffre" {{ request('type') == 'appel_doffre' ? 'selected' : '' }}>Appel d'Offre</option>
                            <option value="adjudication" {{ request('type') == 'adjudication' ? 'selected' : '' }}>Adjudication</option>
                            <option value="marche_negocié" {{ request('type') == 'marche_negocié' ? 'selected' : '' }}>Marché Négocié</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-check-circle text-orange-500 mr-1"></i>Statut
                        </label>
                        <select class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 hover:border-gray-400" 
                                name="status" id="status">
                            <option value="">Tous les statuts</option>
                            <option value="sold" {{ request('status') == 'sold' ? 'selected' : '' }}>Vendus</option>
                            <option value="unsold" {{ request('status') == 'unsold' ? 'selected' : '' }}>Invendus</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="year" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calendar text-purple-500 mr-1"></i>Année
                        </label>
                        <select class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400" 
                                name="year" id="year">
                            <option value="">Toutes les années</option>
                            @for($year = now()->year; $year >= 2020; $year--)
                                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex gap-3">
                        <button type="submit" 
                                class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                            <i class="fas fa-filter"></i>
                            <span>Filtrer</span>
                        </button>
                        <button type="button" 
                                onclick="clearFilters()"
                                class="inline-flex items-center gap-2 px-4 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300"
                                title="Effacer les filtres">
                            <i class="fas fa-times"></i>
                            <span>Effacer</span>
                        </button>
                    </div>
                    
                    @if(request()->hasAny(['search', 'type', 'status', 'year']))
                        <div class="bg-blue-50 text-blue-700 px-4 py-2 rounded-lg text-sm">
                            <i class="fas fa-info-circle mr-1"></i>
                            Filtres actifs
                        </div>
                    @endif
                </div>
                
                <!-- Hidden fields to preserve pagination -->
                @if(request('per_page'))
                    <input type="hidden" name="per_page" value="{{ request('per_page') }}">
                @endif
            </form>
        </div>
            
        <!-- Actions and Pagination Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div class="flex items-center gap-4">
                <label for="perPageSelect" class="text-sm font-semibold text-gray-700">Articles par page:</label>
                <select class="form-input px-4 py-2 border border-gray-300 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                        id="perPageSelect" onchange="changePerPage()">
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                    <option value="15" {{ request('per_page') == 15 ? 'selected' : '' }}>15</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                </select>
            </div>
            <div class="flex gap-3">
                <button type="button" 
                        class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-300"
                        onclick="refreshTable()">
                    <i class="fas fa-sync-alt"></i>
                    <span>Actualiser</span>
                </button>
                <button type="button" 
                        class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-300"
                        onclick="exportAllArticles()">
                    <i class="fas fa-download"></i>
                    <span>Exporter Tout</span>
                </button>
            </div>
        </div>
            
        <!-- Data Table -->
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-gray-50 to-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Année</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Numéro</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date d'Adjudication</th>
                            
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Prix de Retrait</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Prix de Vente</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($articles as $article)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $article->id }}</td>
                                <td>
                                    <span class="badge bg-primary">{{ $article->annee ?? '-' }}</span>
                                </td>
                                <td>
                                    @if($article->numero)
                                        <span class="badge bg-secondary">{{ $article->numero }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($article->date_adjudication)
                                        {{ $article->date_adjudication->format('d/m/Y') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                
                                <td>
                                    @if($article->prix_de_retrait)
                                        <span class="badge bg-warning text-dark">
                                            {{ number_format($article->prix_de_retrait, 2) }} DH
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($article->prix_vente)
                                        <span class="badge bg-success">
                                            {{ number_format($article->prix_vente, 2) }} DH
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($article->type)
                                        <span class="badge {{ $article->type == 'appel_doffre' ? 'bg-info' : 'bg-primary' }}">
                                            {{ $article->type == 'appel_doffre' ? 'Appel d\'Offre' : 'Adjudication' }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex items-center gap-1">
                                        <!-- View Action -->
                                        <a href="{{ route('articles.show', $article) }}" 
                                           class="inline-flex items-center justify-center w-8 h-8 bg-blue-100 hover:bg-blue-200 text-blue-600 rounded-lg transition-colors duration-200" 
                                           title="Voir les détails">
                                            <i class="fas fa-eye text-sm"></i>
                                        </a>
                                        
                                        <!-- Edit Action -->
                                        <a href="{{ route('articles.edit', $article) }}" 
                                           class="inline-flex items-center justify-center w-8 h-8 bg-orange-100 hover:bg-orange-200 text-orange-600 rounded-lg transition-colors duration-200" 
                                           title="Modifier l'article">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>
                                        
                                        
                                        <!-- Export Action -->
                                        <button type="button" 
                                                onclick="exportArticle({{ $article->id }})"
                                                class="inline-flex items-center justify-center w-8 h-8 bg-green-100 hover:bg-green-200 text-green-600 rounded-lg transition-colors duration-200" 
                                                title="Exporter l'article">
                                            <i class="fas fa-download text-sm"></i>
                                        </button>
                                        
                                        <!-- Delete Action -->
                                        <form action="{{ route('articles.destroy', $article) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?')"
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg transition-colors duration-200" 
                                                    title="Supprimer l'article">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-file-alt text-4xl mb-2 d-block"></i>
                                            <p class="h5 mb-2">Aucun article créé</p>
                                            <p class="text-muted mb-3">Commencez par créer votre premier article forestier</p>
                                            <a href="{{ route('articles.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus me-2"></i>Créer le Premier Article
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
        @if($articles->hasPages())
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="text-sm text-gray-600">
                        Affichage de {{ $articles->firstItem() ?? 0 }} à {{ $articles->lastItem() ?? 0 }} 
                        sur {{ $articles->total() }} articles
                    </div>
                    <div class="pagination-controls">
                        {{ $articles->appends(request()->query())->links() }}
                    </div>
                    <div class="text-sm text-gray-500">
                        {{ $articles->perPage() }} par page
                    </div>
                </div>
            </div>
        @endif
        </div>
    </div>
</div>

    <!-- Quick Create Cards -->
    <!-- <div class="quick-create-section">
        <h2 class="section-title">Création Rapide</h2>
        <div class="quick-create-grid">
            <a href="{{ route('settings.essences') }}" class="quick-create-card">
                <div class="quick-create-icon essence">
                    <i class="fas fa-leaf"></i>
                </div>
                <h4>Nouvelle Essence</h4>
                <p>Ajouter un type d'arbre</p>
            </a>
            
            <a href="{{ route('settings.forets') }}" class="quick-create-card">
                <div class="quick-create-icon foret">
                    <i class="fas fa-tree"></i>
                </div>
                <h4>Nouvelle Forêt</h4>
                <p>Ajouter une zone forestière</p>
            </a>
            
            <a href="{{ route('settings.localisations') }}" class="quick-create-card">
                <div class="quick-create-icon localisation">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h4>Nouvelle Localisation</h4>
                <p>Ajouter une zone géographique</p>
            </a>
            
            <a href="{{ route('settings.situation-administratives') }}" class="quick-create-card">
                <div class="quick-create-icon situation">
                    <i class="fas fa-building"></i>
                </div>
                <h4>Nouvelle Situation</h4>
                <p>Ajouter une situation administrative</p>
            </a>
            
            <a href="{{ route('exploitants.index') }}" class="quick-create-card">
                <div class="quick-create-icon exploitant">
                    <i class="fas fa-user-tie"></i>
                </div>
                <h4>Nouvel Exploitant</h4>
                <p>Ajouter un opérateur</p>
            </a>
            
            <a href="{{ route('settings.nature-de-coupes') }}" class="quick-create-card">
                <div class="quick-create-icon nature">
                    <i class="fas fa-cut"></i>
                </div>
                <h4>Nouvelle Nature</h4>
                <p>Ajouter un type de coupe</p>
            </a>
        </div>
    </div> -->

    <!-- Entity Data Management Section -->
    <div class="mb-8">
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-database text-white text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-4xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">
                        Données des Entités
                    </h2>
                    <p class="text-gray-600 text-lg mt-2">Gérez les données de base du système forestier</p>
                </div>
            </div>
            
            <!-- Modern Tabs Section -->
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl overflow-hidden">
                <div class="bg-gradient-to-r from-slate-50/80 to-gray-50/80 backdrop-blur-sm border-b border-white/20 p-2">
                    <div class="flex flex-wrap gap-2">
                        <button class="tab-button active group" data-tab="essences">
                            <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mr-3 shadow-lg group-hover:shadow-xl transition-all duration-300 group-hover:scale-110">
                                <i class="fas fa-leaf text-white text-sm"></i>
                            </div>
                            <div class="text-left">
                                <span class="block font-semibold">Essences</span>
                                <span class="text-xs text-gray-500 group-hover:text-gray-700">Gestion des essences</span>
                            </div>
                            <div class="tab-indicator"></div>
                        </button>
                        <button class="tab-button group" data-tab="forets">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mr-3 shadow-lg group-hover:shadow-xl transition-all duration-300 group-hover:scale-110">
                                <i class="fas fa-tree text-white text-sm"></i>
                            </div>
                            <div class="text-left">
                                <span class="block font-semibold">Forêts</span>
                                <span class="text-xs text-gray-500 group-hover:text-gray-700">Zones forestières</span>
                            </div>
                            <div class="tab-indicator"></div>
                        </button>
                        <button class="tab-button group" data-tab="localisations">
                            <div class="w-10 h-10 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-xl flex items-center justify-center mr-3 shadow-lg group-hover:shadow-xl transition-all duration-300 group-hover:scale-110">
                                <i class="fas fa-map-marker-alt text-white text-sm"></i>
                            </div>
                            <div class="text-left">
                                <span class="block font-semibold">Localisations</span>
                                <span class="text-xs text-gray-500 group-hover:text-gray-700">Points géographiques</span>
                            </div>
                            <div class="tab-indicator"></div>
                        </button>
                        <button class="tab-button group" data-tab="situations">
                            <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center mr-3 shadow-lg group-hover:shadow-xl transition-all duration-300 group-hover:scale-110">
                                <i class="fas fa-building text-white text-sm"></i>
                            </div>
                            <div class="text-left">
                                <span class="block font-semibold">Situations</span>
                                <span class="text-xs text-gray-500 group-hover:text-gray-700">Administratives</span>
                            </div>
                            <div class="tab-indicator"></div>
                        </button>
                        <button class="tab-button group" data-tab="natures-coupe">
                            <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-yellow-600 rounded-xl flex items-center justify-center mr-3 shadow-lg group-hover:shadow-xl transition-all duration-300 group-hover:scale-110">
                                <i class="fas fa-cut text-white text-sm"></i>
                            </div>
                            <div class="text-left">
                                <span class="block font-semibold">Natures</span>
                                <span class="text-xs text-gray-500 group-hover:text-gray-700">de Coupe</span>
                            </div>
                            <div class="tab-indicator"></div>
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    <div class="tab-content" id="entitiesTabContent">
                        <!-- Essences Tab -->
                        <div class="tab-pane fade show active" id="essences" role="tabpanel">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-leaf text-white text-lg"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-900">Liste des Essences</h3>
                                        <p class="text-gray-600">Gérez les essences forestières</p>
                                    </div>
                                </div>
                                <a href="{{ route('settings.essences.create') }}" 
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                                    <i class="fas fa-plus"></i>
                                    <span>Nouvelle Essence</span>
                                </a>
                            </div>
                            
                            <!-- Search Box -->
                            <div class="mb-6">
                                <form method="GET" action="{{ route('articles.index') }}" class="flex gap-3">
                                    <div class="flex-1 relative">
                                        <input type="text" 
                                               name="essence_search" 
                                               class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 hover:border-gray-400" 
                                               placeholder="Rechercher une essence..." 
                                               value="{{ request('essence_search') }}">
                                        <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                            <i class="fas fa-search"></i>
                                        </div>
                                    </div>
                                    <button type="submit" 
                                            class="px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-300">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    @if(request('essence_search'))
                                        <a href="{{ route('articles.index') }}" 
                                           class="px-4 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    @endif
                                </form>
                            </div>
                            
                            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                                <div class="overflow-x-auto">
                                    <table class="w-full">
                                        <thead class="bg-gradient-to-r from-gray-50 to-slate-50">
                                            <tr>
                                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nom de l'Essence</th>
                                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date de Création</th>
                                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @forelse($essences as $essence)
                                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $essence->id }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                                            <i class="fas fa-leaf text-green-600 text-sm"></i>
                                                        </div>
                                                        <span class="font-medium">{{ $essence->essence }}</span>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $essence->created_at->format('d/m/Y') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <div class="flex items-center gap-2">
                                                        <a href="{{ route('settings.essences.edit', $essence) }}" 
                                                           class="inline-flex items-center gap-1 px-3 py-2 bg-gradient-to-r from-yellow-500 to-orange-500 text-white rounded-lg hover:from-yellow-600 hover:to-orange-600 transition-all duration-300 transform hover:scale-105 shadow-sm"
                                                           title="Modifier">
                                                            <i class="fas fa-edit text-sm"></i>
                                                        </a>
                                                        <form action="{{ route('settings.essences.destroy', $essence) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" 
                                                                    class="inline-flex items-center gap-1 px-3 py-2 bg-gradient-to-r from-red-500 to-pink-500 text-white rounded-lg hover:from-red-600 hover:to-pink-600 transition-all duration-300 transform hover:scale-105 shadow-sm"
                                                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette essence ?')"
                                                                    title="Supprimer">
                                                                <i class="fas fa-trash text-sm"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                                    <div class="flex flex-col items-center">
                                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                                            <i class="fas fa-leaf text-2xl text-gray-400"></i>
                                                        </div>
                                                        <p class="text-lg font-medium">Aucune essence trouvée</p>
                                                        <p class="text-sm">Commencez par ajouter une nouvelle essence</p>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            @if($essences->hasPages())
                                <div class="mt-6 flex items-center justify-between">
                                    <div class="text-sm text-gray-700">
                                        Affichage de {{ $essences->firstItem() }} à {{ $essences->lastItem() }} sur {{ $essences->total() }} résultats
                                    </div>
                                    <div class="flex items-center gap-2">
                                        {{ $essences->appends(request()->query())->links() }}
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Forêts Tab -->
                        <div class="tab-pane fade" id="forets" role="tabpanel">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-tree text-white text-lg"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-900">Liste des Forêts</h3>
                                        <p class="text-gray-600">Gérez les forêts forestières</p>
                                    </div>
                                </div>
                                <a href="{{ route('settings.forets.create') }}" 
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                                    <i class="fas fa-plus"></i>
                                    <span>Nouvelle Forêt</span>
                                </a>
                            </div>
                            
                            <!-- Search Box -->
                            <div class="mb-6">
                                <form method="GET" action="{{ route('articles.index') }}" class="flex gap-3">
                                    <div class="flex-1 relative">
                                        <input type="text" 
                                               name="foret_search" 
                                               class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                                               placeholder="Rechercher une forêt..." 
                                               value="{{ request('foret_search') }}">
                                        <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                            <i class="fas fa-search"></i>
                                        </div>
                                    </div>
                                    <button type="submit" 
                                            class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-300">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    @if(request('foret_search'))
                                        <a href="{{ route('articles.index') }}" 
                                           class="px-4 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    @endif
                                </form>
                            </div>
                            
                            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                                <div class="overflow-x-auto">
                                    <table class="w-full">
                                        <thead class="bg-gradient-to-r from-gray-50 to-slate-50">
                                            <tr>
                                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nom de la Forêt</th>
                                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date de Création</th>
                                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @forelse($forets as $foret)
                                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $foret->id }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                                            <i class="fas fa-tree text-blue-600 text-sm"></i>
                                                        </div>
                                                        <span class="font-medium">{{ $foret->foret }}</span>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $foret->created_at->format('d/m/Y') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <div class="flex items-center gap-2">
                                                        <a href="{{ route('settings.forets.edit', $foret) }}" 
                                                           class="inline-flex items-center gap-1 px-3 py-2 bg-gradient-to-r from-yellow-500 to-orange-500 text-white rounded-lg hover:from-yellow-600 hover:to-orange-600 transition-all duration-300 transform hover:scale-105 shadow-sm"
                                                           title="Modifier">
                                                            <i class="fas fa-edit text-sm"></i>
                                                        </a>
                                                        <form action="{{ route('settings.forets.destroy', $foret) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" 
                                                                    class="inline-flex items-center gap-1 px-3 py-2 bg-gradient-to-r from-red-500 to-pink-500 text-white rounded-lg hover:from-red-600 hover:to-pink-600 transition-all duration-300 transform hover:scale-105 shadow-sm"
                                                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette forêt ?')"
                                                                    title="Supprimer">
                                                                <i class="fas fa-trash text-sm"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                                    <div class="flex flex-col items-center">
                                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                                            <i class="fas fa-tree text-2xl text-gray-400"></i>
                                                        </div>
                                                        <p class="text-lg font-medium">Aucune forêt trouvée</p>
                                                        <p class="text-sm">Commencez par ajouter une nouvelle forêt</p>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            @if($forets->hasPages())
                                <div class="mt-6 flex items-center justify-between">
                                    <div class="text-sm text-gray-700">
                                        Affichage de {{ $forets->firstItem() }} à {{ $forets->lastItem() }} sur {{ $forets->total() }} résultats
                                    </div>
                                    <div class="flex items-center gap-2">
                                        {{ $forets->appends(request()->query())->links() }}
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Localisations Tab -->
                        <div class="tab-pane fade" id="localisations" role="tabpanel">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-map-marker-alt text-white text-lg"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-900">Liste des Localisations</h3>
                                        <p class="text-gray-600">Gérez les localisations forestières</p>
                                    </div>
                                </div>
                                <div class="flex gap-3">
                                    <a href="{{ route('settings.localisations.create') }}" 
                                       class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-teal-600 to-cyan-600 text-white rounded-lg hover:from-teal-700 hover:to-cyan-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                                        <i class="fas fa-plus"></i>
                                        <span>Nouvelle Localisation</span>
                                    </a>
                                    <a href="{{ route('settings.localisations.export') }}" 
                                       class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                                        <i class="fas fa-download"></i>
                                        <span>Exporter</span>
                                    </a>
                                    <button onclick="document.getElementById('importLocalisationForm').click()" 
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                                        <i class="fas fa-upload"></i>
                                        <span>Importer</span>
                                    </button>
                                    <input type="file" id="importLocalisationForm" style="display: none;" accept=".xlsx,.xls,.csv" onchange="importLocalisations(this)">
                                </div>
                            </div>
                            
                            <!-- Search Box -->
                            <div class="mb-6">
                                <form method="GET" action="{{ route('articles.index') }}" class="flex gap-3">
                                    <div class="flex-1 relative">
                                        <input type="text" 
                                               name="localisation_search" 
                                               class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 hover:border-gray-400" 
                                               placeholder="Rechercher par Code, DRANEF ou Entité..." 
                                               value="{{ request('localisation_search') }}">
                                        <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                            <i class="fas fa-search"></i>
                                        </div>
                                    </div>
                                    <button type="submit" 
                                            class="px-6 py-3 bg-gradient-to-r from-teal-600 to-cyan-600 text-white rounded-xl hover:from-teal-700 hover:to-cyan-700 transition-all duration-300">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    @if(request('localisation_search'))
                                        <a href="{{ route('articles.index') }}" 
                                           class="px-4 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    @endif
                                </form>
                            </div>
                            
                            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                                <div class="overflow-x-auto">
                                    <table class="w-full">
                                        <thead class="bg-gradient-to-r from-gray-50 to-slate-50">
                                            <tr>
                                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Code</th>
                                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">DRANEF</th>
                                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Entité</th>
                                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date de Création</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @forelse($localisations as $localisation)
                                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $localisation->id }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-8 h-8 bg-teal-100 rounded-lg flex items-center justify-center">
                                                            <i class="fas fa-map-marker-alt text-teal-600 text-sm"></i>
                                                        </div>
                                                        <span class="font-medium">{{ $localisation->CODE }}</span>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $localisation->DRANEF }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $localisation->ENTITE }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $localisation->created_at->format('d/m/Y') }}
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                                    <div class="flex flex-col items-center">
                                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                                            <i class="fas fa-map-marker-alt text-2xl text-gray-400"></i>
                                                        </div>
                                                        <p class="text-lg font-medium">Aucune localisation trouvée</p>
                                                        <p class="text-sm">Commencez par ajouter une nouvelle localisation</p>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            @if($localisations->hasPages())
                                <div class="mt-6 flex items-center justify-between">
                                    <div class="text-sm text-gray-700">
                                        Affichage de {{ $localisations->firstItem() }} à {{ $localisations->lastItem() }} sur {{ $localisations->total() }} résultats
                                    </div>
                                    <div class="flex items-center gap-2">
                                        {{ $localisations->appends(request()->query())->links() }}
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Situations Administratives Tab -->
                        <div class="tab-pane fade" id="situations" role="tabpanel">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-building text-white text-lg"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-900">Liste des Situations Administratives</h3>
                                        <p class="text-gray-600">Gérez les communes et provinces</p>
                                    </div>
                                </div>
                                <a href="{{ route('settings.situation-administratives.create') }}" 
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-lg hover:from-purple-700 hover:to-pink-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                                    <i class="fas fa-plus"></i>
                                    <span>Nouvelle Situation</span>
                                </a>
                            </div>
                            
                            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                                <div class="overflow-x-auto">
                                    <table class="w-full">
                                        <thead class="bg-gradient-to-r from-gray-50 to-slate-50">
                                            <tr>
                                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Commune</th>
                                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Province</th>
                                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach(\App\Models\SituationAdministrative::all() as $situation)
                                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $situation->id }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                                            <i class="fas fa-building text-purple-600 text-sm"></i>
                                                        </div>
                                                        <span class="font-medium">{{ $situation->commune }}</span>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $situation->province }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <div class="flex items-center gap-2">
                                                        <a href="{{ route('settings.situation-administratives.edit', $situation) }}" 
                                                           class="inline-flex items-center gap-1 px-3 py-2 bg-gradient-to-r from-yellow-500 to-orange-500 text-white rounded-lg hover:from-yellow-600 hover:to-orange-600 transition-all duration-300 transform hover:scale-105 shadow-sm"
                                                           title="Modifier">
                                                            <i class="fas fa-edit text-sm"></i>
                                                        </a>
                                                        <form action="{{ route('settings.situation-administratives.destroy', $situation) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" 
                                                                    class="inline-flex items-center gap-1 px-3 py-2 bg-gradient-to-r from-red-500 to-pink-500 text-white rounded-lg hover:from-red-600 hover:to-pink-600 transition-all duration-300 transform hover:scale-105 shadow-sm"
                                                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette situation administrative ?')"
                                                                    title="Supprimer">
                                                                <i class="fas fa-trash text-sm"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    <!-- Exploitants Tab -->
                    <!-- <div class="tab-pane fade" id="exploitants" role="tabpanel" aria-labelledby="exploitants-tab">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5>Liste des Exploitants</h5>
                            <a href="{{ route('exploitants.index') }}" class="btn btn-danger btn-sm">
                                <i class="fas fa-plus me-2"></i>Nouvel Exploitant
                            </a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nom</th>
                                        <th>Date de Création</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(\App\Models\Exploitant::all() as $exploitant)
                                    <tr>
                                        <td>{{ $exploitant->id }}</td>
                                        <td>{{ $exploitant->nom_complet }}</td>
                                        <td>{{ $exploitant->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            <a href="{{ route('exploitants.edit', $exploitant) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('exploitants.destroy', $exploitant) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet exploitant ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div> -->

                        <!-- Natures de Coupe Tab -->
                        <div class="tab-pane fade" id="natures-coupe" role="tabpanel">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-yellow-600 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-cut text-white text-lg"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-900">Liste des Natures de Coupe</h3>
                                        <p class="text-gray-600">Gérez les méthodes d'exploitation</p>
                                    </div>
                                </div>
                                <a href="{{ route('settings.nature-de-coupes.create') }}" 
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-orange-600 to-yellow-600 text-white rounded-lg hover:from-orange-700 hover:to-yellow-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                                    <i class="fas fa-plus"></i>
                                    <span>Nouvelle Nature</span>
                                </a>
                            </div>
                            
                            <!-- Search Box -->
                            <div class="mb-6">
                                <form method="GET" action="{{ route('articles.index') }}" class="flex gap-3">
                                    <div class="flex-1 relative">
                                        <input type="text" 
                                               name="nature_search" 
                                               class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 hover:border-gray-400" 
                                               placeholder="Rechercher une nature de coupe..." 
                                               value="{{ request('nature_search') }}">
                                        <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                            <i class="fas fa-search"></i>
                                        </div>
                                    </div>
                                    <button type="submit" 
                                            class="px-6 py-3 bg-gradient-to-r from-orange-600 to-yellow-600 text-white rounded-xl hover:from-orange-700 hover:to-yellow-700 transition-all duration-300">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    @if(request('nature_search'))
                                        <a href="{{ route('articles.index') }}" 
                                           class="px-4 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    @endif
                                </form>
                            </div>
                            
                            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                                <div class="overflow-x-auto">
                                    <table class="w-full">
                                        <thead class="bg-gradient-to-r from-gray-50 to-slate-50">
                                            <tr>
                                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nature</th>
                                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date de Création</th>
                                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @forelse($natureDeCoupes as $nature)
                                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $nature->id }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                                                            <i class="fas fa-cut text-orange-600 text-sm"></i>
                                                        </div>
                                                        <span class="font-medium">{{ $nature->nature_de_coupe }}</span>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $nature->created_at->format('d/m/Y') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <div class="flex items-center gap-2">
                                                        <a href="{{ route('settings.nature-de-coupes.edit', $nature) }}" 
                                                           class="inline-flex items-center gap-1 px-3 py-2 bg-gradient-to-r from-yellow-500 to-orange-500 text-white rounded-lg hover:from-yellow-600 hover:to-orange-600 transition-all duration-300 transform hover:scale-105 shadow-sm"
                                                           title="Modifier">
                                                            <i class="fas fa-edit text-sm"></i>
                                                        </a>
                                                        <form action="{{ route('settings.nature-de-coupes.destroy', $nature) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" 
                                                                    class="inline-flex items-center gap-1 px-3 py-2 bg-gradient-to-r from-red-500 to-pink-500 text-white rounded-lg hover:from-red-600 hover:to-pink-600 transition-all duration-300 transform hover:scale-105 shadow-sm"
                                                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette nature de coupe ?')"
                                                                    title="Supprimer">
                                                                <i class="fas fa-trash text-sm"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                                    <div class="flex flex-col items-center">
                                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                                            <i class="fas fa-cut text-2xl text-gray-400"></i>
                                                        </div>
                                                        <p class="text-lg font-medium">Aucune nature de coupe trouvée</p>
                                                        <p class="text-sm">Commencez par ajouter une nouvelle nature de coupe</p>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            @if($natureDeCoupes->hasPages())
                                <div class="mt-6 flex items-center justify-between">
                                    <div class="text-sm text-gray-700">
                                        Affichage de {{ $natureDeCoupes->firstItem() }} à {{ $natureDeCoupes->lastItem() }} sur {{ $natureDeCoupes->total() }} résultats
                                    </div>
                                    <div class="flex items-center gap-2">
                                        {{ $natureDeCoupes->appends(request()->query())->links() }}
                                    </div>
                                </div>
                            @endif
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
/* Enhanced table styling */
.table-responsive {
    border-radius: 8px;
    overflow: auto;
    max-height: 70vh;
}

.table-container {
    min-width: 100%;
    overflow: auto;
}

.table {
    margin-bottom: 0;
}

.table th {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
    color: #495057;
    padding: 12px 8px;
}

.table td {
    padding: 12px 8px;
    vertical-align: middle;
}

.table tbody tr:hover {
    background-color: #f8f9fa !important;
    transition: background-color 0.2s ease;
}

/* Dropdown actions styling */
.dropdown-menu {
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    border: 1px solid #dee2e6;
}

.dropdown-item {
    padding: 8px 16px;
    transition: background-color 0.2s ease;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
}

.dropdown-item.text-danger:hover {
    background-color: #f8d7da;
}

/* Search and filter styling */
.input-group-text {
    background-color: #f8f9fa;
    border-color: #dee2e6;
}

.form-select {
    border-radius: 6px;
}

/* Pagination styling */
.pagination-info, .pagination-per-page {
    color: #6c757d;
}

.pagination-controls .pagination {
    margin: 0;
}

.pagination-controls .page-link {
    border-radius: 6px;
    margin: 0 2px;
    border: 1px solid #dee2e6;
}

.pagination-controls .page-link:hover {
    background-color: #e9ecef;
    border-color: #dee2e6;
}

.pagination-controls .page-item.active .page-link {
    background-color: #007bff;
    border-color: #007bff;
}

/* Badge styling */
.badge {
    font-size: 0.75em;
    padding: 4px 8px;
    border-radius: 12px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .pagination-info, .pagination-per-page {
        display: none;
    }
    
    .dropdown-menu {
        position: fixed !important;
        top: 50% !important;
        left: 50% !important;
        transform: translate(-50%, -50%) !important;
        width: 90vw;
        max-width: 300px;
    }
}

/* Table overflow and scrolling enhancements */
.table-responsive::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

.table-responsive::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.table-responsive::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
}

.table-responsive::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

.table-responsive::-webkit-scrollbar-corner {
    background: #f1f1f1;
}

/* Ensure table headers stay visible during scroll */
.table thead th {
    position: sticky;
    top: 0;
    z-index: 10;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

/* Horizontal scroll indicator */
.table-scroll-indicator {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, transparent, #007bff, transparent);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.table-responsive:hover .table-scroll-indicator {
    opacity: 1;
}

/* Enhanced table overflow handling */
.table-responsive {
    box-shadow: inset 0 0 10px rgba(0,0,0,0.1);
}

.table {
    min-width: 800px; /* Ensure minimum width for readability */
}

/* Responsive table behavior */
@media (max-width: 1200px) {
    .table-responsive {
        max-height: 60vh;
    }
}

@media (max-width: 768px) {
    .table-responsive {
        max-height: 50vh;
    }
    
    .table {
        min-width: 600px;
    }
}

/* Smooth scrolling behavior */
.table-responsive {
    scroll-behavior: smooth;
}

/* Table row hover effects with overflow */
.table tbody tr {
    transition: all 0.2s ease;
}

.table tbody tr:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

/* Ensure actions column is always visible */
.table td:last-child {
    position: sticky;
    right: 0;
    background: white;
    z-index: 5;
    box-shadow: -2px 0 5px rgba(0,0,0,0.1);
}

.table th:last-child {
    position: sticky;
    right: 0;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    z-index: 15;
    box-shadow: -2px 0 5px rgba(0,0,0,0.1);
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, page initialized');
    initializeTableFilters();
});

// Function to handle localisation import
function importLocalisations(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const formData = new FormData();
        formData.append('file', file);
        
        fetch('{{ route("settings.localisations.import") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Import réussi ! ' + data.message);
                location.reload();
            } else {
                alert('Erreur lors de l\'import : ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur lors de l\'import');
        });
        
        // Reset input
        input.value = '';
    }
}

// Articles table functionality
function initializeTableFilters() {
    // Add row highlighting on hover
    const tableRows = document.querySelectorAll('tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.backgroundColor = '#f8f9fa';
        });
        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
        });
    });
    
    // Initialize table scrolling functionality
    initializeTableScrolling();
}

function initializeTableScrolling() {
    const tableContainer = document.querySelector('.table-responsive');
    const scrollIndicator = document.querySelector('.table-scroll-indicator');
    
    if (tableContainer && scrollIndicator) {
        // Show scroll indicators based on scroll position
        tableContainer.addEventListener('scroll', function() {
            const { scrollTop, scrollLeft, scrollHeight, scrollWidth, clientHeight, clientWidth } = this;
            
            // Vertical scroll indicator
            if (scrollTop > 0) {
                scrollIndicator.style.opacity = '1';
            } else {
                scrollIndicator.style.opacity = '0';
            }
            
            // Horizontal scroll indicator
            if (scrollLeft > 0) {
                scrollIndicator.style.opacity = '1';
            }
            
            // Show scroll to top button if scrolled down
            if (scrollTop > 100) {
                showScrollToTopButton();
            } else {
                hideScrollToTopButton();
            }
        });
        
        // Add smooth scrolling to top functionality
        addScrollToTopButton();
    }
}

function addScrollToTopButton() {
    // Remove existing button if any
    const existingButton = document.querySelector('.scroll-to-top-btn');
    if (existingButton) {
        existingButton.remove();
    }
    
    // Create scroll to top button
    const scrollButton = document.createElement('button');
    scrollButton.className = 'scroll-to-top-btn btn btn-primary btn-sm position-fixed';
    scrollButton.innerHTML = '<i class="fas fa-arrow-up"></i>';
    scrollButton.style.cssText = `
        bottom: 20px;
        right: 20px;
        z-index: 1000;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    `;
    
    scrollButton.addEventListener('click', function() {
        const tableContainer = document.querySelector('.table-responsive');
        if (tableContainer) {
            tableContainer.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }
    });
    
    document.body.appendChild(scrollButton);
}

function showScrollToTopButton() {
    const scrollButton = document.querySelector('.scroll-to-top-btn');
    if (scrollButton) {
        scrollButton.style.display = 'block';
    }
}

function hideScrollToTopButton() {
    const scrollButton = document.querySelector('.scroll-to-top-btn');
    if (scrollButton) {
        scrollButton.style.display = 'none';
    }
}

function filterTable() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    const typeFilter = document.getElementById('typeFilter').value;
    
    const tableRows = document.querySelectorAll('tbody tr');
    
    tableRows.forEach(row => {
        if (row.cells.length < 12) return; // Skip if not a valid article row
        
        const text = row.textContent.toLowerCase();
        const statusCell = row.cells[10]; // Status column
        const typeCell = row.cells[9]; // Type column
        
        let showRow = true;
        
        // Search filter
        if (searchTerm && !text.includes(searchTerm)) {
            showRow = false;
        }
        
        // Status filter
        if (statusFilter) {
            const statusText = statusCell.textContent.toLowerCase();
            if (statusFilter === 'validated' && !statusText.includes('validé')) {
                showRow = false;
            } else if (statusFilter === 'pending' && !statusText.includes('attente')) {
                showRow = false;
            }
        }
        
        // Type filter
        if (typeFilter) {
            const typeText = typeCell.textContent.toLowerCase();
            if (typeFilter === 'adjudication' && !typeText.includes('adjudication')) {
                showRow = false;
            } else if (typeFilter === 'appel_doffre' && !typeText.includes('appel d\'offre')) {
                showRow = false;
            }
        }
        
        row.style.display = showRow ? '' : 'none';
    });
    
    updateRowCount();
}

function updateRowCount() {
    const visibleRows = document.querySelectorAll('tbody tr:not([style*="display: none"])');
    const totalRows = document.querySelectorAll('tbody tr').length;
    
    // Update pagination info if it exists
    const paginationInfo = document.querySelector('.pagination-info small');
    if (paginationInfo) {
        paginationInfo.textContent = `${visibleRows.length} articles affichés sur ${totalRows} au total`;
    }
}

function duplicateArticle(articleId) {
    UXUtils.confirm('Voulez-vous dupliquer cet article ?', {
        title: 'Dupliquer l\'article',
        confirmText: 'Dupliquer',
        cancelText: 'Annuler',
        type: 'info',
        icon: 'fas fa-copy'
    }).then(confirmed => {
        if (confirmed) {
            UXUtils.showInfo('Redirection vers la page de création...');
            // Redirect to create page with article data
            window.location.href = `/articles/create?duplicate=${articleId}`;
        }
    });
}

function exportArticle(articleId) {
    // Show loading state
    const button = event.target.closest('.dropdown-item');
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Export en cours...';
    
    // Make export request
    fetch(`/articles/export?article_id=${articleId}`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (response.ok) {
            return response.blob();
        }
        throw new Error('Export failed');
    })
    .then(blob => {
        // Create download link
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `article_${articleId}_${new Date().toISOString().split('T')[0]}.csv`;
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
        document.body.removeChild(a);
        
        // Reset button and show success
        button.innerHTML = originalText;
        UXUtils.showSuccess('Article exporté avec succès !');
    })
    .catch(error => {
        console.error('Export error:', error);
        UXUtils.showError('Erreur lors de l\'export de l\'article');
        button.innerHTML = originalText;
    });
}

// Add keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + F to focus search
    if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
        e.preventDefault();
        document.getElementById('searchInput').focus();
    }
    
    // Escape to clear search
    if (e.key === 'Escape') {
        document.getElementById('searchInput').value = '';
        filterTable();
    }
});

function changePerPage() {
    const perPage = document.getElementById('perPageSelect').value;
    const currentUrl = new URL(window.location);
    currentUrl.searchParams.set('per_page', perPage);
    currentUrl.searchParams.delete('page'); // Reset to first page
    window.location.href = currentUrl.toString();
}

function refreshTable() {
    window.location.reload();
}

function exportAllArticles() {
    // Show loading state
    const button = event.target.closest('button');
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Export en cours...';
    button.disabled = true;
    
    // Get current filters
    const searchTerm = document.getElementById('searchInput').value;
    const statusFilter = document.getElementById('statusFilter').value;
    const typeFilter = document.getElementById('typeFilter').value;
    
    // Build export URL with filters
    let exportUrl = '/articles/export';
    const params = new URLSearchParams();
    
    if (searchTerm) params.append('search', searchTerm);
    if (statusFilter) params.append('status', statusFilter);
    if (typeFilter) params.append('type', typeFilter);
    
    if (params.toString()) {
        exportUrl += '?' + params.toString();
    }
    
    // Make export request
    fetch(exportUrl, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (response.ok) {
            return response.blob();
        }
        throw new Error('Export failed');
    })
    .then(blob => {
        // Create download link
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `articles_export_${new Date().toISOString().split('T')[0]}.csv`;
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
        document.body.removeChild(a);
        
        // Reset button
        button.innerHTML = originalText;
        button.disabled = false;
    })
    .catch(error => {
        console.error('Export error:', error);
        alert('Erreur lors de l\'export');
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

// Additional action functions
function printArticle(articleId) {
    const printWindow = window.open(`/articles/${articleId}?print=1`, '_blank');
    printWindow.onload = function() {
        printWindow.print();
    };
}

function shareArticle(articleId) {
    if (navigator.share) {
        navigator.share({
            title: 'Article Forestier',
            text: 'Consultez cet article forestier',
            url: `${window.location.origin}/articles/${articleId}`
        });
    } else {
        // Fallback: copy to clipboard
        const url = `${window.location.origin}/articles/${articleId}`;
        navigator.clipboard.writeText(url).then(() => {
            alert('Lien copié dans le presse-papiers !');
        });
    }
}


function archiveArticle(articleId) {
    if (confirm('Voulez-vous archiver cet article ?')) {
        fetch(`/articles/${articleId}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                is_deleted: true
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erreur lors de l\'archivage');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur lors de l\'archivage');
        });
    }
}

// Clear filters function
function clearFilters() {
    document.getElementById('search').value = '';
    document.getElementById('type').value = '';
    document.getElementById('status').value = '';
    document.getElementById('year').value = '';
    document.getElementById('filterForm').submit();
}

// Reset date filter function
function resetDateFilter() {
    // Set dates to current month
    const today = new Date();
    const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
    
    document.getElementById('start_date').value = firstDay.toISOString().split('T')[0];
    document.getElementById('end_date').value = today.toISOString().split('T')[0];
    
    // Submit the form
    document.getElementById('dateFilterForm').submit();
}

// Enhanced UX features
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const statusFilter = document.getElementById('status');
    const typeFilter = document.getElementById('type');
    const yearFilter = document.getElementById('year');
    const table = document.querySelector('.table');
    
    // Auto-submit form when filters change
    if (searchInput) {
        const debouncedSearch = UXUtils.debounce(function() {
            document.getElementById('filterForm').submit();
        }, 500);
        
        searchInput.addEventListener('input', debouncedSearch);
    }
    
    if (statusFilter) {
        statusFilter.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    }
    
    if (typeFilter) {
        typeFilter.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    }
    
    if (yearFilter) {
        yearFilter.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    }
    
    // Enhanced table interactions
    if (table) {
        // Add loading states to action buttons
        table.querySelectorAll('.btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                if (this.classList.contains('btn-danger') || this.classList.contains('btn-warning')) {
                    UXUtils.setLoading(this, true);
                }
            });
        });
        
        // Enhanced row hover effects
        table.querySelectorAll('tbody tr').forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.backgroundColor = 'rgba(5, 150, 105, 0.05)';
            });
            
            row.addEventListener('mouseleave', function() {
                this.style.backgroundColor = '';
            });
        });
    }
    
    // Enhanced category cards
    document.querySelectorAll('.category-card').forEach(card => {
        card.addEventListener('click', function() {
            this.style.transform = 'scale(0.98)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
    });
    
    // Add keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + K to focus search
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            searchInput.focus();
        }
        
        // Ctrl/Cmd + N to create new article
        if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
            e.preventDefault();
            window.location.href = '{{ route("articles.create") }}';
        }
    });
    
    // Add search input hint
    searchInput.addEventListener('focus', function() {
        UXUtils.showToast('Utilisez Ctrl+K pour rechercher rapidement', 'info', 3000);
    });
    
    // Enhanced pagination
    document.querySelectorAll('.pagination .page-link').forEach(link => {
        link.addEventListener('click', function(e) {
            const btn = this;
            UXUtils.setLoading(btn, true);
        });
    });
    
    // Auto-refresh data every 5 minutes
    setInterval(function() {
        // Check if user is active
        if (document.visibilityState === 'visible') {
            // Auto-refresh logic could go here
            console.log('Auto-refreshing data...');
        }
    }, 300000); // 5 minutes
    });
    
    // Modern Tabs Functionality
    document.addEventListener('DOMContentLoaded', function() {
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabPanes = document.querySelectorAll('.tab-pane');
        
        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                const targetTab = this.getAttribute('data-tab');
                
                // Remove active class from all buttons and panes
                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabPanes.forEach(pane => {
                    pane.classList.remove('show', 'active');
                    pane.style.display = 'none';
                });
                
                // Add active class to clicked button
                this.classList.add('active');
                
                // Show corresponding tab pane
                const targetPane = document.getElementById(targetTab);
                if (targetPane) {
                    targetPane.classList.add('show', 'active');
                    targetPane.style.display = 'block';
                }
            });
        });
    });
</script>

@push('styles')
<style>
    /* Modern Tabs Styling */
    .tab-button {
        @apply relative flex items-center px-6 py-4 text-sm font-medium text-gray-600 bg-white/60 backdrop-blur-sm rounded-2xl border border-white/30 hover:bg-white/80 hover:border-white/50 transition-all duration-300 cursor-pointer shadow-sm hover:shadow-lg transform hover:-translate-y-1;
        min-width: 200px;
    }
    
    .tab-button.active {
        @apply text-white bg-gradient-to-r from-blue-600 to-indigo-600 border-blue-500 shadow-xl;
        transform: translateY(-2px);
    }
    
    .tab-button.active .tab-indicator {
        @apply absolute bottom-0 left-1/2 transform -translate-x-1/2 w-12 h-1 bg-white rounded-full;
    }
    
    .tab-button:hover {
        @apply bg-white/80 shadow-lg;
    }
    
    .tab-button span {
        @apply whitespace-nowrap;
    }
    
    .tab-button .tab-indicator {
        @apply absolute bottom-0 left-1/2 transform -translate-x-1/2 w-0 h-1 bg-blue-500 rounded-full transition-all duration-300;
    }
    
    .tab-button:hover .tab-indicator {
        @apply w-8;
    }
    
    .tab-pane {
        display: none;
    }
    
    .tab-pane.active {
        display: block;
    }
    
    /* Enhanced Table Styling */
    .table th {
        @apply px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider;
    }
    
    .table td {
        @apply px-6 py-4 whitespace-nowrap text-sm text-gray-900;
    }
    
    .table tbody tr {
        @apply hover:bg-gray-50 transition-colors duration-200;
    }
    
    /* Enhanced Pagination */
    .pagination .page-link {
        @apply px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-900 transition-all duration-200;
    }
    
    .pagination .page-item.active .page-link {
        @apply bg-blue-600 text-white border-blue-600;
    }
    
    .pagination .page-item.disabled .page-link {
        @apply text-gray-400 bg-gray-100 border-gray-200 cursor-not-allowed;
    }
    
    /* Form Input Styling */
    .form-input {
        @apply w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400;
    }
</style>
@endpush
