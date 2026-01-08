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
                        'paiement_charges' => ['label' => 'Paiement des charges', 'icon' => 'fa-money-bill-wave', 'color' => 'amber'],
                        'paiement_tranches' => ['label' => 'Paiement des tranches', 'icon' => 'fa-credit-card', 'color' => 'orange'],
                        'recollement' => ['label' => 'Récolement', 'icon' => 'fa-clipboard-check', 'color' => 'indigo'],
                        'main_levee' => ['label' => 'Main levée', 'icon' => 'fa-check-circle', 'color' => 'emerald'],
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
                
                <!-- Navigation Buttons -->
                <div class="mt-6 flex justify-between items-center">
                    @if($previousStepKey)
                        <form action="{{ route('articles.update-step', $article) }}" method="POST" class="inline">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="step" value="{{ $previousStepKey }}">
                            <button type="submit" 
                                    class="inline-flex items-center gap-2 px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                                <i class="fas fa-arrow-left"></i>
                                <span>Étape précédente: {{ $steps[$previousStepKey]['label'] }}</span>
                            </button>
                        </form>
                    @else
                        <div></div>
                    @endif
                    
                    @if($nextStepKey)
                        <form action="{{ route('articles.update-step', $article) }}" method="POST" class="inline">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="step" value="{{ $nextStepKey }}">
                            <button type="submit" 
                                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                                <span>Étape suivante: {{ $steps[$nextStepKey]['label'] }}</span>
                                <i class="fas fa-arrow-right"></i>
                            </button>
                        </form>
                    @else
                        <div></div>
                    @endif
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
                @elseif($currentStep === 'contrat_vente' || $nextStepKey === 'paiement_charges')
                    <!-- Paiement des Charges Section -->
                    <div class="bg-white/70 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                        <div class="bg-gradient-to-r from-amber-500 to-amber-600 px-6 py-4">
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
                                
                                <form action="{{ route('articles.update-charge-payments', $article) }}" method="POST" enctype="multipart/form-data" id="chargePaymentsForm">
                                    @csrf
                                    @method('PUT')
                                    
                                    <!-- Table with all charges and payment information -->
                                    <div class="overflow-x-auto shadow-md rounded-lg">
                                        <table class="w-full table-auto divide-y divide-gray-200" style="min-width: 1400px;">
                                            <thead class="bg-gradient-to-r from-amber-50 to-amber-100">
                                                <tr>
                                                    <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase" style="width: 20%;">Charge</th>
                                                    <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase" style="width: 10%;">Montant</th>
                                                    <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase" style="width: 10%;">Date échéance</th>
                                                    <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase" style="width: 12%;">Statut</th>
                                                    <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase" style="width: 15%;">Référence</th>
                                                    <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase" style="width: 13%;">Date paiement</th>
                                                    <th class="px-3 py-3 text-left text-xs font-semibold text-gray-700 uppercase" style="width: 20%;">Pièce jointe</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach($charges as $index => $charge)
                                                    @php
                                                        $payment = $charge->payments->first() ?? null;
                                                        $isCautionnement = str_contains(strtolower($charge->nom), 'cautionnement');
                                                    @endphp
                                                    <tr class="hover:bg-gray-50">
                                                        <!-- Charge -->
                                                        <td class="px-3 py-3" style="width: 20%;">
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ $charge->nom }}
                                                                @if(in_array($charge->nom, $requiredCharges))
                                                                    <span class="ml-1 text-xs text-red-600 font-semibold">*</span>
                                                                @endif
                                                            </div>
                                                        </td>
                                                        
                                                        <!-- Montant -->
                                                        <td class="px-3 py-3 whitespace-nowrap" style="width: 10%;">
                                                            <div class="text-sm font-semibold text-gray-900">
                                                                {{ number_format($charge->montant ?? 0, 2) }}
                                                            </div>
                                                        </td>
                                                        
                                                        <!-- Date échéance -->
                                                        <td class="px-3 py-3 whitespace-nowrap" style="width: 10%;">
                                                            <div class="text-sm text-gray-900">
                                                                @if($charge->date_echeance)
                                                                    {{ \Carbon\Carbon::parse($charge->date_echeance)->format('d/m/Y') }}
                                                                @else
                                                                    <span class="text-gray-400">-</span>
                                                                @endif
                                                            </div>
                                                        </td>
                                                        
                                                        <!-- Statut de paiement -->
                                                        <td class="px-3 py-3" style="width: 12%;">
                                                            <div class="flex items-center">
                                                                <label class="relative inline-flex items-center cursor-pointer">
                                                                    <input type="hidden" name="payments[{{ $charge->id ?? 'new_' . $index }}][statut]" value="0">
                                                                    <input type="checkbox" 
                                                                           name="payments[{{ $charge->id ?? 'new_' . $index }}][statut]" 
                                                                           value="1"
                                                                           {{ $payment && $payment->is_paye ? 'checked' : '' }}
                                                                           class="sr-only peer"
                                                                           onchange="this.nextElementSibling.nextElementSibling.textContent = this.checked ? 'Payé' : 'Impayé'; this.nextElementSibling.nextElementSibling.classList.toggle('text-green-600', this.checked); this.nextElementSibling.nextElementSibling.classList.toggle('text-red-600', !this.checked);">
                                                                    <div class="w-11 h-6 bg-red-200 rounded-full peer peer-checked:bg-green-500 peer-focus:ring-2 peer-focus:ring-amber-300 transition-all">
                                                                        <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-all peer-checked:translate-x-5 shadow-md"></div>
                                                                    </div>
                                                                    <span class="ml-2 text-xs font-medium {{ $payment && $payment->is_paye ? 'text-green-600' : 'text-red-600' }}">
                                                                        {{ $payment && $payment->is_paye ? 'Payé' : 'Impayé' }}
                                                                    </span>
                                                                </label>
                                                            </div>
                                                        </td>
                                                        
                                                        <!-- Référence -->
                                                        <td class="px-3 py-3" style="width: 15%;">
                                                            <input type="text" 
                                                                   name="payments[{{ $charge->id ?? 'new_' . $index }}][reference]" 
                                                                   value="{{ $payment->num_quittace ?? old('payments.' . ($charge->id ?? 'new_' . $index) . '.reference') }}"
                                                                   class="w-full px-2 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                                                                   placeholder="{{ $isCautionnement ? 'Numéro' : 'N° quittance' }}">
                                                        </td>
                                                        
                                                        <!-- Date de paiement -->
                                                        <td class="px-3 py-3" style="width: 13%;">
                                                            <input type="date" 
                                                                   name="payments[{{ $charge->id ?? 'new_' . $index }}][date_payment]" 
                                                                   value="{{ $payment && $payment->date_payment ? \Carbon\Carbon::parse($payment->date_payment)->format('Y-m-d') : old('payments.' . ($charge->id ?? 'new_' . $index) . '.date_payment') }}"
                                                                   class="w-full px-2 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                                                        </td>
                                                        
                                                        <!-- Pièce jointe -->
                                                        <td class="px-3 py-3" style="width: 20%;">
                                                            <div class="flex items-center gap-2">
                                                                <label for="file_{{ $charge->id ?? 'new_' . $index }}" class="cursor-pointer inline-flex items-center justify-center w-10 h-10 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
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
                                                                    <a href="{{ asset('storage/' . $payment->fichier_joint) }}" target="_blank" class="text-blue-600 hover:text-blue-800 transition-colors">
                                                                        <i class="fas fa-eye text-sm"></i>
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        </td>
                                                        
                                                        <input type="hidden" name="payments[{{ $charge->id ?? 'new_' . $index }}][charge_id]" value="{{ $charge->id }}">
                                                        <input type="hidden" name="payments[{{ $charge->id ?? 'new_' . $index }}][charge_nom]" value="{{ $charge->nom }}">
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                            </div>

                                    <div class="flex justify-end gap-4 mt-6">
                                    <button type="submit" 
                                                class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-amber-500 to-amber-600 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                                            <i class="fas fa-save"></i>
                                            <span>Enregistrer les paiements</span>
                                    </button>
                                </div>
                            </form>
                            @endif
                        </div>
                    </div>
                @elseif($currentStep === 'paiement_charges' || $nextStepKey === 'paiement_tranches')
                    <!-- Paiement des Tranches Section -->
                    <div class="bg-white/70 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                        <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4">
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
                                                <thead class="bg-gradient-to-r from-orange-50 to-orange-100">
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
                                                                   class="tranche-checkbox rounded border-gray-300 text-orange-600 focus:ring-orange-500" 
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
                                        
                                        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 mb-6">
                                            <div class="flex items-center justify-between mb-4">
                                                <span class="text-sm font-medium text-gray-700">Montant total à payer: </span>
                                                <span class="text-lg font-bold text-orange-600" id="totalMontant">0.00 MAD</span>
                                            </div>
                                            
                                            <!-- Informations de paiement -->
                                            <div id="paymentSection" class="hidden space-y-4 pt-4 border-t border-orange-300">
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
                                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
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
                                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                                    </div>
                                                </div>
                                                
                                                <div>
                                                    <label for="fichier_joint" class="block text-sm font-semibold text-gray-700 mb-2">
                                                        Pièce jointe (justificatif)
                                                    </label>
                                                    <div class="flex items-center gap-2">
                                                        <label for="fichier_joint" class="cursor-pointer inline-flex items-center justify-center w-10 h-10 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
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
                                                            class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
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
                                        <i class="fas fa-check-circle text-green-600"></i>
                                        2. Rappel des tranches payées
                                    </h3>
                                    
                                    <div class="overflow-x-auto shadow-md rounded-lg">
                                        <table class="w-full divide-y divide-gray-200">
                                            <thead class="bg-gradient-to-r from-green-50 to-green-100">
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
                                                            <div class="text-sm font-semibold text-green-600">{{ number_format($tranche->montant ?? 0, 2) }} MAD</div>
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
                                                                <a href="{{ asset('storage/' . $payment->fichier_joint) }}" target="_blank" class="text-blue-600 hover:text-blue-800 transition-colors">
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
                @elseif($currentStep === 'paiement_tranches' || $nextStepKey === 'recollement')
                    <!-- Récolement Section -->
                    <div class="bg-white/70 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-4">
                            <h2 class="text-xl font-bold text-white flex items-center gap-3">
                                <i class="fas fa-clipboard-check"></i>
                                Récolement
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="text-center py-12">
                                <div class="mb-6">
                                    <i class="fas fa-clipboard-check text-indigo-400 text-6xl mb-4"></i>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-700 mb-3">Section Récolement</h3>
                                <p class="text-gray-500 mb-6">Cette section sera disponible prochainement</p>
                                <div class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-50 text-indigo-600 rounded-lg text-sm">
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
                @elseif($currentStep === 'recollement' || $nextStepKey === 'main_levee')
                    <!-- Main Levée Section -->
                    <div class="bg-white/70 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                        <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 px-6 py-4">
                            <h2 class="text-xl font-bold text-white flex items-center gap-3">
                                <i class="fas fa-check-circle"></i>
                                Main Levée
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="text-center py-12">
                                <div class="mb-6">
                                    <i class="fas fa-check-circle text-emerald-400 text-6xl mb-4"></i>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-700 mb-3">Section Main Levée</h3>
                                <p class="text-gray-500 mb-6">Cette section sera disponible prochainement</p>
                                <div class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-50 text-emerald-600 rounded-lg text-sm">
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
                        <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 px-6 py-4">
                            <h2 class="text-xl font-bold text-white flex items-center gap-3">
                                <i class="fas fa-check-circle"></i>
                                Main Levée - Processus Terminé
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
                        @if($article->dranef)
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">DRANEF</label>
                            <div class="text-sm font-medium text-gray-800">
                                {{ $article->dranef->dranef ?? 'N/A' }}
                            </div>
                        </div>
                        @endif
                        @if($article->dpanef)
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">DPANEF</label>
                            <div class="text-sm font-medium text-gray-800">
                                {{ $article->dpanef->dpanef ?? 'N/A' }}
                            </div>
                        </div>
                        @endif
                        @if($article->zdtf)
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">ZDTF</label>
                            <div class="text-sm font-medium text-gray-800">
                                {{ $article->zdtf->zdtf ?? 'N/A' }}
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
