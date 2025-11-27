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

    <!-- Drafts Available Dropdown -->
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
                <select id="draftsDropdown" 
                        onchange="if(this.value) loadDraft(this.value)" 
                        class="px-4 py-2 bg-white border border-blue-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 min-w-[300px]">
                    <option value="">Sélectionner un brouillon...</option>
                </select>
                <button type="button" 
                        onclick="if(draftsDropdown.value) deleteDraftFromDropdown(draftsDropdown.value)" 
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm"
                        title="Supprimer le brouillon sélectionné">
                    <i class="fas fa-trash"></i>
                </button>
                <button type="button" 
                        onclick="clearAllDrafts()" 
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm"
                        title="Supprimer tous les brouillons">
                    <i class="fas fa-trash-alt mr-1"></i> Tout supprimer
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
                        <label for="essences" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Essences <span class="text-red-500">*</span></span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Sélectionnez une ou plusieurs essences"></i>
                        </label>
                        <input type="text" placeholder="Rechercher..." class="form-input w-full mb-2 px-4 py-2 border border-gray-300 rounded-lg" onkeyup="filterSelectOptions(this, 'essences')">
                        <select class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                                id="essences" 
                                name="essences[]" 
                                multiple
                                required>
                            @foreach($essences as $essence)
                                <option value="{{ $essence->id }}" {{ in_array($essence->id, old('essences', [])) ? 'selected' : '' }}>
                                    {{ $essence->essence }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Maintenez Ctrl (ou Cmd sur Mac) pour sélectionner plusieurs essences</p>
                        @error('essences')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                        @error('essences.*')
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
                            onclick="saveAsDraft()"
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

// Save as draft - auto-generate name with date and auto-increment number
function saveAsDraft() {
    const draftData = collectFormData();
    if (!draftData || Object.keys(draftData).length === 0) {
        alert('Aucune donnée à sauvegarder');
        return;
    }
    
    // Get all drafts
    const drafts = getAllDrafts();
    
    // Generate auto-increment number based on today's date
    const today = new Date();
    const dateStr = today.toISOString().split('T')[0]; // YYYY-MM-DD
    const todayDrafts = drafts.filter(d => {
        if (!d.savedAt) return false;
        const draftDate = new Date(d.savedAt).toISOString().split('T')[0];
        return draftDate === dateStr;
    });
    
    // Auto-increment number for today
    const nextNumber = todayDrafts.length + 1;
    
    // Generate name: "Brouillon YYYY-MM-DD #N"
    const draftName = `Brouillon ${dateStr} #${nextNumber}`;
    
    // Add metadata
    draftData.name = draftName;
    draftData.savedAt = today.toISOString();
    draftData.id = Date.now().toString() + '_' + Math.random().toString(36).substr(2, 9);
    
    // Add to drafts
    drafts.push(draftData);
    
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
    
    // Update drafts indicator and dropdown
    checkDraftsExists();
    updateDraftsDropdown();
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
        updateDraftsDropdown();
        return true;
    } else {
        if (indicator) {
            indicator.classList.add('hidden');
        }
        return false;
    }
}

// Update drafts dropdown
function updateDraftsDropdown() {
    const drafts = getAllDrafts();
    const dropdown = document.getElementById('draftsDropdown');
    if (!dropdown) return;
    
    // Sort by date (newest first)
    drafts.sort((a, b) => new Date(b.savedAt) - new Date(a.savedAt));
    
    // Clear existing options except the first one
    dropdown.innerHTML = '<option value="">Sélectionner un brouillon...</option>';
    
    // Add draft options
    drafts.forEach(draft => {
        const savedDate = new Date(draft.savedAt);
        const dateStr = savedDate.toLocaleDateString('fr-FR', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
        const option = document.createElement('option');
        option.value = draft.id;
        option.textContent = `${draft.name || 'Brouillon'} - ${dateStr}`;
        if (draft.currentStep) {
            option.textContent += ` (Étape ${draft.currentStep}/4)`;
        }
        dropdown.appendChild(option);
    });
}

// Load draft from localStorage
// Load draft by ID
function loadDraft(draftId) {
    const drafts = getAllDrafts();
    const draftData = drafts.find(d => d.id === draftId);
    
    if (!draftData) {
        alert('Brouillon introuvable');
        return false;
    }
    
    if (!confirm('Charger ce brouillon ? Les données actuelles seront remplacées.')) {
        return false;
    }
    
    const form = document.getElementById('contractForm');
    if (!form) return false;
    
    // Restore form fields
    Object.keys(draftData).forEach(key => {
        if (key === 'prestations' || key === 'products' || key === 'currentStep' || key === 'savedAt' || key === 'name' || key === 'id') {
            return; // Skip special fields
        }
        
        const input = form.querySelector(`[name="${key}"]`);
        if (!input) {
            // Try array notation
            if (Array.isArray(draftData[key])) {
                draftData[key].forEach(value => {
                    const arrayInput = form.querySelector(`[name="${key}[]"][value="${value}"]`);
                    if (arrayInput) {
                        arrayInput.selected = true;
                    }
                });
            }
            return;
        }
        
        if (input.type === 'checkbox') {
            input.checked = draftData[key] === input.value || draftData[key] === '1';
        } else if (input.type === 'radio') {
            if (input.value === draftData[key]) {
                input.checked = true;
            }
        } else if (input.tagName === 'SELECT' && input.multiple) {
            // Handle multiple select
            if (Array.isArray(draftData[key])) {
                Array.from(input.options).forEach(option => {
                    option.selected = draftData[key].includes(option.value);
                });
            }
        } else {
            input.value = draftData[key] || '';
        }
    });
    
    // Restore dynamic prestations
    if (draftData.prestations && Array.isArray(draftData.prestations)) {
        // Clear existing prestations
        document.querySelectorAll('.prestation-row').forEach(row => row.remove());
        prestationCount = 0;
        draftData.prestations.forEach(prestation => {
            addPrestation();
            const rows = document.querySelectorAll('.prestation-row');
            const lastRow = rows[rows.length - 1];
            if (lastRow) {
                const nameInput = lastRow.querySelector('input[name*="[name]"]');
                const quantityInput = lastRow.querySelector('input[name*="[quantity]"]');
                if (nameInput) nameInput.value = prestation.name || '';
                if (quantityInput) quantityInput.value = prestation.quantity || '';
            }
        });
    }
    
    // Restore dynamic products
    if (draftData.products && Array.isArray(draftData.products)) {
        // Clear existing products
        document.querySelectorAll('.product-row').forEach(row => row.remove());
        productCount = 0;
        draftData.products.forEach(product => {
            addProduct();
            const rows = document.querySelectorAll('.product-row');
            const lastRow = rows[rows.length - 1];
            if (lastRow) {
                const nameSelect = lastRow.querySelector('select[name*="[name]"]');
                const quantityInput = lastRow.querySelector('input[name*="[quantity]"]');
                if (nameSelect) nameSelect.value = product.name || '';
                if (quantityInput) quantityInput.value = product.quantity || '';
            }
        });
    }
    
    // Restore current step
    if (draftData.currentStep) {
        currentStep = draftData.currentStep;
        if (typeof showStep === 'function') {
            showStep(currentStep);
        }
    }
    
    // Show cooperative info if selected
    const coperativeId = form.querySelector('[name="coperative_id"]')?.value;
    if (coperativeId && typeof showCooperativeInfo === 'function') {
        showCooperativeInfo(coperativeId);
    }
    
    // Reset dropdown
    const dropdown = document.getElementById('draftsDropdown');
    if (dropdown) {
        dropdown.value = '';
    }
    
    return true;
}

// Delete draft by ID
function deleteDraft(draftId) {
    deleteDraftFromDropdown(draftId);
}

// Delete draft by ID (from dropdown)
function deleteDraftFromDropdown(draftId) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer ce brouillon ?')) {
        return;
    }
    
    const drafts = getAllDrafts();
    const filteredDrafts = drafts.filter(d => d.id !== draftId);
    saveAllDrafts(filteredDrafts);
    
    // Update dropdown
    updateDraftsDropdown();
    checkDraftsExists();
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
    
    // Modal event listeners are now handled by modal.js globally
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
