@extends('layouts.app')

@section('title', 'Modifier Contrat - DEFATP')

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
                    Modifier Contrat
                </h1>
                <p class="text-gray-600 text-lg mt-2">Modifiez les informations du contrat de partenariat</p>
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

    <!-- Edit Form -->
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #059669, #047857);">
                <i class="fas fa-edit text-white text-xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold" style="color: #059669;">Formulaire de modification</h2>
                <p class="text-gray-600">Modifiez les informations du contrat</p>
            </div>
        </div>

        <form action="{{ route('contracts.update', $contract) }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')
            
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
                        <label for="annee" class="block text-sm font-semibold text-gray-700 mb-2">
                            Année <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="annee" 
                               name="annee" 
                               value="{{ old('annee', $contract->annee) }}"
                               required>
                        @error('annee')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="contarct" class="block text-sm font-semibold text-gray-700 mb-2">
                            Contrat <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="contarct" 
                               name="contarct" 
                               value="{{ old('contarct', $contract->contarct) }}"
                               required>
                        @error('contarct')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="date" class="block text-sm font-semibold text-gray-700 mb-2">
                            Date
                        </label>
                        <input type="date" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="date" 
                               name="date" 
                               value="{{ old('date', $contract->date ? $contract->date->format('Y-m-d') : '') }}">
                        @error('date')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="localisation_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            Localisation <span class="text-red-500">*</span>
                        </label>
                        <select class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                                id="localisation_id" 
                                name="localisation_id" 
                                required>
                            <option value="">Sélectionner une localisation</option>
                            @foreach($localisations as $localisation)
                                <option value="{{ $localisation->id }}" {{ old('localisation_id', $contract->localisation_id) == $localisation->id ? 'selected' : '' }}>
                                    {{ $localisation->DRANEF }} - {{ $localisation->DPANEF }} - {{ $localisation->ENTITE }}
                                </option>
                            @endforeach
                        </select>
                        @error('localisation_id')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="situation_administrative_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            Situation Administrative <span class="text-red-500">*</span>
                        </label>
                        <select class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                                id="situation_administrative_id" 
                                name="situation_administrative_id" 
                                required>
                            <option value="">Sélectionner une situation</option>
                            @foreach($situationAdministratives as $situation)
                                <option value="{{ $situation->id }}" {{ old('situation_administrative_id', $contract->situation_administrative_id) == $situation->id ? 'selected' : '' }}>
                                    {{ $situation->commune }} - {{ $situation->province }}
                                </option>
                            @endforeach
                        </select>
                        @error('situation_administrative_id')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="forets" class="block text-sm font-semibold text-gray-700 mb-2">
                            Forêts <span class="text-red-500">*</span>
                        </label>
                        <input type="text" placeholder="Rechercher..." class="form-input w-full mb-2 px-4 py-2 border border-gray-300 rounded-lg" onkeyup="filterSelectOptions(this, 'forets')">
                        <select class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                                id="forets" 
                                name="forets[]" 
                                multiple
                                required>
                            @php
                                $selectedForets = old('forets', $contract->forets->pluck('id')->toArray());
                            @endphp
                            @foreach($forets as $foret)
                                <option value="{{ $foret->id }}" {{ in_array($foret->id, $selectedForets) ? 'selected' : '' }}>
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
                        <label for="coperative_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            Coopérative <span class="text-red-500">*</span>
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
                                        {{ old('coperative_id', $contract->coperative_id) == $coperative->id ? 'selected' : '' }}>
                                    {{ $coperative->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('coperative_id')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="especes" class="block text-sm font-semibold text-gray-700 mb-2">
                            Espèces <span class="text-red-500">*</span>
                        </label>
                        <input type="text" placeholder="Rechercher..." class="form-input w-full mb-2 px-4 py-2 border border-gray-300 rounded-lg" onkeyup="filterSelectOptions(this, 'especes')">
                        <select class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                                id="especes" 
                                name="especes[]" 
                                multiple
                                required>
                            @php
                                $selectedEspeces = old('especes', $contract->especes->pluck('id')->toArray());
                            @endphp
                            @foreach($especes as $espece)
                                <option value="{{ $espece->id }}" {{ in_array($espece->id, $selectedEspeces) ? 'selected' : '' }}>
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
                        <label for="superficie" class="block text-sm font-semibold text-gray-700 mb-2">Superficie <span class="text-red-500">*</span></label>
                        <input type="number" 
                               step="0.01"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="superficie" 
                               name="superficie" 
                               value="{{ old('superficie', $contract->superficie) }}"
                               required>
                        @error('superficie')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section: Coopérative Informations (Conditional) -->
            <div id="cooperative-info-section" class="hidden bg-gradient-to-r from-cyan-50 to-blue-50 rounded-2xl p-6 border border-cyan-200">
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

            <!-- Section 2: Prestations -->
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
                                       value="{{ old('gardiennage_nbjour', $contract->gardiennage_nbjour) }}"
                                       min="0">
                            </div>
                            <div>
                                <label for="gardiennage_superficie" class="block text-xs font-medium text-gray-600 mb-1">Superficie</label>
                                <input type="number" 
                                       step="1"
                                       class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                                       id="gardiennage_superficie" 
                                       name="gardiennage_superficie" 
                                       value="{{ old('gardiennage_superficie', $contract->gardiennage_superficie) }}"
                                       min="0">
                            </div>
                            <div>
                                <label for="gardiennage_parcelle" class="block text-xs font-medium text-gray-600 mb-1">Parcelle</label>
                                <input type="text" 
                                       class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                                       id="gardiennage_parcelle" 
                                       name="gardiennage_parcelle" 
                                       value="{{ old('gardiennage_parcelle', $contract->gardiennage_parcelle) }}">
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
                                       value="{{ old('prevention_incendies_nbjour', $contract->prevention_incendies_nbjour) }}"
                                       min="0">
                            </div>
                            <div>
                                <label for="prevention_incendies_superficie" class="block text-xs font-medium text-gray-600 mb-1">Superficie</label>
                                <input type="number" 
                                       step="1"
                                       class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                                       id="prevention_incendies_superficie" 
                                       name="prevention_incendies_superficie" 
                                       value="{{ old('prevention_incendies_superficie', $contract->prevention_incendies_superficie) }}"
                                       min="0">
                            </div>
                            <div>
                                <label for="prevention_incendies_parcelle" class="block text-xs font-medium text-gray-600 mb-1">Parcelle</label>
                                <input type="text" 
                                       class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                                       id="prevention_incendies_parcelle" 
                                       name="prevention_incendies_parcelle" 
                                       value="{{ old('prevention_incendies_parcelle', $contract->prevention_incendies_parcelle) }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="elagage" class="block text-sm font-semibold text-gray-700 mb-2">Elagage</label>
                        <input type="text" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="elagage" 
                               name="elagage" 
                               value="{{ old('elagage', $contract->elagage) }}">
                    </div>

                    <div class="form-group">
                        <label for="eclaircie" class="block text-sm font-semibold text-gray-700 mb-2">Eclaircie</label>
                        <input type="text" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="eclaircie" 
                               name="eclaircie" 
                               value="{{ old('eclaircie', $contract->eclaircie) }}">
                    </div>

                    <div class="form-group">
                        <label for="rajeunissement_romarin" class="block text-sm font-semibold text-gray-700 mb-2">Rajeunissement Romarin</label>
                        <input type="text" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="rajeunissement_romarin" 
                               name="rajeunissement_romarin" 
                               value="{{ old('rajeunissement_romarin', $contract->rajeunissement_romarin) }}">
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
                    @if($contract->prestations && $contract->prestations->count() > 0)
                        @foreach($contract->prestations as $index => $prestation)
                            <div class="prestation-row flex items-center gap-4 mb-4 p-4 bg-gray-50 rounded-xl border border-gray-200">
                                <div class="flex-1">
                                    <input type="text" 
                                           name="prestations[{{ $index }}][name]" 
                                           placeholder="Nom de la prestation" 
                                           value="{{ old("prestations.{$index}.name", $prestation->name) }}"
                                           class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400"
                                           required>
                                </div>
                                <div class="w-32">
                                    <input type="number" 
                                           name="prestations[{{ $index }}][quantity]" 
                                           placeholder="Quantité" 
                                           min="1" 
                                           value="{{ old("prestations.{$index}.quantity", $prestation->quantity) }}"
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
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

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
                        <label for="valeurs_des_produits" class="block text-sm font-semibold text-gray-700 mb-2">Valeurs des Produits <span class="text-red-500">*</span></label>
                        <input type="text" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="valeurs_des_produits" 
                               name="valeurs_des_produits" 
                               value="{{ old('valeurs_des_produits', $contract->valeurs_des_produits) }}"
                               required>
                        @error('valeurs_des_produits')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="valeur_des_prestations" class="block text-sm font-semibold text-gray-700 mb-2">Valeur des Prestations <span class="text-red-500">*</span></label>
                        <input type="text" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="valeur_des_prestations" 
                               name="valeur_des_prestations" 
                               value="{{ old('valeur_des_prestations', $contract->valeur_des_prestations) }}"
                               required>
                        @error('valeur_des_prestations')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="redevances" class="block text-sm font-semibold text-gray-700 mb-2">Redevances <span class="text-red-500">*</span></label>
                        <input type="text" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="redevances" 
                               name="redevances" 
                               value="{{ old('redevances', $contract->redevances) }}"
                               required>
                        @error('redevances')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="taxes" class="block text-sm font-semibold text-gray-700 mb-2">Taxes <span class="text-red-500">*</span></label>
                        <input type="text" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="taxes" 
                               name="taxes" 
                               value="{{ old('taxes', $contract->taxes) }}"
                               required>
                        @error('taxes')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="total_avenant" class="block text-sm font-semibold text-gray-700 mb-2">Total contract <span class="text-red-500">*</span></label>
                        <input type="text" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="total_avenant" 
                               name="total_avenant" 
                               value="{{ old('total_avenant', $contract->total_avenant) }}"
                               required>
                        @error('total_avenant')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

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
                               value="{{ old('bo_m3', $contract->bo_m3) }}">
                    </div>

                    <div class="form-group">
                        <label for="bi_m3" class="block text-sm font-semibold text-gray-700 mb-2">BI (m³)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="bi_m3" 
                               name="bi_m3" 
                               value="{{ old('bi_m3', $contract->bi_m3) }}">
                    </div>

                    <div class="form-group">
                        <label for="bf_st" class="block text-sm font-semibold text-gray-700 mb-2">BF (st)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="bf_st" 
                               name="bf_st" 
                               value="{{ old('bf_st', $contract->bf_st) }}">
                    </div>

                    <div class="form-group">
                        <label for="tanin_t" class="block text-sm font-semibold text-gray-700 mb-2">Tanin (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="tanin_t" 
                               name="tanin_t" 
                               value="{{ old('tanin_t', $contract->tanin_t) }}">
                    </div>

                    <div class="form-group">
                        <label for="fleur_acacia_t" class="block text-sm font-semibold text-gray-700 mb-2">Fleur Acacia (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="fleur_acacia_t" 
                               name="fleur_acacia_t" 
                               value="{{ old('fleur_acacia_t', $contract->fleur_acacia_t) }}">
                    </div>

                    <div class="form-group">
                        <label for="caroube_t" class="block text-sm font-semibold text-gray-700 mb-2">Caroube (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="caroube_t" 
                               name="caroube_t" 
                               value="{{ old('caroube_t', $contract->caroube_t) }}">
                    </div>

                    <div class="form-group">
                        <label for="romarin_t" class="block text-sm font-semibold text-gray-700 mb-2">Romarin (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="romarin_t" 
                               name="romarin_t" 
                               value="{{ old('romarin_t', $contract->romarin_t) }}">
                    </div>

                    <div class="form-group">
                        <label for="ps_t" class="block text-sm font-semibold text-gray-700 mb-2">PS (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="ps_t" 
                               name="ps_t" 
                               value="{{ old('ps_t', $contract->ps_t) }}">
                    </div>

                    <div class="form-group">
                        <label for="liége_st" class="block text-sm font-semibold text-gray-700 mb-2">Liège (st)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="liége_st" 
                               name="liége_st" 
                               value="{{ old('liége_st', $contract->liége_st) }}">
                    </div>

                    <div class="form-group">
                        <label for="laurier_sauce" class="block text-sm font-semibold text-gray-700 mb-2">Laurier Sauce (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="laurier_sauce" 
                               name="laurier_sauce" 
                               value="{{ old('laurier_sauce', $contract->laurier_sauce) }}">
                    </div>

                    <div class="form-group">
                        <label for="myrte" class="block text-sm font-semibold text-gray-700 mb-2">Myrte (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="myrte" 
                               name="myrte" 
                               value="{{ old('myrte', $contract->myrte) }}">
                    </div>

                    <div class="form-group">
                        <label for="callune" class="block text-sm font-semibold text-gray-700 mb-2">Callune (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="callune" 
                               name="callune" 
                               value="{{ old('callune', $contract->callune) }}">
                    </div>

                    <div class="form-group">
                        <label for="thym" class="block text-sm font-semibold text-gray-700 mb-2">Thym (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="thym" 
                               name="thym" 
                               value="{{ old('thym', $contract->thym) }}">
                    </div>

                    <div class="form-group">
                        <label for="bruyetre" class="block text-sm font-semibold text-gray-700 mb-2">Bruyère (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="bruyetre" 
                               name="bruyetre" 
                               value="{{ old('bruyetre', $contract->bruyetre) }}">
                    </div>

                    <div class="form-group">
                        <label for="lichen" class="block text-sm font-semibold text-gray-700 mb-2">Lichen (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="lichen" 
                               name="lichen" 
                               value="{{ old('lichen', $contract->lichen) }}">
                    </div>

                    <div class="form-group">
                        <label for="tanin" class="block text-sm font-semibold text-gray-700 mb-2">Tanin (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="tanin" 
                               name="tanin" 
                               value="{{ old('tanin', $contract->tanin) }}">
                    </div>

                    <div class="form-group">
                        <label for="romarin" class="block text-sm font-semibold text-gray-700 mb-2">Romarin (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="romarin" 
                               name="romarin" 
                               value="{{ old('romarin', $contract->romarin) }}">
                    </div>

                    <div class="form-group">
                        <label for="liege_male" class="block text-sm font-semibold text-gray-700 mb-2">Liège Mâle (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="liege_male" 
                               name="liege_male" 
                               value="{{ old('liege_male', $contract->liege_male) }}">
                    </div>

                    <div class="form-group">
                        <label for="liege_de_reproduction" class="block text-sm font-semibold text-gray-700 mb-2">Liège de Reproduction (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="liege_de_reproduction" 
                               name="liege_de_reproduction" 
                               value="{{ old('liege_de_reproduction', $contract->liege_de_reproduction) }}">
                    </div>

                    <div class="form-group">
                        <label for="sauge" class="block text-sm font-semibold text-gray-700 mb-2">Sauge (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="sauge" 
                               name="sauge" 
                               value="{{ old('sauge', $contract->sauge) }}">
                    </div>

                    <div class="form-group">
                        <label for="lavande" class="block text-sm font-semibold text-gray-700 mb-2">Lavande (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="lavande" 
                               name="lavande" 
                               value="{{ old('lavande', $contract->lavande) }}">
                    </div>

                    <div class="form-group">
                        <label for="armoise" class="block text-sm font-semibold text-gray-700 mb-2">Armoise (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="armoise" 
                               name="armoise" 
                               value="{{ old('armoise', $contract->armoise) }}">
                    </div>

                    <div class="form-group">
                        <label for="origan" class="block text-sm font-semibold text-gray-700 mb-2">Origan (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="origan" 
                               name="origan" 
                               value="{{ old('origan', $contract->origan) }}">
                    </div>

                    <div class="form-group">
                        <label for="alfa" class="block text-sm font-semibold text-gray-700 mb-2">Alfa (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="alfa" 
                               name="alfa" 
                               value="{{ old('alfa', $contract->alfa) }}">
                    </div>

                    <div class="form-group">
                        <label for="lentisque" class="block text-sm font-semibold text-gray-700 mb-2">Lentisque (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="lentisque" 
                               name="lentisque" 
                               value="{{ old('lentisque', $contract->lentisque) }}">
                    </div>

                    <div class="form-group">
                        <label for="ciste" class="block text-sm font-semibold text-gray-700 mb-2">Ciste (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="ciste" 
                               name="ciste" 
                               value="{{ old('ciste', $contract->ciste) }}">
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
                    @if($contract->products && $contract->products->count() > 0)
                        @foreach($contract->products as $index => $product)
                            <div class="product-row flex items-center gap-4 mb-4 p-4 bg-gray-50 rounded-xl border border-gray-200">
                                <div class="flex-1">
                                    <input type="text" 
                                           name="products[{{ $index }}][name]" 
                                           placeholder="Nom du produit" 
                                           value="{{ old("products.{$index}.name", $product->name) }}"
                                           class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400"
                                           required>
                                </div>
                                <div class="w-32">
                                    <input type="number" 
                                           name="products[{{ $index }}][quantity]" 
                                           placeholder="Quantité" 
                                           min="1" 
                                           value="{{ old("products.{$index}.quantity", $product->quantity) }}"
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
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Section 4: Résiliation -->
            <div class="bg-gradient-to-r from-red-50 to-pink-50 rounded-2xl p-6 border border-red-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #059669, #047857);">
                        <i class="fas fa-ban text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold" style="color: #059669;">Résiliation</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label for="resiliation" class="flex items-center gap-2">
                            <input type="checkbox" 
                                   id="resiliation" 
                                   name="resiliation" 
                                   value="1"
                                   {{ old('resiliation', $contract->resiliation) ? 'checked' : '' }}
                                   class="w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500">
                            <span class="text-sm font-semibold text-gray-700">Résilié</span>
                        </label>
                    </div>

                    <div class="form-group">
                        <label for="date_resiliation" class="block text-sm font-semibold text-gray-700 mb-2">
                            Date de Résiliation
                            <i class="fas fa-question-circle mx-1 text-gray-400" title="Format: jj/mm/aaaa (ex: 01/01/2024)"></i>
                        </label>
                        <input type="date" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="date_resiliation" 
                               name="date_resiliation" 
                               value="{{ old('date_resiliation', $contract->date_resiliation ? \Carbon\Carbon::parse($contract->date_resiliation)->format('Y-m-d') : '') }}"
                               placeholder="jj/mm/aaaa">
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                <a href="{{ route('contracts.index') }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300">
                    <i class="fas fa-times"></i>
                    <span>Annuler</span>
                </a>
                <button type="submit" 
                        class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-save"></i>
                    <span>Enregistrer</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
let productCount = {{ $contract->products ? $contract->products->count() : 0 }};
let prestationCount = {{ $contract->prestations ? $contract->prestations->count() : 0 }};

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

// Show cooperative info on page load if already selected
document.addEventListener('DOMContentLoaded', function() {
    const select = document.getElementById('coperative_id');
    if (select && select.value) {
        showCooperativeInfo(select.value);
    }
});

</script>
@endpush
