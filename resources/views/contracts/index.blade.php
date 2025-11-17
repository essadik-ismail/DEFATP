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
        <div class="mb-8">
            <x-card 
                title="Filtres" 
                subtitle="Filtrez les contrats selon vos critères"
                variant="colored"
                color="blue"
                icon="fas fa-filter"
                padding="compact"
            >
                <form method="GET" action="{{ route('contracts.index') }}" class="space-y-4">
                    <!-- Global Search -->
                    <div class="mb-4">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-search text-blue-500 mr-1"></i>Recherche globale
                        </label>
                        <input 
                            type="text" 
                            name="search" 
                            id="search" 
                            value="{{ request('search') }}"
                            placeholder="Rechercher dans les contrats (numéro, année, localisation, situation, espèce, forêt, coopérative...)"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label for="year" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-calendar text-purple-500 mr-1"></i>Année
                            </label>
                            <select name="year" id="year" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Toutes les années</option>
                                @foreach($availableYears as $year)
                                    <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="localisation_id" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-map-marker-alt text-green-500 mr-1"></i>Localisation
                            </label>
                            <select name="localisation_id" id="localisation_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Toutes les localisations</option>
                                @foreach($localisations as $localisation)
                                    <option value="{{ $localisation->id }}" {{ request('localisation_id') == $localisation->id ? 'selected' : '' }}>
                                        {{ $localisation->CODE }} - {{ $localisation->DRANEF }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="situation_administrative_id" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-building text-indigo-500 mr-1"></i>Situation Administrative
                            </label>
                            <select name="situation_administrative_id" id="situation_administrative_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Toutes les situations</option>
                                @foreach($situations as $situation)
                                    <option value="{{ $situation->id }}" {{ request('situation_administrative_id') == $situation->id ? 'selected' : '' }}>
                                        {{ $situation->commune }} - {{ $situation->province }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="espece_id" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-leaf text-emerald-500 mr-1"></i>Espèce
                            </label>
                            <select name="espece_id" id="espece_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Toutes les espèces</option>
                                @foreach($especesList as $espece)
                                    <option value="{{ $espece->id }}" {{ request('espece_id') == $espece->id ? 'selected' : '' }}>
                                        {{ $espece->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="foret_id" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-tree text-green-500 mr-1"></i>Forêt
                            </label>
                            <select name="foret_id" id="foret_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Toutes les forêts</option>
                                @foreach($forets as $foret)
                                    <option value="{{ $foret->id }}" {{ request('foret_id') == $foret->id ? 'selected' : '' }}>
                                        {{ $foret->foret }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="coperative_id" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-users-cog text-cyan-500 mr-1"></i>Coopérative
                            </label>
                            <select name="coperative_id" id="coperative_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Toutes les coopératives</option>
                                @foreach($coperativesList as $coperative)
                                    <option value="{{ $coperative->id }}" {{ request('coperative_id') == $coperative->id ? 'selected' : '' }}>
                                        {{ $coperative->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <div class="flex gap-3">
                            <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-300">
                                <i class="fas fa-filter"></i>
                                <span>Appliquer les filtres</span>
                            </button>
                            <a href="{{ route('contracts.index') }}" class="inline-flex items-center gap-2 px-4 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300">
                                <i class="fas fa-redo"></i>
                                <span>Réinitialiser</span>
                            </a>
                        </div>
                    </div>
                </form>
            </x-card>
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
