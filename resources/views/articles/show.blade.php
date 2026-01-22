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
                        <div class="w-16 h-16 rounded-xl flex items-center justify-center shadow-lg" style="background: linear-gradient(135deg, #059669, #047857);">
                            <i class="fas fa-file-alt text-white text-2xl"></i>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-800 mb-2">
                                Détails de l'Article #{{ $article->numero ?? $article->id }}
                            </h1>
                            <p class="text-gray-600 flex items-center gap-2">
                                <i class="fas fa-calendar-alt text-green-400"></i>
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
                    <i class="fas fa-tasks text-green-400"></i>
                    Statut de l'Article
                </h2>
                @php
                    $steps = [
                        'cahier_affiche' => ['label' => 'Cahier affiche', 'icon' => 'fa-file-alt', 'color' => 'green'],
                        'contrat_vente' => ['label' => 'Contrat de vente', 'icon' => 'fa-file-contract', 'color' => 'green'],
                        'paiement_charges' => ['label' => 'Paiement des charges', 'icon' => 'fa-money-bill-wave', 'color' => 'green'],
                        'paiement_tranches' => ['label' => 'Paiement des tranches', 'icon' => 'fa-credit-card', 'color' => 'green'],
                        'recollement' => ['label' => 'Récolement', 'icon' => 'fa-clipboard-check', 'color' => 'green'],
                        'main_levee' => ['label' => 'Main levée', 'icon' => 'fa-check-circle', 'color' => 'green'],
                    ];
                    $currentStep = $article->current_step ?? 'cahier_affiche';
                    $stepIndex = array_search($currentStep, array_keys($steps));
                    // Progress is based on completed steps (current_step is the last completed step)
                    $stepProgress = $stepIndex !== false ? (($stepIndex + 1) / count($steps)) * 100 : 0;
                    
                    // Calculate previous and next steps
                    $previousStepIndex = $stepIndex !== false && $stepIndex > 0 ? $stepIndex - 1 : null;
                    $nextStepIndex = $stepIndex !== false && $stepIndex < count($steps) - 1 ? $stepIndex + 1 : null;
                    $previousStepKey = $previousStepIndex !== null ? array_keys($steps)[$previousStepIndex] : null;
                    $nextStepKey = $nextStepIndex !== null ? array_keys($steps)[$nextStepIndex] : null;
                @endphp
                <div class="relative">
                    <!-- Progress Line -->
                    <div class="absolute top-5 left-0 right-0 h-1 bg-gray-200 rounded-full"></div>
                    <div class="absolute top-5 left-0 h-1 rounded-full transition-all duration-500" 
                         style="width: {{ $stepProgress }}%; background: linear-gradient(135deg, #059669, #047857);"></div>
                    
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
                                    $circleClass = 'text-white border-0';
                                    $circleStyle = 'background: linear-gradient(135deg, #059669, #047857);';
                                    $textClass = 'text-gray-600';
                                    $ringClass = '';
                                } elseif ($isActive) {
                                    $circleClass = 'text-white border-0 ring-4 ring-green-100';
                                    $circleStyle = 'background: linear-gradient(135deg, #059669, #047857);';
                                    $textClass = 'text-green-500';
                                    $ringClass = 'scale-110';
                                } else {
                                    $circleClass = 'bg-white text-gray-400 border-gray-300';
                                    $circleStyle = '';
                                    $textClass = 'text-gray-400';
                                    $ringClass = '';
                                }
                            @endphp
                            <div class="flex flex-col items-center flex-1">
                                <form action="{{ route('articles.update-step', $article) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="step" value="{{ $stepKey }}">
                                    <button type="submit" 
                                            class="relative z-10 w-12 h-12 rounded-full {{ $circleClass }} border-2 flex items-center justify-center shadow-lg transition-all duration-300 {{ $ringClass }} hover:scale-110 cursor-pointer focus:outline-none focus:ring-2 focus:ring-green-300" 
                                            style="{{ $circleStyle }}"
                                            title="Aller à l'étape: {{ $step['label'] }}">
                                        <i class="fas {{ $step['icon'] }} text-sm"></i>
                                    </button>
                                </form>
                                <div class="mt-3 text-center">
                                    <div class="text-xs font-semibold {{ $textClass }}">
                                        {{ $step['label'] }}
                                    </div>
                                    @if($isActive)
                                        <div class="mt-1 text-xs text-gray-500">En cours</div>
                                    @elseif($isCompleted)
                                        <div class="mt-1 text-xs text-green-500">✓ Terminé</div>
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
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
            <!-- Left Column: Dynamic Form Section (4/5 width) -->
            <div class="lg:col-span-4 space-y-6">
                @php
                    $currentStep = $article->current_step ?? 'cahier_affiche';
                    $currentStepIndex = array_search($currentStep, array_keys($steps));
                    $nextStepIndex = $currentStepIndex !== false ? ($currentStepIndex + 1) : 0;
                    $nextStepKey = $nextStepIndex < count($steps) ? array_keys($steps)[$nextStepIndex] : null;
                    // Active step is the next step (the one that should be worked on)
                    $activeStep = $nextStepKey ?? $currentStep;
                @endphp

                @if($activeStep === 'cahier_affiche' || $activeStep === 'contrat_vente')
                    <!-- Contract Vente Section -->
                    <div class="bg-white/70 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                        <div class="px-6 py-4" style="background: linear-gradient(135deg, #059669, #047857);">
                            <h2 class="text-xl font-bold text-white flex items-center gap-3">
                                <i class="fas fa-file-contract"></i>
                                Contrat de Vente
                            </h2>
                        </div>
                    <div class="p-6">
                            @if($contractVente)
                                <div class="mb-6 p-4 bg-green-50 border border-green-100 rounded-xl">
                                    <p class="text-green-600 text-sm mb-4">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        Un contrat de vente existe déjà pour cet article.
                                    </p>
                                    <div class="flex gap-3">
                                        <a href="{{ route('contract-ventes.edit', [$article, $contractVente]) }}"
                                           class="inline-flex items-center gap-2 px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-all duration-300">
                                            <i class="fas fa-edit"></i>
                                            <span>Modifier le Contrat</span>
                                        </a>
                                </div>
                                </div>

                                <!-- Display existing contract details -->
                                <div class="space-y-6">
                                    <!-- Section 1: Type & Date -->
                                    <x-form-section 
                                        title="Informations de l'adjudication"
                                        icon="fas fa-info-circle"
                                        color="green"
                                        columns="2"
                                    >
                                        <div>
                                            <label class="block text-sm font-medium text-green-700 mb-1">Type</label>
                                            <p class="text-green-900 font-semibold">
                                                @if($contractVente->type == 'adjudication')
                                                    Adjudication
                                                @elseif($contractVente->type == 'appel_doffre')
                                                    Appel d'offre
                                                @elseif($contractVente->type == 'marche_negocie')
                                                    Marché négocié
                                                @else
                                                    {{ $contractVente->type }}
                                                @endif
                                            </p>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-green-700 mb-1">Date d'Adjudication</label>
                                            <p class="text-green-900 font-semibold">
                                                {{ $contractVente->date_adjudication ? \Carbon\Carbon::parse($contractVente->date_adjudication)->format('d/m/Y') : 'N/A' }}
                                            </p>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-green-700 mb-1">Numéro Appel d'Offre</label>
                                            <p class="text-green-900 font-semibold">{{ $contractVente->numeraAO ?? 'N/A' }}</p>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-green-700 mb-1">Durée d'échéance</label>
                                            <p class="text-green-900 font-semibold">{{ $contractVente->duree_decheache ?? 'N/A' }}</p>
                                        </div>
                                    </x-form-section>

                                    <!-- Section 2: Exploitant -->
                                    <x-form-section 
                                        title="Informations sur l'exploitant"
                                        icon="fas fa-user-tie"
                                        color="green"
                                        columns="1"
                                    >
                                        <div>
                                            <label class="block text-sm font-medium text-green-700 mb-1">Exploitant</label>
                                            <p class="text-green-900 font-semibold">
                                                {{ $contractVente->exploitant->nom_complet ?? 'N/A' }}
                                                @if($contractVente->exploitant && $contractVente->exploitant->raison_sociale)
                                                    <span class="text-blue-600">({{ $contractVente->exploitant->raison_sociale }})</span>
                                                @endif
                                            </p>
                                        </div>
                                    </x-form-section>

                                    <!-- Section 3: Prix -->
                                    <x-form-section 
                                        title="Détails de la vente"
                                        icon="fas fa-money-bill-wave"
                                        color="green"
                                        columns="3"
                                    >
                                        <div>
                                            <label class="block text-sm font-medium text-green-700 mb-1">Prix de Vente</label>
                                            <p class="text-green-900 font-semibold">{{ number_format($contractVente->prix_vente, 2, ',', ' ') }} MAD</p>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-green-700 mb-1">Prix de Retrait</label>
                                            <p class="text-green-900 font-semibold">{{ number_format($contractVente->prix_de_retrait ?? 0, 2, ',', ' ') }} MAD</p>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-green-700 mb-1">Nombre de Tranches</label>
                                            <p class="text-green-900 font-semibold">{{ $contractVente->nombre_tranche ?? 1 }}</p>
                                        </div>
                                    </x-form-section>

                                    <!-- Section 4: Charges -->
                                    @php
                                        $charges = $contractVente->chargeApayer->filter(function($charge) {
                                            return !str_starts_with($charge->nom, 'Tranche');
                                        });
                                    @endphp
                                    
                                    @if($charges->count() > 0)
                                    <x-form-section 
                                        title="Charges"
                                        icon="fas fa-calculator"
                                        color="blue"
                                        columns="2"
                                    >
                                        @foreach($charges as $charge)
                                            <div>
                                                <label class="block text-sm font-medium text-blue-700 mb-1">{{ $charge->nom }}</label>
                                                <p class="text-blue-900 font-semibold">{{ number_format($charge->montant, 2, ',', ' ') }} MAD</p>
                                                @if($charge->date_echeance)
                                                    <p class="text-xs text-blue-600 mt-1">Échéance: {{ \Carbon\Carbon::parse($charge->date_echeance)->format('d/m/Y') }}</p>
                                                @endif
                                            </div>
                                        @endforeach
                                    </x-form-section>
                                    @endif

                                    <!-- Section 5: Tranches -->
                                    @php
                                        $tranches = $contractVente->chargeApayer->filter(function($charge) {
                                            return str_starts_with($charge->nom, 'Tranche');
                                        });
                                    @endphp
                                    
                                    @if($tranches->count() > 0)
                                    <x-form-section 
                                        title="Détail des tranches"
                                        icon="fas fa-list-ol"
                                        color="purple"
                                        columns="2"
                                    >
                                        @foreach($tranches as $tranche)
                                            <div>
                                                <label class="block text-sm font-medium text-purple-700 mb-1">{{ $tranche->nom }}</label>
                                                <p class="text-purple-900 font-semibold">{{ number_format($tranche->montant, 2, ',', ' ') }} MAD</p>
                                                @if($tranche->date_echeance)
                                                    <p class="text-xs text-purple-600 mt-1">Échéance: {{ \Carbon\Carbon::parse($tranche->date_echeance)->format('d/m/Y') }}</p>
                                                @endif
                                            </div>
                                        @endforeach
                                    </x-form-section>
                                    @endif
                                </div>
                            @else
                                <!-- Inline Contract de Vente Creation Form -->
                                <form action="{{ route('contract-ventes.store', $article) }}" method="POST" id="contractVenteForm" class="space-y-6">
                                    @csrf

                                    <x-validation-errors />

                                    <!-- Section 1: Type & Date -->
                                    <x-form-section 
                                        title="Informations de l'adjudication"
                                        icon="fas fa-info-circle"
                                        color="green"
                                        columns="2"
                                    >
                                        <x-form-input
                                            type="select"
                                            name="type"
                                            label="Type"
                                            :required="true"
                                            focusColor="green"
                                        >
                                            <option value="">Sélectionner un type</option>
                                            <option value="adjudication" {{ old('type') == 'adjudication' ? 'selected' : '' }}>Adjudication</option>
                                            <option value="appel_doffre" {{ old('type') == 'appel_doffre' ? 'selected' : '' }}>Appel d'offre</option>
                                            <option value="marche_negocie" {{ old('type') == 'marche_negocie' ? 'selected' : '' }}>Marché négocié</option>
                                        </x-form-input>

                                        <x-form-input
                                            type="date"
                                            name="date_adjudication"
                                            label="Date d'Adjudication"
                                            :required="true"
                                            :value="old('date_adjudication')"
                                            focusColor="green"
                                        />

                                        <x-form-input
                                            type="text"
                                            name="numeraAO"
                                            label="Numéro Appel d'Offre"
                                            :value="old('numeraAO')"
                                            focusColor="green"
                                            placeholder="Numéro AO (optionnel)"
                                        />

                                        <x-form-input
                                            type="text"
                                            name="duree_decheache"
                                            label="Durée d'échéance"
                                            :value="old('duree_decheache')"
                                            focusColor="green"
                                            placeholder="Ex: 12 mois, 1 an"
                                        />
                                    </x-form-section>

                                    <!-- Section 2: Exploitant -->
                                    <x-form-section 
                                        title="Informations sur l'exploitant"
                                        icon="fas fa-user-tie"
                                        color="green"
                                        columns="1"
                                    >
                                        <x-form-input
                                            type="select"
                                            name="exploitant_id"
                                            label="Exploitant"
                                            :required="true"
                                            focusColor="green"
                                        >
                                            <option value="">Sélectionner un exploitant</option>
                                            @foreach($exploitants as $exploitant)
                                                <option value="{{ $exploitant->id }}" {{ old('exploitant_id') == $exploitant->id ? 'selected' : '' }}>
                                                    {{ $exploitant->nom_complet }} {{ $exploitant->raison_sociale ? '(' . $exploitant->raison_sociale . ')' : '' }}
                                                </option>
                                            @endforeach
                                        </x-form-input>
                                    </x-form-section>

                                    <!-- Section 3: Prix -->
                                    <x-form-section 
                                        title="Détails de la vente"
                                        icon="fas fa-money-bill-wave"
                                        color="green"
                                        columns="3"
                                    >
                                        <x-form-input
                                            type="number"
                                            name="prix_vente"
                                            label="Prix de Vente (MAD)"
                                            :required="true"
                                            :value="old('prix_vente')"
                                            focusColor="green"
                                            placeholder="0.00"
                                            step="0.01"
                                            min="0"
                                            id="prix_vente"
                                            onchange="calculateCharges()"
                                        />

                                        <x-form-input
                                            type="number"
                                            name="prix_de_retrait"
                                            label="Prix de Retrait (MAD)"
                                            :value="old('prix_de_retrait')"
                                            focusColor="green"
                                            placeholder="0.00"
                                            step="0.01"
                                            min="0"
                                        />

                                        <x-form-input
                                            type="select"
                                            name="nombre_tranche"
                                            label="Nombre de Tranches"
                                            :required="true"
                                            focusColor="green"
                                            id="nombre_tranche"
                                            onchange="updateTranchesInputs()"
                                        >
                                            <option value="1" {{ old('nombre_tranche', 1) == 1 ? 'selected' : '' }}>1</option>
                                            <option value="2" {{ old('nombre_tranche', 1) == 2 ? 'selected' : '' }}>2</option>
                                            <option value="4" {{ old('nombre_tranche', 1) == 4 ? 'selected' : '' }}>4</option>
                                        </x-form-input>
                                    </x-form-section>

                                    <!-- Section 4: Charges (Auto-calculated) -->
                                    <x-form-section 
                                        title="Récapitulatif des charges (calcul automatique)"
                                        icon="fas fa-calculator"
                                        color="blue"
                                        columns="2"
                                    >
                                        <!-- Cautionnement définitif (10%) -->
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">Cautionnement définitif (10%)</label>
                                            <input type="number" 
                                                id="charge_cautionnement" 
                                                name="charges[0][montant]" 
                                                step="0.01" 
                                                readonly
                                                class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-100"
                                                value="0.00">
                                            <input type="hidden" name="charges[0][nom]" value="Cautionnement définitif">
                                        </div>
                                        <x-form-input
                                            type="date"
                                            name="charges[0][date_echeance]"
                                            label="Date d'échéance"
                                            :required="true"
                                            :value="old('charges.0.date_echeance')"
                                            focusColor="blue"
                                        />

                                        <!-- Taxe FNF (20%) -->
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">Taxe FNF (20%)</label>
                                            <input type="number" 
                                                id="charge_taxe_fnf" 
                                                name="charges[1][montant]" 
                                                step="0.01" 
                                                readonly
                                                class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-100"
                                                value="0.00">
                                            <input type="hidden" name="charges[1][nom]" value="Taxe FNF">
                                        </div>
                                        <x-form-input
                                            type="date"
                                            name="charges[1][date_echeance]"
                                            label="Date d'échéance"
                                            :required="true"
                                            :value="old('charges.1.date_echeance')"
                                            focusColor="blue"
                                        />

                                        <!-- Frais d'adjudication (1.6%) -->
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">Frais d'adjudication (1.6%)</label>
                                            <input type="number" 
                                                id="charge_frais_adjudication" 
                                                name="charges[2][montant]" 
                                                step="0.01" 
                                                readonly
                                                class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-100"
                                                value="0.00">
                                            <input type="hidden" name="charges[2][nom]" value="Frais d'adjudication">
                                        </div>
                                        <x-form-input
                                            type="date"
                                            name="charges[2][date_echeance]"
                                            label="Date d'échéance"
                                            :required="true"
                                            :value="old('charges.2.date_echeance')"
                                            focusColor="blue"
                                        />

                                        <!-- Taxe provinciale (10%) -->
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">Taxe provinciale (10%)</label>
                                            <input type="number" 
                                                id="charge_taxe_provinciale" 
                                                name="charges[3][montant]" 
                                                step="0.01" 
                                                readonly
                                                class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-100"
                                                value="0.00">
                                            <input type="hidden" name="charges[3][nom]" value="Taxe provinciale">
                                        </div>
                                        <x-form-input
                                            type="date"
                                            name="charges[3][date_echeance]"
                                            label="Date d'échéance"
                                            :required="true"
                                            :value="old('charges.3.date_echeance')"
                                            focusColor="blue"
                                        />
                                    </x-form-section>

                                    <!-- Section 5: Tranches -->
                                    <x-form-section 
                                        title="Détail des tranches"
                                        icon="fas fa-list-ol"
                                        color="purple"
                                        columns="1"
                                    >
                                        <div id="tranchesContainer" class="space-y-4">
                                            <!-- Tranches will be dynamically generated -->
                                        </div>
                                    </x-form-section>

                                    <!-- Submit Button -->
                                    <div class="flex justify-end gap-4">
                                        <button type="submit" 
                                                class="inline-flex items-center gap-2 px-8 py-4 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300"
                                                style="background: linear-gradient(135deg, #059669, #047857);">
                                            <i class="fas fa-save"></i>
                                            <span>Créer le Contrat de Vente</span>
                                        </button>
                                    </div>
                                </form>

                                @push('scripts')
                                <script>
                                // Calculate charges based on prix_vente
                                function calculateCharges() {
                                    const prixVente = parseFloat(document.getElementById('prix_vente').value) || 0;
                                    
                                    // Cautionnement définitif (10%)
                                    document.getElementById('charge_cautionnement').value = (prixVente * 0.10).toFixed(2);
                                    
                                    // Taxe FNF (20%)
                                    document.getElementById('charge_taxe_fnf').value = (prixVente * 0.20).toFixed(2);
                                    
                                    // Frais d'adjudication (1.6%)
                                    document.getElementById('charge_frais_adjudication').value = (prixVente * 0.016).toFixed(2);
                                    
                                    // Taxe provinciale (10%)
                                    document.getElementById('charge_taxe_provinciale').value = (prixVente * 0.10).toFixed(2);
                                }

                                // Update tranches inputs based on nombre_tranche
                                function updateTranchesInputs() {
                                    const nombreTranches = parseInt(document.getElementById('nombre_tranche').value) || 1;
                                    const container = document.getElementById('tranchesContainer');
                                    
                                    // Clear existing tranches
                                    container.innerHTML = '';
                                    
                                    // Create tranche inputs
                                    for (let i = 0; i < nombreTranches; i++) {
                                        const trancheDiv = document.createElement('div');
                                        trancheDiv.className = 'bg-purple-50 rounded-xl p-4 border border-purple-200';
                                        trancheDiv.innerHTML = `
                                            <h3 class="text-md font-semibold text-purple-700 mb-4">
                                                <i class="fas fa-money-check-alt mr-2"></i>Tranche ${i + 1}
                                            </h3>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                        Montant (MAD) <span class="text-red-500">*</span>
                                                    </label>
                                                    <input type="number" 
                                                        name="tranches[${i}][montant]" 
                                                        step="0.01" 
                                                        min="0"
                                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                                        placeholder="0.00"
                                                        required>
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                        Date d'échéance <span class="text-red-500">*</span>
                                                    </label>
                                                    <input type="date" 
                                                        name="tranches[${i}][date_echeance]" 
                                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                                        required>
                                                </div>
                                            </div>
                                        `;
                                        container.appendChild(trancheDiv);
                                    }
                                }

                                // Initialize on page load
                                document.addEventListener('DOMContentLoaded', function() {
                                    updateTranchesInputs();
                                    calculateCharges();
                                });
                                </script>
                                @endpush
                            @endif
                    </div>
                    </div>
                @elseif($activeStep === 'paiement_charges')
                    <!-- Paiement des Charges Section -->
                    <div class="bg-white/70 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                        <div class="px-6 py-4" style="background: linear-gradient(135deg, #059669, #047857);">
                            <h2 class="text-xl font-bold text-white flex items-center gap-3">
                                <i class="fas fa-money-bill-wave"></i>
                                Paiement des Charges
                            </h2>
                        </div>
                        <div class="p-6">
                            @if(!$contractVente)
                            <div class="text-center space-y-6">
                                <div>
                                        <i class="fas fa-exclamation-triangle text-amber-500 text-6xl mb-4"></i>
                                        <p class="text-gray-700 mb-6">Aucun contrat de vente n'a été créé pour cet article. Veuillez d'abord créer un contrat de vente.</p>
                                    </div>
                                </div>
                            @else
                                @php
                                    $charges = $contractVente->chargeApayer->filter(function($charge) {
                                        return !str_starts_with($charge->nom, 'Tranche');
                                    })->values();
                                    
                                    // Define required charges
                                    $requiredCharges = [
                                        'Cautionnement définitif',
                                        'Taxe FNF',
                                        'Taxe des frais d\'adjudication',
                                        'Taxe provinciale',
                                        'Taxe pour la réfection des chemins forestiers',
                                        'Service rendu par l\'ANEF'
                                    ];
                                    
                                    // Ensure all required charges exist
                                    $existingChargeNames = $charges->pluck('nom')->toArray();
                                    foreach ($requiredCharges as $requiredName) {
                                        if (!in_array($requiredName, $existingChargeNames)) {
                                            // Create missing charge
                                            $charges->push((object)[
                                                'id' => null,
                                                'nom' => $requiredName,
                                                'montant' => 0,
                                                'date_echeance' => null,
                                                'payments' => collect()
                                            ]);
                                        }
                                    }
                                @endphp
                                
                                <!-- Table with all charges and payment information -->
                                <div class="overflow-x-auto shadow-md rounded-lg">
                                    <table class="w-full table-auto divide-y divide-gray-200" style="min-width: 1500px;">
                                        <thead class="bg-green-50 border-b border-green-200">
                                            <tr>
                                                <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase" style="width: 18%;">Charge</th>
                                                <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase" style="width: 9%;">Montant</th>
                                                <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase" style="width: 9%;">Date échéance</th>
                                                <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase" style="width: 10%;">Statut</th>
                                                <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase" style="width: 13%;">Référence</th>
                                                <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase" style="width: 12%;">Date paiement</th>
                                                <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase" style="width: 18%;">Pièce jointe</th>
                                                <th class="px-3 py-3 text-center text-xs font-semibold text-gray-700 uppercase" style="width: 11%;">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($charges as $index => $charge)
                                                @php
                                                    $payment = $charge->payments->first() ?? null;
                                                    $isCautionnement = str_contains(strtolower($charge->nom), 'cautionnement');
                                                @endphp
                                                <tr class="hover:bg-gray-50">
                                                    <form action="{{ route('articles.update-charge-payments', $article) }}" method="POST" enctype="multipart/form-data" class="contents">
                                                        @csrf
                                                        @method('PUT')
                                                        
                                                        <!-- Charge -->
                                                        <td class="px-3 py-3" style="width: 18%;">
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ $charge->nom }}
                                                                @if(in_array($charge->nom, $requiredCharges))
                                                                    <span class="ml-1 text-xs text-red-600 font-semibold">*</span>
                                                                @endif
                                                            </div>
                                                        </td>
                                                        
                                                        <!-- Montant -->
                                                        <td class="px-3 py-3 whitespace-nowrap" style="width: 9%;">
                                                            <div class="text-sm font-semibold text-gray-900">
                                                                {{ number_format($charge->montant ?? 0, 2) }}
                                                            </div>
                                                        </td>
                                                        
                                                        <!-- Date échéance -->
                                                        <td class="px-3 py-3 whitespace-nowrap" style="width: 9%;">
                                                            <div class="text-sm text-gray-900">
                                                                @if($charge->date_echeance)
                                                                    {{ \Carbon\Carbon::parse($charge->date_echeance)->format('d/m/Y') }}
                                                                @else
                                                                    <span class="text-gray-400">-</span>
                                                                @endif
                                                            </div>
                                                        </td>
                                                        
                                                        <!-- Statut de paiement -->
                                                        <td class="px-3 py-3" style="width: 10%;">
                                                            <div class="flex items-center">
                                                                <label class="relative inline-flex items-center cursor-pointer">
                                                                    <input type="hidden" name="payments[{{ $charge->id ?? 'new_' . $index }}][statut]" value="0">
                                                                    <input type="checkbox" 
                                                                           name="payments[{{ $charge->id ?? 'new_' . $index }}][statut]" 
                                                                           value="1"
                                                                           {{ $payment && $payment->is_paye ? 'checked' : '' }}
                                                                           class="sr-only peer"
                                                                           onchange="this.nextElementSibling.nextElementSibling.textContent = this.checked ? 'Payé' : 'Impayé'; this.nextElementSibling.nextElementSibling.classList.toggle('text-green-500', this.checked); this.nextElementSibling.nextElementSibling.classList.toggle('text-red-600', !this.checked);">
                                                                    <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-green-400 peer-focus:ring-2 peer-focus:ring-green-200 transition-all">
                                                                        <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-all peer-checked:translate-x-5 shadow-md"></div>
                                                                    </div>
                                                                    <span class="ml-2 text-xs font-medium {{ $payment && $payment->is_paye ? 'text-green-500' : 'text-red-600' }}">
                                                                        {{ $payment && $payment->is_paye ? 'Payé' : 'Impayé' }}
                                                                    </span>
                                                                </label>
                                                            </div>
                                                        </td>
                                                        
                                                        <!-- Référence -->
                                                        <td class="px-3 py-3" style="width: 13%;">
                                                            <input type="text" 
                                                                   name="payments[{{ $charge->id ?? 'new_' . $index }}][reference]" 
                                                                   value="{{ $payment->num_quittace ?? old('payments.' . ($charge->id ?? 'new_' . $index) . '.reference') }}"
                                                                   class="w-full px-2 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-green-400"
                                                                   placeholder="{{ $isCautionnement ? 'Numéro' : 'N° quittance' }}">
                                                        </td>
                                                        
                                                        <!-- Date de paiement -->
                                                        <td class="px-3 py-3" style="width: 12%;">
                                                            <input type="date" 
                                                                   name="payments[{{ $charge->id ?? 'new_' . $index }}][date_payment]" 
                                                                   value="{{ $payment && $payment->date_payment ? \Carbon\Carbon::parse($payment->date_payment)->format('Y-m-d') : old('payments.' . ($charge->id ?? 'new_' . $index) . '.date_payment') }}"
                                                                   class="w-full px-2 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-green-400">
                                                        </td>
                                                        
                                                        <!-- Pièce jointe -->
                                                        <td class="px-3 py-3" style="width: 18%;">
                                                            <div class="flex items-center gap-2">
                                                                <label for="file_{{ $charge->id ?? 'new_' . $index }}" class="cursor-pointer inline-flex items-center justify-center w-10 h-10 text-white rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md" style="background: linear-gradient(135deg, #059669, #047857);">
                                                                    <i class="fas fa-upload text-lg"></i>
                                                                </label>
                                                                <input type="file" 
                                                                       id="file_{{ $charge->id ?? 'new_' . $index }}"
                                                                       name="payments[{{ $charge->id ?? 'new_' . $index }}][fichier_joint]" 
                                                                       accept=".pdf,.jpg,.jpeg,.png"
                                                                       class="hidden"
                                                                       onchange="this.nextElementSibling.textContent = this.files[0]?.name || '';">
                                                                <span class="text-xs text-gray-600 truncate flex-1">
                                                                    @if($payment && $payment->fichier_joint)
                                                                        {{ basename($payment->fichier_joint) }}
                                                                    @endif
                                                                </span>
                                                                @if($payment && $payment->fichier_joint)
                                                                    <a href="{{ asset('storage/' . $payment->fichier_joint) }}" target="_blank" class="text-green-500 hover:text-green-700 transition-colors">
                                                                        <i class="fas fa-eye text-sm"></i>
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        </td>
                                                        
                                                        <!-- Actions -->
                                                        <td class="px-3 py-3 text-center" style="width: 11%;">
                                                                <button type="submit"
                                                                    class="inline-flex items-center gap-1 px-3 py-2 text-white rounded-lg font-medium text-xs shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-200"
                                                                    style="background: linear-gradient(135deg, #059669, #047857);">
                                                                    <i class="fas fa-check"></i>
                                                                    <span>Valider</span>
                                                                </button>
                                                        </td>
                                                        
                                                        <input type="hidden" name="payments[{{ $charge->id ?? 'new_' . $index }}][charge_id]" value="{{ $charge->id }}">
                                                        <input type="hidden" name="payments[{{ $charge->id ?? 'new_' . $index }}][charge_nom]" value="{{ $charge->nom }}">
                                                    </form>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                @elseif($activeStep === 'paiement_tranches')
                    <!-- Paiement des Tranches Section -->
                    <div class="bg-white/70 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                        <div class="px-6 py-4" style="background: linear-gradient(135deg, #059669, #047857);">
                            <h2 class="text-xl font-bold text-white flex items-center gap-3">
                                <i class="fas fa-credit-card"></i>
                                Paiement des Tranches
                            </h2>
                        </div>
                        <div class="p-6">
                            @if(!$contractVente)
                                <div class="text-center space-y-6">
                                    <div>
                                        <i class="fas fa-exclamation-triangle text-orange-500 text-6xl mb-4"></i>
                                        <p class="text-gray-700 mb-6">Aucun contrat de vente n'a été créé pour cet article. Veuillez d'abord créer un contrat de vente.</p>
                                    </div>
                                </div>
                            @else
                                @php
                                    $tranches = $contractVente->chargeApayer->filter(function($charge) {
                                        return str_starts_with($charge->nom, 'Tranche');
                                    })->values();
                                    
                                    $tranchesImpayees = $tranches->filter(function($tranche) {
                                        $payment = $tranche->payments->first();
                                        return !$payment || !$payment->is_paye;
                                    });
                                    
                                    $tranchesPayees = $tranches->filter(function($tranche) {
                                        $payment = $tranche->payments->first();
                                        return $payment && $payment->is_paye;
                                    });
                                @endphp
                                
                                <!-- 1. Sélection des tranches à payer -->
                                @if($tranchesImpayees->count() > 0)
                                <form action="{{ route('articles.pay-tranches', $article) }}" method="POST" enctype="multipart/form-data" id="payTranchesForm">
                                    @csrf
                                    @method('PUT')
                                    
                                    <input type="hidden" name="selected_tranches" id="selectedTranches" value="">
                                    
                                    <div class="mb-8">
                                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                                            <i class="fas fa-hand-pointer text-orange-600"></i>
                                            1. Sélection des tranches à payer
                                        </h3>
                                        
                                        <div class="overflow-x-auto shadow-md rounded-lg mb-4">
                                            <table class="w-full divide-y divide-gray-200">
                                                <thead class="bg-green-50 border-b border-green-100">
                                                    <tr>
                                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">
                                                            <input type="checkbox" id="selectAllTranches" class="rounded border-gray-300 text-orange-600 focus:ring-orange-500" onclick="toggleAllTranches(this)">
                                                        </th>
                                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Numéro de tranche</th>
                                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Montant</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200">
                                                    @foreach($tranchesImpayees as $tranche)
                                                    <tr class="hover:bg-gray-50">
                                                        <td class="px-4 py-3 whitespace-nowrap">
                                                            <input type="checkbox" 
                                                                   class="tranche-checkbox rounded border-gray-300 text-green-500 focus:ring-green-400" 
                                                                   data-tranche-id="{{ $tranche->id }}"
                                                                   data-montant="{{ $tranche->montant }}"
                                                                   onclick="updateTotal()">
                                                        </td>
                                                        <td class="px-4 py-3 whitespace-nowrap">
                                                            <div class="text-sm font-medium text-gray-900">{{ $tranche->nom }}</div>
                                                        </td>
                                                        <td class="px-4 py-3 whitespace-nowrap">
                                                            <div class="text-sm font-semibold text-gray-900">{{ number_format($tranche->montant ?? 0, 2) }} MAD</div>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        
                                        <div class="bg-green-50 border border-green-100 rounded-lg p-4 mb-6">
                                            <div class="flex items-center justify-between mb-4">
                                                <span class="text-sm font-medium text-gray-700">Montant total à payer: </span>
                                                <span class="text-lg font-bold text-green-500" id="totalMontant">0.00 MAD</span>
                                            </div>
                                            
                                            <!-- Informations de paiement -->
                                            <div id="paymentSection" class="hidden space-y-4 pt-4 border-t border-green-200">
                                                <h4 class="font-semibold text-gray-800 mb-3">Informations de paiement</h4>
                                                
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    <div>
                                                        <label for="num_quittance" class="block text-sm font-semibold text-gray-700 mb-2">
                                                            Numéro de quittance <span class="text-red-500">*</span>
                                                        </label>
                                                        <input type="text" 
                                                               id="num_quittance" 
                                                               name="num_quittance" 
                                                               required
                                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                                               placeholder="Numéro de quittance">
                                                    </div>
                                                    
                                                    <div>
                                                        <label for="date_payment" class="block text-sm font-semibold text-gray-700 mb-2">
                                                            Date de paiement <span class="text-red-500">*</span>
                                                        </label>
                                                        <input type="date" 
                                                               id="date_payment" 
                                                               name="date_payment" 
                                                               required
                                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                                    </div>
                                                </div>
                                                
                                                <div>
                                                    <label for="fichier_joint" class="block text-sm font-semibold text-gray-700 mb-2">
                                                        Pièce jointe (justificatif)
                                                    </label>
                                                    <div class="flex items-center gap-2">
                                                        <label for="fichier_joint" class="cursor-pointer inline-flex items-center justify-center w-10 h-10 text-white rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md" style="background: linear-gradient(135deg, #059669, #047857);">
                                                            <i class="fas fa-upload text-lg"></i>
                                                        </label>
                                                        <input type="file" 
                                                               id="fichier_joint" 
                                                               name="fichier_joint" 
                                                               accept=".pdf,.jpg,.jpeg,.png"
                                                               class="hidden"
                                                               onchange="document.getElementById('fileName').textContent = this.files[0]?.name || 'Aucun fichier';">
                                                        <span id="fileName" class="text-sm text-gray-600">Aucun fichier</span>
                                                    </div>
                                                </div>
                                                
                                                <div class="flex justify-end pt-2">
                                                    <button type="submit" 
                                                            class="inline-flex items-center gap-2 px-6 py-3 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300"
                                                            style="background: linear-gradient(135deg, #059669, #047857);">
                                                        <i class="fas fa-check"></i>
                                                        <span>Payer</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                @endif
                                
                                <!-- 2. Rappel des tranches payées -->
                                @if($tranchesPayees->count() > 0)
                                <div class="mb-6">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                                        <i class="fas fa-check-circle text-green-500"></i>
                                        2. Rappel des tranches payées
                                    </h3>
                                    
                                    <div class="overflow-x-auto shadow-md rounded-lg">
                                        <table class="w-full divide-y divide-gray-200">
                                            <thead class="bg-green-50 border-b border-green-100">
                                                <tr>
                                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Tranche payée</th>
                                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Montant payé</th>
                                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Date paiement</th>
                                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Référence</th>
                                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Justificatif</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach($tranchesPayees as $tranche)
                                                    @php $payment = $tranche->payments->first(); @endphp
                                                    <tr class="hover:bg-gray-50">
                                                        <td class="px-4 py-3 whitespace-nowrap">
                                                            <div class="text-sm font-medium text-gray-900">{{ $tranche->nom }}</div>
                                                        </td>
                                                        <td class="px-4 py-3 whitespace-nowrap">
                                                            <div class="text-sm font-semibold text-green-500">{{ number_format($tranche->montant ?? 0, 2) }} MAD</div>
                                                        </td>
                                                        <td class="px-4 py-3 whitespace-nowrap">
                                                            <div class="text-sm text-gray-900">
                                                                {{ $payment && $payment->date_payment ? \Carbon\Carbon::parse($payment->date_payment)->format('d/m/Y') : '-' }}
                                                            </div>
                                                        </td>
                                                        <td class="px-4 py-3 whitespace-nowrap">
                                                            <div class="text-sm text-gray-900">{{ $payment->num_quittace ?? '-' }}</div>
                                                        </td>
                                                        <td class="px-4 py-3 whitespace-nowrap">
                                                            @if($payment && $payment->fichier_joint)
                                                                <a href="{{ asset('storage/' . $payment->fichier_joint) }}" target="_blank" class="text-green-600 hover:text-green-800 transition-colors">
                                                                    <i class="fas fa-file-pdf mr-1"></i> Voir
                                                                </a>
                                                            @else
                                                                <span class="text-gray-400">-</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                @endif
                                
                                @if($tranchesImpayees->count() == 0 && $tranchesPayees->count() == 0)
                                    <div class="text-center space-y-6">
                                        <div>
                                            <i class="fas fa-info-circle text-orange-500 text-6xl mb-4"></i>
                                            <p class="text-gray-700 mb-6">Aucune tranche n'a été définie pour ce contrat de vente.</p>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                    
                    <script>
                    function toggleAllTranches(checkbox) {
                        const checkboxes = document.querySelectorAll('.tranche-checkbox');
                        checkboxes.forEach(cb => {
                            cb.checked = checkbox.checked;
                        });
                        updateTotal();
                    }
                    
                    function updateTotal() {
                        const checkboxes = document.querySelectorAll('.tranche-checkbox:checked');
                        let total = 0;
                        const trancheIds = [];
                        
                        checkboxes.forEach(cb => {
                            total += parseFloat(cb.dataset.montant) || 0;
                            trancheIds.push(cb.dataset.trancheId);
                        });
                        
                        document.getElementById('totalMontant').textContent = total.toFixed(2) + ' MAD';
                        
                        // Show/hide payment section based on selection
                        const paymentSection = document.getElementById('paymentSection');
                        const selectedTranchesInput = document.getElementById('selectedTranches');
                        
                        if (checkboxes.length > 0) {
                            paymentSection.classList.remove('hidden');
                            selectedTranchesInput.value = JSON.stringify(trancheIds);
                        } else {
                            paymentSection.classList.add('hidden');
                            selectedTranchesInput.value = '';
                        }
                    }
                    </script>
                @elseif($activeStep === 'recollement')
                    <!-- Récolement Section -->
                    <div class="bg-white/70 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                        <div class="px-6 py-4" style="background: linear-gradient(135deg, #059669, #047857);">
                            <h2 class="text-xl font-bold text-white flex items-center gap-3">
                                <i class="fas fa-clipboard-check"></i>
                                Récolement
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="text-center py-12">
                                <div class="mb-6">
                                    <i class="fas fa-clipboard-check text-green-400 text-6xl mb-4"></i>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-700 mb-3">Section Récolement</h3>
                                <p class="text-gray-500 mb-6">Cette section sera disponible prochainement</p>
                                <div class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-600 rounded-lg text-sm">
                                    <i class="fas fa-info-circle"></i>
                                    <span>Fonctionnalité en cours de développement</span>
                                </div>
                            </div>
                            @if(false)
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
                            @endif
                        </div>
                    </div>
                @elseif($activeStep === 'main_levee')
                    <!-- Main Levée Section -->
                    <div class="bg-white/70 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                        <div class="bg-green-500 px-6 py-4">
                            <h2 class="text-xl font-bold text-white flex items-center gap-3">
                                <i class="fas fa-check-circle"></i>
                                Main Levée
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="text-center py-12">
                                <div class="mb-6">
                                    <i class="fas fa-check-circle text-green-400 text-6xl mb-4"></i>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-700 mb-3">Section Main Levée</h3>
                                <p class="text-gray-500 mb-6">Cette section sera disponible prochainement</p>
                                <div class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-600 rounded-lg text-sm">
                                    <i class="fas fa-info-circle"></i>
                                    <span>Fonctionnalité en cours de développement</span>
                                </div>
                            </div>
                            @if(false)
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
                            @endif
                        </div>
                    </div>
                @elseif($currentStep === 'main_levee')
                    <!-- Main Levée - Toutes les étapes sont terminées -->
                    <div class="bg-white/70 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                        <div class="bg-green-600 px-6 py-4">
                            <h2 class="text-xl font-bold text-white flex items-center gap-3">
                                <i class="fas fa-check-circle"></i>
                                Main Levée - Processus Terminé
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="text-center py-12">
                                <div class="mb-6">
                                    <i class="fas fa-check-double text-green-400 text-6xl mb-4"></i>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-800 mb-3">🎉 Toutes les étapes sont terminées</h3>
                                <p class="text-gray-500 mb-6">Le processus de l'article est complété avec succès</p>
                                <div class="inline-flex items-center gap-2 px-6 py-3 bg-green-100 text-green-600 rounded-lg text-sm font-medium">
                                    <i class="fas fa-trophy"></i>
                                    <span>Article finalisé</span>
                                </div>
                            </div>
                            @if(false)
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
                            @endif
                        </div>
                    </div>
                @else
                    <!-- All Steps Completed -->
                    <div class="bg-white/70 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                        <div class="px-6 py-4" style="background: linear-gradient(135deg, #059669, #047857);">
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
                    <div class="px-4 py-3" style="background: linear-gradient(135deg, #059669, #047857);">
                        <h2 class="text-lg font-bold text-white flex items-center gap-2">
                            <i class="fas fa-info-circle"></i>
                            Documents
                        </h2>
                    </div>
                    <div class="p-4 space-y-4">
                        <!-- Documents Section -->
                        <div class="space-y-2">
                            @php
                                $hasContractVente = isset($contractVente) && $contractVente;
                                $hasPaidTranches = false;
                                if ($hasContractVente && $contractVente->chargeApayer) {
                                    foreach ($contractVente->chargeApayer as $charge) {
                                        if (str_starts_with($charge->nom, 'Tranche')) {
                                            $payment = $charge->payments->first();
                                            if ($payment && $payment->is_paye && $payment->date_payment) {
                                                $hasPaidTranches = true;
                                                break;
                                            }
                                        }
                                    }
                                }
                            @endphp
                            
                            <!-- Lettre adjudicataire - Enabled when contract vente exists -->
                            @if($hasContractVente)
                                <a href="{{ route('articles.lettre-adjudicataire', $article) }}" 
                                    class="w-full inline-flex items-center justify-between px-4 py-3 bg-green-50 hover:bg-green-100 border border-green-200 text-green-700 rounded-lg transition-all duration-200 group">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-file-alt text-green-500"></i>
                                        <span class="text-sm font-medium">Lettre adjudicataire</span>
                                    </div>
                                    <i class="fas fa-download text-green-500 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                </a>
                            @else
                                <button type="button" 
                                        disabled
                                        class="w-full inline-flex items-center justify-between px-4 py-3 bg-gray-100 border border-gray-300 text-gray-400 rounded-lg cursor-not-allowed opacity-60">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-file-alt text-gray-400"></i>
                                        <span class="text-sm font-medium">Lettre adjudicataire</span>
                                    </div>
                                    <i class="fas fa-download text-gray-400"></i>
                                </button>
                            @endif

                            <!-- Permis d'exploiter Section -->
                            @php
                                $permisExploiter = $contractVente?->permisExploiter;
                            @endphp
                            
                            <div class="w-full">
                                @if($hasPaidTranches && !$permisExploiter)
                                    <!-- Button to create Permis d'exploiter -->
                                    <a href="{{ route('articles.permis-exploiter', $article) }}"
                                       class="w-full inline-flex items-center justify-between px-4 py-3 bg-green-50 hover:bg-green-100 border border-green-200 text-green-700 rounded-lg transition-all duration-200 group">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-file-contract text-green-500"></i>
                                            <span class="text-sm font-medium">Permis d'exploiter</span>
                                        </div>
                                        <i class="fas fa-arrow-right text-green-500 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                    </a>
                                @elseif($permisExploiter)
                                    <!-- Permis d'exploiter already created - Link to view page -->
                                    <a href="{{ route('articles.permis-exploiter', $article) }}" 
                                       class="w-full inline-flex items-center justify-between px-4 py-3 bg-green-50 hover:bg-green-100 border border-green-200 text-green-700 rounded-lg transition-all duration-200 group">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-file-contract text-green-500"></i>
                                            <span class="text-sm font-medium">Permis d'exploiter</span>
                                        </div>
                                        <i class="fas fa-check-circle text-green-500"></i>
                                    </a>
                                @else
                                    <!-- Disabled state -->
                                    <button type="button" 
                                            disabled
                                            class="w-full inline-flex items-center justify-between px-4 py-3 bg-blue-100 border border-blue-300 text-blue-400 rounded-lg cursor-not-allowed opacity-60">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-file-contract text-blue-400"></i>
                                            <span class="text-sm font-medium">Permis d'exploiter</span>
                                        </div>
                                        <i class="fas fa-arrow-right text-blue-400"></i>
                                    </button>
                                @endif
                            </div>
                            
                            <!-- PV d'Installation - Enabled when tranches are paid -->
                            @if($hasPaidTranches)
                                <a href="{{ route('articles.pv-installation', $article) }}" 
                                    class="w-full inline-flex items-center justify-between px-4 py-3 bg-green-50 hover:bg-green-100 border border-green-200 text-green-700 rounded-lg transition-all duration-200 group">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-clipboard-check text-green-500"></i>
                                        <span class="text-sm font-medium">PV d'Installation</span>
                                    </div>
                                    <i class="fas fa-arrow-right text-green-500 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                </a>
                            @else
                                <button type="button" 
                                        disabled
                                        class="w-full inline-flex items-center justify-between px-4 py-3 bg-blue-100 border border-blue-300 text-blue-400 rounded-lg cursor-not-allowed opacity-60">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-clipboard-check text-blue-400"></i>
                                        <span class="text-sm font-medium">PV d'Installation</span>
                                    </div>
                                    <i class="fas fa-arrow-right text-blue-400"></i>
                                </button>
                            @endif

                            <!-- Permis d'enlever - Enabled when tranches are paid -->
                            @if($hasPaidTranches)
                                <a href="{{ route('articles.permis-enlever', $article) }}" 
                                    class="w-full inline-flex items-center justify-between px-4 py-3 bg-green-50 hover:bg-green-100 border border-green-200 text-green-700 rounded-lg transition-all duration-200 group">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-file-alt text-green-500"></i>
                                        <span class="text-sm font-medium">Permis d'enlever</span>
                                    </div>
                                    <i class="fas fa-arrow-right text-green-500 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                </a>
                            @else
                                <button type="button" 
                                        disabled
                                        class="w-full inline-flex items-center justify-between px-4 py-3 bg-gray-100 border border-gray-300 text-gray-400 rounded-lg cursor-not-allowed opacity-60">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-file-alt text-gray-400"></i>
                                        <span class="text-sm font-medium">Permis d'enlever</span>
                                    </div>
                                    <i class="fas fa-arrow-right text-gray-400"></i>
                                </button>
                            @endif            
                            
                            <!-- Permis de colportage - Enabled when tranches are paid -->
                            @if($hasPaidTranches)
                                <a href="{{ route('articles.permis-colportage', $article) }}" 
                                    class="w-full inline-flex items-center justify-between px-4 py-3 bg-green-50 hover:bg-green-100 border border-green-200 text-green-700 rounded-lg transition-all duration-200 group">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-truck text-green-500"></i>
                                        <span class="text-sm font-medium">Permis de colportage</span>
                                    </div>
                                    <i class="fas fa-arrow-right text-green-500 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                </a>
                            @else
                                <button type="button" 
                                        disabled
                                        class="w-full inline-flex items-center justify-between px-4 py-3 bg-blue-100 border border-blue-300 text-blue-400 rounded-lg cursor-not-allowed opacity-60">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-truck text-blue-400"></i>
                                        <span class="text-sm font-medium">Permis de colportage</span>
                                    </div>
                                    <i class="fas fa-arrow-right text-blue-400"></i>
                                </button>
                            @endif
                        </div>
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
