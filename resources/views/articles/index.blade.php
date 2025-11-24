@extends('layouts.app')

@section('title', 'Articles Forestiers - DEFATP')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #059669, #047857);">
                <i class="fas fa-file-alt text-white text-2xl"></i>
            </div>
            <div>
                <h1 class="text-4xl font-bold bg-clip-text text-transparent" style="background: linear-gradient(to right, #059669, #047857); -webkit-background-clip: text; background-clip: text;">
                    Articles Forestiers
                </h1>
                <p class="text-gray-600 text-lg mt-2">Gérez et consultez tous les articles forestiers du système</p>
            </div>
        </div>
    </div>


    <!-- Articles Data Table -->
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #059669, #047857);">
                    <i class="fas fa-table text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold bg-clip-text text-transparent" style="background: linear-gradient(to right, #059669, #047857); -webkit-background-clip: text; background-clip: text;">Liste des Articles</h2>
                    <p class="text-gray-600">Gérez et consultez tous les articles forestiers</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('articles.legacy-articles') }}" 
                   class="inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-br from-amber-500 to-orange-600 text-white rounded-xl hover:from-amber-700 hover:to-orange-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-archive"></i>
                    <span class="font-semibold">Articles Historiques</span>
                </a>
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
                        <label for="localisation_ids" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-map-marker-alt text-indigo-500 mr-1"></i>Localisations
                        </label>
                        <input type="text" placeholder="Rechercher..." class="form-input w-full mb-2 px-4 py-2 border border-gray-300 rounded-lg" onkeyup="filterSelectOptions(this, 'localisation_ids')">
                        <select multiple
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 hover:border-gray-400" 
                                name="localisation_ids[]" id="localisation_ids">
                            @foreach($allLocalisations ?? [] as $localisation)
                                <option value="{{ $localisation->id }}" {{ in_array($localisation->id, request('localisation_ids', [])) ? 'selected' : '' }}>
                                    {{ $localisation->DRANEF }} - {{ $localisation->DPANEF }} - {{ $localisation->ENTITE }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Maintenez Ctrl/Cmd pour sélectionner plusieurs</p>
                    </div>
                    
                    <div class="form-group">
                        <label for="years" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calendar text-purple-500 mr-1"></i>Années
                        </label>
                        <input type="text" placeholder="Rechercher..." class="form-input w-full mb-2 px-4 py-2 border border-gray-300 rounded-lg" onkeyup="filterSelectOptions(this, 'years')">
                        <select multiple
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400" 
                                name="years[]" id="years">
                            @for($year = now()->year; $year >= 2020; $year--)
                                <option value="{{ $year }}" {{ in_array($year, request('years', [])) ? 'selected' : '' }}>{{ $year }}</option>
                            @endfor
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Maintenez Ctrl/Cmd pour sélectionner plusieurs</p>
                    </div>
                </div>
                
                <!-- Date Range Filter -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="form-group">
                        <label for="start_date" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calendar-plus text-blue-500 mr-1"></i>
                            Date de début
                            <i class="fas fa-question-circle mx-1 text-gray-400" title="Format: jj/mm/aaaa (ex: 01/01/2024)"></i>
                        </label>
                        <input type="date" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="start_date" 
                               name="start_date" 
                               value="{{ request('start_date') }}"
                               placeholder="jj/mm/aaaa">
                    </div>
                    
                    <div class="form-group">
                        <label for="end_date" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calendar-minus text-blue-500 mr-1"></i>
                            Date de fin
                            <i class="fas fa-question-circle mx-1 text-gray-400" title="Format: jj/mm/aaaa (ex: 31/12/2024)"></i>
                        </label>
                        <input type="date" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="end_date" 
                               name="end_date" 
                               value="{{ request('end_date') }}"
                               placeholder="jj/mm/aaaa">
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
                    
                    @if(request()->hasAny(['search', 'type', 'localisation_ids', 'years', 'start_date', 'end_date']))
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
        </div>
            
        <!-- Data Table -->
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table id="articlesTable" class="w-full">
                    <thead class="bg-gradient-to-r from-gray-50 to-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider relative">
                                <div class="flex items-center justify-between">
                                    <span>ID</span>
                                    <button class="filter-btn ml-2 text-gray-400 hover:text-gray-600" data-column="0" title="Filtrer">
                                        <i class="fas fa-filter text-xs"></i>
                                    </button>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider relative">
                                <div class="flex items-center justify-between">
                                    <span>Année</span>
                                    <button class="filter-btn ml-2 text-gray-400 hover:text-gray-600" data-column="1" title="Filtrer">
                                        <i class="fas fa-filter text-xs"></i>
                                    </button>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider relative">
                                <div class="flex items-center justify-between">
                                    <span>Numéro</span>
                                    <button class="filter-btn ml-2 text-gray-400 hover:text-gray-600" data-column="2" title="Filtrer">
                                        <i class="fas fa-filter text-xs"></i>
                                    </button>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider relative">
                                <div class="flex items-center justify-between">
                                    <span>Date d'Adjudication</span>
                                    <button class="filter-btn ml-2 text-gray-400 hover:text-gray-600" data-column="3" title="Filtrer">
                                        <i class="fas fa-filter text-xs"></i>
                                    </button>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider relative">
                                <div class="flex items-center justify-between">
                                    <span>Prix de Retrait</span>
                                    <button class="filter-btn ml-2 text-gray-400 hover:text-gray-600" data-column="4" title="Filtrer">
                                        <i class="fas fa-filter text-xs"></i>
                                    </button>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider relative">
                                <div class="flex items-center justify-between">
                                    <span>Prix de Vente</span>
                                    <button class="filter-btn ml-2 text-gray-400 hover:text-gray-600" data-column="5" title="Filtrer">
                                        <i class="fas fa-filter text-xs"></i>
                                    </button>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider relative">
                                <div class="flex items-center justify-between">
                                    <span>Type</span>
                                    <button class="filter-btn ml-2 text-gray-400 hover:text-gray-600" data-column="6" title="Filtrer">
                                        <i class="fas fa-filter text-xs"></i>
                                    </button>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($articles as $article)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $article->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span class="badge bg-primary">{{ $article->annee ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($article->numero)
                                        <span class="badge bg-secondary">{{ $article->numero }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($article->date_adjudication)
                                        {{ $article->date_adjudication->format('d/m/Y') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($article->prix_de_retrait)
                                        <span class="badge bg-warning text-dark">
                                            {{ number_format($article->prix_de_retrait, 2) }} DH
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($article->prix_vente)
                                        <span class="badge bg-success">
                                            {{ number_format($article->prix_vente, 2) }} DH
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($article->type)
                                        <span class="badge {{ $article->type == 'appel_doffre' ? 'bg-info' : 'bg-primary' }}">
                                            {{ $article->type == 'appel_doffre' ? 'Appel d\'Offre' : 'Adjudication' }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
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
                                        
                                        <!-- Delete Action -->
                                        <form action="{{ route('articles.destroy', $article) }}" method="POST" style="display: contents;">
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
                                <td colspan="8" class="text-center py-4">
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

    {{-- Quick Create Cards --}}
    {{-- <div class="quick-create-section">
        <h2 class="section-title">Création Rapide</h2>
        <div class="quick-create-grid">
            <a href="{{ route('settings.essences.index') }}" class="quick-create-card">
                <div class="quick-create-icon essence">
                    <i class="fas fa-leaf"></i>
                </div>
                <h4>Nouvelle Essence</h4>
                <p>Ajouter un type d'arbre</p>
            </a>
            
            <a href="{{ route('settings.forets.index') }}" class="quick-create-card">
                <div class="quick-create-icon foret">
                    <i class="fas fa-tree"></i>
                </div>
                <h4>Nouvelle Forêt</h4>
                <p>Ajouter une zone forestière</p>
            </a>
            
            <a href="{{ route('settings.localisations.index') }}" class="quick-create-card">
                <div class="quick-create-icon localisation">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h4>Nouvelle Localisation</h4>
                <p>Ajouter une zone géographique</p>
            </a>
            
            <a href="{{ route('settings.situation-administratives.index') }}" class="quick-create-card">
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
            
            <a href="{{ route('settings.nature-de-coupes.index') }}" class="quick-create-card">
                <div class="quick-create-icon nature">
                    <i class="fas fa-cut"></i>
                </div>
                <h4>Nouvelle Nature</h4>
                <p>Ajouter un type de coupe</p>
            </a>
        </div>
    </div> --}}
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

// Filter select options function
function filterSelectOptions(inputEl, selectId) {
    const filter = inputEl.value.toLowerCase();
    const select = document.getElementById(selectId);
    if (!select) return;
    Array.from(select.options).forEach(function(opt) {
        const text = (opt.text || '').toLowerCase();
        const match = text.indexOf(filter) !== -1;
        opt.style.display = match ? '' : 'none';
    });
}

// Clear filters function
function clearFilters() {
    document.getElementById('search').value = '';
    document.getElementById('type').value = '';
    document.getElementById('status').value = '';
    // Clear multiple select fields
    const localisationSelect = document.getElementById('localisation_ids');
    if (localisationSelect) {
        Array.from(localisationSelect.options).forEach(opt => opt.selected = false);
    }
    const yearsSelect = document.getElementById('years');
    if (yearsSelect) {
        Array.from(yearsSelect.options).forEach(opt => opt.selected = false);
    }
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
    const localisationSelect = document.getElementById('localisation_ids');
    const yearsSelect = document.getElementById('years');
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
    
    // Handle multiple select changes - submit on change
    if (localisationSelect) {
        localisationSelect.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    }
    
    if (yearsSelect) {
        yearsSelect.addEventListener('change', function() {
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
    
    // Tab functionality
    document.addEventListener('DOMContentLoaded', function() {
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabPanes = document.querySelectorAll('.tab-pane');

        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                const targetTab = this.getAttribute('data-tab');
                
                // Remove active class from all buttons and panes
                tabButtons.forEach(btn => {
                    btn.classList.remove('active');
                });
                tabPanes.forEach(pane => {
                    pane.classList.remove('show', 'active');
                });
                
                // Add active class to clicked button and corresponding pane
                this.classList.add('active');
                const targetPane = document.getElementById(targetTab);
                if (targetPane) {
                    targetPane.classList.add('show', 'active');
                }
            });
        });

        // Show the tab from URL parameter or default to first
        const urlParams = new URLSearchParams(window.location.search);
        const tabParam = urlParams.get('tab');
        if (tabParam) {
            const tabButton = document.querySelector(`[data-tab="${tabParam}"]`);
            if (tabButton) {
                tabButton.click();
            }
        }
    });
</script>

@push('styles')
<style>
    .tab-button {
        position: relative;
        padding: 0.75rem 1rem;
        border-radius: 0.75rem;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        background: white;
        border: 1px solid #e5e7eb;
        cursor: pointer;
    }
    
    .tab-button:hover {
        border-color: #d1d5db;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        transform: scale(1.05);
    }
    
    .tab-button.active {
        background: linear-gradient(to right, #f0fdf4, #d1fae5);
        border-color: #86efac;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    
    .tab-pane {
        display: none;
    }
    
    .tab-pane.show.active {
        display: block;
        animation: fadeIn 0.3s ease-in;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
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

<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/dataTables.tailwindcss.min.css">

<!-- jQuery (required for DataTables) -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<!-- DataTables JS -->
<script type="text/javascript" src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.7/js/dataTables.tailwindcss.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#articlesTable').DataTable({
        processing: false,
        serverSide: false,
        order: [[3, 'desc']], // Sort by date
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Tous']],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json'
        },
        columnDefs: [
            {
                targets: [4, 5], // Price columns
                type: 'num',
                render: function(data, type, row) {
                    if (type === 'display' || type === 'filter') {
                        // Extract number from badge text
                        var match = data.match(/[\d,]+\.?\d*/);
                        return match ? match[0].replace(/,/g, '') : '0';
                    }
                    return data;
                }
            }
        ]
    });
    
    // Initialize Excel-style filters
    ExcelFilters.init('articlesTable');
});
</script>
@endpush
