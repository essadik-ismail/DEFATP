@extends('layouts.app')

@section('title', 'Contrats de Partenariat - DEFATP')

@section('breadcrumb')
<li class="breadcrumb-item active">Contrats</li>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #059669, #047857);">
                <i class="fas fa-handshake text-white text-2xl"></i>
            </div>
            <div class="flex-1">
                <div class="flex items-center gap-4">
                    <h1 class="text-4xl font-bold bg-clip-text text-transparent" style="background: linear-gradient(to right, #059669, #047857); -webkit-background-clip: text; background-clip: text;">
                        Contrats de Partenariat
                    </h1>
                </div>
                <p class="text-gray-600 text-lg mt-2">Gérez et consultez tous les contrats de partenariat</p>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-700 p-6 rounded-xl mb-6 shadow-lg">
            <div class="flex items-center gap-3">
                <i class="fas fa-check-circle text-2xl"></i>
                <div>
                    <h3 class="font-semibold text-lg">Succès!</h3>
                    <p>{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-500 text-red-700 p-6 rounded-xl mb-6 shadow-lg">
            <div class="flex items-center gap-3">
                <i class="fas fa-exclamation-triangle text-2xl"></i>
                <div>
                    <h3 class="font-semibold text-lg">Erreur!</h3>
                    <p>{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Contracts Data Table -->
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #059669, #047857);">
                    <i class="fas fa-table text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold bg-clip-text text-transparent" style="background: linear-gradient(to right, #059669, #047857); -webkit-background-clip: text; background-clip: text;">Liste des Contrats</h2>
                    <p class="text-gray-600">Gérez et consultez tous les contrats de partenariat</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('contracts.create') }}" 
                   class="inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-br from-green-500 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-plus"></i>
                    <span class="font-semibold">Nouveau Contrat</span>
                </a>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="bg-gradient-to-r from-gray-50 to-slate-50 rounded-2xl p-6 border border-gray-200 mb-6">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-gray-500 to-slate-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-search text-white"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Recherche et Filtres Avancés</h3>
                </div>
                <button type="button" 
                        onclick="toggleFilterSection()"
                        class="inline-flex items-center gap-2 px-4 py-2 text-gray-600 hover:text-gray-900 transition-colors"
                        id="toggleFilterBtn">
                    <i class="fas fa-chevron-down" id="toggleFilterIcon"></i>
                    <span class="hidden sm:inline">Réduire</span>
                </button>
            </div>
            
            <!-- Active Filters Badges -->
            <div id="activeFiltersContainer" class="mb-4 flex flex-wrap gap-2">
                @if(request('search'))
                    <span class="inline-flex items-center gap-2 px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                        <i class="fas fa-search text-xs"></i>
                        Recherche: "{{ request('search') }}"
                        <button type="button" onclick="removeFilter('search')" class="ml-1 hover:text-blue-900">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                    </span>
                @endif
                @if(request('years'))
                    <span class="inline-flex items-center gap-2 px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm">
                        <i class="fas fa-calendar text-xs"></i>
                        Années: {{ count(request('years', [])) }} sélectionnée(s)
                        <button type="button" onclick="removeFilter('years')" class="ml-1 hover:text-purple-900">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                    </span>
                @endif
                @if(request('localisation_ids'))
                    <span class="inline-flex items-center gap-2 px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                        <i class="fas fa-map-marker-alt text-xs"></i>
                        Localisations: {{ count(request('localisation_ids', [])) }} sélectionnée(s)
                        <button type="button" onclick="removeFilter('localisation_ids')" class="ml-1 hover:text-green-900">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                    </span>
                @endif
                @if(request('situation_administrative_ids'))
                    <span class="inline-flex items-center gap-2 px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm">
                        <i class="fas fa-building text-xs"></i>
                        Situations: {{ count(request('situation_administrative_ids', [])) }} sélectionnée(s)
                        <button type="button" onclick="removeFilter('situation_administrative_ids')" class="ml-1 hover:text-indigo-900">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                    </span>
                @endif
                @if(request('essence_ids'))
                    <span class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-100 text-emerald-800 rounded-full text-sm">
                        <i class="fas fa-leaf text-xs"></i>
                        Essences: {{ count(request('essence_ids', [])) }} sélectionnée(s)
                        <button type="button" onclick="removeFilter('essence_ids')" class="ml-1 hover:text-emerald-900">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                    </span>
                @endif
                @if(request('foret_ids'))
                    <span class="inline-flex items-center gap-2 px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                        <i class="fas fa-tree text-xs"></i>
                        Forêts: {{ count(request('foret_ids', [])) }} sélectionnée(s)
                        <button type="button" onclick="removeFilter('foret_ids')" class="ml-1 hover:text-green-900">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                    </span>
                @endif
                @if(request('coperative_ids'))
                    <span class="inline-flex items-center gap-2 px-3 py-1 bg-cyan-100 text-cyan-800 rounded-full text-sm">
                        <i class="fas fa-users-cog text-xs"></i>
                        Coopératives: {{ count(request('coperative_ids', [])) }} sélectionnée(s)
                        <button type="button" onclick="removeFilter('coperative_ids')" class="ml-1 hover:text-cyan-900">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                    </span>
                @endif
                @if(request('date_debut') || request('date_fin'))
                    <span class="inline-flex items-center gap-2 px-3 py-1 bg-amber-100 text-amber-800 rounded-full text-sm">
                        <i class="fas fa-calendar-alt text-xs"></i>
                        Période: {{ request('date_debut') ? \Carbon\Carbon::parse(request('date_debut'))->format('d/m/Y') : '...' }} - {{ request('date_fin') ? \Carbon\Carbon::parse(request('date_fin'))->format('d/m/Y') : '...' }}
                        <button type="button" onclick="removeFilter('dates')" class="ml-1 hover:text-amber-900">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                    </span>
                @endif
            </div>
            
            <form method="GET" action="{{ route('contracts.index') }}" id="filterForm" class="space-y-4">
                <div id="filterContent" class="transition-all duration-300">
                    <!-- Quick Filter Presets -->
                    <div class="mb-6 p-4 bg-white rounded-xl border border-gray-200">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            <i class="fas fa-bolt text-yellow-500 mr-1"></i>Filtres Rapides
                        </label>
                        <div class="flex flex-wrap gap-2">
                            <button type="button" 
                                    onclick="applyQuickFilter('today')"
                                    class="quick-filter-btn px-4 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-all duration-200 text-sm">
                                <i class="fas fa-calendar-day mr-1"></i>Aujourd'hui
                            </button>
                            <button type="button" 
                                    onclick="applyQuickFilter('thisWeek')"
                                    class="quick-filter-btn px-4 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-all duration-200 text-sm">
                                <i class="fas fa-calendar-week mr-1"></i>Cette Semaine
                            </button>
                            <button type="button" 
                                    onclick="applyQuickFilter('thisMonth')"
                                    class="quick-filter-btn px-4 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-all duration-200 text-sm">
                                <i class="fas fa-calendar mr-1"></i>Ce Mois
                            </button>
                            <button type="button" 
                                    onclick="applyQuickFilter('thisYear')"
                                    class="quick-filter-btn px-4 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-all duration-200 text-sm">
                                <i class="fas fa-calendar-alt mr-1"></i>Cette Année
                            </button>
                            <button type="button" 
                                    onclick="applyQuickFilter('lastMonth')"
                                    class="quick-filter-btn px-4 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-all duration-200 text-sm">
                                <i class="fas fa-arrow-left mr-1"></i>Mois Dernier
                            </button>
                        </div>
                    </div>
                    
                    <!-- Global Search -->
                    <div class="mb-4">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-search text-blue-500 mr-1"></i>Recherche globale
                        </label>
                        <div class="relative">
                            <input 
                                type="text" 
                                name="search" 
                                id="search" 
                                value="{{ request('search') }}"
                                placeholder="Rechercher dans les contrats (numéro, année, localisation, situation, essence, forêt, coopérative...)"
                                class="w-full px-4 py-3 pl-12 pr-10 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400"
                            >
                            <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <i class="fas fa-search"></i>
                            </div>
                            @if(request('search'))
                                <button type="button" 
                                        onclick="clearSearchInput()"
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-times"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="form-group">
                            <label for="years" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-calendar text-purple-500 mr-1"></i>Années
                                <span class="text-xs text-gray-500 font-normal" id="years_count">({{ count(request('years', [])) }} sélectionnée(s))</span>
                            </label>
                            <div class="relative">
                                <input type="text" 
                                       placeholder="Rechercher..." 
                                       class="form-input w-full mb-2 px-4 py-2 pr-8 border border-gray-300 rounded-lg" 
                                       id="years_search"
                                       onkeyup="enhancedFilterSelectOptions(this, 'years')">
                                <button type="button" 
                                        onclick="clearSelectFilter('years_search', 'years')"
                                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 text-xs">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="flex gap-2 mb-2">
                                <button type="button" 
                                        onclick="selectAllOptions('years')"
                                        class="flex-1 px-2 py-1 text-xs bg-purple-50 text-purple-700 rounded hover:bg-purple-100 transition-colors">
                                    <i class="fas fa-check-double mr-1"></i>Tout sélectionner
                                </button>
                                <button type="button" 
                                        onclick="deselectAllOptions('years')"
                                        class="flex-1 px-2 py-1 text-xs bg-gray-50 text-gray-700 rounded hover:bg-gray-100 transition-colors">
                                    <i class="fas fa-times mr-1"></i>Tout désélectionner
                                </button>
                            </div>
                            <select multiple
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400" 
                                    name="years[]" 
                                    id="years"
                                    onchange="updateSelectCount('years', 'years_count')"
                                    size="5">
                                @foreach($availableYears as $year)
                                    <option value="{{ $year }}" {{ in_array($year, request('years', [])) ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">
                                <span id="years_filtered_count">{{ count($availableYears) }}</span> résultat(s) | Maintenez Ctrl/Cmd pour sélectionner plusieurs
                            </p>
                        </div>
                        
                        <div class="form-group">
                            <label for="localisation_ids" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-map-marker-alt text-green-500 mr-1"></i>Localisations
                                <span class="text-xs text-gray-500 font-normal" id="localisation_count">({{ count(request('localisation_ids', [])) }} sélectionnée(s))</span>
                            </label>
                            <div class="relative">
                                <input type="text" 
                                       placeholder="Rechercher..." 
                                       class="form-input w-full mb-2 px-4 py-2 pr-8 border border-gray-300 rounded-lg" 
                                       id="localisation_search"
                                       onkeyup="enhancedFilterSelectOptions(this, 'localisation_ids')">
                                <button type="button" 
                                        onclick="clearSelectFilter('localisation_search', 'localisation_ids')"
                                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 text-xs">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="flex gap-2 mb-2">
                                <button type="button" 
                                        onclick="selectAllOptions('localisation_ids')"
                                        class="flex-1 px-2 py-1 text-xs bg-green-50 text-green-700 rounded hover:bg-green-100 transition-colors">
                                    <i class="fas fa-check-double mr-1"></i>Tout sélectionner
                                </button>
                                <button type="button" 
                                        onclick="deselectAllOptions('localisation_ids')"
                                        class="flex-1 px-2 py-1 text-xs bg-gray-50 text-gray-700 rounded hover:bg-gray-100 transition-colors">
                                    <i class="fas fa-times mr-1"></i>Tout désélectionner
                                </button>
                            </div>
                            <select multiple
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 hover:border-gray-400" 
                                    name="localisation_ids[]" 
                                    id="localisation_ids"
                                    onchange="updateSelectCount('localisation_ids', 'localisation_count')"
                                    size="5">
                                @foreach($localisations as $localisation)
                                    <option value="{{ $localisation->id }}" {{ in_array($localisation->id, request('localisation_ids', [])) ? 'selected' : '' }}>
                                        {{ $localisation->DRANEF }} - {{ $localisation->DPANEF }} - {{ $localisation->ENTITE }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">
                                <span id="localisation_filtered_count">{{ count($localisations) }}</span> résultat(s) | Maintenez Ctrl/Cmd pour sélectionner plusieurs
                            </p>
                        </div>
                        
                        <div class="form-group">
                            <label for="situation_administrative_ids" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-building text-indigo-500 mr-1"></i>Situations Administratives
                                <span class="text-xs text-gray-500 font-normal" id="situation_count">({{ count(request('situation_administrative_ids', [])) }} sélectionnée(s))</span>
                            </label>
                            <div class="relative">
                                <input type="text" 
                                       placeholder="Rechercher..." 
                                       class="form-input w-full mb-2 px-4 py-2 pr-8 border border-gray-300 rounded-lg" 
                                       id="situation_search"
                                       onkeyup="enhancedFilterSelectOptions(this, 'situation_administrative_ids')">
                                <button type="button" 
                                        onclick="clearSelectFilter('situation_search', 'situation_administrative_ids')"
                                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 text-xs">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="flex gap-2 mb-2">
                                <button type="button" 
                                        onclick="selectAllOptions('situation_administrative_ids')"
                                        class="flex-1 px-2 py-1 text-xs bg-indigo-50 text-indigo-700 rounded hover:bg-indigo-100 transition-colors">
                                    <i class="fas fa-check-double mr-1"></i>Tout sélectionner
                                </button>
                                <button type="button" 
                                        onclick="deselectAllOptions('situation_administrative_ids')"
                                        class="flex-1 px-2 py-1 text-xs bg-gray-50 text-gray-700 rounded hover:bg-gray-100 transition-colors">
                                    <i class="fas fa-times mr-1"></i>Tout désélectionner
                                </button>
                            </div>
                            <select multiple
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 hover:border-gray-400" 
                                    name="situation_administrative_ids[]" 
                                    id="situation_administrative_ids"
                                    onchange="updateSelectCount('situation_administrative_ids', 'situation_count')"
                                    size="5">
                                @foreach($situations as $situation)
                                    <option value="{{ $situation->id }}" {{ in_array($situation->id, request('situation_administrative_ids', [])) ? 'selected' : '' }}>
                                        {{ $situation->commune }} - {{ $situation->province }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">
                                <span id="situation_filtered_count">{{ count($situations) }}</span> résultat(s) | Maintenez Ctrl/Cmd pour sélectionner plusieurs
                            </p>
                        </div>
                        
                        <div class="form-group">
                            <label for="essence_ids" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-leaf text-emerald-500 mr-1"></i>Essences
                                <span class="text-xs text-gray-500 font-normal" id="essence_count">({{ count(request('essence_ids', [])) }} sélectionnée(s))</span>
                            </label>
                            <div class="relative">
                                <input type="text" 
                                       placeholder="Rechercher..." 
                                       class="form-input w-full mb-2 px-4 py-2 pr-8 border border-gray-300 rounded-lg" 
                                       id="essence_search"
                                       onkeyup="enhancedFilterSelectOptions(this, 'essence_ids')">
                                <button type="button" 
                                        onclick="clearSelectFilter('essence_search', 'essence_ids')"
                                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 text-xs">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="flex gap-2 mb-2">
                                <button type="button" 
                                        onclick="selectAllOptions('essence_ids')"
                                        class="flex-1 px-2 py-1 text-xs bg-emerald-50 text-emerald-700 rounded hover:bg-emerald-100 transition-colors">
                                    <i class="fas fa-check-double mr-1"></i>Tout sélectionner
                                </button>
                                <button type="button" 
                                        onclick="deselectAllOptions('essence_ids')"
                                        class="flex-1 px-2 py-1 text-xs bg-gray-50 text-gray-700 rounded hover:bg-gray-100 transition-colors">
                                    <i class="fas fa-times mr-1"></i>Tout désélectionner
                                </button>
                            </div>
                            <select multiple
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 hover:border-gray-400" 
                                    name="essence_ids[]" 
                                    id="essence_ids"
                                    onchange="updateSelectCount('essence_ids', 'essence_count')"
                                    size="5">
                                @foreach($essencesList as $essence)
                                    <option value="{{ $essence->id }}" {{ in_array($essence->id, request('essence_ids', [])) ? 'selected' : '' }}>
                                        {{ $essence->essence }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">
                                <span id="essence_filtered_count">{{ count($essencesList) }}</span> résultat(s) | Maintenez Ctrl/Cmd pour sélectionner plusieurs
                            </p>
                        </div>
                        
                        <div class="form-group">
                            <label for="foret_ids" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-tree text-green-500 mr-1"></i>Forêts
                                <span class="text-xs text-gray-500 font-normal" id="foret_count">({{ count(request('foret_ids', [])) }} sélectionnée(s))</span>
                            </label>
                            <div class="relative">
                                <input type="text" 
                                       placeholder="Rechercher..." 
                                       class="form-input w-full mb-2 px-4 py-2 pr-8 border border-gray-300 rounded-lg" 
                                       id="foret_search"
                                       onkeyup="enhancedFilterSelectOptions(this, 'foret_ids')">
                                <button type="button" 
                                        onclick="clearSelectFilter('foret_search', 'foret_ids')"
                                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 text-xs">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="flex gap-2 mb-2">
                                <button type="button" 
                                        onclick="selectAllOptions('foret_ids')"
                                        class="flex-1 px-2 py-1 text-xs bg-green-50 text-green-700 rounded hover:bg-green-100 transition-colors">
                                    <i class="fas fa-check-double mr-1"></i>Tout sélectionner
                                </button>
                                <button type="button" 
                                        onclick="deselectAllOptions('foret_ids')"
                                        class="flex-1 px-2 py-1 text-xs bg-gray-50 text-gray-700 rounded hover:bg-gray-100 transition-colors">
                                    <i class="fas fa-times mr-1"></i>Tout désélectionner
                                </button>
                            </div>
                            <select multiple
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 hover:border-gray-400" 
                                    name="foret_ids[]" 
                                    id="foret_ids"
                                    onchange="updateSelectCount('foret_ids', 'foret_count')"
                                    size="5">
                                @foreach($forets as $foret)
                                    <option value="{{ $foret->id }}" {{ in_array($foret->id, request('foret_ids', [])) ? 'selected' : '' }}>
                                        {{ $foret->foret }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">
                                <span id="foret_filtered_count">{{ count($forets) }}</span> résultat(s) | Maintenez Ctrl/Cmd pour sélectionner plusieurs
                            </p>
                        </div>
                        
                        <div class="form-group">
                            <label for="coperative_ids" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-users-cog text-cyan-500 mr-1"></i>Coopératives
                                <span class="text-xs text-gray-500 font-normal" id="coperative_count">({{ count(request('coperative_ids', [])) }} sélectionnée(s))</span>
                            </label>
                            <div class="relative">
                                <input type="text" 
                                       placeholder="Rechercher..." 
                                       class="form-input w-full mb-2 px-4 py-2 pr-8 border border-gray-300 rounded-lg" 
                                       id="coperative_search"
                                       onkeyup="enhancedFilterSelectOptions(this, 'coperative_ids')">
                                <button type="button" 
                                        onclick="clearSelectFilter('coperative_search', 'coperative_ids')"
                                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 text-xs">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="flex gap-2 mb-2">
                                <button type="button" 
                                        onclick="selectAllOptions('coperative_ids')"
                                        class="flex-1 px-2 py-1 text-xs bg-cyan-50 text-cyan-700 rounded hover:bg-cyan-100 transition-colors">
                                    <i class="fas fa-check-double mr-1"></i>Tout sélectionner
                                </button>
                                <button type="button" 
                                        onclick="deselectAllOptions('coperative_ids')"
                                        class="flex-1 px-2 py-1 text-xs bg-gray-50 text-gray-700 rounded hover:bg-gray-100 transition-colors">
                                    <i class="fas fa-times mr-1"></i>Tout désélectionner
                                </button>
                            </div>
                            <select multiple
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 hover:border-gray-400" 
                                    name="coperative_ids[]" 
                                    id="coperative_ids"
                                    onchange="updateSelectCount('coperative_ids', 'coperative_count')"
                                    size="5">
                                @foreach($coperativesList as $coperative)
                                    <option value="{{ $coperative->id }}" {{ in_array($coperative->id, request('coperative_ids', [])) ? 'selected' : '' }}>
                                        {{ $coperative->nom }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">
                                <span id="coperative_filtered_count">{{ count($coperativesList) }}</span> résultat(s) | Maintenez Ctrl/Cmd pour sélectionner plusieurs
                            </p>
                        </div>
                    </div>

                    <!-- Date Range Filter -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label for="date_debut" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-calendar-alt text-blue-500 mr-1"></i>Date de Début
                            </label>
                            <input 
                                type="date" 
                                name="date_debut" 
                                id="date_debut" 
                                value="{{ request('date_debut') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400"
                            >
                        </div>
                        
                        <div>
                            <label for="date_fin" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-calendar-check text-blue-500 mr-1"></i>Date de Fin
                            </label>
                            <input 
                                type="date" 
                                name="date_fin" 
                                id="date_fin" 
                                value="{{ request('date_fin') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400"
                            >
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <div class="flex gap-3">
                            <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                                <i class="fas fa-filter"></i>
                                <span>Appliquer les filtres</span>
                            </button>
                            <button type="button" 
                                    onclick="clearFilters()"
                                    class="inline-flex items-center gap-2 px-4 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300"
                                    title="Effacer les filtres">
                                <i class="fas fa-times"></i>
                                <span>Effacer</span>
                            </button>
                        </div>
                        <div class="text-sm text-gray-600">
                            <i class="fas fa-info-circle mr-1"></i>
                            <span id="filterResultCount">{{ $contracts->total() }}</span> contrat(s) trouvé(s)
                        </div>
                    </div>
                </div>
            </form>
        </div>
            
        <!-- Data Table -->
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table id="contractsTable" class="w-full">
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
                                    <span>Contrat</span>
                                    <button class="filter-btn ml-2 text-gray-400 hover:text-gray-600" data-column="2" title="Filtrer">
                                        <i class="fas fa-filter text-xs"></i>
                                    </button>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider relative">
                                <div class="flex items-center justify-between">
                                    <span>Localisation</span>
                                    <button class="filter-btn ml-2 text-gray-400 hover:text-gray-600" data-column="3" title="Filtrer">
                                        <i class="fas fa-filter text-xs"></i>
                                    </button>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider relative">
                                <div class="flex items-center justify-between">
                                    <span>Situation Administrative</span>
                                    <button class="filter-btn ml-2 text-gray-400 hover:text-gray-600" data-column="4" title="Filtrer">
                                        <i class="fas fa-filter text-xs"></i>
                                    </button>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider relative">
                                <div class="flex items-center justify-between">
                                    <span>Essence</span>
                                    <button class="filter-btn ml-2 text-gray-400 hover:text-gray-600" data-column="5" title="Filtrer">
                                        <i class="fas fa-filter text-xs"></i>
                                    </button>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($contracts as $contract)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $contract->id }}</td>
                                <td>
                                    <span class="badge bg-primary">{{ $contract->annee ?? '-' }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $contract->contarct ?? '-' }}</span>
                                </td>
                                <td>
                                    @if($contract->localisation)
                                        <span class="text-sm text-gray-900">{{ $contract->localisation->DRANEF }} - {{ $contract->localisation->DPANEF }} - {{ $contract->localisation->ENTITE }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($contract->situationAdministrative)
                                        <span class="text-sm text-gray-900">{{ $contract->situationAdministrative->commune }}@if($contract->situationAdministrative->province) - {{ $contract->situationAdministrative->province }}@endif</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($contract->essences && $contract->essences->count() > 0)
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($contract->essences->take(2) as $essence)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                    {{ $essence->essence }}
                                                </span>
                                            @endforeach
                                            @if($contract->essences->count() > 2)
                                                <span class="text-xs text-gray-500">+{{ $contract->essences->count() - 2 }}</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex items-center gap-1">
                                        <!-- View Action -->
                                        <a href="{{ route('contracts.show', $contract) }}" 
                                           class="inline-flex items-center justify-center w-8 h-8 bg-blue-100 hover:bg-blue-200 text-blue-600 rounded-lg transition-colors duration-200" 
                                           title="Voir les détails">
                                            <i class="fas fa-eye text-sm"></i>
                                        </a>
                                        
                                        <!-- Edit Action -->
                                        <a href="{{ route('contracts.edit', $contract) }}" 
                                           class="inline-flex items-center justify-center w-8 h-8 bg-yellow-100 hover:bg-yellow-200 text-yellow-600 rounded-lg transition-colors duration-200" 
                                           title="Modifier">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>
                                        
                                        <!-- Delete Action -->
                                        <form action="{{ route('contracts.destroy', $contract) }}" 
                                              method="POST" 
                                              class="inline"
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce contrat ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg transition-colors duration-200" 
                                                    title="Supprimer">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center gap-3">
                                        <i class="fas fa-inbox text-4xl text-gray-300"></i>
                                        <p class="text-lg font-semibold">Aucun contrat trouvé</p>
                                        <p class="text-sm">Créez votre premier contrat de partenariat</p>
                                        <a href="{{ route('contracts.create') }}" 
                                           class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                            <i class="fas fa-plus"></i>
                                            <span>Créer un contrat</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
                
        @if($contracts->hasPages())
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 mt-6">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="text-sm text-gray-600">
                        Affichage de {{ $contracts->firstItem() ?? 0 }} à {{ $contracts->lastItem() ?? 0 }} 
                        sur {{ $contracts->total() }} contrats
                    </div>
                    <div class="pagination-controls">
                        {{ $contracts->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>

<script>
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

    // Add keyboard shortcut Ctrl+K to focus search
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + K to focus search (only when not in input/textarea)
        if ((e.ctrlKey || e.metaKey) && e.key === 'k' && !['INPUT', 'TEXTAREA'].includes(e.target.tagName)) {
            e.preventDefault();
            const searchInput = document.getElementById('search');
            if (searchInput) {
                searchInput.focus();
                searchInput.select();
            }
        }
    });

    // Enhanced filter select options function with result count
    function enhancedFilterSelectOptions(inputEl, selectId) {
        const filter = inputEl.value.toLowerCase();
        const select = document.getElementById(selectId);
        if (!select) return;
        
        let visibleCount = 0;
        Array.from(select.options).forEach(function(opt) {
            const text = (opt.text || '').toLowerCase();
            const match = text.indexOf(filter) !== -1;
            opt.style.display = match ? '' : 'none';
            if (match) visibleCount++;
        });
        
        // Update filtered count display
        const countElement = document.getElementById(selectId + '_filtered_count');
        if (countElement) {
            countElement.textContent = visibleCount;
        }
    }

    // Legacy function for backward compatibility
    function filterSelectOptions(inputEl, selectId) {
        enhancedFilterSelectOptions(inputEl, selectId);
    }

    // Clear select filter input
    function clearSelectFilter(inputId, selectId) {
        const input = document.getElementById(inputId);
        if (input) {
            input.value = '';
            enhancedFilterSelectOptions(input, selectId);
        }
    }

    // Select all visible options
    function selectAllOptions(selectId) {
        const select = document.getElementById(selectId);
        if (!select) return;
        
        const countMappings = {
            'years': 'years_count',
            'localisation_ids': 'localisation_count',
            'situation_administrative_ids': 'situation_count',
            'essence_ids': 'essence_count',
            'foret_ids': 'foret_count',
            'coperative_ids': 'coperative_count'
        };
        
        Array.from(select.options).forEach(function(opt) {
            if (opt.style.display !== 'none') {
                opt.selected = true;
            }
        });
        updateSelectCount(selectId, countMappings[selectId] || selectId.replace('_ids', '_count'));
    }

    // Deselect all options
    function deselectAllOptions(selectId) {
        const select = document.getElementById(selectId);
        if (!select) return;
        
        const countMappings = {
            'years': 'years_count',
            'localisation_ids': 'localisation_count',
            'situation_administrative_ids': 'situation_count',
            'essence_ids': 'essence_count',
            'foret_ids': 'foret_count',
            'coperative_ids': 'coperative_count'
        };
        
        Array.from(select.options).forEach(function(opt) {
            opt.selected = false;
        });
        updateSelectCount(selectId, countMappings[selectId] || selectId.replace('_ids', '_count'));
    }

    // Update select count display
    function updateSelectCount(selectId, countElementId) {
        const select = document.getElementById(selectId);
        const countElement = document.getElementById(countElementId);
        if (select && countElement) {
            const selectedCount = Array.from(select.selectedOptions).length;
            countElement.textContent = `(${selectedCount} sélectionnée(s))`;
        }
    }

    // Clear search input
    function clearSearchInput() {
        document.getElementById('search').value = '';
    }

    // Toggle filter section
    function toggleFilterSection() {
        const content = document.getElementById('filterContent');
        const icon = document.getElementById('toggleFilterIcon');
        const btn = document.getElementById('toggleFilterBtn');
        
        if (content && icon && btn) {
            if (content.style.display === 'none') {
                content.style.display = 'block';
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
                btn.querySelector('span').textContent = 'Réduire';
            } else {
                content.style.display = 'none';
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
                btn.querySelector('span').textContent = 'Afficher';
            }
        }
    }

    // Remove specific filter
    function removeFilter(filterName) {
        const form = document.getElementById('filterForm');
        if (!form) return;
        
        if (filterName === 'search') {
            document.getElementById('search').value = '';
        } else if (filterName === 'years') {
            const select = document.getElementById('years');
            if (select) {
                Array.from(select.options).forEach(opt => opt.selected = false);
                updateSelectCount('years', 'years_count');
            }
        } else if (filterName === 'localisation_ids') {
            const select = document.getElementById('localisation_ids');
            if (select) {
                Array.from(select.options).forEach(opt => opt.selected = false);
                updateSelectCount('localisation_ids', 'localisation_count');
            }
        } else if (filterName === 'situation_administrative_ids') {
            const select = document.getElementById('situation_administrative_ids');
            if (select) {
                Array.from(select.options).forEach(opt => opt.selected = false);
                updateSelectCount('situation_administrative_ids', 'situation_count');
            }
        } else if (filterName === 'essence_ids') {
            const select = document.getElementById('essence_ids');
            if (select) {
                Array.from(select.options).forEach(opt => opt.selected = false);
                updateSelectCount('essence_ids', 'essence_count');
            }
        } else if (filterName === 'foret_ids') {
            const select = document.getElementById('foret_ids');
            if (select) {
                Array.from(select.options).forEach(opt => opt.selected = false);
                updateSelectCount('foret_ids', 'foret_count');
            }
        } else if (filterName === 'coperative_ids') {
            const select = document.getElementById('coperative_ids');
            if (select) {
                Array.from(select.options).forEach(opt => opt.selected = false);
                updateSelectCount('coperative_ids', 'coperative_count');
            }
        } else if (filterName === 'dates') {
            document.getElementById('date_debut').value = '';
            document.getElementById('date_fin').value = '';
        }
        
        form.submit();
    }

    // Apply quick filter presets
    function applyQuickFilter(preset) {
        const today = new Date();
        const startDate = document.getElementById('date_debut');
        const endDate = document.getElementById('date_fin');
        
        let start, end;
        
        switch(preset) {
            case 'today':
                start = new Date(today);
                end = new Date(today);
                break;
            case 'thisWeek':
                start = new Date(today);
                start.setDate(today.getDate() - today.getDay());
                end = new Date(today);
                end.setDate(today.getDate() + (6 - today.getDay()));
                break;
            case 'thisMonth':
                start = new Date(today.getFullYear(), today.getMonth(), 1);
                end = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                break;
            case 'thisYear':
                start = new Date(today.getFullYear(), 0, 1);
                end = new Date(today.getFullYear(), 11, 31);
                break;
            case 'lastMonth':
                start = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                end = new Date(today.getFullYear(), today.getMonth(), 0);
                break;
            default:
                return;
        }
        
        if (startDate) startDate.value = start.toISOString().split('T')[0];
        if (endDate) endDate.value = end.toISOString().split('T')[0];
        
        document.getElementById('filterForm').submit();
    }

    // Clear filters function
    function clearFilters() {
        // Clear search
        const searchInput = document.getElementById('search');
        if (searchInput) searchInput.value = '';
        
        // Clear all selects
        const selectMappings = {
            'years': 'years_count',
            'localisation_ids': 'localisation_count',
            'situation_administrative_ids': 'situation_count',
            'essence_ids': 'essence_count',
            'foret_ids': 'foret_count',
            'coperative_ids': 'coperative_count'
        };
        
        Object.keys(selectMappings).forEach(selectId => {
            const select = document.getElementById(selectId);
            if (select) {
                Array.from(select.options).forEach(opt => opt.selected = false);
                updateSelectCount(selectId, selectMappings[selectId]);
            }
            const searchInputId = selectId === 'years' ? 'years_search' : selectId.replace('_ids', '_search');
            const searchInput = document.getElementById(searchInputId);
            if (searchInput) {
                searchInput.value = '';
                enhancedFilterSelectOptions(searchInput, selectId);
            }
        });
        
        // Clear dates
        const startDate = document.getElementById('date_debut');
        if (startDate) startDate.value = '';
        const endDate = document.getElementById('date_fin');
        if (endDate) endDate.value = '';
        
        // Submit form
        document.getElementById('filterForm').submit();
    }

    // Initialize enhanced filters
    function initializeEnhancedFilters() {
        // Initialize select counts
        updateSelectCount('years', 'years_count');
        updateSelectCount('localisation_ids', 'localisation_count');
        updateSelectCount('situation_administrative_ids', 'situation_count');
        updateSelectCount('essence_ids', 'essence_count');
        updateSelectCount('foret_ids', 'foret_count');
        updateSelectCount('coperative_ids', 'coperative_count');
        
        // Initialize filtered counts
        const selectIds = ['years', 'localisation_ids', 'situation_administrative_ids', 'essence_ids', 'foret_ids', 'coperative_ids'];
        selectIds.forEach(selectId => {
            const select = document.getElementById(selectId);
            if (select) {
                const countElement = document.getElementById(selectId + '_filtered_count');
                if (countElement) {
                    countElement.textContent = Array.from(select.options).length;
                }
            }
        });
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        initializeEnhancedFilters();
        
        // Auto-submit form when multiple selects change
        const multipleSelects = ['years', 'localisation_ids', 'situation_administrative_ids', 'essence_ids', 'foret_ids', 'coperative_ids'];
        
        multipleSelects.forEach(function(selectId) {
            const select = document.getElementById(selectId);
            if (select) {
                select.addEventListener('change', function() {
                    document.getElementById('filterForm').submit();
                });
            }
        });
    });
</script>

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
    
    /* Enhanced Filter Styles */
    #activeFiltersContainer {
        min-height: 40px;
    }

    #activeFiltersContainer:empty {
        display: none;
    }

    .filter-badge {
        animation: slideIn 0.3s ease-out;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    #filterContent {
        animation: fadeIn 0.3s ease-in;
    }

    /* Enhanced select styling */
    select[multiple] {
        min-height: 120px;
        max-height: 200px;
    }

    select[multiple] option {
        padding: 8px 12px;
        border-bottom: 1px solid #f3f4f6;
    }

    select[multiple] option:hover {
        background-color: #f9fafb;
    }

    select[multiple] option:checked {
        background-color: #dbeafe;
        color: #1e40af;
        font-weight: 600;
    }

    /* Quick filter buttons */
    .quick-filter-btn {
        transition: all 0.2s ease;
    }

    .quick-filter-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
</style>

<!-- DataTables removed - using Laravel pagination instead -->
@endsection
