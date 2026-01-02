@extends('layouts.app')

@section('title', 'Détails de l\'Article - DEFATP')

@section('content')
<div class="min-h-screen py-8">
    <div class="container mx-auto px-4">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="bg-white/70 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 p-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-700 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-file-alt text-white text-2xl"></i>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-800 mb-2">
                                Détails de l'Article #{{ $article->numero ?? $article->id }}
                            </h1>
                            <p class="text-gray-600 flex items-center gap-2">
                                <i class="fas fa-calendar-alt text-blue-500"></i>
                                Créé le {{ $article->created_at ? $article->created_at->format('d/m/Y à H:i') : 'N/A' }}
                            </p>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('articles.edit', $article) }}" 
                           class="inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                            <i class="fas fa-edit"></i>
                            <span>Modifier</span>
                        </a>
                        <a href="{{ route('articles.index') }}" 
                           class="inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                            <i class="fas fa-arrow-left"></i>
                            <span>Retour</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Article Steps Progress -->
        <div class="mb-8">
            <div class="bg-white/70 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                    <i class="fas fa-tasks text-blue-500"></i>
                    Statut de l'Article
                </h2>
                @php
                    $steps = [
                        'cahier_affiche' => ['label' => 'Cahier affiché', 'icon' => 'fa-file-alt', 'color' => 'blue'],
                        'contrat_vente' => ['label' => 'Contrat de vente', 'icon' => 'fa-file-contract', 'color' => 'green'],
                        'lettre_adjudicataire' => ['label' => 'Lettre adjudicataire', 'icon' => 'fa-envelope', 'color' => 'purple'],
                        'permis_exploiter' => ['label' => 'Permis d\'exploiter | d\'enlever', 'icon' => 'fa-id-card', 'color' => 'amber'],
                        'pv_installation' => ['label' => 'PV d\'installation', 'icon' => 'fa-clipboard-check', 'color' => 'indigo'],
                        'permis_colportage' => ['label' => 'Permis de colportage', 'icon' => 'fa-certificate', 'color' => 'emerald'],
                    ];
                    $currentStep = $article->current_step ?? 'cahier_affiche';
                    $stepIndex = array_search($currentStep, array_keys($steps));
                    $stepProgress = $stepIndex !== false ? (($stepIndex + 1) / count($steps)) * 100 : 0;
                @endphp
                <div class="relative">
                    <!-- Progress Line -->
                    <div class="absolute top-5 left-0 right-0 h-1 bg-gray-200 rounded-full"></div>
                    <div class="absolute top-5 left-0 h-1 bg-gradient-to-r from-blue-500 to-green-500 rounded-full transition-all duration-500" 
                         style="width: {{ $stepProgress }}%"></div>
                    
                    <!-- Steps -->
                    <div class="relative flex justify-between">
                        
                        @foreach($steps as $stepKey => $step)
                            @php
                                $stepNum = array_search($stepKey, array_keys($steps));
                                $isActive = $stepKey === $currentStep;
                                $isCompleted = $stepNum < $stepIndex;
                                $isPending = $stepNum > $stepIndex;
                                
                                // Determine styling based on state
                                if ($isCompleted) {
                                    $circleClass = match($step['color']) {
                                        'blue' => 'bg-blue-500 text-white border-blue-500',
                                        'green' => 'bg-green-500 text-white border-green-500',
                                        'purple' => 'bg-purple-500 text-white border-purple-500',
                                        'amber' => 'bg-amber-500 text-white border-amber-500',
                                        'indigo' => 'bg-indigo-500 text-white border-indigo-500',
                                        'emerald' => 'bg-emerald-500 text-white border-emerald-500',
                                        default => 'bg-gray-500 text-white border-gray-500',
                                    };
                                    $textClass = 'text-gray-600';
                                    $ringClass = '';
                                } elseif ($isActive) {
                                    $circleClass = match($step['color']) {
                                        'blue' => 'bg-blue-500 text-white border-blue-500 ring-4 ring-blue-200',
                                        'green' => 'bg-green-500 text-white border-green-500 ring-4 ring-green-200',
                                        'purple' => 'bg-purple-500 text-white border-purple-500 ring-4 ring-purple-200',
                                        'amber' => 'bg-amber-500 text-white border-amber-500 ring-4 ring-amber-200',
                                        'indigo' => 'bg-indigo-500 text-white border-indigo-500 ring-4 ring-indigo-200',
                                        'emerald' => 'bg-emerald-500 text-white border-emerald-500 ring-4 ring-emerald-200',
                                        default => 'bg-gray-500 text-white border-gray-500 ring-4 ring-gray-200',
                                    };
                                    $textClass = match($step['color']) {
                                        'blue' => 'text-blue-600',
                                        'green' => 'text-green-600',
                                        'purple' => 'text-purple-600',
                                        'amber' => 'text-amber-600',
                                        'indigo' => 'text-indigo-600',
                                        'emerald' => 'text-emerald-600',
                                        default => 'text-gray-600',
                                    };
                                    $ringClass = 'scale-110';
                                } else {
                                    $circleClass = 'bg-white text-gray-400 border-gray-300';
                                    $textClass = 'text-gray-400';
                                    $ringClass = '';
                                }
                            @endphp
                            <div class="flex flex-col items-center flex-1">
                                <div class="relative z-10 w-12 h-12 rounded-full {{ $circleClass }} border-2 flex items-center justify-center shadow-lg transition-all duration-300 {{ $ringClass }}">
                                    <i class="fas {{ $step['icon'] }} text-sm"></i>
                                </div>
                                <div class="mt-3 text-center">
                                    <div class="text-xs font-semibold {{ $textClass }}">
                                        {{ $step['label'] }}
                                    </div>
                                    @if($isActive)
                                        <div class="mt-1 text-xs text-gray-500">En cours</div>
                                    @elseif($isCompleted)
                                        <div class="mt-1 text-xs text-green-600">✓ Terminé</div>
                                    @else
                                        <div class="mt-1 text-xs text-gray-400">En attente</div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content: Two Column Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Contract Vente Form (2/3 width) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Contract Vente Form -->
                <div class="bg-white/70 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                    <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-4">
                        <h2 class="text-xl font-bold text-white flex items-center gap-3">
                            <i class="fas fa-file-contract"></i>
                            Contrat de Vente
                        </h2>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('contract-ventes.store') }}" method="POST" id="contractVenteForm">
                            @if($contractVente)
                                <input type="hidden" name="_method" value="PUT">
                                <input type="hidden" name="contract_vente_id" value="{{ $contractVente->id }}">
                            @endif
                            @csrf
                            <input type="hidden" name="article_id" value="{{ $article->id }}">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="form-group">
                                    <label for="date_adjudication" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Date d'Adjudication <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" 
                                        id="date_adjudication" 
                                        name="date_adjudication" 
                                        value="{{ old('date_adjudication', $contractVente->date_adjudication ?? '') }}" 
                                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                        required>
                                    @error('date_adjudication')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="exploitant_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Exploitant
                                    </label>
                                    <select id="exploitant_id" 
                                        name="exploitant_id" 
                                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                        <option value="">Sélectionner un exploitant</option>
                                        @foreach($exploitants as $exploitant)
                                            <option value="{{ $exploitant->id }}" 
                                                {{ old('exploitant_id', $contractVente->exploitant_id ?? $article->exploitant_id) == $exploitant->id ? 'selected' : '' }}>
                                                {{ $exploitant->nom_complet ?? $exploitant->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('exploitant_id')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="prix_vente" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Prix de Vente
                                    </label>
                                    <input type="number" 
                                        id="prix_vente" 
                                        name="prix_vente" 
                                        step="0.01" 
                                        min="0"
                                        value="{{ old('prix_vente', $contractVente->prix_vente ?? '') }}" 
                                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                        placeholder="0.00">
                                    @error('prix_vente')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="prix_de_retrait" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Prix de Retrait
                                    </label>
                                    <input type="number" 
                                        id="prix_de_retrait" 
                                        name="prix_de_retrait" 
                                        step="0.01" 
                                        min="0"
                                        value="{{ old('prix_de_retrait', $contractVente->prix_de_retrait ?? '') }}" 
                                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                        placeholder="0.00">
                                    @error('prix_de_retrait')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="nombre_tranche" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Nombre de Tranches
                                    </label>
                                    <select id="nombre_tranche" 
                                        name="nombre_tranche" 
                                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                        <option value="">Sélectionner...</option>
                                        <option value="1" {{ old('nombre_tranche', $contractVente->nombre_tranche ?? '') == 1 ? 'selected' : '' }}>1</option>
                                        <option value="2" {{ old('nombre_tranche', $contractVente->nombre_tranche ?? '') == 2 ? 'selected' : '' }}>2</option>
                                        <option value="4" {{ old('nombre_tranche', $contractVente->nombre_tranche ?? '') == 4 ? 'selected' : '' }}>4</option>
                                    </select>
                                    @error('nombre_tranche')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="date_de_decheance" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Date de Déchéance
                                    </label>
                                    <input type="date" 
                                        id="date_de_decheance" 
                                        name="date_de_decheance" 
                                        value="{{ old('date_de_decheance', $contractVente->date_de_decheance ?? '') }}" 
                                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                    @error('date_de_decheance')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="id_decheance" class="block text-sm font-semibold text-gray-700 mb-2">
                                        ID Déchéance
                                    </label>
                                    <input type="text" 
                                        id="id_decheance" 
                                        name="id_decheance" 
                                        value="{{ old('id_decheance', $contractVente->id_decheance ?? '') }}" 
                                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                        placeholder="ID de déchéance">
                                    @error('id_decheance')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="date_de_resiliation" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Date de Résiliation
                                    </label>
                                    <input type="date" 
                                        id="date_de_resiliation" 
                                        name="date_de_resiliation" 
                                        value="{{ old('date_de_resiliation', $contractVente->date_de_resiliation ?? '') }}" 
                                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                    @error('date_de_resiliation')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Résiliation
                                    </label>
                                    <div class="flex items-center space-x-4 mt-2">
                                        <label class="inline-flex items-center">
                                            <input type="radio" 
                                                name="is_resiliation" 
                                                value="0" 
                                                {{ old('is_resiliation', $contractVente->is_resiliation ?? 0) == 0 ? 'checked' : '' }}
                                                class="form-radio text-green-600">
                                            <span class="ml-2">Non</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" 
                                                name="is_resiliation" 
                                                value="1" 
                                                {{ old('is_resiliation', $contractVente->is_resiliation ?? 0) == 1 ? 'checked' : '' }}
                                                class="form-radio text-green-600">
                                            <span class="ml-2">Oui</span>
                                        </label>
                                    </div>
                                    @error('is_resiliation')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end gap-4">
                                <button type="submit" 
                                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                                    <i class="fas fa-save"></i>
                                    <span>{{ $contractVente ? 'Mettre à jour' : 'Créer' }} le Contrat</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Column: Article Data (1/3 width) -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Compact Article Information -->
                <div class="bg-white/70 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-4 py-3">
                        <h2 class="text-lg font-bold text-white flex items-center gap-2">
                            <i class="fas fa-info-circle"></i>
                            Informations Article
                        </h2>
                    </div>
                    <div class="p-4 space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Année</label>
                            <div class="text-sm font-medium text-gray-800">{{ $article->annee ?? 'N/A' }}</div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Numéro</label>
                            <div class="text-sm font-medium text-gray-800">{{ $article->numero ?? 'N/A' }}</div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Type</label>
                            <div class="text-sm font-medium text-gray-800">
                                @if($article->type)
                                    {{ ucfirst(str_replace('_', ' ', $article->type)) }}
                                @else
                                    N/A
                                @endif
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Lot</label>
                            <div class="text-sm font-medium text-gray-800">{{ $article->lot ?? 'N/A' }}</div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Parcelle</label>
                            <div class="text-sm font-medium text-gray-800">{{ $article->parcelle ?? 'N/A' }}</div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Superficie</label>
                            <div class="text-sm font-medium text-gray-800">
                                {{ $article->superficie ? number_format($article->superficie, 2) . ' ha' : 'N/A' }}
                            </div>
                        </div>
                        @if($article->forets && $article->forets->count() > 0)
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Forêts</label>
                            <div class="flex flex-wrap gap-1">
                                @foreach($article->forets->take(3) as $foret)
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-emerald-100 text-emerald-800">
                                        {{ $foret->foret }}
                                    </span>
                                @endforeach
                                @if($article->forets->count() > 3)
                                    <span class="text-xs text-gray-500">+{{ $article->forets->count() - 3 }}</span>
                                @endif
                            </div>
                        </div>
                        @endif
                        @if($article->essences && $article->essences->count() > 0)
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Essences</label>
                            <div class="flex flex-wrap gap-1">
                                @foreach($article->essences->take(3) as $essence)
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-purple-100 text-purple-800">
                                        {{ $essence->essence }}
                                    </span>
                                @endforeach
                                @if($article->essences->count() > 3)
                                    <span class="text-xs text-gray-500">+{{ $article->essences->count() - 3 }}</span>
                                @endif
                            </div>
                        </div>
                        @endif
                        @if($article->exploitant)
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Exploitant</label>
                            <div class="text-sm font-medium text-gray-800">
                                {{ $article->exploitant->nom_complet ?? $article->exploitant->nom ?? 'N/A' }}
                            </div>
                        </div>
                        @endif
                        @if($article->zdtf)
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">ZDTF</label>
                            <div class="text-sm font-medium text-gray-800">
                                {{ $article->zdtf->sdtf }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-group {
        margin-bottom: 1rem;
    }
    
    .form-group:last-child {
        margin-bottom: 0;
    }
</style>
@endsection
