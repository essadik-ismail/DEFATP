@extends('layouts.app')

@section('title', 'Contrats de Partenariat - DEFATP')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #059669, #047857);">
                <i class="fas fa-handshake text-white text-2xl"></i>
            </div>
            <div>
                <h1 class="text-4xl font-bold bg-clip-text text-transparent" style="background: linear-gradient(to right, #059669, #047857); -webkit-background-clip: text; background-clip: text;">
                    Contrats de Partenariat
                </h1>
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

        <!-- Search and Filter Section -->
        <div class="bg-gradient-to-r from-gray-50 to-slate-50 rounded-2xl p-6 border border-gray-200 mb-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-gray-500 to-slate-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-search text-white"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Recherche et Filtres</h3>
            </div>
            
            <form method="GET" action="{{ route('contracts.index') }}" id="filterForm">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
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
                                   placeholder="Numéro, année, localisation...">
                            <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <i class="fas fa-search"></i>
                            </div>
                        </div>
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
                        <a href="{{ route('contracts.index') }}" 
                           class="inline-flex items-center gap-2 px-4 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300">
                            <i class="fas fa-times"></i>
                            <span>Effacer</span>
                        </a>
                    </div>
                </div>
            </form>
        </div>
            
        <!-- Data Table -->
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-gray-50 to-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Année</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Contrat</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Localisation</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Situation Administrative</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Espèce</th>
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
                                        <span class="text-sm text-gray-900">{{ $contract->localisation->CODE ?? '-' }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($contract->situationAdministrative)
                                        <span class="text-sm text-gray-900">{{ $contract->situationAdministrative->commune ?? '-' }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($contract->especes && $contract->especes->count() > 0)
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($contract->especes->take(2) as $espece)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                    {{ $espece->name }}
                                                </span>
                                            @endforeach
                                            @if($contract->especes->count() > 2)
                                                <span class="text-xs text-gray-500">+{{ $contract->especes->count() - 2 }}</span>
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

    <!-- Entity Data Management Section -->
    <div class="mb-8">
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #059669, #047857);">
                    <i class="fas fa-database text-white text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-4xl font-bold bg-clip-text text-transparent" style="background: linear-gradient(to right, #059669, #047857); -webkit-background-clip: text; background-clip: text;">
                        Données des Entités
                    </h2>
                    <p class="text-gray-600 text-lg mt-2">Gérez les données de base du système de contrats</p>
                </div>
            </div>
            
            <!-- Modern Tabs Section -->
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl overflow-hidden">
                <div class="bg-gradient-to-r from-slate-50/80 to-gray-50/80 backdrop-blur-sm border-b border-white/20 p-2">
                    <div class="flex flex-wrap gap-2">
                        <button class="tab-button active group" data-tab="especes">
                            <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mr-3 shadow-lg group-hover:shadow-xl transition-all duration-300 group-hover:scale-110">
                                <i class="fas fa-leaf text-white text-sm"></i>
                            </div>
                            <div class="text-left">
                                <span class="block font-semibold">Espèces</span>
                                <span class="text-xs text-gray-500 group-hover:text-gray-700">Gestion des espèces</span>
                            </div>
                            <div class="tab-indicator"></div>
                        </button>
                        <button class="tab-button group" data-tab="avenants">
                            <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center mr-3 shadow-lg group-hover:shadow-xl transition-all duration-300 group-hover:scale-110">
                                <i class="fas fa-file-contract text-white text-sm"></i>
                            </div>
                            <div class="text-left">
                                <span class="block font-semibold">Avenants</span>
                                <span class="text-xs text-gray-500 group-hover:text-gray-700">Amendements</span>
                            </div>
                            <div class="tab-indicator"></div>
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    <div class="tab-content" id="entitiesTabContent">
                        <!-- Espèces Tab -->
                        <div class="tab-pane fade show active" id="especes" role="tabpanel">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-leaf text-white text-lg"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-900">Liste des Espèces</h3>
                                        <p class="text-gray-600">Gérez les espèces forestières</p>
                                    </div>
                                </div>
                                <a href="{{ route('contracts.especes.create') }}" 
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                                    <i class="fas fa-plus"></i>
                                    <span>Nouvelle Espèce</span>
                                </a>
                            </div>
                            
                            <!-- Search Box -->
                            <div class="mb-6">
                                <form method="GET" action="{{ route('contracts.index') }}" class="flex gap-3">
                                    <input type="hidden" name="tab" value="especes">
                                    <div class="flex-1 relative">
                                        <input type="text" 
                                               name="espece_search" 
                                               class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 hover:border-gray-400" 
                                               placeholder="Rechercher une espèce..." 
                                               value="{{ request('espece_search') }}">
                                        <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                            <i class="fas fa-search"></i>
                                        </div>
                                    </div>
                                    <button type="submit" 
                                            class="px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-300">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    @if(request('espece_search'))
                                        <a href="{{ route('contracts.index') }}" 
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
                                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nom de l'Espèce</th>
                                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date de Création</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @forelse($especes as $espece)
                                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $espece->id }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                                            <i class="fas fa-leaf text-green-600 text-sm"></i>
                                                        </div>
                                                        <span class="font-medium">{{ $espece->name }}</span>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $espece->created_at ? $espece->created_at->format('d/m/Y') : 'N/A' }}
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="3" class="px-6 py-12 text-center text-gray-500">
                                                    <div class="flex flex-col items-center">
                                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                                            <i class="fas fa-leaf text-2xl text-gray-400"></i>
                                                        </div>
                                                        <p class="text-lg font-medium">Aucune espèce trouvée</p>
                                                        <p class="text-sm">Commencez par ajouter une nouvelle espèce</p>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            @if($especes->hasPages())
                                <div class="mt-6 flex items-center justify-between">
                                    <div class="text-sm text-gray-700">
                                        Affichage de {{ $especes->firstItem() }} à {{ $especes->lastItem() }} sur {{ $especes->total() }} résultats
                                    </div>
                                    <div class="flex items-center gap-2">
                                        {{ $especes->appends(request()->query())->links() }}
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Avenants Tab -->
                        <div class="tab-pane fade" id="avenants" role="tabpanel">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-file-contract text-white text-lg"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-900">Liste des Avenants</h3>
                                        <p class="text-gray-600">Gérez les avenants de contrat</p>
                                    </div>
                                </div>
                                <a href="{{ route('contracts.avenants.create') }}" 
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                                    <i class="fas fa-plus"></i>
                                    <span>Nouvel Avenant</span>
                                </a>
                            </div>
                            
                            <!-- Search Box -->
                            <div class="mb-6">
                                <form method="GET" action="{{ route('contracts.index') }}" class="flex gap-3">
                                    <input type="hidden" name="tab" value="avenants">
                                    <div class="flex-1 relative">
                                        <input type="text" 
                                               name="avenant_search" 
                                               class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 hover:border-gray-400" 
                                               placeholder="Rechercher un avenant (année, coopérative)..." 
                                               value="{{ request('avenant_search') }}">
                                        <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                            <i class="fas fa-search"></i>
                                        </div>
                                    </div>
                                    <button type="submit" 
                                            class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-300">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    @if(request('avenant_search'))
                                        <a href="{{ route('contracts.index') }}" 
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
                                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Année</th>
                                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Coopérative</th>
                                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Avenant</th>
                                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date de Création</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @forelse($avenants as $avenant)
                                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $avenant->id }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    <span class="badge bg-primary">{{ $avenant->annee }}</span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $avenant->date ? $avenant->date->format('d/m/Y') : 'N/A' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    @if($avenant->coperative)
                                                        <div class="flex items-center gap-2">
                                                            <i class="fas fa-building text-indigo-500"></i>
                                                            <span>{{ $avenant->coperative->nom ?? 'N/A' }}</span>
                                                        </div>
                                                    @else
                                                        <span class="text-gray-500">-</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    @if($avenant->total_avenant)
                                                        <span class="badge bg-success">
                                                            {{ number_format($avenant->total_avenant, 2) }} DH
                                                        </span>
                                                    @else
                                                        <span class="text-gray-500">-</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $avenant->created_at ? $avenant->created_at->format('d/m/Y') : 'N/A' }}
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                                    <div class="flex flex-col items-center">
                                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                                            <i class="fas fa-file-contract text-2xl text-gray-400"></i>
                                                        </div>
                                                        <p class="text-lg font-medium">Aucun avenant trouvé</p>
                                                        <p class="text-sm">Commencez par ajouter un nouvel avenant</p>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            @if($avenants->hasPages())
                                <div class="mt-6 flex items-center justify-between">
                                    <div class="text-sm text-gray-700">
                                        Affichage de {{ $avenants->firstItem() }} à {{ $avenants->lastItem() }} sur {{ $avenants->total() }} résultats
                                    </div>
                                    <div class="flex items-center gap-2">
                                        {{ $avenants->appends(request()->query())->links() }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
</style>
@endsection
