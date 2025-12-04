@extends('layouts.app')

@section('title', 'Nouvel Avenant - DEFATP')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 bg-gradient-to-br rounded-2xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #6366f1, #8b5cf6);">
                <i class="fas fa-file-contract text-white text-2xl"></i>
            </div>
            <div>
                <h1 class="text-4xl font-bold bg-clip-text text-transparent" style="background: linear-gradient(to right, #6366f1, #8b5cf6); -webkit-background-clip: text; background-clip: text;">
                    Nouvel Avenant
                </h1>
                <p class="text-gray-600 text-lg mt-2">Créez un nouvel avenant de contrat</p>
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

    @if ($errors->any())
    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6">
        <div class="font-semibold mb-2">Erreurs de validation:</div>
        <ul class="list-disc pl-5">
            @php
                $uniqueErrors = array_unique($errors->all());
            @endphp
            @foreach ($uniqueErrors as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Create Form -->
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #6366f1, #8b5cf6);">
                <i class="fas fa-plus text-white text-xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold" style="color: #6366f1;">Formulaire de création</h2>
                <p class="text-gray-600">Remplissez les informations pour créer un nouvel avenant</p>
            </div>
        </div>

        <form action="{{ route('contracts.avenants.store') }}" method="POST" class="space-y-8">
            @csrf
            
            <!-- Section 1: Informations de Base -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #6366f1, #8b5cf6);">
                        <i class="fas fa-info-circle text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold" style="color: #6366f1;">Informations de Base</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="form-group">
                        <label for="contact_id" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Contrat <span class="text-red-500">*</span></span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Sélectionnez le contrat associé à cet avenant"></i>
                        </label>
                        <select 
                            name="contact_id" 
                            id="contact_id" 
                            class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400"
                            required
                            onchange="loadContractData(this.value)"
                        >
                            <option value="">Sélectionner un contrat</option>
                            @foreach($contracts as $contract)
                                <option value="{{ $contract->id }}" 
                                        {{ old('contact_id', isset($selectedContract) && $selectedContract && $selectedContract->id == $contract->id ? $contract->id : null) == $contract->id ? 'selected' : '' }}
                                        data-annee="{{ $contract->annee }}"
                                        data-coperative-id="{{ $contract->coperative_id ?? '' }}"
                                        data-superficie="{{ $contract->superficie ?? '' }}"
                                        data-gardiennage-nbjour="{{ $contract->gardiennage_nbjour ?? '' }}"
                                        data-gardiennage-superficie="{{ $contract->gardiennage_superficie ?? '' }}"
                                        data-gardiennage-parcelle="{{ $contract->gardiennage_parcelle ?? '' }}"
                                        data-prevention-incendies-nbjour="{{ $contract->prevention_incendies_nbjour ?? '' }}"
                                        data-prevention-incendies-superficie="{{ $contract->prevention_incendies_superficie ?? '' }}"
                                        data-prevention-incendies-parcelle="{{ $contract->prevention_incendies_parcelle ?? '' }}"
                                        data-elagage="{{ $contract->elagage ?? '' }}"
                                        data-eclaircie="{{ $contract->eclaircie ?? '' }}"
                                        data-rajeunissement-romarin="{{ $contract->rajeunissement_romarin ?? '' }}"
                                        data-bo-m3="{{ $contract->bo_m3 ?? '' }}"
                                        data-bi-m3="{{ $contract->bi_m3 ?? '' }}"
                                        data-bf-st="{{ $contract->bf_st ?? '' }}"
                                        data-tanin-t="{{ $contract->tanin_t ?? '' }}"
                                        data-laurier-sauce="{{ $contract->laurier_sauce ?? '' }}"
                                        data-myrte="{{ $contract->myrte ?? '' }}"
                                        data-callune="{{ $contract->callune ?? '' }}"
                                        data-thym="{{ $contract->thym ?? '' }}"
                                        data-bruyetre="{{ $contract->bruyetre ?? '' }}"
                                        data-lichen="{{ $contract->lichen ?? '' }}"
                                        data-tanin="{{ $contract->tanin ?? '' }}"
                                        data-romarin="{{ $contract->romarin ?? '' }}"
                                        data-liege-male="{{ $contract->liege_male ?? '' }}"
                                        data-liege-de-reproduction="{{ $contract->liege_de_reproduction ?? '' }}"
                                        data-sauge="{{ $contract->sauge ?? '' }}"
                                        data-lavande="{{ $contract->lavande ?? '' }}"
                                        data-armoise="{{ $contract->armoise ?? '' }}"
                                        data-origan="{{ $contract->origan ?? '' }}"
                                        data-alfa="{{ $contract->alfa ?? '' }}"
                                        data-lentisque="{{ $contract->lentisque ?? '' }}"
                                        data-ciste="{{ $contract->ciste ?? '' }}"
                                        data-fleur-acacia-t="{{ $contract->fleur_acacia_t ?? '' }}"
                                        data-valeurs-des-produits="{{ $contract->valeurs_des_produits ?? '' }}"
                                        data-valeur-des-prestations="{{ $contract->valeur_des_prestations ?? '' }}"
                                        data-redevances="{{ $contract->redevances ?? '' }}"
                                        data-taxes="{{ $contract->taxes ?? '' }}"
                                        data-total-avenant="{{ $contract->total_avenant ?? '' }}"
                                        data-products="{{ htmlspecialchars(json_encode($contract->products ?? []), ENT_QUOTES, 'UTF-8') }}"
                                        data-prestations="{{ htmlspecialchars(json_encode($contract->prestations ?? []), ENT_QUOTES, 'UTF-8') }}">
                                    Contrat #{{ $contract->contarct }} ({{ $contract->annee }}) - {{ $contract->localisation->DRANEF ?? 'N/A' }} - {{ $contract->situationAdministrative->commune ?? 'N/A' }}
                                </option>
                            @endforeach
                        </select>
                        @error('contact_id')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="annee" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Année <span class="text-red-500">*</span></span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Année de l'avenant"></i>
                        </label>
                        <input type="number" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="annee" 
                               name="annee" 
                               value="{{ old('annee', date('Y')) }}"
                               required>
                        @error('annee')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="date" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Date <span class="text-red-500">*</span></span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Date de l'avenant"></i>
                        </label>
                        <input type="date" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="date" 
                               name="date" 
                               value="{{ old('date') }}"
                               required>
                        @error('date')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="avenant" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Avenant <span class="text-red-500">*</span></span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Numéro ou référence de l'avenant"></i>
                        </label>
                        <input type="text" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="avenant" 
                               name="avenant" 
                               value="{{ old('avenant') }}"
                               required>
                        @error('avenant')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="coperative_id" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Coopérative</span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Sélectionnez la coopérative"></i>
                        </label>
                        <select class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                                id="coperative_id" 
                                name="coperative_id">
                            <option value="">Sélectionner une coopérative</option>
                            @foreach($coperatives as $coperative)
                                <option value="{{ $coperative->id }}" {{ old('coperative_id') == $coperative->id ? 'selected' : '' }}>
                                    {{ $coperative->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('coperative_id')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="superficie" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Superficie</span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Superficie en hectares"></i>
                        </label>
                        <input type="number" 
                               step="0.01"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="superficie" 
                               name="superficie" 
                               value="{{ old('superficie') }}">
                        @error('superficie')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 2: Prestations -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #6366f1, #8b5cf6);">
                        <i class="fas fa-tools text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold" style="color: #6366f1;">Prestations</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Gardiennage Section -->
                    <div class="form-group md:col-span-3">
                        <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                            <span>Gardiennage</span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Informations sur le gardiennage"></i>
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="gardiennage_nbjour" class="block text-xs font-medium text-gray-600 mb-1">Nombre de Jours</label>
                                <input type="number" 
                                       step="1"
                                       class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                                       id="gardiennage_nbjour" 
                                       name="gardiennage_nbjour" 
                                       value="{{ old('gardiennage_nbjour') }}"
                                       min="0">
                            </div>
                            <div>
                                <label for="gardiennage_superficie" class="block text-xs font-medium text-gray-600 mb-1">Superficie</label>
                                <input type="number" 
                                       step="1"
                                       class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                                       id="gardiennage_superficie" 
                                       name="gardiennage_superficie" 
                                       value="{{ old('gardiennage_superficie') }}"
                                       min="0">
                            </div>
                            <div>
                                <label for="gardiennage_parcelle" class="block text-xs font-medium text-gray-600 mb-1">Parcelle</label>
                                <input type="text" 
                                       class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                                       id="gardiennage_parcelle" 
                                       name="gardiennage_parcelle" 
                                       value="{{ old('gardiennage_parcelle') }}">
                            </div>
                        </div>
                    </div>

                    <!-- Prévention Incendies Section -->
                    <div class="form-group md:col-span-3">
                        <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                            <span>Prévention contre les Incendies</span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Informations sur la prévention contre les incendies"></i>
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="prevention_incendies_nbjour" class="block text-xs font-medium text-gray-600 mb-1">Nombre de Jours</label>
                                <input type="number" 
                                       step="1"
                                       class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                                       id="prevention_incendies_nbjour" 
                                       name="prevention_incendies_nbjour" 
                                       value="{{ old('prevention_incendies_nbjour') }}"
                                       min="0">
                            </div>
                            <div>
                                <label for="prevention_incendies_superficie" class="block text-xs font-medium text-gray-600 mb-1">Superficie</label>
                                <input type="number" 
                                       step="1"
                                       class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                                       id="prevention_incendies_superficie" 
                                       name="prevention_incendies_superficie" 
                                       value="{{ old('prevention_incendies_superficie') }}"
                                       min="0">
                            </div>
                            <div>
                                <label for="prevention_incendies_parcelle" class="block text-xs font-medium text-gray-600 mb-1">Parcelle</label>
                                <input type="text" 
                                       class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                                       id="prevention_incendies_parcelle" 
                                       name="prevention_incendies_parcelle" 
                                       value="{{ old('prevention_incendies_parcelle') }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="elagage" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Elagage</span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Montant de l'élagage"></i>
                        </label>
                        <input type="number" 
                               step="0.01"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="elagage" 
                               name="elagage" 
                               value="{{ old('elagage') }}">
                    </div>

                    <div class="form-group">
                        <label for="eclaircie" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Eclaircie</span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Montant de l'éclaircie"></i>
                        </label>
                        <input type="number" 
                               step="0.01"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="eclaircie" 
                               name="eclaircie" 
                               value="{{ old('eclaircie') }}">
                    </div>

                    <div class="form-group">
                        <label for="rajeunissement_romarin" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Rajeunissement Romarin</span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Montant du rajeunissement du romarin"></i>
                        </label>
                        <input type="number" 
                               step="0.01"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="rajeunissement_romarin" 
                               name="rajeunissement_romarin" 
                               value="{{ old('rajeunissement_romarin') }}">
                    </div>
                </div>
            </div>

            <!-- Prestations Section -->
           <div class="bg-white rounded-2xl p-6 border border-blue-200">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: linear-gradient(to bottom right, #3b82f6, #2563eb);">
                            <i class="fas fa-tasks text-white text-sm"></i>
                        </div>
                        <h4 class="text-lg font-bold" style="color: #3b82f6;">Prestations</h4>
                    </div>
                    <button type="button" 
                            onclick="addPrestation()" 
                            class="inline-flex items-center gap-2 px-4 py-2 text-white rounded-lg transition-all duration-300 text-sm"
                            style="background: linear-gradient(to right, #3b82f6, #2563eb);"
                            onmouseover="this.style.background='linear-gradient(to right, #2563eb, #1d4ed8)'"
                            onmouseout="this.style.background='linear-gradient(to right, #3b82f6, #2563eb)'">
                        <i class="fas fa-plus"></i>
                        Ajouter Prestation
                    </button>
                </div>
                
                <div id="prestations-container">
                    <!-- Prestations will be added dynamically here -->
                </div>
            </div>

            <!-- Section 4: Valeurs Financières -->
            <div class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-2xl p-6 border border-yellow-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #6366f1, #8b5cf6);">
                        <i class="fas fa-coins text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold" style="color: #6366f1;">Valeurs Financières</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="form-group">
                        <label for="valeurs_des_produits" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Valeurs des Produits <span class="text-red-500">*</span></span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Valeur totale des produits"></i>
                        </label>
                        <input type="number" 
                               step="0.01"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="valeurs_des_produits" 
                               name="valeurs_des_produits" 
                               value="{{ old('valeurs_des_produits') }}"
                               required>
                        @error('valeurs_des_produits')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="valeur_des_prestations" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Valeur des Prestations <span class="text-red-500">*</span></span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Valeur totale des prestations"></i>
                        </label>
                        <input type="number" 
                               step="0.01"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="valeur_des_prestations" 
                               name="valeur_des_prestations" 
                               value="{{ old('valeur_des_prestations') }}"
                               required>
                        @error('valeur_des_prestations')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="redevances" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Redevances <span class="text-red-500">*</span></span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Montant des redevances"></i>
                        </label>
                        <input type="number" 
                               step="0.01"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="redevances" 
                               name="redevances" 
                               value="{{ old('redevances') }}"
                               required>
                        @error('redevances')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="taxes" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Taxes <span class="text-red-500">*</span></span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Montant des taxes"></i>
                        </label>
                        <input type="number" 
                               step="0.01"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="taxes" 
                               name="taxes" 
                               value="{{ old('taxes') }}"
                               required>
                        @error('taxes')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="total_avenant" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Total Avenant <span class="text-red-500">*</span></span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Total de l'avenant"></i>
                        </label>
                        <input type="number" 
                               step="0.01"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="total_avenant" 
                               name="total_avenant" 
                               value="{{ old('total_avenant') }}"
                               required>
                        @error('total_avenant')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Products Section -->
            <div class="bg-white rounded-2xl p-6 border border-purple-200">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: linear-gradient(to bottom right, #6366f1, #8b5cf6);">
                            <i class="fas fa-box text-white text-sm"></i>
                        </div>
                        <h4 class="text-lg font-bold" style="color: #6366f1;">Produits</h4>
                    </div>
                    <button type="button" 
                            onclick="addProduct()" 
                            class="inline-flex items-center gap-2 px-4 py-2 text-white rounded-lg transition-all duration-300 text-sm"
                            style="background: linear-gradient(to right, #6366f1, #8b5cf6);"
                            onmouseover="this.style.background='linear-gradient(to right, #4f46e5, #6366f1)'"
                            onmouseout="this.style.background='linear-gradient(to right, #6366f1, #8b5cf6)'">
                        <i class="fas fa-plus"></i>
                        Ajouter Produit
                    </button>
                </div>
                
                <div id="products-container">
                    <!-- Products will be added dynamically here -->
                </div>
            </div>



            <!-- Actions -->
            <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                <a href="{{ route('contracts.index', ['tab' => 'avenants']) }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300">
                    <i class="fas fa-times"></i>
                    <span>Annuler</span>
                </a>
                <button type="submit" 
                        class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-save"></i>
                    <span>Enregistrer</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let productCount = 0;
let prestationCount = 0;

// Add new product row
function addProduct() {
    productCount++;
    const container = document.getElementById('products-container');
    
    const products = @json($products ?? []);
    
    const productRow = document.createElement('div');
    productRow.className = 'product-row flex items-center gap-4 mb-4 p-4 bg-gray-50 rounded-xl border border-gray-200';
    
    let productOptions = '<option value="">Sélectionner un produit</option>';
    products.forEach(product => {
        productOptions += `<option value="${product.name}">${product.name}</option>`;
    });
    
    productRow.innerHTML = `
        <div class="flex-1">
            <select name="products[${productCount}][name]" 
                   class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400"
                   required>
                ${productOptions}
            </select>
        </div>
        <div class="w-32">
            <input type="number" 
                   name="products[${productCount}][quantity]" 
                   placeholder="Quantité" 
                   min="0.01" 
                   step="0.01"
                   value="1"
                   class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400"
                   required>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" 
                    onclick="removeProduct(this)" 
                    class="inline-flex items-center justify-center w-10 h-10 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-all duration-300">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    `;
    
    container.appendChild(productRow);
}

// Remove product row
function removeProduct(button) {
    const productRow = button.closest('.product-row');
    productRow.remove();
}

// Add new prestation row
function addPrestation() {
    prestationCount++;
    const container = document.getElementById('prestations-container');
    
    const prestations = @json($prestations ?? []);
    
    const prestationRow = document.createElement('div');
    prestationRow.className = 'prestation-row flex items-center gap-4 mb-4 p-4 bg-gray-50 rounded-xl border border-gray-200';
    
    let prestationOptions = '<option value="">Sélectionner une prestation</option>';
    prestations.forEach(prestation => {
        prestationOptions += `<option value="${prestation.name}">${prestation.name}</option>`;
    });

    prestationRow.innerHTML = `
        <div class="flex-1">
            <select name="prestations[${prestationCount}][name]" 
                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400"
                    required>
                ${prestationOptions}
            </select>
        </div>
        <div class="w-32">
            <input type="number" 
                   name="prestations[${prestationCount}][quantity]" 
                   placeholder="Quantité" 
                   min="1" 
                   value="1"
                   class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400"
                   required>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" 
                    onclick="removePrestation(this)" 
                    class="inline-flex items-center justify-center w-10 h-10 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-all duration-300">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    `;
    
    container.appendChild(prestationRow);
}

// Remove prestation row
function removePrestation(button) {
    const prestationRow = button.closest('.prestation-row');
    prestationRow.remove();
}

// Load contract data into avenant form
function loadContractData(contractId) {
    const select = document.getElementById('contact_id');
    if (!select || !contractId) {
        return;
    }
    
    const selectedOption = select.options[select.selectedIndex];
    if (!selectedOption || !selectedOption.value) {
        return;
    }
    
    // Load basic information
    const annee = selectedOption.getAttribute('data-annee');
    const coperativeId = selectedOption.getAttribute('data-coperative-id');
    const superficie = selectedOption.getAttribute('data-superficie');
    
    if (annee) document.getElementById('annee').value = annee;
    if (coperativeId) document.getElementById('coperative_id').value = coperativeId;
    if (superficie) document.getElementById('superficie').value = superficie;
    
    // Load prestations
    const gardiennageNbjour = selectedOption.getAttribute('data-gardiennage-nbjour');
    const gardiennageSuperficie = selectedOption.getAttribute('data-gardiennage-superficie');
    const gardiennageParcelle = selectedOption.getAttribute('data-gardiennage-parcelle');
    const preventionIncendiesNbjour = selectedOption.getAttribute('data-prevention-incendies-nbjour');
    const preventionIncendiesSuperficie = selectedOption.getAttribute('data-prevention-incendies-superficie');
    const preventionIncendiesParcelle = selectedOption.getAttribute('data-prevention-incendies-parcelle');
    const elagage = selectedOption.getAttribute('data-elagage');
    const eclaircie = selectedOption.getAttribute('data-eclaircie');
    const rajeunissementRomarin = selectedOption.getAttribute('data-rajeunissement-romarin');
    
    if (gardiennageNbjour) document.getElementById('gardiennage_nbjour').value = gardiennageNbjour;
    if (gardiennageSuperficie) document.getElementById('gardiennage_superficie').value = gardiennageSuperficie;
    if (gardiennageParcelle) document.getElementById('gardiennage_parcelle').value = gardiennageParcelle;
    if (preventionIncendiesNbjour) document.getElementById('prevention_incendies_nbjour').value = preventionIncendiesNbjour;
    if (preventionIncendiesSuperficie) document.getElementById('prevention_incendies_superficie').value = preventionIncendiesSuperficie;
    if (preventionIncendiesParcelle) document.getElementById('prevention_incendies_parcelle').value = preventionIncendiesParcelle;
    if (elagage) document.getElementById('elagage').value = elagage;
    if (eclaircie) document.getElementById('eclaircie').value = eclaircie;
    if (rajeunissementRomarin) document.getElementById('rajeunissement_romarin').value = rajeunissementRomarin;
    
    // Load products quantities
    const boM3 = selectedOption.getAttribute('data-bo-m3');
    const biM3 = selectedOption.getAttribute('data-bi-m3');
    const bfSt = selectedOption.getAttribute('data-bf-st');
    const taninT = selectedOption.getAttribute('data-tanin-t');
    const laurierSauce = selectedOption.getAttribute('data-laurier-sauce');
    const myrte = selectedOption.getAttribute('data-myrte');
    const callune = selectedOption.getAttribute('data-callune');
    const thym = selectedOption.getAttribute('data-thym');
    const bruyetre = selectedOption.getAttribute('data-bruyetre');
    const lichen = selectedOption.getAttribute('data-lichen');
    const tanin = selectedOption.getAttribute('data-tanin');
    const romarin = selectedOption.getAttribute('data-romarin');
    const liegeMale = selectedOption.getAttribute('data-liege-male');
    const liegeDeReproduction = selectedOption.getAttribute('data-liege-de-reproduction');
    const sauge = selectedOption.getAttribute('data-sauge');
    const lavande = selectedOption.getAttribute('data-lavande');
    const armoise = selectedOption.getAttribute('data-armoise');
    const origan = selectedOption.getAttribute('data-origan');
    const alfa = selectedOption.getAttribute('data-alfa');
    const lentisque = selectedOption.getAttribute('data-lentisque');
    const ciste = selectedOption.getAttribute('data-ciste');
    const fleurAcaciaT = selectedOption.getAttribute('data-fleur-acacia-t');
    
    if (boM3) document.getElementById('bo_m3').value = boM3;
    if (biM3) document.getElementById('bi_m3').value = biM3;
    if (bfSt) document.getElementById('bf_st').value = bfSt;
    if (taninT) document.getElementById('tanin_t').value = taninT;
    if (laurierSauce) document.getElementById('laurier_sauce').value = laurierSauce;
    if (myrte) document.getElementById('myrte').value = myrte;
    if (callune) document.getElementById('callune').value = callune;
    if (thym) document.getElementById('thym').value = thym;
    if (bruyetre) document.getElementById('bruyetre').value = bruyetre;
    if (lichen) document.getElementById('lichen').value = lichen;
    if (tanin) document.getElementById('tanin').value = tanin;
    if (romarin) document.getElementById('romarin').value = romarin;
    if (liegeMale) document.getElementById('liege_male').value = liegeMale;
    if (liegeDeReproduction) document.getElementById('liege_de_reproduction').value = liegeDeReproduction;
    if (sauge) document.getElementById('sauge').value = sauge;
    if (lavande) document.getElementById('lavande').value = lavande;
    if (armoise) document.getElementById('armoise').value = armoise;
    if (origan) document.getElementById('origan').value = origan;
    if (alfa) document.getElementById('alfa').value = alfa;
    if (lentisque) document.getElementById('lentisque').value = lentisque;
    if (ciste) document.getElementById('ciste').value = ciste;
    if (fleurAcaciaT) document.getElementById('fleur_acacia_t').value = fleurAcaciaT;
    
    // Load financial values
    const valeursDesProduits = selectedOption.getAttribute('data-valeurs-des-produits');
    const valeurDesPrestations = selectedOption.getAttribute('data-valeur-des-prestations');
    const redevances = selectedOption.getAttribute('data-redevances');
    const taxes = selectedOption.getAttribute('data-taxes');
    const totalAvenant = selectedOption.getAttribute('data-total-avenant');
    
    if (valeursDesProduits) document.getElementById('valeurs_des_produits').value = valeursDesProduits;
    if (valeurDesPrestations) document.getElementById('valeur_des_prestations').value = valeurDesPrestations;
    if (redevances) document.getElementById('redevances').value = redevances;
    if (taxes) document.getElementById('taxes').value = taxes;
    if (totalAvenant) document.getElementById('total_avenant').value = totalAvenant;
    
    // Load products and prestations from JSON
    const productsJson = selectedOption.getAttribute('data-products');
    const prestationsJson = selectedOption.getAttribute('data-prestations');
    
    // Clear existing products and prestations
    document.getElementById('products-container').innerHTML = '';
    document.getElementById('prestations-container').innerHTML = '';
    productCount = 0;
    prestationCount = 0;
    
    // Load products
    if (productsJson) {
        try {
            const products = JSON.parse(productsJson);
            const availableProducts = @json($products ?? []);
            
            products.forEach(product => {
                productCount++;
                const container = document.getElementById('products-container');
                const productRow = document.createElement('div');
                productRow.className = 'product-row flex items-center gap-4 mb-4 p-4 bg-gray-50 rounded-xl border border-gray-200';
                
                let productOptions = '<option value="">Sélectionner un produit</option>';
                const productName = product.name || '';
                availableProducts.forEach(prod => {
                    const selected = prod.name === productName ? 'selected' : '';
                    productOptions += `<option value="${prod.name}" ${selected}>${prod.name}</option>`;
                });
                
                productRow.innerHTML = `
                    <div class="flex-1">
                        <select name="products[${productCount}][name]" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400"
                               required>
                            ${productOptions}
                        </select>
                    </div>
                    <div class="w-32">
                        <input type="number" 
                               name="products[${productCount}][quantity]" 
                               value="${product.quantity || 1}"
                               placeholder="Quantité" 
                               min="0.01" 
                               step="0.01"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400"
                               required>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" 
                                onclick="removeProduct(this)" 
                                class="inline-flex items-center justify-center w-10 h-10 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-all duration-300">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                `;
                container.appendChild(productRow);
            });
        } catch (e) {
            console.error('Error parsing products JSON:', e);
        }
    }
    
    // Load prestations
    if (prestationsJson) {
        try {
            const prestations = JSON.parse(prestationsJson);
            prestations.forEach(prestation => {
                prestationCount++;
                const container = document.getElementById('prestations-container');
                const prestationRow = document.createElement('div');
                prestationRow.className = 'prestation-row flex items-center gap-4 mb-4 p-4 bg-gray-50 rounded-xl border border-gray-200';
                const prestationName = (prestation.name || '').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
                prestationRow.innerHTML = `
                    <div class="flex-1">
                        <input type="text" 
                               name="prestations[${prestationCount}][name]" 
                               value="${prestationName}"
                               placeholder="Nom de la prestation" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400"
                               required>
                    </div>
                    <div class="w-32">
                        <input type="number" 
                               name="prestations[${prestationCount}][quantity]" 
                               value="${prestation.quantity || 1}"
                               placeholder="Quantité" 
                               min="1" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400"
                               required>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" 
                                onclick="removePrestation(this)" 
                                class="inline-flex items-center justify-center w-10 h-10 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-all duration-300">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                `;
                container.appendChild(prestationRow);
            });
        } catch (e) {
            console.error('Error parsing prestations JSON:', e);
        }
    }
}

// Load contract data on page load if contract is preselected
document.addEventListener('DOMContentLoaded', function() {
    const contactSelect = document.getElementById('contact_id');
    if (contactSelect && contactSelect.value) {
        loadContractData(contactSelect.value);
    }
});
</script>
@endpush
@endsection
