@extends('layouts.app')

@section('title', 'Nouveau Contrat - DEFATP')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 bg-gradient-to-br rounded-2xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #059669, #047857);">
                <i class="fas fa-handshake text-white text-2xl"></i>
            </div>
            <div>
                <h1 class="text-4xl font-bold bg-clip-text text-transparent" style="background: linear-gradient(to right, #059669, #047857); -webkit-background-clip: text; background-clip: text;">
                    Nouveau Contrat
                </h1>
                <p class="text-gray-600 text-lg mt-2">Créez un nouveau contrat de partenariat</p>
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

    <!-- Drafts Available Indicator -->
    <div id="draftsAvailableIndicator" class="hidden bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-4 rounded-lg mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <i class="fas fa-folder-open text-2xl"></i>
                <div>
                    <div class="font-semibold">Brouillons disponibles</div>
                    <div class="text-sm" id="draftsCount">0 brouillon(s) sauvegardé(s)</div>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" 
                        onclick="showDraftsModal()" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                    <i class="fas fa-list mr-1"></i> Gérer les brouillons
                </button>
            </div>
        </div>
    </div>

    <!-- Create Form -->
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #059669, #047857);">
                <i class="fas fa-plus text-white text-xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold" style="color: #059669;">Formulaire de création</h2>
                <p class="text-gray-600">Remplissez les informations pour créer un nouveau contrat</p>
            </div>
        </div>

        <form action="{{ route('contracts.store') }}" method="POST" id="contractForm" class="space-y-8">
            @csrf
            
            <!-- Progress Bar -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <div class="step-indicator active" data-step="1">
                            <div class="step-number">1</div>
                            <div class="step-label">Informations de Base</div>
                        </div>
                        <div class="step-line"></div>
                        <div class="step-indicator" data-step="2">
                            <div class="step-number">2</div>
                            <div class="step-label">Prestations</div>
                        </div>
                        <div class="step-line"></div>
                        <div class="step-indicator" data-step="3">
                            <div class="step-number">3</div>
                            <div class="step-label">Produits</div>
                        </div>
                        <div class="step-line"></div>
                        <div class="step-indicator" data-step="4">
                            <div class="step-number">4</div>
                            <div class="step-label">Valeurs Financières</div>
                        </div>
                    </div>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-gradient-to-r from-green-500 to-emerald-500 h-2 rounded-full transition-all duration-300" id="progress-bar" style="width: 25%"></div>
                </div>
            </div>
            
            <!-- Step 1: Informations de Base -->
            <div class="step-content" data-step="1">
            <!-- Section 1: Informations de Base -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #059669, #047857);">
                        <i class="fas fa-info-circle text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold" style="color: #059669;">Informations de Base</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="form-group">
                        <label for="annee" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Année <span class="text-red-500">*</span></span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Année du contrat"></i>
                        </label>
                        <input type="number" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="annee" 
                               name="annee" 
                               value="{{ old('annee') ?? date('Y') }}"
                               required>
                        @error('annee')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="contarct" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Contrat <span class="text-red-500">*</span></span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Numéro du contrat"></i>
                        </label>
                        <input type="number" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="contarct" 
                               name="contarct" 
                               value="{{ old('contarct') }}"
                               required>
                        @error('contarct')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="date" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Date</span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Date du contrat"></i>
                        </label>
                        <input type="date" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="date" 
                               name="date" 
                               value="{{ old('date') }}">
                        @error('date')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="localisation_id" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Localisation (DRANEF - DPANEF - ENTITE) <span class="text-red-500">*</span></span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Sélectionnez la localisation du contrat"></i>
                        </label>
                        <select class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                                id="localisation_id" 
                                name="localisation_id" 
                                required>
                            <option value="">Sélectionner une localisation</option>
                            @foreach($localisations as $localisation)
                                <option value="{{ $localisation->id }}" {{ old('localisation_id') == $localisation->id ? 'selected' : '' }}>
                                    {{ $localisation->DRANEF }} - {{ $localisation->DPANEF }} - {{ $localisation->ENTITE }}
                                </option>
                            @endforeach
                        </select>
                        @error('localisation_id')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="situation_administrative_id" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Situation Administrative (commune - province) <span class="text-red-500">*</span></span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Sélectionnez la situation administrative"></i>
                        </label>
                        <select class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                                id="situation_administrative_id" 
                                name="situation_administrative_id" 
                                required>
                            <option value="">Sélectionner une situation</option>
                            @foreach($situationAdministratives as $situation)
                                <option value="{{ $situation->id }}" {{ old('situation_administrative_id') == $situation->id ? 'selected' : '' }}>
                                    {{ $situation->commune }} - {{ $situation->province }}
                                </option>
                            @endforeach
                        </select>
                        @error('situation_administrative_id')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="forets" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Forêts <span class="text-red-500">*</span></span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Sélectionnez une ou plusieurs forêts"></i>
                        </label>
                        <input type="text" placeholder="Rechercher..." class="form-input w-full mb-2 px-4 py-2 border border-gray-300 rounded-lg" onkeyup="filterSelectOptions(this, 'forets')">
                        <select class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                                id="forets" 
                                name="forets[]" 
                                multiple
                                required>
                            @foreach($forets as $foret)
                                <option value="{{ $foret->id }}" {{ in_array($foret->id, old('forets', [])) ? 'selected' : '' }}>
                                    {{ $foret->foret }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Maintenez Ctrl (ou Cmd sur Mac) pour sélectionner plusieurs forêts</p>
                        @error('forets')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                        @error('forets.*')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="coperative_id" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Coopérative <span class="text-red-500">*</span></span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Sélectionnez la coopérative"></i>
                        </label>
                        <select class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                                id="coperative_id" 
                                name="coperative_id" 
                                required
                                onchange="showCooperativeInfo(this.value)">
                            <option value="">Sélectionner une coopérative</option>
                            @foreach($coperatives as $coperative)
                                <option value="{{ $coperative->id }}" 
                                        data-nom="{{ $coperative->nom }}"
                                        data-vocation="{{ $coperative->vocation ? $coperative->vocation->name : 'N/A' }}"
                                        data-nombre-membres="{{ $coperative->nombre_membres }}"
                                        data-nombre-coperatives="{{ $coperative->nombre_coperatives }}"
                                        {{ old('coperative_id') == $coperative->id ? 'selected' : '' }}>
                                    {{ $coperative->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('coperative_id')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="especes" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Espèces <span class="text-red-500">*</span></span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Sélectionnez une ou plusieurs espèces"></i>
                        </label>
                        <input type="text" placeholder="Rechercher..." class="form-input w-full mb-2 px-4 py-2 border border-gray-300 rounded-lg" onkeyup="filterSelectOptions(this, 'especes')">
                        <select class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                                id="especes" 
                                name="especes[]" 
                                multiple
                                required>
                            @foreach($especes as $espece)
                                <option value="{{ $espece->id }}" {{ in_array($espece->id, old('especes', [])) ? 'selected' : '' }}>
                                    {{ $espece->name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Maintenez Ctrl (ou Cmd sur Mac) pour sélectionner plusieurs espèces</p>
                        @error('especes')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                        @error('especes.*')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="superficie" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Superficie <span class="text-red-500">*</span></span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Superficie en hectares"></i>
                        </label>
                        <input type="number" 
                               step="0.01"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="superficie" 
                               name="superficie" 
                               value="{{ old('superficie') }}"
                               required>
                        @error('superficie')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <!-- Section: Coopérative Informations (Conditional) -->
                <div id="cooperative-info-section" class="hidden bg-gradient-to-r from-cyan-50 to-blue-50 rounded-2xl p-6 border border-cyan-200 mt-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #06b6d4, #0891b2);">
                            <i class="fas fa-users-cog text-white"></i>
                        </div>
                        <h3 class="text-xl font-bold" style="color: #06b6d4;">Informations de la Coopérative</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-500 mb-1">Nom</label>
                            <div class="text-lg font-medium text-gray-900" id="cooperative-nom">-</div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-500 mb-1">Vocation</label>
                            <div class="text-lg font-medium text-gray-900" id="cooperative-vocation">-</div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-500 mb-1">Nombre de Membres</label>
                            <div class="text-lg font-medium text-gray-900" id="cooperative-nombre-membres">-</div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-500 mb-1">Nombre de Coopératives</label>
                            <div class="text-lg font-medium text-gray-900" id="cooperative-nombre-coperatives">-</div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
            <!-- End Step 1 -->

            <!-- Step 2: Informations Complémentaires -->
            <div class="step-content hidden" data-step="2">
            <!-- Section 2: Informations Complémentaires -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #059669, #047857);">
                        <i class="fas fa-info-circle text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold" style="color: #059669;">Prestations</h3>
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
                        <input type="text" 
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
                        <input type="text" 
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
                        <input type="text" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="rajeunissement_romarin" 
                               name="rajeunissement_romarin" 
                               value="{{ old('rajeunissement_romarin') }}">
                    </div>
                </div>
            </div>

            <!-- Prestations Section (Dynamic) -->
            <div class="bg-white rounded-2xl p-6 border border-blue-200 mt-6">
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
            </div>
            <!-- End Step 2 -->

            <!-- Step 3: Prestations & Produits -->
            <div class="step-content hidden" data-step="3">
            <!-- Section 3: Produits -->
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-2xl p-6 border border-purple-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #059669, #047857);">
                        <i class="fas fa-boxes text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold" style="color: #059669;">Produits</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="form-group">
                        <label for="bo_m3" class="block text-sm font-semibold text-gray-700 mb-2">BO (m³)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="bo_m3" 
                               name="bo_m3" 
                               value="{{ old('bo_m3') }}">
                    </div>

                    <div class="form-group">
                        <label for="bi_m3" class="block text-sm font-semibold text-gray-700 mb-2">BI (m³)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="bi_m3" 
                               name="bi_m3" 
                               value="{{ old('bi_m3') }}">
                    </div>

                    <div class="form-group">
                        <label for="bf_st" class="block text-sm font-semibold text-gray-700 mb-2">BF (st)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="bf_st" 
                               name="bf_st" 
                               value="{{ old('bf_st') }}">
                    </div>

                    <div class="form-group">
                        <label for="tanin_t" class="block text-sm font-semibold text-gray-700 mb-2">Tanin (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="tanin_t" 
                               name="tanin_t" 
                               value="{{ old('tanin_t') }}">
                    </div>

                    <div class="form-group">
                        <label for="fleur_acacia_t" class="block text-sm font-semibold text-gray-700 mb-2">Fleur Acacia (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="fleur_acacia_t" 
                               name="fleur_acacia_t" 
                               value="{{ old('fleur_acacia_t') }}">
                    </div>

                    <div class="form-group">
                        <label for="caroube_t" class="block text-sm font-semibold text-gray-700 mb-2">Caroube (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="caroube_t" 
                               name="caroube_t" 
                               value="{{ old('caroube_t') }}">
                    </div>

                    <div class="form-group">
                        <label for="romarin_t" class="block text-sm font-semibold text-gray-700 mb-2">Romarin (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="romarin_t" 
                               name="romarin_t" 
                               value="{{ old('romarin_t') }}">
                    </div>

                    <div class="form-group">
                        <label for="ps_t" class="block text-sm font-semibold text-gray-700 mb-2">PS (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="ps_t" 
                               name="ps_t" 
                               value="{{ old('ps_t') }}">
                    </div>

                    <div class="form-group">
                        <label for="liége_st" class="block text-sm font-semibold text-gray-700 mb-2">Liège (st)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="liége_st" 
                               name="liége_st" 
                               value="{{ old('liége_st') }}">
                    </div>

                    <div class="form-group">
                        <label for="laurier_sauce" class="block text-sm font-semibold text-gray-700 mb-2">Laurier Sauce (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="laurier_sauce" 
                               name="laurier_sauce" 
                               value="{{ old('laurier_sauce') }}">
                    </div>

                    <div class="form-group">
                        <label for="myrte" class="block text-sm font-semibold text-gray-700 mb-2">Myrte (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="myrte" 
                               name="myrte" 
                               value="{{ old('myrte') }}">
                    </div>

                    <div class="form-group">
                        <label for="callune" class="block text-sm font-semibold text-gray-700 mb-2">Callune (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="callune" 
                               name="callune" 
                               value="{{ old('callune') }}">
                    </div>

                    <div class="form-group">
                        <label for="thym" class="block text-sm font-semibold text-gray-700 mb-2">Thym (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="thym" 
                               name="thym" 
                               value="{{ old('thym') }}">
                    </div>

                    <div class="form-group">
                        <label for="bruyetre" class="block text-sm font-semibold text-gray-700 mb-2">Bruyère (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="bruyetre" 
                               name="bruyetre" 
                               value="{{ old('bruyetre') }}">
                    </div>

                    <div class="form-group">
                        <label for="lichen" class="block text-sm font-semibold text-gray-700 mb-2">Lichen (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="lichen" 
                               name="lichen" 
                               value="{{ old('lichen') }}">
                    </div>

                    <div class="form-group">
                        <label for="tanin" class="block text-sm font-semibold text-gray-700 mb-2">Tanin (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="tanin" 
                               name="tanin" 
                               value="{{ old('tanin') }}">
                    </div>

                    <div class="form-group">
                        <label for="romarin" class="block text-sm font-semibold text-gray-700 mb-2">Romarin (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="romarin" 
                               name="romarin" 
                               value="{{ old('romarin') }}">
                    </div>

                    <div class="form-group">
                        <label for="liege_male" class="block text-sm font-semibold text-gray-700 mb-2">Liège Mâle (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="liege_male" 
                               name="liege_male" 
                               value="{{ old('liege_male') }}">
                    </div>

                    <div class="form-group">
                        <label for="liege_de_reproduction" class="block text-sm font-semibold text-gray-700 mb-2">Liège de Reproduction (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="liege_de_reproduction" 
                               name="liege_de_reproduction" 
                               value="{{ old('liege_de_reproduction') }}">
                    </div>

                    <div class="form-group">
                        <label for="sauge" class="block text-sm font-semibold text-gray-700 mb-2">Sauge (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="sauge" 
                               name="sauge" 
                               value="{{ old('sauge') }}">
                    </div>

                    <div class="form-group">
                        <label for="lavande" class="block text-sm font-semibold text-gray-700 mb-2">Lavande (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="lavande" 
                               name="lavande" 
                               value="{{ old('lavande') }}">
                    </div>

                    <div class="form-group">
                        <label for="armoise" class="block text-sm font-semibold text-gray-700 mb-2">Armoise (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="armoise" 
                               name="armoise" 
                               value="{{ old('armoise') }}">
                    </div>

                    <div class="form-group">
                        <label for="origan" class="block text-sm font-semibold text-gray-700 mb-2">Origan (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="origan" 
                               name="origan" 
                               value="{{ old('origan') }}">
                    </div>

                    <div class="form-group">
                        <label for="alfa" class="block text-sm font-semibold text-gray-700 mb-2">Alfa (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="alfa" 
                               name="alfa" 
                               value="{{ old('alfa') }}">
                    </div>

                    <div class="form-group">
                        <label for="lentisque" class="block text-sm font-semibold text-gray-700 mb-2">Lentisque (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="lentisque" 
                               name="lentisque" 
                               value="{{ old('lentisque') }}">
                    </div>

                    <div class="form-group">
                        <label for="ciste" class="block text-sm font-semibold text-gray-700 mb-2">Ciste (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="ciste" 
                               name="ciste" 
                               value="{{ old('ciste') }}">
                    </div>
                </div>
            </div>

            <!-- Products Section -->
            <div class="bg-white rounded-2xl p-6 border border-purple-200">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: linear-gradient(to bottom right, #059669, #047857);">
                            <i class="fas fa-box text-white text-sm"></i>
                        </div>
                        <h4 class="text-lg font-bold" style="color: #059669;">Produits</h4>
                    </div>
                    <button type="button" 
                            onclick="addProduct()" 
                            class="inline-flex items-center gap-2 px-4 py-2 text-white rounded-lg transition-all duration-300 text-sm"
                            style="background: linear-gradient(to right, #059669, #047857);"
                            onmouseover="this.style.background='linear-gradient(to right, #047857, #065f46)'"
                            onmouseout="this.style.background='linear-gradient(to right, #059669, #047857)'">
                        <i class="fas fa-plus"></i>
                        Ajouter Produit
                    </button>
                </div>
                
                <div id="products-container">
                    <!-- Products will be added dynamically here -->
                </div>
            </div>
            </div>
            <!-- End Step 3 -->

            <!-- Step 4: Valeurs Financières -->
            <div class="step-content hidden" data-step="4">
            <!-- Section 3: Valeurs Financières -->
            <div class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-2xl p-6 border border-yellow-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #059669, #047857);">
                        <i class="fas fa-coins text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold" style="color: #059669;">Valeurs Financières</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="form-group">
                        <label for="valeurs_des_produits" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Valeurs des Produits <span class="text-red-500">*</span></span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Valeur totale des produits"></i>
                        </label>
                        <input type="text" 
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
                        <input type="text" 
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
                        <input type="text" 
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
                        <input type="text" 
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
                            <span>Total contract <span class="text-red-500">*</span></span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Total du contrat"></i>
                        </label>
                        <input type="text" 
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
            </div>
            <!-- End Step 4 -->

            <!-- Navigation Buttons -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200 mt-8 sticky bottom-0 bg-white pb-4 z-10">
                <div>
                    <button type="button" 
                            id="prevBtn" 
                            onclick="changeStep(-1)"
                            class="hidden inline-flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300">
                        <i class="fas fa-arrow-left"></i>
                        <span>Précédent</span>
                    </button>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('contracts.index') }}" 
                       class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300">
                        <i class="fas fa-times"></i>
                        <span>Annuler</span>
                    </a>
                    <button type="button" 
                            id="nextBtn" 
                            onclick="changeStep(1)"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-lg"
                            style="display: inline-flex !important; visibility: visible !important; opacity: 1 !important;">
                        <span>Suivant</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                    <button type="button" 
                            id="saveDraftBtn"
                            onclick="showSaveDraftModal()"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-gray-600 to-gray-700 text-white rounded-xl hover:from-gray-700 hover:to-gray-800 transition-all duration-300 transform hover:scale-105 shadow-lg">
                        <i class="fas fa-file-alt"></i>
                        <span>Enregistrer comme brouillon</span>
                    </button>
                    <button type="submit" 
                            id="submitBtn"
                            class="hidden inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                        <i class="fas fa-save"></i>
                        <span>Enregistrer</span>
                    </button>
                </div>
            </div>
            
            <!-- Draft Saved Indicator -->
            <div id="draftIndicator" class="hidden fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                <span>Brouillon enregistré avec succès</span>
            </div>
            
            <!-- Save Draft Modal -->
            <div id="saveDraftModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
                <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-bold text-gray-900">Enregistrer le brouillon</h3>
                        <button type="button" onclick="closeSaveDraftModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    <div class="mb-6">
                        <label for="draftName" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nom du brouillon
                        </label>
                        <input type="text" 
                               id="draftName" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Ex: Contrat Forêt X - 2024"
                               maxlength="100">
                        <p class="text-sm text-gray-500 mt-2">Donnez un nom descriptif à votre brouillon pour le retrouver facilement.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="button" 
                                onclick="closeSaveDraftModal()" 
                                class="flex-1 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors">
                            Annuler
                        </button>
                        <button type="button" 
                                onclick="saveDraftWithName()" 
                                class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all">
                            <i class="fas fa-save mr-2"></i> Enregistrer
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Manage Drafts Modal -->
            <div id="draftsModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
                <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-2xl w-full mx-4 max-h-[80vh] overflow-hidden flex flex-col">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-bold text-gray-900">Gérer les brouillons</h3>
                        <button type="button" onclick="closeDraftsModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    <div id="draftsList" class="flex-1 overflow-y-auto mb-6 space-y-3">
                        <!-- Drafts will be loaded here -->
                    </div>
                    <div class="flex items-center gap-3 pt-4 border-t border-gray-200">
                        <button type="button" 
                                onclick="closeDraftsModal()" 
                                class="flex-1 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors">
                            Fermer
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
let productCount = 0;
let prestationCount = 0;

// Add new product row
function addProduct() {
    productCount++;
    const container = document.getElementById('products-container');
    
    const productRow = document.createElement('div');
    productRow.className = 'product-row flex items-center gap-4 mb-4 p-4 bg-gray-50 rounded-xl border border-gray-200';
    productRow.innerHTML = `
        <div class="flex-1">
            <input type="text" 
                   name="products[${productCount}][name]" 
                   placeholder="Nom du produit" 
                   class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400"
                   required>
        </div>
        <div class="w-32">
            <input type="number" 
                   name="products[${productCount}][quantity]" 
                   placeholder="Quantité" 
                   min="1" 
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
    
    const prestationRow = document.createElement('div');
    prestationRow.className = 'prestation-row flex items-center gap-4 mb-4 p-4 bg-gray-50 rounded-xl border border-gray-200';
    prestationRow.innerHTML = `
        <div class="flex-1">
            <input type="text" 
                   name="prestations[${prestationCount}][name]" 
                   placeholder="Nom de la prestation" 
                   class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400"
                   required>
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

// Generic select filter
window.filterSelectOptions = function(inputEl, selectId) {
    const filter = inputEl.value.toLowerCase();
    const select = document.getElementById(selectId);
    if (!select) return;
    Array.from(select.options).forEach(function(opt) {
        const text = (opt.text || '').toLowerCase();
        const match = text.indexOf(filter) !== -1;
        opt.style.display = match ? '' : 'none';
    });
};

// Show cooperative information when selected
function showCooperativeInfo(coperativeId) {
    const section = document.getElementById('cooperative-info-section');
    const select = document.getElementById('coperative_id');
    
    if (!coperativeId || !select) {
        section.classList.add('hidden');
        return;
    }
    
    const selectedOption = select.options[select.selectedIndex];
    if (selectedOption && selectedOption.value) {
        document.getElementById('cooperative-nom').textContent = selectedOption.getAttribute('data-nom') || '-';
        document.getElementById('cooperative-vocation').textContent = selectedOption.getAttribute('data-vocation') || '-';
        document.getElementById('cooperative-nombre-membres').textContent = selectedOption.getAttribute('data-nombre-membres') || '0';
        document.getElementById('cooperative-nombre-coperatives').textContent = selectedOption.getAttribute('data-nombre-coperatives') || '0';
        section.classList.remove('hidden');
    } else {
        section.classList.add('hidden');
    }
}

// Multi-step form functionality
let currentStep = 1;
const totalSteps = 4;

function showStep(step) {
    // Hide all steps
    document.querySelectorAll('.step-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Show current step
    const currentStepContent = document.querySelector(`.step-content[data-step="${step}"]`);
    if (currentStepContent) {
        currentStepContent.classList.remove('hidden');
    }
    
    // Update step indicators
    document.querySelectorAll('.step-indicator').forEach((indicator, index) => {
        const stepNum = index + 1;
        if (stepNum < step) {
            indicator.classList.add('completed');
            indicator.classList.remove('active');
        } else if (stepNum === step) {
            indicator.classList.add('active');
            indicator.classList.remove('completed');
        } else {
            indicator.classList.remove('active', 'completed');
        }
    });
    
    // Update progress bar
    const progressBar = document.getElementById('progress-bar');
    if (progressBar) {
        const progress = (step / totalSteps) * 100;
        progressBar.style.width = progress + '%';
    }
    
    // Update navigation buttons
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');
    
    if (!prevBtn || !nextBtn || !submitBtn) {
        console.error('Navigation buttons not found', { prevBtn, nextBtn, submitBtn });
        return;
    }
    
    if (step === 1) {
        prevBtn.classList.add('hidden');
    } else {
        prevBtn.classList.remove('hidden');
    }
    
    if (step === totalSteps) {
        nextBtn.classList.add('hidden');
        nextBtn.style.display = 'none';
        submitBtn.classList.remove('hidden');
        submitBtn.style.display = 'inline-flex';
    } else {
        nextBtn.classList.remove('hidden');
        nextBtn.style.display = 'inline-flex';
        submitBtn.classList.add('hidden');
        submitBtn.style.display = 'none';
    }
    
    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function changeStep(direction) {
    const newStep = currentStep + direction;
    
    if (newStep < 1 || newStep > totalSteps) {
        return;
    }
    
    // Validate current step before moving
    if (direction > 0 && !validateStep(currentStep)) {
        return;
    }
    
    currentStep = newStep;
    showStep(currentStep);
}

function validateStep(step) {
    const stepContent = document.querySelector(`.step-content[data-step="${step}"]`);
    if (!stepContent) return true;
    
    const requiredFields = stepContent.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value || (field.type === 'checkbox' && !field.checked)) {
            isValid = false;
            field.classList.add('border-red-500');
            
            // Remove error class after user interaction
            field.addEventListener('input', function() {
                this.classList.remove('border-red-500');
            }, { once: true });
        } else {
            field.classList.remove('border-red-500');
        }
    });
    
    if (!isValid) {
        alert('Veuillez remplir tous les champs obligatoires avant de continuer.');
    }
    
    return isValid;
}

// Get all drafts from localStorage
function getAllDrafts() {
    try {
        const draftsStr = localStorage.getItem('contract_drafts');
        if (!draftsStr) return [];
        return JSON.parse(draftsStr);
    } catch (e) {
        console.error('Error loading drafts:', e);
        return [];
    }
}

// Save all drafts to localStorage
function saveAllDrafts(drafts) {
    try {
        localStorage.setItem('contract_drafts', JSON.stringify(drafts));
    } catch (e) {
        console.error('Error saving drafts:', e);
        alert('Erreur lors de l\'enregistrement des brouillons');
    }
}

// Collect form data
function collectFormData() {
    const form = document.getElementById('contractForm');
    if (!form) return {};
    
    const draftData = {};
    
    // Get all form inputs
    const inputs = form.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        if (input.type === 'file') {
            // Skip file inputs
            return;
        }
        if (input.type === 'checkbox') {
            draftData[input.name] = input.checked ? input.value : '';
        } else if (input.type === 'radio') {
            if (input.checked) {
                draftData[input.name] = input.value;
            }
        } else if (input.tagName === 'SELECT' && input.multiple) {
            // Handle multiple select
            const selected = Array.from(input.selectedOptions).map(option => option.value);
            draftData[input.name] = selected;
        } else {
            draftData[input.name] = input.value || '';
        }
    });
    
    // Save dynamic prestations
    const prestations = [];
    document.querySelectorAll('.prestation-row').forEach((row, index) => {
        const nameInput = row.querySelector('input[name*="[name]"]');
        const quantityInput = row.querySelector('input[name*="[quantity]"]');
        if (nameInput && quantityInput) {
            prestations.push({
                name: nameInput.value,
                quantity: quantityInput.value
            });
        }
    });
    if (prestations.length > 0) {
        draftData.prestations = prestations;
    }
    
    // Save dynamic products
    const products = [];
    document.querySelectorAll('.product-row').forEach((row, index) => {
        const nameInput = row.querySelector('input[name*="[name]"]');
        const quantityInput = row.querySelector('input[name*="[quantity]"]');
        if (nameInput && quantityInput) {
            products.push({
                name: nameInput.value,
                quantity: quantityInput.value
            });
        }
    });
    if (products.length > 0) {
        draftData.products = products;
    }
    
    // Save current step
    draftData.currentStep = currentStep;
    
    return draftData;
}

// Show save draft modal
function showSaveDraftModal() {
    document.getElementById('saveDraftModal').classList.remove('hidden');
    document.getElementById('draftName').value = '';
    document.getElementById('draftName').focus();
}

// Close save draft modal
function closeSaveDraftModal() {
    document.getElementById('saveDraftModal').classList.add('hidden');
}

// Save draft with name
function saveDraftWithName() {
    const draftName = document.getElementById('draftName').value.trim();
    if (!draftName) {
        alert('Veuillez entrer un nom pour le brouillon');
        return;
    }
    
    const draftData = collectFormData();
    if (!draftData || Object.keys(draftData).length === 0) {
        alert('Aucune donnée à sauvegarder');
        return;
    }
    
    // Add metadata
    draftData.name = draftName;
    draftData.savedAt = new Date().toISOString();
    draftData.id = Date.now().toString();
    
    // Get all drafts
    const drafts = getAllDrafts();
    
    // Check if name already exists
    const existingIndex = drafts.findIndex(d => d.name === draftName);
    if (existingIndex !== -1) {
        if (!confirm('Un brouillon avec ce nom existe déjà. Voulez-vous le remplacer ?')) {
            return;
        }
        drafts[existingIndex] = draftData;
    } else {
        drafts.push(draftData);
    }
    
    // Save all drafts
    saveAllDrafts(drafts);
    
    // Show success indicator
    const indicator = document.getElementById('draftIndicator');
    if (indicator) {
        indicator.classList.remove('hidden');
        setTimeout(() => {
            indicator.classList.add('hidden');
        }, 3000);
    }
    
    // Close modal
    closeSaveDraftModal();
    
    // Update drafts indicator
    checkDraftsExists();
}

// Check if drafts exist
function checkDraftsExists() {
    const drafts = getAllDrafts();
    const indicator = document.getElementById('draftsAvailableIndicator');
    const countEl = document.getElementById('draftsCount');
    
    if (drafts.length > 0) {
        if (indicator) {
            indicator.classList.remove('hidden');
        }
        if (countEl) {
            countEl.textContent = drafts.length + ' brouillon(s) sauvegardé(s)';
        }
        return true;
    } else {
        if (indicator) {
            indicator.classList.add('hidden');
        }
        return false;
    }
}

// Load draft from localStorage
// This function is now replaced by loadDraft(draftId) above

// Delete draft by ID
function deleteDraft(draftId) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer ce brouillon ?')) {
        return;
    }
    
    const drafts = getAllDrafts();
    const filteredDrafts = drafts.filter(d => d.id !== draftId);
    saveAllDrafts(filteredDrafts);
    
    // Refresh drafts list
    loadDraftsList();
    checkDraftsExists();
}

// Show drafts modal
function showDraftsModal() {
    document.getElementById('draftsModal').classList.remove('hidden');
    loadDraftsList();
}

// Close drafts modal
function closeDraftsModal() {
    document.getElementById('draftsModal').classList.add('hidden');
}

// Load and display drafts list
function loadDraftsList() {
    const drafts = getAllDrafts();
    const draftsList = document.getElementById('draftsList');
    
    if (!draftsList) return;
    
    if (drafts.length === 0) {
        draftsList.innerHTML = `
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-folder-open text-4xl mb-4"></i>
                <p>Aucun brouillon sauvegardé</p>
            </div>
        `;
        return;
    }
    
    // Sort by date (newest first)
    drafts.sort((a, b) => new Date(b.savedAt) - new Date(a.savedAt));
    
    draftsList.innerHTML = drafts.map(draft => {
        const savedDate = new Date(draft.savedAt);
        const dateStr = savedDate.toLocaleString('fr-FR', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
        
        return `
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 hover:border-blue-300 transition-colors">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-900 mb-1">${escapeHtml(draft.name || 'Brouillon sans nom')}</h4>
                        <p class="text-sm text-gray-500">${dateStr}</p>
                        ${draft.currentStep ? `<p class="text-xs text-gray-400 mt-1">Étape ${draft.currentStep}/4</p>` : ''}
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" 
                                onclick="loadDraft('${draft.id}')" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                            <i class="fas fa-download mr-1"></i> Charger
                        </button>
                        <button type="button" 
                                onclick="deleteDraft('${draft.id}')" 
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
    }).join('');
}

// Escape HTML to prevent XSS
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Clear all drafts (for form submission)
function clearAllDrafts() {
    localStorage.removeItem('contract_drafts');
    checkDraftsExists();
}

// Show cooperative info on page load if already selected
document.addEventListener('DOMContentLoaded', function() {
    // Check if draft exists
    checkDraftExists();
    
    // Try to load draft first (only if user wants to)
    // Don't auto-load, just show indicator
    
    if (!draftLoaded) {
        // Ensure next button is visible first
        const nextBtn = document.getElementById('nextBtn');
        if (nextBtn) {
            nextBtn.classList.remove('hidden');
            nextBtn.style.display = 'inline-flex';
            nextBtn.style.visibility = 'visible';
        }
        
        // Initialize form steps
        if (typeof showStep === 'function') {
            showStep(1);
        } else {
            console.error('showStep function not found');
        }
        
        const select = document.getElementById('coperative_id');
        if (select && select.value) {
            showCooperativeInfo(select.value);
        }
    }
    
    // Handle form submission
    const contractForm = document.getElementById('contractForm');
    if (contractForm) {
        contractForm.addEventListener('submit', function(e) {
            if (!validateStep(currentStep)) {
                e.preventDefault();
                return false;
            }
            // Clear all drafts on successful submission
            clearAllDrafts();
        });
    }
    
    // Double check next button visibility after a short delay
    setTimeout(function() {
        const nextBtnCheck = document.getElementById('nextBtn');
        if (nextBtnCheck) {
            nextBtnCheck.classList.remove('hidden');
            nextBtnCheck.style.display = 'inline-flex';
            nextBtnCheck.style.visibility = 'visible';
        }
    }, 200);
    
    // Close modals on outside click
    document.getElementById('saveDraftModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeSaveDraftModal();
        }
    });
    
    document.getElementById('draftsModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDraftsModal();
        }
    });
});

</script>

<style>
.step-indicator {
    display: flex;
    flex-direction: column;
    align-items: center;
    flex: 1;
    position: relative;
}

.step-number {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #e5e7eb;
    color: #6b7280;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    transition: all 0.3s ease;
    border: 2px solid #e5e7eb;
}

.step-indicator.active .step-number {
    background: linear-gradient(to bottom right, #059669, #047857);
    color: white;
    border-color: #059669;
    transform: scale(1.1);
}

.step-indicator.completed .step-number {
    background: linear-gradient(to bottom right, #10b981, #059669);
    color: white;
    border-color: #10b981;
}

.step-label {
    margin-top: 8px;
    font-size: 0.75rem;
    color: #6b7280;
    text-align: center;
    font-weight: 500;
}

.step-indicator.active .step-label {
    color: #059669;
    font-weight: 600;
}

.step-indicator.completed .step-label {
    color: #10b981;
}

.step-line {
    flex: 1;
    height: 2px;
    background: #e5e7eb;
    margin: 0 8px;
    margin-top: -20px;
    z-index: -1;
}

.step-content {
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

@media (max-width: 768px) {
    .step-indicator {
        flex: 0 0 auto;
    }
    
    .step-label {
        display: none;
    }
    
    .step-line {
        display: none;
    }
}
</style>
@endpush
