@extends('layouts.app')

@section('title', 'Détails de l\'Article - DEFATP')

@section('content')
<div class="min-h-screen py-8">
    <div class="container mx-auto px-4">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="p-6">
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
                        'cahier_affiche' => ['label' => 'Cahier affiche', 'icon' => 'fa-file-alt', 'color' => 'blue'],
                        'contrat_vente' => ['label' => 'Contrat de vente', 'icon' => 'fa-file-contract', 'color' => 'green'],
                        'lettre_adjudicataire' => ['label' => 'Lettre adjudicataire', 'icon' => 'fa-envelope', 'color' => 'purple'],
                        'permis_exploiter' => ['label' => 'Permis d\'exploiter', 'icon' => 'fa-id-card', 'color' => 'amber'],
                        'permis_enlever' => ['label' => 'Permis d\'enlever', 'icon' => 'fa-id-badge', 'color' => 'orange'],
                        'pv_installation' => ['label' => 'PV d\'installation', 'icon' => 'fa-clipboard-check', 'color' => 'indigo'],
                        'permis_colportage' => ['label' => 'Permis de colportage', 'icon' => 'fa-certificate', 'color' => 'emerald'],
                    ];
                    $currentStep = $article->current_step ?? 'cahier_affiche';
                    $stepIndex = array_search($currentStep, array_keys($steps));
                    // Progress is based on completed steps (current_step is the last completed step)
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
                                // current_step represents the LAST completed step
                                // So steps up to and including current_step are completed
                                // The step after current_step is active/in progress
                                $isCompleted = $stepNum <= $stepIndex;
                                $isActive = $stepNum === ($stepIndex + 1);
                                $isPending = $stepNum > ($stepIndex + 1);
                                
                                // Determine styling based on state
                                if ($isCompleted) {
                                    $circleClass = match($step['color']) {
                                        'blue' => 'bg-blue-500 text-white border-blue-500',
                                        'green' => 'bg-green-500 text-white border-green-500',
                                        'purple' => 'bg-purple-500 text-white border-purple-500',
                                        'amber' => 'bg-amber-500 text-white border-amber-500',
                                        'orange' => 'bg-orange-500 text-white border-orange-500',
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
                                        'orange' => 'bg-orange-500 text-white border-orange-500 ring-4 ring-orange-200',
                                        'indigo' => 'bg-indigo-500 text-white border-indigo-500 ring-4 ring-indigo-200',
                                        'emerald' => 'bg-emerald-500 text-white border-emerald-500 ring-4 ring-emerald-200',
                                        default => 'bg-gray-500 text-white border-gray-500 ring-4 ring-gray-200',
                                    };
                                    $textClass = match($step['color']) {
                                        'blue' => 'text-blue-600',
                                        'green' => 'text-green-600',
                                        'purple' => 'text-purple-600',
                                        'amber' => 'text-amber-600',
                                        'orange' => 'text-orange-600',
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
            <!-- Left Column: Dynamic Form Section (2/3 width) -->
            <div class="lg:col-span-2 space-y-6">
                @php
                    $currentStep = $article->current_step ?? 'cahier_affiche';
                    $currentStepIndex = array_search($currentStep, array_keys($steps));
                    $nextStepIndex = $currentStepIndex !== false ? ($currentStepIndex + 1) : 0;
                    $nextStepKey = $nextStepIndex < count($steps) ? array_keys($steps)[$nextStepIndex] : null;
                @endphp

                @if($currentStep === 'cahier_affiche' || $nextStepKey === 'contrat_vente')
                    <!-- Contract Vente Section -->
                    <div class="bg-white/70 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                        <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-4">
                            <h2 class="text-xl font-bold text-white flex items-center gap-3">
                                <i class="fas fa-file-contract"></i>
                                Contrat de Vente
                            </h2>
                        </div>
                        <div class="p-6">
                            @if($contractVente)
                                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
                                    <p class="text-green-800 text-sm mb-4">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        Un contrat de vente existe déjà pour cet article.
                                    </p>
                                    <div class="flex gap-3">
                                        <a href="{{ route('contract-ventes.edit', [$article, $contractVente]) }}" 
                                           class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all duration-300">
                                            <i class="fas fa-edit"></i>
                                            <span>Modifier le Contrat</span>
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="text-center space-y-6">
                                    <div>
                                        <i class="fas fa-file-contract text-green-500 text-6xl mb-4"></i>
                                        <p class="text-gray-700 mb-6">Aucun contrat de vente n'a été créé pour cet article</p>
                                    </div>
                                    
                                    <div class="flex justify-center gap-4">
                                        <a href="{{ route('contract-ventes.create', $article) }}" 
                                           class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                                            <i class="fas fa-plus"></i>
                                            <span>Créer un Contrat de Vente</span>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @elseif($currentStep === 'contrat_vente' || $nextStepKey === 'lettre_adjudicataire')
                    <!-- Lettre Adjudicataire Section -->
                    <div class="bg-white/70 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-500 to-purple-600 px-6 py-4">
                            <h2 class="text-xl font-bold text-white flex items-center gap-3">
                                <i class="fas fa-envelope"></i>
                                Lettre Adjudicataire
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="text-center space-y-6">
                                <div>
                                    <i class="fas fa-file-pdf text-purple-500 text-6xl mb-4"></i>
                                    <p class="text-gray-700 mb-6">Téléchargez la lettre adjudicataire pour cet article</p>
                                </div>
                                
                                <div class="flex justify-center gap-4">
                                    <a href="#" 
                                       class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                                        <i class="fas fa-download"></i>
                                        <span>Télécharger la Lettre Adjudicataire</span>
                                    </a>
                                </div>
                            </div>

                            {{-- Route removed: articles.update-step --}}
                            {{-- 
                            <form action="{{ route('articles.update-step', $article) }}" method="POST" class="mt-8">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="next_step" value="lettre_adjudicataire">
                                
                                <div class="flex justify-end gap-4">
                                    <button type="submit" 
                                        class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                                        <i class="fas fa-arrow-right"></i>
                                        <span>Passer à l'étape suivante</span>
                                    </button>
                                </div>
                            </form>
                            --}}
                        </div>
                    </div>
                @elseif($currentStep === 'lettre_adjudicataire' || $nextStepKey === 'permis_exploiter')
                    <!-- Permis d'Exploiter Form -->
                    <div class="bg-white/70 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                        <div class="bg-gradient-to-r from-amber-500 to-amber-600 px-6 py-4">
                            <h2 class="text-xl font-bold text-white flex items-center gap-3">
                                <i class="fas fa-id-card"></i>
                                Permis d'Exploiter
                            </h2>
                        </div>
                        <div class="p-6">
                            {{-- Route removed: permis.store --}}
                            <form action="#" method="POST" id="permisExploiterForm" onsubmit="alert('Route permis.store non disponible'); return false;">
                                @csrf
                                <input type="hidden" name="article_id" value="{{ $article->id }}">
                                @if($contractVente)
                                    <input type="hidden" name="contract_vente_id" value="{{ $contractVente->id }}">
                                @endif
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="form-group">
                                        <label for="num_assurance" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Numéro d'Assurance
                                        </label>
                                        <input type="text" 
                                            id="num_assurance" 
                                            name="num_assurance" 
                                            value="{{ old('num_assurance', $permis->num_assurance ?? '') }}" 
                                            class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                                            placeholder="Numéro d'assurance">
                                    </div>
                                    <div class="form-group">
                                        <label for="percepteur" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Percepteur
                                        </label>
                                        <input type="text" 
                                            id="percepteur" 
                                            name="percepteur" 
                                            value="{{ old('percepteur', $permis->percepteur ?? '') }}" 
                                            class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                                            placeholder="Nom du percepteur">
                                    </div>
                                    <div class="form-group">
                                        <label for="num_quittance" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Numéro de Quittance
                                        </label>
                                        <input type="text" 
                                            id="num_quittance" 
                                            name="num_quittance" 
                                            value="{{ old('num_quittance', $permis->num_quittance ?? '') }}" 
                                            class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                                            placeholder="Numéro de quittance">
                                    </div>
                                    <div class="form-group">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Clôture
                                        </label>
                                        <div class="flex items-center space-x-4 mt-2">
                                            <label class="inline-flex items-center">
                                                <input type="radio" 
                                                    name="cloture" 
                                                    value="0" 
                                                    {{ old('cloture', $permis->cloture ?? 0) == 0 ? 'checked' : '' }}
                                                    class="form-radio text-amber-600">
                                                <span class="ml-2">Non</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" 
                                                    name="cloture" 
                                                    value="1" 
                                                    {{ old('cloture', $permis->cloture ?? 0) == 1 ? 'checked' : '' }}
                                                    class="form-radio text-amber-600">
                                                <span class="ml-2">Oui</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6 flex justify-end gap-4">
                                    <button type="submit" 
                                        class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-amber-500 to-amber-600 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                                        <i class="fas fa-save"></i>
                                        <span>Enregistrer le Permis d'Exploiter</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @elseif($currentStep === 'permis_exploiter' || $nextStepKey === 'permis_enlever')
                    <!-- Permis d'Enlever Form -->
                    <div class="bg-white/70 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                        <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4">
                            <h2 class="text-xl font-bold text-white flex items-center gap-3">
                                <i class="fas fa-id-badge"></i>
                                Permis d'Enlever
                            </h2>
                        </div>
                        <div class="p-6">
                            @php
                                $nombreTranche = $contractVente->nombre_tranche ?? 1;
                                $nombreTranche = in_array($nombreTranche, [1, 2, 4]) ? $nombreTranche : 1;
                            @endphp
                            
                            @if(!$contractVente || !$contractVente->nombre_tranche)
                                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6">
                                    <p class="text-amber-800 text-sm">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>
                                        Le nombre de tranches n'est pas défini dans le contrat de vente. Veuillez d'abord créer un contrat de vente avec un nombre de tranches (1, 2 ou 4).
                                    </p>
                                </div>
                            @else
                                {{-- Route removed: permi-enlevers.store --}}
                                <form action="#" method="POST" id="permisEnleverForm" onsubmit="alert('Route permi-enlevers.store non disponible'); return false;">
                                    @csrf
                                    <input type="hidden" name="article_id" value="{{ $article->id }}">
                                    
                                    <div class="mb-4">
                                        <p class="text-sm text-gray-600">
                                            <i class="fas fa-info-circle mr-2"></i>
                                            Nombre de tranches: <strong>{{ $nombreTranche }}</strong> - Veuillez remplir {{ $nombreTranche }} permis d'enlever.
                                        </p>
                                    </div>

                                    @for($i = 1; $i <= $nombreTranche; $i++)
                                        <div class="mb-8 p-4 bg-gray-50 rounded-xl border border-gray-200">
                                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                                                <span class="w-8 h-8 bg-orange-500 text-white rounded-full flex items-center justify-center text-sm">{{ $i }}</span>
                                                Permis d'Enlever #{{ $i }}
                                            </h3>
                                            
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                <div class="form-group">
                                                    <label for="date_{{ $i }}" class="block text-sm font-semibold text-gray-700 mb-2">
                                                        Date <span class="text-red-500">*</span>
                                                    </label>
                                                    <input type="date" 
                                                        id="date_{{ $i }}" 
                                                        name="permi_enlevers[{{ $i-1 }}][date]" 
                                                        value="{{ old('permi_enlevers.' . ($i-1) . '.date', '') }}" 
                                                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                                        required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="num_quittance_{{ $i }}" class="block text-sm font-semibold text-gray-700 mb-2">
                                                        Numéro de Quittance
                                                    </label>
                                                    <input type="text" 
                                                        id="num_quittance_{{ $i }}" 
                                                        name="permi_enlevers[{{ $i-1 }}][num_quittance]" 
                                                        value="{{ old('permi_enlevers.' . ($i-1) . '.num_quittance', '') }}" 
                                                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                                        placeholder="Numéro de quittance">
                                                </div>
                                                <div class="form-group">
                                                    <label for="num_tranche_paye_{{ $i }}" class="block text-sm font-semibold text-gray-700 mb-2">
                                                        Numéro de Tranche Payée
                                                    </label>
                                                    <input type="number" 
                                                        id="num_tranche_paye_{{ $i }}" 
                                                        name="permi_enlevers[{{ $i-1 }}][num_tranche_paye]" 
                                                        min="1"
                                                        value="{{ old('permi_enlevers.' . ($i-1) . '.num_tranche_paye', $i) }}" 
                                                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                                        placeholder="Numéro de tranche">
                                                </div>
                                                <div class="form-group">
                                                    <label for="percepteur_{{ $i }}" class="block text-sm font-semibold text-gray-700 mb-2">
                                                        Percepteur
                                                    </label>
                                                    <input type="text" 
                                                        id="percepteur_{{ $i }}" 
                                                        name="permi_enlevers[{{ $i-1 }}][percepteur]" 
                                                        value="{{ old('permi_enlevers.' . ($i-1) . '.percepteur', '') }}" 
                                                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                                        placeholder="Nom du percepteur">
                                                </div>
                                                <div class="form-group md:col-span-2">
                                                    <label for="volume_{{ $i }}" class="block text-sm font-semibold text-gray-700 mb-2">
                                                        Volume
                                                    </label>
                                                    <input type="number" 
                                                        id="volume_{{ $i }}" 
                                                        name="permi_enlevers[{{ $i-1 }}][volume]" 
                                                        step="0.01"
                                                        min="0"
                                                        value="{{ old('permi_enlevers.' . ($i-1) . '.volume', '') }}" 
                                                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                                        placeholder="Volume">
                                                </div>
                                            </div>
                                        </div>
                                    @endfor

                                    <div class="mt-6 flex justify-end gap-4">
                                        <button type="submit" 
                                            class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                                            <i class="fas fa-save"></i>
                                            <span>Enregistrer les {{ $nombreTranche }} Permis d'Enlever</span>
                                        </button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                @elseif($currentStep === 'permis_enlever' || $nextStepKey === 'pv_installation')
                    <!-- PV d'Installation Form -->
                    <div class="bg-white/70 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-4">
                            <h2 class="text-xl font-bold text-white flex items-center gap-3">
                                <i class="fas fa-clipboard-check"></i>
                                PV d'Installation
                            </h2>
                        </div>
                        <div class="p-6">
                            {{-- Route removed: pv-installations.store --}}
                            <form action="#" method="POST" id="pvInstallationForm" onsubmit="alert('Route pv-installations.store non disponible'); return false;">
                                @csrf
                                <input type="hidden" name="article_id" value="{{ $article->id }}">
                                @if($contractVente)
                                    <input type="hidden" name="contract_vente_id" value="{{ $contractVente->id }}">
                                @endif
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="form-group">
                                        <label for="pvn" class="block text-sm font-semibold text-gray-700 mb-2">
                                            PVN <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" 
                                            id="pvn" 
                                            name="pvn" 
                                            value="{{ old('pvn', '') }}" 
                                            class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                            placeholder="Numéro PVN"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label for="pv_date" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Date du PV <span class="text-red-500">*</span>
                                        </label>
                                        <input type="date" 
                                            id="pv_date" 
                                            name="pv_date" 
                                            value="{{ old('pv_date', '') }}" 
                                            class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                            required>
                                    </div>
                                    <div class="form-group md:col-span-2">
                                        <label for="pv_participants" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Participants
                                        </label>
                                        <textarea id="pv_participants" 
                                            name="pv_participants" 
                                            rows="3"
                                            class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                            placeholder="Liste des participants..."></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="pv_exploitant" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Exploitant
                                        </label>
                                        <input type="text" 
                                            id="pv_exploitant" 
                                            name="pv_exploitant" 
                                            value="{{ old('pv_exploitant', $article->exploitant->nom_complet ?? '') }}" 
                                            class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                            placeholder="Nom de l'exploitant">
                                    </div>
                                    <div class="form-group">
                                        <label for="pv_reserve" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Réserve
                                        </label>
                                        <input type="text" 
                                            id="pv_reserve" 
                                            name="pv_reserve" 
                                            value="{{ old('pv_reserve', '') }}" 
                                            class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                            placeholder="Réserve">
                                    </div>
                                </div>

                                <div class="mt-6 flex justify-end gap-4">
                                    <button type="submit" 
                                        class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-500 to-indigo-600 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                                        <i class="fas fa-save"></i>
                                        <span>Enregistrer le PV</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @elseif($currentStep === 'pv_installation' || $nextStepKey === 'permis_colportage')
                    <!-- Permis de Colportage Form -->
                    <div class="bg-white/70 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                        <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 px-6 py-4">
                            <h2 class="text-xl font-bold text-white flex items-center gap-3">
                                <i class="fas fa-certificate"></i>
                                Permis de Colportage
                            </h2>
                        </div>
                        <div class="p-6">
                            <form action="#" method="POST" id="permisColportageForm">
                                @csrf
                                <input type="hidden" name="article_id" value="{{ $article->id }}">
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="form-group">
                                        <label for="colportage_numero" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Numéro du Permis <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" 
                                            id="colportage_numero" 
                                            name="colportage_numero" 
                                            value="{{ old('colportage_numero', '') }}" 
                                            class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                            placeholder="Numéro du permis de colportage"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label for="colportage_date" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Date du Permis <span class="text-red-500">*</span>
                                        </label>
                                        <input type="date" 
                                            id="colportage_date" 
                                            name="colportage_date" 
                                            value="{{ old('colportage_date', '') }}" 
                                            class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label for="colportage_emetteur" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Émetteur
                                        </label>
                                        <input type="text" 
                                            id="colportage_emetteur" 
                                            name="colportage_emetteur" 
                                            value="{{ old('colportage_emetteur', '') }}" 
                                            class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                            placeholder="Organisme émetteur">
                                    </div>
                                    <div class="form-group">
                                        <label for="colportage_validite" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Date de Validité
                                        </label>
                                        <input type="date" 
                                            id="colportage_validite" 
                                            name="colportage_validite" 
                                            value="{{ old('colportage_validite', '') }}" 
                                            class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                    </div>
                                </div>

                                <div class="mt-6 flex justify-end gap-4">
                                    <button type="submit" 
                                        class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                                        <i class="fas fa-save"></i>
                                        <span>Enregistrer le Permis</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- All Steps Completed -->
                    <div class="bg-white/70 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                        <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-4">
                            <h2 class="text-xl font-bold text-white flex items-center gap-3">
                                <i class="fas fa-check-circle"></i>
                                Tous les Étapes sont Terminées
                            </h2>
                        </div>
                        <div class="p-6 text-center">
                            <div class="text-6xl mb-4">🎉</div>
                            <p class="text-lg text-gray-700 mb-2">Félicitations!</p>
                            <p class="text-gray-600">Tous les étapes de cet article ont été complétées avec succès.</p>
                        </div>
                    </div>
                @endif
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
