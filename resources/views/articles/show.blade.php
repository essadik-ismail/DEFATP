@extends('layouts.app')

@section('title', 'Détails de l\'Article - DEFATP')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('cessions.index') }}">Cessions</a></li>
<li class="breadcrumb-item active">Détail #{{ $article->numero ?? $article->id }}</li>
@endsection

@section('content')
@php
    // Calculate document availability conditions
    $hasPaidTranches = false;
    $permisExploiter = $contractVente?->permisExploiter;
    if ($contractVente && $contractVente->chargeApayer) {
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
    
    // Determine current step for document visibility
    $currentStep = $article->current_step ?? 'cahier_affiche';
    $steps = ['cahier_affiche', 'contrat_vente', 'paiement_charges', 'paiement_tranches', 'recollement', 'main_levee'];
    $currentStepIndex = array_search($currentStep, $steps);
    
    // Documents available based on step:
    // - Lettre adjudicataire: from contrat_vente step onwards (if contract exists)
    // - Permis d'exploiter & PV d'Installation: from paiement_charges step onwards (if hasPaidTranches)
    // - Permis d'enlever & Permis de colportage: from recollement step onwards (if hasPaidTranches)
    $showLettreAdjudicataire = $contractVente && $currentStepIndex >= 1; // contrat_vente is index 1
    $showPermisExploiter = $hasPaidTranches && $currentStepIndex >= 2; // paiement_charges is index 2
    $showPVInstallation = $hasPaidTranches && $currentStepIndex >= 2; // paiement_charges is index 2
    $showPermisEnlever = $hasPaidTranches && $currentStepIndex >= 3; // paiement_tranches is index 3
    $showPermisColportage = $hasPaidTranches && $currentStepIndex >= 3; // paiement_tranches is index 3
@endphp
<div class="min-w-0 max-w-full">
    <!-- Header Section -->
    <x-page-header
        title="Détails de l'Article #{{ $article->numero ?? $article->id }}"
        subtitle="Créé le {{ $article->created_at ? $article->created_at->format('d/m/Y à H:i') : 'N/A' }}"
        icon="fas fa-file-alt"
    >
        <x-slot name="actions">
            @php
                $hasAnyDocument = $showLettreAdjudicataire || $showPermisExploiter || $showPVInstallation || $showPermisEnlever || $showPermisColportage;
            @endphp
            @if($hasAnyDocument)
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" 
                        class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white border border-green-700 rounded-lg font-medium hover:bg-green-700 transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                    <i class="fas fa-file-alt"></i>
                    <span>Documents</span>
                    <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                </button>
                
                <div x-show="open" 
                     @click.away="open = false"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50"
                     style="display: none;">
                    @if($showLettreAdjudicataire)
                    <a href="{{ route('articles.lettre-adjudicataire', $article) }}" 
                       class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-green-50 transition-colors">
                        <i class="fas fa-file-alt text-green-600 w-5"></i>
                        <span>Lettre adjudicataire</span>
                    </a>
                    @endif

                    @if($showPermisExploiter)
                    <a href="{{ route('articles.permis-exploiter', $article) }}" 
                       class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-green-50 transition-colors">
                        <i class="fas fa-file-contract text-green-600 w-5"></i>
                        <span>Permis d'exploiter</span>
                        @if($permisExploiter)
                            <i class="fas fa-check-circle text-green-600 text-xs ml-auto"></i>
                        @endif
                    </a>
                    @endif

                    @if($showPVInstallation)
                    <a href="{{ route('articles.pv-installation', $article) }}" 
                       class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-green-50 transition-colors">
                        <i class="fas fa-clipboard-check text-green-600 w-5"></i>
                        <span>PV d'Installation</span>
                    </a>
                    @endif

                    @if($showPermisEnlever)
                    <a href="{{ route('articles.permis-enlever', $article) }}" 
                       class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-green-50 transition-colors">
                        <i class="fas fa-file-alt text-green-600 w-5"></i>
                        <span>Permis d'enlever</span>
                    </a>
                    @endif

                    @if($showPermisColportage)
                    <a href="{{ route('articles.permis-colportage', $article) }}" 
                       class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-green-50 transition-colors">
                        <i class="fas fa-truck text-green-600 w-5"></i>
                        <span>Permis de colportage</span>
                    </a>
                    @endif
                </div>
            </div>
            @endif
        </x-slot>
    </x-page-header>

    @if(session('success'))
        <x-alert type="success" title="Succès !" dismissible class="mb-4">{{ session('success') }}</x-alert>
    @endif
    @if(session('error'))
        <x-alert type="error" title="Erreur !" dismissible class="mb-4">{{ session('error') }}</x-alert>
    @endif

    <!-- Description du lot (Limites et Coordonnées) -->
    @if($article->limite_nord || $article->limite_sud || $article->limite_est || $article->limite_ouest || $article->coordonnee_x !== null || $article->coordonnee_y !== null)
    <div x-data="{ open: true }" class="mb-6 rounded-2xl border bg-white overflow-hidden" style="border-color:rgba(154,179,163,0.35);box-shadow:var(--shadow-card);">
        <button
            type="button"
            class="w-full flex items-center justify-between gap-3 px-6 py-4"
            @click="open = !open"
        >
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:linear-gradient(135deg,#059669,#047857);box-shadow:0 2px 6px rgba(5,150,105,0.25);">
                    <i class="fas fa-clipboard-list text-white text-sm"></i>
                </div>
                <h3 class="text-base font-semibold text-gray-900">Description du lot</h3>
            </div>
            <i
                class="fas fa-chevron-down text-gray-500 text-sm transition-transform duration-200"
                :class="{ 'rotate-180': open }"
            ></i>
        </button>
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 -translate-y-1"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-1"
            class="px-6 pb-6 border-t border-gray-100"
        >
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="text-base font-semibold text-gray-800 mb-3">Limites du lot</h4>
                    <dl class="space-y-2 text-sm">
                        @if($article->limite_nord)
                            <div class="flex gap-2"><dt class="font-semibold text-gray-700 w-24">Limite Nord</dt><dd class="text-gray-900">{{ $article->limite_nord }}</dd></div>
                        @endif
                        @if($article->limite_sud)
                            <div class="flex gap-2"><dt class="font-semibold text-gray-700 w-24">Limite Sud</dt><dd class="text-gray-900">{{ $article->limite_sud }}</dd></div>
                        @endif
                        @if($article->limite_est)
                            <div class="flex gap-2"><dt class="font-semibold text-gray-700 w-24">Limite Est</dt><dd class="text-gray-900">{{ $article->limite_est }}</dd></div>
                        @endif
                        @if($article->limite_ouest)
                            <div class="flex gap-2"><dt class="font-semibold text-gray-700 w-24">Limite Ouest</dt><dd class="text-gray-900">{{ $article->limite_ouest }}</dd></div>
                        @endif
                        @if(!$article->limite_nord && !$article->limite_sud && !$article->limite_est && !$article->limite_ouest)
                            <p class="text-gray-500">—</p>
                        @endif
                    </dl>
                </div>
                <div>
                    <h4 class="text-base font-semibold text-gray-800 mb-3">Coordonnées du centre</h4>
                    <dl class="space-y-2 text-sm">
                        @if($article->coordonnee_x !== null && $article->coordonnee_x !== '')
                            <div class="flex gap-2"><dt class="font-semibold text-gray-700 w-32">Coordonnée X</dt><dd class="text-gray-900">{{ $article->coordonnee_x }}</dd></div>
                        @endif
                        @if($article->coordonnee_y !== null && $article->coordonnee_y !== '')
                            <div class="flex gap-2"><dt class="font-semibold text-gray-700 w-32">Coordonnée Y</dt><dd class="text-gray-900">{{ $article->coordonnee_y }}</dd></div>
                        @endif
                        @if(($article->coordonnee_x === null || $article->coordonnee_x === '') && ($article->coordonnee_y === null || $article->coordonnee_y === ''))
                            <p class="text-gray-500">—</p>
                        @endif
                    </dl>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Article Steps Progress -->
    <div class="mb-6">
        <div class="rounded-2xl border bg-white p-6" style="border-color:rgba(154,179,163,0.35);box-shadow:var(--shadow-card);">
                <div class="flex items-center gap-2.5 mb-6 pb-4 border-b" style="border-color:rgba(154,179,163,0.2);">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:linear-gradient(135deg,#059669,#047857);box-shadow:0 2px 6px rgba(5,150,105,0.3);">
                        <i class="fas fa-tasks text-white text-sm"></i>
                    </div>
                    <h2 class="text-base font-bold" style="color:#1F2D24;">Statut de l'Article</h2>
                </div>
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
                    <div class="absolute top-5 left-0 right-0 h-1 rounded-full" style="background: rgba(154,179,163,0.3);"></div>
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
                                    $textClass = '';
                                    $ringClass = '';
                                } elseif ($isActive) {
                                    $circleClass = 'text-white border-0 ring-4 ring-green-100';
                                    $circleStyle = 'background: linear-gradient(135deg, #059669, #047857);';
                                    $textClass = '';
                                    $ringClass = 'scale-110';
                                } else {
                                    $circleClass = 'bg-white border-2';
                                    $circleStyle = 'color: #9AB3A3; border-color: rgba(154,179,163,0.5) !important;';
                                    $textClass = '';
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
                                    <div class="text-xs font-semibold {{ $textClass }}" style="{{ $isActive ? 'color: #059669;' : ($isCompleted ? 'color: #1F2D24;' : 'color: #6B7C72;') }}">
                                        {{ $step['label'] }}
                                    </div>
                                    @if($isActive)
                                        <div class="mt-1 text-xs" style="color: #6B7C72;">En cours</div>
                                    @elseif($isCompleted)
                                        <div class="mt-1 text-xs" style="color: #059669;">✓ Terminé</div>
                                    @else
                                        <div class="mt-1 text-xs" style="color: #6B7C72;">En attente</div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content: Full Width Layout -->
        <div class="space-y-6">
            <!-- Dynamic Form Section -->
            <div class="space-y-6">
                @php
                    $currentStep = $article->current_step ?? 'cahier_affiche';
                    $currentStepIndex = array_search($currentStep, array_keys($steps));
                    $nextStepIndex = $currentStepIndex !== false ? ($currentStepIndex + 1) : 0;
                    $nextStepKey = $nextStepIndex < count($steps) ? array_keys($steps)[$nextStepIndex] : null;
                    // Active step is the next step (the one that should be worked on)
                    $activeStep = $nextStepKey ?? $currentStep;
                @endphp

                @if($activeStep === 'cahier_affiche' || $activeStep === 'contrat_vente')
                    <!-- Contract Vente Section (styled like Create Article) -->
                    <div class="rounded-2xl border bg-white overflow-hidden mb-5" style="border-color:rgba(154,179,163,0.35);box-shadow:var(--shadow-card);">
                        <div class="section-header flex flex-wrap items-center justify-between gap-3 px-6 py-4 border-b border-gray-200" style="background: rgba(242, 246, 243, 0.6);">
                            <h2 class="text-lg font-semibold flex items-center gap-3" style="color: #1F2D24;">
                                <i class="fas fa-file-contract" style="color: #6B7C72;"></i>
                                Contrat de vente
                            </h2>
                            @if($showLettreAdjudicataire)
                                <div class="flex items-center gap-2 text-sm">
                                    <span class="text-gray-500">Document :</span>
                                    <a href="{{ route('articles.lettre-adjudicataire', $article) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg font-medium transition-colors text-white hover:opacity-90" style="background: linear-gradient(135deg, #059669, #047857);">
                                        <i class="fas fa-file-alt text-xs"></i>
                                        Lettre adjudicataire
                                    </a>
                                </div>
                            @endif
                        </div>
                        <div class="p-6">
                            @if($contractVente)
                                <div class="mb-6 p-4 rounded-lg border border-gray-200 bg-gray-50">
                                    <p class="text-sm mb-4 text-gray-700">
                                        <i class="fas fa-check-circle text-green-600 mr-2"></i>
                                        Un contrat de vente existe déjà pour cet article.
                                    </p>
                                    <div class="flex gap-3">
                                        <button type="button" onclick="toggleEditForm()" id="editContractBtn" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                            <i class="fas fa-edit"></i>
                                            <span>Modifier le Contrat</span>
                                        </button>
                                        <button type="button" onclick="toggleEditForm()" id="cancelEditBtn" class="hidden inline-flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                                            <i class="fas fa-times"></i>
                                            <span>Annuler</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Inline Edit Form (Hidden by default) -->
                                <div id="editContractForm" class="hidden">
                                    <form action="{{ route('contract-ventes.update', [$article, $contractVente]) }}" method="POST" id="contractVenteEditForm" class="space-y-8">
                                        @csrf
                                        @method('PUT')
                                        <x-validation-errors />

                                        <!-- 1. Informations de l'adjudication -->
                                        <div class="rounded-xl p-5 border mb-5" style="background:#f8faf9;border-color:rgba(154,179,163,0.25);">
                                            <div class="flex items-center gap-3 mb-4">
                                                <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-green-600">
                                                    <i class="fas fa-info-circle text-white text-sm"></i>
                                                </div>
                                                <h3 class="text-lg font-semibold text-gray-900">1. Informations de l'adjudication</h3>
                                            </div>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                <div class="form-group">
                                                    <label for="edit_duree_decheache" class="block text-sm font-semibold text-gray-700 mb-2">Durée de contract</label>
                                                    <input type="number" id="edit_duree_decheache" name="duree_decheache" value="{{ $contractVente->duree_decheache ?? '' }}" placeholder="Ex: 12 mois, 1 an" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- 2. Informations sur l'exploitant -->
                                        <div class="rounded-xl p-5 border mb-5" style="background:#f8faf9;border-color:rgba(154,179,163,0.25);">
                                            <div class="flex items-center gap-3 mb-4">
                                                <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-green-600">
                                                    <i class="fas fa-user-tie text-white text-sm"></i>
                                                </div>
                                                <h3 class="text-lg font-semibold text-gray-900">2. Informations sur l'exploitant</h3>
                                            </div>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                <div class="form-group">
                                                    <label for="edit_exploitant_id" class="block text-sm font-semibold text-gray-700 mb-2">Exploitant <span class="text-red-500">*</span></label>
                                                    <select id="edit_exploitant_id" name="exploitant_id" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
                                                        <option value="">Sélectionner un exploitant</option>
                                                        @foreach($exploitants as $exploitant)
                                                            <option value="{{ $exploitant->id }}" data-cin="{{ $exploitant->n_cin ?? '' }}" data-numero="{{ $exploitant->numero ?? '' }}" data-adresse="{{ $exploitant->adresse ?? '' }}" {{ $contractVente->exploitant_id == $exploitant->id ? 'selected' : '' }}>{{ $exploitant->nom_complet }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('exploitant_id')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="edit_exploitant_cin" class="block text-sm font-semibold text-gray-700 mb-2">CIN</label>
                                                    <input type="text" id="edit_exploitant_cin" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label for="edit_exploitant_numero" class="block text-sm font-semibold text-gray-700 mb-2">Numéro de l'exploitant</label>
                                                    <input type="text" id="edit_exploitant_numero" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label for="edit_exploitant_adresse" class="block text-sm font-semibold text-gray-700 mb-2">Adresse</label>
                                                    <input type="text" id="edit_exploitant_adresse" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- 3. Détails de la vente -->
                                        <div class="rounded-xl p-5 border mb-5" style="background:#f8faf9;border-color:rgba(154,179,163,0.25);">
                                            <div class="flex items-center gap-3 mb-4">
                                                <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-green-600">
                                                    <i class="fas fa-money-bill-wave text-white text-sm"></i>
                                                </div>
                                                <h3 class="text-lg font-semibold text-gray-900">3. Détails de la vente</h3>
                                            </div>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                <div class="form-group">
                                                    <label for="edit_prix_vente" class="block text-sm font-semibold text-gray-700 mb-2">Prix de Vente (MAD) <span class="text-red-500">*</span></label>
                                                    <input type="number" id="edit_prix_vente" name="prix_vente" value="{{ $contractVente->prix_vente }}" placeholder="0.00" step="0.01" min="0" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" required onchange="calculateEditCharges(); updateEditTranchesInputs();">
                                                    @error('prix_vente')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                                                </div>

                                                <div class="form-group">
                                                    <label for="edit_nombre_tranche" class="block text-sm font-semibold text-gray-700 mb-2">Nombre de Tranches <span class="text-red-500">*</span></label>
                                                    <select id="edit_nombre_tranche" name="nombre_tranche" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" required onchange="updateEditTranchesInputs()">
                                                        <option value="1" {{ $contractVente->nombre_tranche == 1 ? 'selected' : '' }}>1</option>
                                                        <option value="2" {{ $contractVente->nombre_tranche == 2 ? 'selected' : '' }}>2</option>
                                                        <option value="4" {{ $contractVente->nombre_tranche == 4 ? 'selected' : '' }}>4</option>
                                                    </select>
                                                    @error('nombre_tranche')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                                                </div>
                                            </div>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                                                <div class="form-group">
                                                    <label for="edit_date_limite_tranche" class="block text-sm font-semibold text-gray-700 mb-2">Date Limite Tranche</label>
                                                    <input type="date" id="edit_date_limite_tranche" name="date_limite_tranche" value="{{ $contractVente->date_limite_tranche ? \Carbon\Carbon::parse($contractVente->date_limite_tranche)->format('Y-m-d') : '' }}" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                                    @error('date_limite_tranche')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="edit_date_limite_taxes" class="block text-sm font-semibold text-gray-700 mb-2">Date Limite Taxes</label>
                                                    <input type="date" id="edit_date_limite_taxes" name="date_limite_taxes" value="{{ $contractVente->date_limite_taxes ? \Carbon\Carbon::parse($contractVente->date_limite_taxes)->format('Y-m-d') : '' }}" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                                    @error('date_limite_taxes')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                                                </div>
                                            </div>
                                        </div>

                                        @php
                                            $editCharges = $contractVente->chargeApayer->filter(function($charge) { return !str_starts_with($charge->nom, 'Tranche'); })->values();
                                            $editTranches = $contractVente->chargeApayer->filter(function($charge) { return str_starts_with($charge->nom, 'Tranche'); })->sortBy(function($charge) { preg_match('/Tranche (\d+)/', $charge->nom, $m); return isset($m[1]) ? (int)$m[1] : 0; })->values();
                                        @endphp

                                        <!-- 4. Charges -->
                                        <div class="rounded-xl p-5 border mb-5" style="background:#f8faf9;border-color:rgba(154,179,163,0.25);">
                                            <div class="flex items-center gap-3 mb-4">
                                                <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-green-600">
                                                    <i class="fas fa-calculator text-white text-sm"></i>
                                                </div>
                                                <h3 class="text-lg font-semibold text-gray-900">4. Charges</h3>
                                            </div>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                @foreach($editCharges as $index => $charge)
                                                <div class="form-group">
                                                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ $charge->nom }}</label>
                                                    <input type="number" name="charges[{{ $index }}][montant]" step="0.01" value="{{ $charge->montant }}" readonly class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100">
                                                    <input type="hidden" name="charges[{{ $index }}][nom]" value="{{ $charge->nom }}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="edit_charge_date_{{ $index }}" class="block text-sm font-semibold text-gray-700 mb-2">Date d'échéance <span class="text-red-500">*</span></label>
                                                    <input type="date" id="edit_charge_date_{{ $index }}" name="charges[{{ $index }}][date_echeance]" value="{{ $charge->date_echeance ? \Carbon\Carbon::parse($charge->date_echeance)->format('Y-m-d') : '' }}" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <!-- 5. Tranches -->
                                        <div class="rounded-xl p-5 border mb-5" style="background:#f8faf9;border-color:rgba(154,179,163,0.25);">
                                            <div class="flex items-center gap-3 mb-4">
                                                <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-green-600">
                                                    <i class="fas fa-list-ol text-white text-sm"></i>
                                                </div>
                                                <h3 class="text-lg font-semibold text-gray-900">5. Tranches</h3>
                                            </div>
                                            <div id="editTranchesContainer" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                @foreach($editTranches as $index => $tranche)
                                                <div class="form-group">
                                                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ $tranche->nom }} <span class="text-xs font-normal text-gray-500">(Prix de vente ÷ {{ $contractVente->nombre_tranche }})</span></label>
                                                    <input type="number" name="tranches[{{ $index }}][montant]" step="0.01" value="{{ $contractVente->nombre_tranche > 0 ? number_format(round($contractVente->prix_vente / $contractVente->nombre_tranche, 2), 2, '.', '') : $tranche->montant }}" readonly class="edit-tranche-montant form-input w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="edit_tranche_date_{{ $index }}" class="block text-sm font-semibold text-gray-700 mb-2">Date d'échéance <span class="text-red-500">*</span></label>
                                                    <input type="date" id="edit_tranche_date_{{ $index }}" name="tranches[{{ $index }}][date_echeance]" value="{{ $tranche->date_echeance ? \Carbon\Carbon::parse($tranche->date_echeance)->format('Y-m-d') : '' }}" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <div class="flex justify-end gap-3 pt-2">
                                            <button type="button" onclick="toggleEditForm()" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                                                Annuler
                                            </button>
                                            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                                <i class="fas fa-save"></i>
                                                Enregistrer les modifications
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Display existing contract details -->
                                <div id="contractDetails" class="space-y-8">
                                    <!-- 1. Informations de l'adjudication -->
                                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                                        <div class="flex items-center gap-3 mb-4">
                                            <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-green-600">
                                                <i class="fas fa-info-circle text-white text-sm"></i>
                                            </div>
                                            <h3 class="text-lg font-semibold text-gray-900">1. Informations de l'adjudication</h3>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Durée d'échéance</label>
                                                <p class="text-gray-900 font-semibold">{{ $contractVente->duree_decheache ?? 'N/A' }}</p>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Date de déchéance</label>
                                                <p class="text-gray-900 font-semibold">{{ $contractVente->date_de_decheance ? \Carbon\Carbon::parse($contractVente->date_de_decheance)->format('d/m/Y') : 'N/A' }}</p>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">ID de déchéance</label>
                                                <p class="text-gray-900 font-semibold">{{ $contractVente->id_decheance ?? 'N/A' }}</p>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">État actuel</label>
                                                <p class="text-gray-900 font-semibold">{{ $contractVente->Current_state ?? 'N/A' }}</p>
                                            </div>
                                            @if($contractVente->is_resiliation)
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Résiliation</label>
                                                <p class="text-gray-900 font-semibold"><span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800"><i class="fas fa-times-circle mr-1"></i>Résilié</span></p>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Date de résiliation</label>
                                                <p class="text-gray-900 font-semibold">{{ $contractVente->date_de_resiliation ? \Carbon\Carbon::parse($contractVente->date_de_resiliation)->format('d/m/Y') : 'N/A' }}</p>
                                            </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- 2. Informations sur l'exploitant -->
                                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                                        <div class="flex items-center gap-3 mb-4">
                                            <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-green-600">
                                                <i class="fas fa-user-tie text-white text-sm"></i>
                                            </div>
                                            <h3 class="text-lg font-semibold text-gray-900">2. Informations sur l'exploitant</h3>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        @if($contractVente && $contractVente->exploitant_id && $contractVente->exploitant)
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Nom complet</label>
                                                <p class="text-gray-900 font-semibold">{{ $contractVente->exploitant->nom_complet ?? 'N/A' }}</p>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Raison sociale</label>
                                                <p class="text-gray-900 font-semibold">{{ $contractVente->exploitant->raison_sociale ?? 'N/A' }}</p>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">CIN</label>
                                                <p class="text-gray-900 font-semibold">{{ $contractVente->exploitant->n_cin ?? 'N/A' }}</p>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Numéro de carte professionnelle</label>
                                                <p class="text-gray-900 font-semibold">{{ $contractVente->exploitant->numero ?? 'N/A' }}</p>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
                                                <p class="text-gray-900 font-semibold">{{ $contractVente->exploitant->adresse ?? 'N/A' }}</p>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Catégorie</label>
                                                <p class="text-gray-900 font-semibold">{{ $contractVente->exploitant->categorie ?? 'N/A' }}</p>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Activité</label>
                                                <p class="text-gray-900 font-semibold">{{ $contractVente->exploitant->activite ?? 'N/A' }}</p>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Qualification RC</label>
                                                <p class="text-gray-900 font-semibold">{{ $contractVente->exploitant->qualification_rc ?? 'N/A' }}</p>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Date d'obtention</label>
                                                <p class="text-gray-900 font-semibold">@if($contractVente->exploitant->date_obtention){{ $contractVente->exploitant->date_obtention->format('d/m/Y') }}@else N/A @endif</p>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Durée de validité</label>
                                                <p class="text-gray-900 font-semibold">{{ $contractVente->exploitant->duree_validite ?? 'N/A' }}</p>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">État de validité</label>
                                                <p class="text-gray-900 font-semibold">{{ $contractVente->exploitant->etat_validite ?? 'N/A' }}</p>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Situation administrative</label>
                                                <p class="text-gray-900 font-semibold">{{ $contractVente->exploitant->situation_administrative ?? 'N/A' }}</p>
                                            </div>
                                            @if($contractVente->exploitant->dranef)
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Localisation (DRANEF)</label>
                                                <p class="text-gray-900 font-semibold">{{ $contractVente->exploitant->dranef->code ?? '' }} - {{ $contractVente->exploitant->dranef->dranef ?? 'N/A' }}</p>
                                            </div>
                                            @endif
                                        @else
                                            <div class="md:col-span-2">
                                                <div class="p-4 bg-amber-50 border border-amber-200 rounded-lg">
                                                    <p class="text-amber-800 text-sm">
                                                        <i class="fas fa-exclamation-triangle mr-2"></i>
                                                        @if($contractVente && $contractVente->exploitant_id)
                                                            L'exploitant sélectionné n'a pas pu être chargé. Veuillez vérifier que l'exploitant existe toujours.
                                                        @else
                                                            Aucun exploitant n'a été sélectionné pour ce contrat de vente.
                                                            <button type="button" onclick="toggleEditForm()" class="text-green-600 hover:text-green-800 underline ml-1 bg-transparent border-none cursor-pointer">Cliquez ici pour modifier le contrat et sélectionner un exploitant.</button>
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        @endif
                                        </div>
                                    </div>

                                    <!-- 3. Détails de la vente -->
                                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                                        <div class="flex items-center gap-3 mb-4">
                                            <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-green-600">
                                                <i class="fas fa-money-bill-wave text-white text-sm"></i>
                                            </div>
                                            <h3 class="text-lg font-semibold text-gray-900">3. Détails de la vente</h3>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Prix de Vente</label>
                                                <p class="text-gray-900 font-semibold">{{ number_format($contractVente->prix_vente, 2, ',', ' ') }} MAD</p>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Prix de Retrait</label>
                                                <p class="text-gray-900 font-semibold">{{ number_format($contractVente->prix_de_retrait ?? 0, 2, ',', ' ') }} MAD</p>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre de Tranches</label>
                                                <p class="text-gray-900 font-semibold">{{ $contractVente->nombre_tranche ?? 1 }}</p>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Date Limite Tranche</label>
                                                <p class="text-gray-900 font-semibold">{{ $contractVente->date_limite_tranche ? \Carbon\Carbon::parse($contractVente->date_limite_tranche)->format('d/m/Y') : 'N/A' }}</p>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Date Limite Taxes</label>
                                                <p class="text-gray-900 font-semibold">{{ $contractVente->date_limite_taxes ? \Carbon\Carbon::parse($contractVente->date_limite_taxes)->format('d/m/Y') : 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    @php
                                        $charges = $contractVente->chargeApayer->filter(fn($c) => !str_starts_with($c->nom, 'Tranche'));
                                        $tranches = $contractVente->chargeApayer->filter(fn($c) => str_starts_with($c->nom, 'Tranche'));
                                    @endphp

                                    @if($charges->count() > 0)
                                    <!-- 4. Charges -->
                                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                                        <div class="flex items-center gap-3 mb-4">
                                            <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-green-600">
                                                <i class="fas fa-calculator text-white text-sm"></i>
                                            </div>
                                            <h3 class="text-lg font-semibold text-gray-900">4. Charges</h3>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            @foreach($charges as $charge)
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ $charge->nom }}</label>
                                                <p class="text-gray-900 font-semibold">{{ number_format($charge->montant, 2, ',', ' ') }} MAD</p>
                                                @if($charge->date_echeance)<p class="text-xs text-gray-600 mt-1">Échéance: {{ \Carbon\Carbon::parse($charge->date_echeance)->format('d/m/Y') }}</p>@endif
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif

                                    @if($tranches->count() > 0)
                                    <!-- 5. Détail des tranches -->
                                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                                        <div class="flex items-center gap-3 mb-4">
                                            <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-green-600">
                                                <i class="fas fa-list-ol text-white text-sm"></i>
                                            </div>
                                            <h3 class="text-lg font-semibold text-gray-900">5. Détail des tranches</h3>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            @foreach($tranches as $tranche)
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ $tranche->nom }}</label>
                                                <p class="text-gray-900 font-semibold">{{ number_format($tranche->montant, 2, ',', ' ') }} MAD</p>
                                                @if($tranche->date_echeance)<p class="text-xs text-gray-600 mt-1">Échéance: {{ \Carbon\Carbon::parse($tranche->date_echeance)->format('d/m/Y') }}</p>@endif
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            @else
                                <!-- Inline Contract de Vente Creation Form -->
                                <form action="{{ route('contract-ventes.store', $article) }}" method="POST" id="contractVenteForm" class="space-y-8">
                                    @csrf

                                    <x-validation-errors />

                                    <!-- 1. Informations de l'adjudication -->
                                    <div class="rounded-xl p-5 border mb-5" style="background:#f8faf9;border-color:rgba(154,179,163,0.25);">
                                        <div class="flex items-center gap-3 mb-4">
                                            <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-green-600">
                                                <i class="fas fa-info-circle text-white text-sm"></i>
                                            </div>
                                            <h3 class="text-lg font-semibold text-gray-900">1. Informations de l'adjudication</h3>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div class="form-group">
                                                <label for="duree_decheache" class="block text-sm font-semibold text-gray-700 mb-2">Durée d'échéance</label>
                                                <input type="number" id="duree_decheache" name="duree_decheache" value="{{ old('duree_decheache') }}" placeholder="Ex: 12 mois" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                                @error('duree_decheache')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 2. Informations sur l'exploitant -->
                                    <div class="rounded-xl p-5 border mb-5" style="background:#f8faf9;border-color:rgba(154,179,163,0.25);">
                                        <div class="flex items-center gap-3 mb-4">
                                            <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-green-600">
                                                <i class="fas fa-user-tie text-white text-sm"></i>
                                            </div>
                                            <h3 class="text-lg font-semibold text-gray-900">2. Informations sur l'exploitant</h3>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div class="form-group">
                                                <label for="exploitant_id" class="block text-sm font-semibold text-gray-700 mb-2">Exploitant <span class="text-red-500">*</span></label>
                                                <select id="exploitant_id" name="exploitant_id" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
                                                    <option value="">Sélectionner un exploitant</option>
                                                    @foreach($exploitants as $exploitant)
                                                        <option value="{{ $exploitant->id }}" data-cin="{{ $exploitant->n_cin ?? '' }}" data-numero="{{ $exploitant->numero ?? '' }}" data-adresse="{{ $exploitant->adresse ?? '' }}" {{ old('exploitant_id') == $exploitant->id ? 'selected' : '' }}>{{ $exploitant->nom_complet }}</option>
                                                    @endforeach
                                                </select>
                                                @error('exploitant_id')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="exploitant_cin" class="block text-sm font-semibold text-gray-700 mb-2">CIN</label>
                                                <input type="text" id="exploitant_cin" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="exploitant_numero" class="block text-sm font-semibold text-gray-700 mb-2">Numéro de carte professionnelle</label>
                                                <input type="text" id="exploitant_numero" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="exploitant_adresse" class="block text-sm font-semibold text-gray-700 mb-2">Adresse</label>
                                                <input type="text" id="exploitant_adresse" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 3. Détails de la vente -->
                                    <div class="rounded-xl p-5 border mb-5" style="background:#f8faf9;border-color:rgba(154,179,163,0.25);">
                                        <div class="flex items-center gap-3 mb-4">
                                            <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-green-600">
                                                <i class="fas fa-money-bill-wave text-white text-sm"></i>
                                            </div>
                                            <h3 class="text-lg font-semibold text-gray-900">3. Détails de la vente</h3>
                                        </div>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div class="form-group">
                                                <label for="prix_vente" class="block text-sm font-semibold text-gray-700 mb-2">Prix de Vente (MAD) <span class="text-red-500">*</span></label>
                                                <input type="number" id="prix_vente" name="prix_vente" value="{{ old('prix_vente') }}" placeholder="0.00" step="0.01" min="0" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" required onchange="calculateCharges(); updateTranchesInputs();">
                                                @error('prix_vente')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="nombre_tranche" class="block text-sm font-semibold text-gray-700 mb-2">Nombre de Tranches <span class="text-red-500">*</span></label>
                                                <select id="nombre_tranche" name="nombre_tranche" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" required onchange="updateTranchesInputs()">
                                                    <option value="1" {{ old('nombre_tranche', 1) == 1 ? 'selected' : '' }}>1</option>
                                                    <option value="2" {{ old('nombre_tranche', 1) == 2 ? 'selected' : '' }}>2</option>
                                                    <option value="4" {{ old('nombre_tranche', 1) == 4 ? 'selected' : '' }}>4</option>
                                                </select>
                                                @error('nombre_tranche')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                                            <div class="form-group">
                                                <label for="date_limite_tranche" class="block text-sm font-semibold text-gray-700 mb-2">Date Limite Tranche</label>
                                                <input type="date" id="date_limite_tranche" name="date_limite_tranche" value="{{ old('date_limite_tranche') }}" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                                @error('date_limite_tranche')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="date_limite_taxes" class="block text-sm font-semibold text-gray-700 mb-2">Date Limite Taxes</label>
                                                <input type="date" id="date_limite_taxes" name="date_limite_taxes" value="{{ old('date_limite_taxes') }}" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                                @error('date_limite_taxes')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 4. Récapitulatif des charges (calcul automatique) -->
                                    <div class="rounded-xl p-5 border mb-5" style="background:#f8faf9;border-color:rgba(154,179,163,0.25);">
                                        <div class="flex items-center gap-3 mb-4">
                                            <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-green-600">
                                                <i class="fas fa-calculator text-white text-sm"></i>
                                            </div>
                                            <h3 class="text-lg font-semibold text-gray-900">4. Récapitulatif des charges (calcul automatique)</h3>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div class="form-group">
                                                <label class="block text-sm font-semibold text-gray-700 mb-2">Cautionnement définitif (10%)</label>
                                                <input type="number" id="charge_cautionnement" name="charges[0][montant]" step="0.01" readonly class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100" value="0.00">
                                                <input type="hidden" name="charges[0][nom]" value="Cautionnement définitif">
                                            </div>
                                            <div class="form-group">
                                                <label for="charges_0_date" class="block text-sm font-semibold text-gray-700 mb-2">Date d'échéance <span class="text-red-500">*</span></label>
                                                <input type="date" id="charges_0_date" name="charges[0][date_echeance]" value="{{ old('charges.0.date_echeance') }}" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
                                            </div>
                                            <div class="form-group">
                                                <label class="block text-sm font-semibold text-gray-700 mb-2">Taxe FNF (20%)</label>
                                                <input type="number" id="charge_taxe_fnf" name="charges[1][montant]" step="0.01" readonly class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100" value="0.00">
                                                <input type="hidden" name="charges[1][nom]" value="Taxe FNF">
                                            </div>
                                            <div class="form-group">
                                                <label for="charges_1_date" class="block text-sm font-semibold text-gray-700 mb-2">Date d'échéance <span class="text-red-500">*</span></label>
                                                <input type="date" id="charges_1_date" name="charges[1][date_echeance]" value="{{ old('charges.1.date_echeance') }}" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
                                            </div>
                                            <div class="form-group">
                                                <label class="block text-sm font-semibold text-gray-700 mb-2">Frais d'adjudication (1.6%)</label>
                                                <input type="number" id="charge_frais_adjudication" name="charges[2][montant]" step="0.01" readonly class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100" value="0.00">
                                                <input type="hidden" name="charges[2][nom]" value="Frais d'adjudication">
                                            </div>
                                            <div class="form-group">
                                                <label for="charges_2_date" class="block text-sm font-semibold text-gray-700 mb-2">Date d'échéance <span class="text-red-500">*</span></label>
                                                <input type="date" id="charges_2_date" name="charges[2][date_echeance]" value="{{ old('charges.2.date_echeance') }}" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
                                            </div>
                                            <div class="form-group">
                                                <label class="block text-sm font-semibold text-gray-700 mb-2">Taxe provinciale (10%)</label>
                                                <input type="number" id="charge_taxe_provinciale" name="charges[3][montant]" step="0.01" readonly class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100" value="0.00">
                                                <input type="hidden" name="charges[3][nom]" value="Taxe provinciale">
                                            </div>
                                            <div class="form-group">
                                                <label for="charges_3_date" class="block text-sm font-semibold text-gray-700 mb-2">Date d'échéance <span class="text-red-500">*</span></label>
                                                <input type="date" id="charges_3_date" name="charges[3][date_echeance]" value="{{ old('charges.3.date_echeance') }}" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 5. Détail des tranches -->
                                    <div class="rounded-xl p-5 border mb-5" style="background:#f8faf9;border-color:rgba(154,179,163,0.25);">
                                        <div class="flex items-center gap-3 mb-4">
                                            <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-green-600">
                                                <i class="fas fa-list-ol text-white text-sm"></i>
                                            </div>
                                            <h3 class="text-lg font-semibold text-gray-900">5. Détail des tranches</h3>
                                        </div>
                                        <div id="tranchesContainer" class="space-y-4">
                                            <!-- Tranches will be dynamically generated -->
                                        </div>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="flex justify-end gap-4 pt-2">
                                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
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

                                // Update tranches inputs based on nombre_tranche; montant = prix_vente / nombre_tranche
                                function updateTranchesInputs() {
                                    const nombreTranches = parseInt(document.getElementById('nombre_tranche').value) || 1;
                                    const prixVente = parseFloat(document.getElementById('prix_vente').value) || 0;
                                    const montantParTranche = nombreTranches > 0 ? (prixVente / nombreTranches) : 0;
                                    const container = document.getElementById('tranchesContainer');
                                    
                                    container.innerHTML = '';
                                    
                                    for (let i = 0; i < nombreTranches; i++) {
                                        const trancheDiv = document.createElement('div');
                                        trancheDiv.className = 'bg-purple-50 rounded-xl p-4 border border-purple-200';
                                        const montant = (Math.round(montantParTranche * 100) / 100).toFixed(2);
                                        trancheDiv.innerHTML = `
                                            <h3 class="text-md font-semibold text-purple-700 mb-4">
                                                <i class="fas fa-money-check-alt mr-2"></i>Tranche ${i + 1}
                                            </h3>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                        Montant (MAD) <span class="text-red-500">*</span> <span class="text-xs font-normal text-gray-500">(Prix de vente ÷ ${nombreTranches})</span>
                                                    </label>
                                                    <input type="number" 
                                                        name="tranches[${i}][montant]" 
                                                        step="0.01" 
                                                        min="0"
                                                        value="${montant}"
                                                        readonly
                                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-100"
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

                                // Auto-fill exploitant details (run when DOM ready; script may load after DOMContentLoaded)
                                function initExploitantFields() {
                                    const exploitantSelect = document.getElementById('exploitant_id');
                                    if (!exploitantSelect) return;

                                    function setFieldValue(fieldId, value) {
                                        const field = document.getElementById(fieldId);
                                        if (field) field.value = value || '';
                                    }

                                    function updateExploitantFromSelect() {
                                        const selectedOption = exploitantSelect.options[exploitantSelect.selectedIndex];
                                        if (selectedOption && selectedOption.value) {
                                            setFieldValue('exploitant_cin', selectedOption.getAttribute('data-cin'));
                                            setFieldValue('exploitant_numero', selectedOption.getAttribute('data-numero'));
                                            setFieldValue('exploitant_adresse', selectedOption.getAttribute('data-adresse'));
                                        } else {
                                            setFieldValue('exploitant_cin', '');
                                            setFieldValue('exploitant_numero', '');
                                            setFieldValue('exploitant_adresse', '');
                                        }
                                    }

                                    exploitantSelect.addEventListener('change', updateExploitantFromSelect);
                                    updateExploitantFromSelect();
                                }

                                (function runWhenReady() {
                                    if (document.readyState === 'loading') {
                                        document.addEventListener('DOMContentLoaded', function() {
                                            initExploitantFields();
                                            updateTranchesInputs();
                                            calculateCharges();
                                        });
                                    } else {
                                        initExploitantFields();
                                        updateTranchesInputs();
                                        calculateCharges();
                                    }
                                })();
                                </script>
                                @endpush
                            @endif

                            @if($contractVente)
                                @push('scripts')
                                <script>
                                // Toggle edit form - Make it globally available
                                window.toggleEditForm = function() {
                                    try {
                                        const editForm = document.getElementById('editContractForm');
                                        const contractDetails = document.getElementById('contractDetails');
                                        const editBtn = document.getElementById('editContractBtn');
                                        const cancelBtn = document.getElementById('cancelEditBtn');
                                        
                                        if (!editForm || !contractDetails || !editBtn || !cancelBtn) {
                                            console.error('Required elements not found for edit form toggle');
                                            return;
                                        }
                                        
                                        if (editForm.classList.contains('hidden')) {
                                            editForm.classList.remove('hidden');
                                            contractDetails.classList.add('hidden');
                                            editBtn.classList.add('hidden');
                                            cancelBtn.classList.remove('hidden');
                                            
                                            // Initialize edit form
                                            const editExploitantSelect = document.getElementById('edit_exploitant_id');
                                            if (editExploitantSelect && editExploitantSelect.value) {
                                                editExploitantSelect.dispatchEvent(new Event('change'));
                                            }
                                            
                                            if (typeof calculateEditCharges === 'function') {
                                                calculateEditCharges();
                                            }
                                            if (typeof updateEditTranchesInputs === 'function') {
                                                updateEditTranchesInputs();
                                            }
                                        } else {
                                            editForm.classList.add('hidden');
                                            contractDetails.classList.remove('hidden');
                                            editBtn.classList.remove('hidden');
                                            cancelBtn.classList.add('hidden');
                                        }
                                    } catch (error) {
                                        console.error('Error toggling edit form:', error);
                                        alert('Une erreur s\'est produite lors de l\'ouverture du formulaire d\'édition.');
                                    }
                                };

                                // Calculate charges for edit form
                                function calculateEditCharges() {
                                    try {
                                        const prixVente = parseFloat(document.getElementById('edit_prix_vente')?.value) || 0;
                                        
                                        // Update charge inputs if they exist (exclude tranche montants)
                                        const chargeInputs = document.querySelectorAll('#editContractForm input[name*="charges"][name*="[montant]"][readonly]');
                                        chargeInputs.forEach(input => {
                                            const chargeName = input.closest('div').querySelector('label')?.textContent || '';
                                            let percentage = 0;
                                            
                                            if (chargeName.includes('Cautionnement')) percentage = 0.10;
                                            else if (chargeName.includes('Taxe FNF')) percentage = 0.20;
                                            else if (chargeName.includes('Frais d\'adjudication')) percentage = 0.016;
                                            else if (chargeName.includes('Taxe provinciale')) percentage = 0.10;
                                            
                                            if (percentage > 0) {
                                                input.value = (prixVente * percentage).toFixed(2);
                                            }
                                        });
                                    } catch (error) {
                                        console.error('Error calculating edit charges:', error);
                                    }
                                }

                                // Update tranches for edit form: montant = prix_vente / nombre_tranche
                                function updateEditTranchesInputs() {
                                    const prixVente = parseFloat(document.getElementById('edit_prix_vente')?.value) || 0;
                                    const nombreTranches = parseInt(document.getElementById('edit_nombre_tranche')?.value) || 1;
                                    const montantParTranche = nombreTranches > 0 ? (Math.round((prixVente / nombreTranches) * 100) / 100).toFixed(2) : '0.00';
                                    document.querySelectorAll('.edit-tranche-montant').forEach(function(input) {
                                        input.value = montantParTranche;
                                    });
                                }

                                // Auto-fill exploitant details for edit form
                                document.addEventListener('DOMContentLoaded', function() {
                                    const editExploitantSelect = document.getElementById('edit_exploitant_id');
                                    
                                    if (editExploitantSelect) {
                                        editExploitantSelect.addEventListener('change', function() {
                                            const selectedOption = this.options[this.selectedIndex];
                                            if (selectedOption && selectedOption.value) {
                                                const cinField = document.getElementById('edit_exploitant_cin');
                                                const numeroField = document.getElementById('edit_exploitant_numero');
                                                const adresseField = document.getElementById('edit_exploitant_adresse');
                                                
                                                if (cinField) cinField.value = selectedOption.dataset.cin || '';
                                                if (numeroField) numeroField.value = selectedOption.dataset.numero || '';
                                                if (adresseField) adresseField.value = selectedOption.dataset.adresse || '';
                                            } else {
                                                const cinField = document.getElementById('edit_exploitant_cin');
                                                const numeroField = document.getElementById('edit_exploitant_numero');
                                                const adresseField = document.getElementById('edit_exploitant_adresse');
                                                
                                                if (cinField) cinField.value = '';
                                                if (numeroField) numeroField.value = '';
                                                if (adresseField) adresseField.value = '';
                                            }
                                        });

                                        // Trigger exploitant change on page load if already selected
                                        if (editExploitantSelect.value) {
                                            editExploitantSelect.dispatchEvent(new Event('change'));
                                        }
                                    }
                                });
                                </script>
                                @endpush
                            @endif
                        </div>
                    </div>
                @elseif($activeStep === 'paiement_charges')
                    <!-- Paiement des Charges Section -->
                    <div class="card overflow-hidden">
                        <div class="section-header flex flex-wrap items-center justify-between gap-3 px-6 py-4">
                            <h2 class="text-lg font-semibold flex items-center gap-3" style="color: #1F2D24;">
                                <i class="fas fa-money-bill-wave" style="color: #6B7C72;"></i>
                                Paiement des Charges
                            </h2>
                            @if($showPermisExploiter || $showPVInstallation)
                                <div class="flex flex-wrap items-center gap-2 text-sm">
                                    <span class="text-gray-500">Documents :</span>
                                    @if($showPermisExploiter)
                                        <a href="{{ route('articles.permis-exploiter', $article) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg font-medium transition-colors text-white hover:opacity-90" style="background: linear-gradient(135deg, #059669, #047857);">
                                            <i class="fas fa-file-contract text-xs"></i>
                                            Permis d'exploiter
                                        </a>
                                    @endif
                                    @if($showPVInstallation)
                                        <a href="{{ route('articles.pv-installation', $article) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg font-medium transition-colors text-white hover:opacity-90" style="background: linear-gradient(135deg, #059669, #047857);">
                                            <i class="fas fa-clipboard-check text-xs"></i>
                                            PV d'installation
                                        </a>
                                    @endif
                                </div>
                            @endif
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
                                        <thead class="bg-gray-50 border-b border-gray-200">
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
                                                                   class="w-full px-2 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                                                   placeholder="{{ $isCautionnement ? 'Numéro' : 'N° quittance' }}">
                                                        </td>
                                                        
                                                        <!-- Date de paiement -->
                                                        <td class="px-3 py-3" style="width: 12%;">
                                                            <input type="date" 
                                                                   name="payments[{{ $charge->id ?? 'new_' . $index }}][date_payment]" 
                                                                   value="{{ $payment && $payment->date_payment ? \Carbon\Carbon::parse($payment->date_payment)->format('Y-m-d') : old('payments.' . ($charge->id ?? 'new_' . $index) . '.date_payment') }}"
                                                                   class="w-full px-2 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
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
                    <div class="card overflow-hidden">
                        <div class="section-header flex flex-wrap items-center justify-between gap-3 px-6 py-4">
                            <h2 class="text-lg font-semibold flex items-center gap-3" style="color: #1F2D24;">
                                <i class="fas fa-credit-card" style="color: #6B7C72;"></i>
                                Paiement des Tranches
                            </h2>
                            @if($showPermisEnlever || $showPermisColportage)
                                <div class="flex flex-wrap items-center gap-2 text-sm">
                                    <span class="text-gray-500">Documents :</span>
                                    @if($showPermisEnlever)
                                        <a href="{{ route('articles.permis-enlever', $article) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg font-medium transition-colors text-white hover:opacity-90" style="background: linear-gradient(135deg, #059669, #047857);">
                                            <i class="fas fa-file-alt text-xs"></i>
                                            Permis d'enlever
                                        </a>
                                    @endif
                                    @if($showPermisColportage)
                                        <a href="{{ route('articles.permis-colportage', $article) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg font-medium transition-colors text-white hover:opacity-90" style="background: linear-gradient(135deg, #059669, #047857);">
                                            <i class="fas fa-truck text-xs"></i>
                                            Permis de colportage
                                        </a>
                                    @endif
                                </div>
                            @endif
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
                                    
                                    <input type="hidden" name="selected_tranche" id="selectedTranche" value="">
                                    
                                    <div class="mb-8">
                                        <h3 class="text-lg font-semibold mb-4 flex items-center gap-2" style="color: #1F2D24;">
                                            <i class="fas fa-hand-pointer text-orange-600"></i>
                                            1. Sélection des tranches à payer
                                        </h3>
                                        
                                        <div class="overflow-x-auto shadow-md rounded-lg mb-4">
                                            <table class="w-full divide-y divide-gray-200">
                                                <thead class="bg-gray-50 border-b border-gray-200">
                                                    <tr>
                                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">
                                                            <!-- Single selection: radio button per tranche -->
                                                        </th>
                                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Numéro de tranche</th>
                                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Montant</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200">
                                                    @foreach($tranchesImpayees as $tranche)
                                                    <tr class="hover:bg-gray-50">
                                                        <td class="px-4 py-3 whitespace-nowrap">
                                                            <input type="radio" 
                                                                   name="tranche_radio"
                                                                   class="tranche-radio rounded border-gray-300 text-green-500 focus:ring-green-400" 
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
                                        
                                        <div class="rounded-xl border p-4 mb-6" style="background: rgba(242,246,243,0.6); border-color: rgba(154,179,163,0.4);">
                                            <div class="flex items-center justify-between mb-4">
                                                <span class="text-sm font-medium text-gray-700">Montant total à payer: </span>
                                                <span class="text-lg font-bold text-green-600" id="totalMontant">0.00 MAD</span>
                                            </div>
                                            
                                            <!-- Informations de paiement -->
                                            <div id="paymentSection" class="hidden space-y-4 pt-4 border-t border-gray-200">
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
                                    <h3 class="text-lg font-semibold mb-4 flex items-center gap-2" style="color: #1F2D24;">
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
                                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach($tranchesPayees as $tranche)
                                                    @php 
                                                        $payment = $tranche->payments->first();
                                                        // Find permis d'enlever for this tranche
                                                        // Since permis are created per payment date, find the most recent one
                                                        // that could correspond to this tranche's payment date
                                                        $permisEnlever = null;
                                                        if ($payment && $payment->date_payment && $contractVente && $permisEnlevers->isNotEmpty()) {
                                                            // Get all tranches paid on the same date as this tranche
                                                            $tranchesSameDate = $contractVente->chargeApayer->filter(function($charge) use ($payment) {
                                                                if (!str_starts_with($charge->nom, 'Tranche')) return false;
                                                                $p = $charge->payments->first();
                                                                return $p && $p->is_paye && $p->date_payment == $payment->date_payment;
                                                            });
                                                            
                                                            // Try to find permis d'enlever created for this number of tranches
                                                            // If multiple exist, get the most recent one
                                                            $permisEnlever = $permisEnlevers->filter(function($permis) use ($tranchesSameDate) {
                                                                return $permis->num_tranche_paye == $tranchesSameDate->count();
                                                            })->first();
                                                            
                                                            // If no exact match, use the most recent permis (fallback)
                                                            if (!$permisEnlever) {
                                                                $permisEnlever = $permisEnlevers->first();
                                                            }
                                                        }
                                                    @endphp
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
                                                        <td class="px-4 py-3 whitespace-nowrap">
                                                            <div class="flex items-center gap-2">
                                                                <a href="{{ route('articles.permis-enlever', $article) }}" 
                                                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-green-700 bg-green-50 border border-green-200 rounded-lg hover:bg-green-100 transition-colors">
                                                                    <i class="fas fa-file-alt"></i>
                                                                    <span>Permis d'enlever</span>
                                                                </a>
                                                                @if($permisEnlever)
                                                                    <button onclick="printPermisEnlever({{ $permisEnlever->id }})" 
                                                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors">
                                                                        <i class="fas fa-print"></i>
                                                                        <span>Imprimer</span>
                                                                    </button>
                                                                @endif
                                                            </div>
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

                                <!-- Documents Section: Permis d'enlever & Permis de colportage -->
                                @php
                                    $hasPaidTranches = $tranchesPayees->count() > 0;
                                @endphp
                            @endif
                        </div>
                    </div>
                    
                    <script>
                    // Single-tranche payment: allow paying only one tranche at a time
                    function updateTotal() {
                        const radios = document.querySelectorAll('.tranche-radio');
                        let total = 0;
                        let selectedId = null;

                        radios.forEach(rb => {
                            if (rb.checked) {
                                total = parseFloat(rb.dataset.montant) || 0;
                                selectedId = rb.dataset.trancheId;
                            }
                        });

                        document.getElementById('totalMontant').textContent = total.toFixed(2) + ' MAD';

                        // Show/hide payment section based on selection
                        const paymentSection = document.getElementById('paymentSection');
                        const selectedTrancheInput = document.getElementById('selectedTranche');

                        if (selectedId) {
                            paymentSection.classList.remove('hidden');
                            selectedTrancheInput.value = selectedId;
                        } else {
                            paymentSection.classList.add('hidden');
                            selectedTrancheInput.value = '';
                        }
                    }
                    </script>
                @elseif($activeStep === 'recollement')
                    <!-- Récolement Section -->
                    <div class="card overflow-hidden">
                        <div class="section-header">
                            <h2 class="text-lg font-semibold flex items-center gap-3" style="color: #1F2D24;">
                                <i class="fas fa-clipboard-check" style="color: #6B7C72;"></i>
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
                                        <div class="mb-8 p-4 rounded-xl border" style="background: rgba(242,246,243,0.6); border-color: rgba(154,179,163,0.4);">
                                            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2" style="color: #1F2D24;">
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
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-orange-600 text-white rounded-lg font-medium hover:bg-orange-700 transition-colors">
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
                    <div class="card overflow-hidden">
                        <div class="section-header">
                            <h2 class="text-lg font-semibold flex items-center gap-3" style="color: #1F2D24;">
                                <i class="fas fa-check-circle" style="color: #6B7C72;"></i>
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
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition-colors">
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
                    <div class="card overflow-hidden">
                        <div class="section-header">
                            <h2 class="text-lg font-semibold flex items-center gap-3" style="color: #1F2D24;">
                                <i class="fas fa-check-circle" style="color: #6B7C72;"></i>
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
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg font-medium hover:bg-emerald-700 transition-colors">
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
                    <div class="card overflow-hidden">
                        <div class="section-header">
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

@push('scripts')
<script>
function printPermisEnlever(permisId) {
    // Open permis-enlever page in new window and trigger print
    const url = '{{ route("articles.permis-enlever", $article) }}?print=' + permisId;
    const printWindow = window.open(url, '_blank');
    
    // Wait for page to load, then trigger print
    if (printWindow) {
        printWindow.onload = function() {
            setTimeout(function() {
                printWindow.print();
            }, 500);
        };
    }
}
</script>
@endpush
@endsection
