@extends('layouts.app')

@section('title', 'Nouvel Article - DEFATP')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 bg-gradient-to-br rounded-2xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #059669, #047857);">
                <i class="fas fa-file-alt text-white text-2xl"></i>
            </div>
            <div>
                <h1 class="text-4xl font-bold bg-clip-text text-transparent" style="background: linear-gradient(to right, #059669, #047857); -webkit-background-clip: text; background-clip: text;">
                    Nouvel Article
                </h1>
                <p class="text-gray-600 text-lg mt-2">Créez un nouvel article forestier pour votre système</p>
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
                <p class="text-gray-600">Remplissez les informations pour créer un nouvel article forestier</p>
            </div>
        </div>

        <form action="{{ route('articles.store') }}" method="POST" id="articleForm" class="space-y-8" data-server-validation enctype="multipart/form-data">
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
                            <div class="step-label">Localisation</div>
                        </div>
                        <div class="step-line"></div>
                        <div class="step-indicator" data-step="3">
                            <div class="step-number">3</div>
                            <div class="step-label">Détails Techniques</div>
                        </div>
                        <div class="step-line"></div>
                        <div class="step-indicator" data-step="4">
                            <div class="step-number">4</div>
                            <div class="step-label">Plan du Situation</div>
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
                    <h3 class="text-xl font-bold" style="color: #059669;">Section 1: Informations de Base</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="form-group">
                        <label for="type" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Type <span class="text-red-500">*</span></span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Sélectionnez le type d'article (Appel d'Offre, Adjudication, Marche Negocié)"></i>
                        </label>
                        <select class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                                id="type" name="type" required onchange="toggleNumeroAdjudication()">
                            <option value="">Sélectionner un type</option>
                            <option value="appel_doffre" {{ old('type') == 'appel_doffre' ? 'selected' : '' }}>Appel d'Offre</option>
                            <option value="adjudication" {{ old('type') == 'adjudication' ? 'selected' : '' }}>Adjudication</option>
                            <option value="marche_negocié" {{ old('type') == 'marche_negocié' ? 'selected' : '' }}>Marche Negocié</option>
                        </select>
                        @error('type')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group" id="numero_adjudication_group" style="display: none;">
                        <label for="numero_adjudication" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Numéro d'Appel d'Offre</span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Numéro juridique de l'adjudication"></i>
                        </label>
                        <input type="number" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="numero_adjudication" name="numero_adjudication" 
                               value="{{ old('numero_adjudication') }}" 
                               placeholder="Numéro juridique">
                        @error('numero_adjudication')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="annee" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Année <span class="text-red-500">*</span></span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Année de l'article"></i>
                        </label>
                        <input type="number" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="annee" name="annee" value="{{ old('annee', date('Y')) }}" 
                               min="2000" max="2100" required>
                        @error('annee')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
                    <div class="form-group">
                        <label for="date_adjudication" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Date d'Adjudication <span class="text-red-500">*</span></span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Format: jj/mm/aaaa (ex: 01/01/2024)"></i>
                        </label>
                        <input type="date" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="date_adjudication" name="date_adjudication" 
                               value="{{ old('date_adjudication') }}" 
                               placeholder="jj/mm/aaaa"
                               required>
                        @error('date_adjudication')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="numero" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Numéro d'Article <span class="text-red-500">*</span></span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Numéro unique de l'article"></i>
                        </label>
                        <input type="number" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="numero" name="numero" value="{{ old('numero') }}" 
                               placeholder="Ex: ART001" required>
                        @error('numero')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="lot" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Lot</span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Numéro de lot"></i>
                        </label>
                        <input type="number" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="lot" name="lot" value="{{ old('lot') }}" 
                               min="0" placeholder="Numéro de lot">
                        @error('lot')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
            </div>
            <!-- End Step 1 -->

            <!-- Step 2: Localisation -->
            <div class="step-content hidden" data-step="2">
            <!-- Section 2: Localisation -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #059669, #047857);">
                        <i class="fas fa-map-marker-alt text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold" style="color: #059669;">Section 2: Localisation</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label for="localisation_ids" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Localisations (DRANEF - DPANEF - ENTITE) <span class="text-red-500">*</span></span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Sélectionnez une ou plusieurs localisations"></i>
                        </label>
                        <input type="text" placeholder="Rechercher..." class="form-input w-full mb-2 px-4 py-2 border border-gray-300 rounded-lg" onkeyup="filterSelectOptions(this, 'localisation_ids')">
                        <select multiple required
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 hover:border-gray-400" 
                                id="localisation_ids" name="localisation_ids[]">
                            @foreach($localisations as $localisation)
                                <option value="{{ $localisation->id }}" {{ collect(old('localisation_ids', []))->contains($localisation->id) ? 'selected' : '' }}>
                                    {{ $localisation->DRANEF }} - {{ $localisation->DPANEF }} - {{ $localisation->ENTITE }}
                                </option>
                            @endforeach
                        </select>
                        @error('localisation_ids')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="situation_administrative_ids" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Situation Administrative (commune - province) <span class="text-red-500">*</span></span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Sélectionnez une ou plusieurs situations administratives"></i>
                        </label>
                        <input type="text" placeholder="Rechercher..." class="form-input w-full mb-2 px-4 py-2 border border-gray-300 rounded-lg" onkeyup="filterSelectOptions(this, 'situation_administrative_ids')">
                        <select multiple required
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 hover:border-gray-400" 
                                id="situation_administrative_ids" name="situation_administrative_ids[]">
                            @foreach($situationAdministratives as $situation)
                                <option value="{{ $situation->id }}" {{ collect(old('situation_administrative_ids', []))->contains($situation->id) ? 'selected' : '' }}>
                                    {{ $situation->commune }} - {{ $situation->province }}
                                </option>
                            @endforeach
                        </select>
                        @error('situation_administrative_ids')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div class="form-group">
                        <label for="foret_ids" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Forêts <span class="text-red-500">*</span></span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Sélectionnez une ou plusieurs forêts"></i>
                        </label>
                        <input type="text" placeholder="Rechercher..." class="form-input w-full mb-2 px-4 py-2 border border-gray-300 rounded-lg" onkeyup="filterSelectOptions(this, 'foret_ids')">
                        <select multiple required
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 hover:border-gray-400" 
                                id="foret_ids" name="foret_ids[]">
                            @foreach($forets as $foret)
                                <option value="{{ $foret->id }}" {{ collect(old('foret_ids', []))->contains($foret->id) ? 'selected' : '' }}>
                                    {{ $foret->foret }}
                                </option>
                            @endforeach
                        </select>
                        @error('foret_ids')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="essence_ids" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Essences <span class="text-red-500">*</span></span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Sélectionnez une ou plusieurs essences"></i>
                        </label>
                        <input type="text" placeholder="Rechercher..." class="form-input w-full mb-2 px-4 py-2 border border-gray-300 rounded-lg" onkeyup="filterSelectOptions(this, 'essence_ids')">
                        <select multiple required
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 hover:border-gray-400" 
                                id="essence_ids" name="essence_ids[]">
                            @foreach($essences as $essence)
                                <option value="{{ $essence->id }}" {{ collect(old('essence_ids', []))->contains($essence->id) ? 'selected' : '' }}>
                                    {{ $essence->essence }}
                                </option>
                            @endforeach
                        </select>
                        @error('essence_ids')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div class="form-group">
                        <label for="nature_de_coupe_ids" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Natures de Coupe <span class="text-red-500">*</span></span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Sélectionnez une ou plusieurs natures de coupe"></i>
                        </label>
                        <input type="text" placeholder="Rechercher..." class="form-input w-full mb-2 px-4 py-2 border border-gray-300 rounded-lg" onkeyup="filterSelectOptions(this, 'nature_de_coupe_ids')">
                        <select multiple required
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 hover:border-gray-400" 
                                id="nature_de_coupe_ids" name="nature_de_coupe_ids[]">
                            @foreach($natureDeCoupes as $natureDeCoupe)
                                <option value="{{ $natureDeCoupe->id }}" {{ collect(old('nature_de_coupe_ids', []))->contains($natureDeCoupe->id) ? 'selected' : '' }}>
                                    {{ $natureDeCoupe->nature_de_coupe }}
                                </option>
                            @endforeach
                        </select>
                        @error('nature_de_coupe_ids')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="nature_juridique" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Nature Juridique</span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Nature juridique de l'article"></i>
                        </label>
                        <input type="text" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 hover:border-gray-400" 
                               id="nature_juridique" name="nature_juridique" value="{{ old('nature_juridique') }}" 
                               placeholder="Nature juridique">
                        @error('nature_juridique')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                    <div class="form-group">
                        <label for="parcelle" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Parcelle</span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Numéro de parcelle"></i>
                        </label>
                        <input type="number" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 hover:border-gray-400" 
                               id="parcelle" name="parcelle" value="{{ old('parcelle') }}" 
                               min="0" placeholder="Numéro de parcelle">
                        @error('parcelle')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <!-- <div class="form-group">
                        <label for="lat" class="block text-sm font-semibold text-gray-700 mb-2">
                            Latitude
                        </label>
                        <input type="text" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 hover:border-gray-400" 
                               id="lat" name="lat" value="{{ old('lat') }}" 
                               placeholder="Latitude">
                        @error('lat')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="log" class="block text-sm font-semibold text-gray-700 mb-2">
                            Longitude
                        </label>
                        <input type="text" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 hover:border-gray-400" 
                               id="log" name="log" value="{{ old('log') }}" 
                               placeholder="Longitude">
                        @error('log')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div> -->
                </div>
            </div>
            </div>
            <!-- End Step 2 -->

            <!-- Step 3: Détails Techniques -->
            <div class="step-content hidden" data-step="3">
            <!-- Section 3: Détails Techniques -->
            <div class="bg-gradient-to-r from-purple-50 to-violet-50 rounded-2xl p-6 border border-purple-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #059669, #047857);">
                        <i class="fas fa-cogs text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold" style="color: #059669;">Section 3: Détails Techniques</h3>
                </div>
                
                <!-- Technical Measurements -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="form-group">
                        <label for="superficie" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Superficie</span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Superficie en hectares"></i>
                        </label>
                        <input type="number" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400" 
                               id="superficie" name="superficie" value="{{ old('superficie') }}" 
                               min="0" step="0.01" placeholder="Superficie en ha">
                        @error('superficie')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="fourniture_mise_charge" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Fourniture mise en charge</span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Fourniture mise en charge"></i>
                        </label>
                        <input type="number" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400" 
                               id="fourniture_mise_charge" name="fourniture_mise_charge" value="{{ old('fourniture_mise_charge') }}" 
                               min="0" step="0.01" placeholder="Fourniture mise en charge">
                        @error('fourniture_mise_charge')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
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
            </div>
            <!-- End Step 3 -->

            <!-- Step 4: Plan du Situation -->
            <div class="step-content hidden" data-step="4">
            <!-- Section 4: Plan du Situation -->
            <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-2xl p-6 border border-purple-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #059669, #047857);">
                        <i class="fas fa-map-marked-alt text-white text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold" style="color: #059669;">Plan du Situation</h3>
                        <p class="text-gray-600">Importez un fichier Excel avec les coordonnées des emplacements</p>
                    </div>
                </div>

                <div class="bg-white/60 backdrop-blur-sm rounded-xl p-6 border border-white/40">
                    <div class="mb-4">
                        <label for="locations_file" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Fichier Excel des Emplacements</span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Fichier Excel contenant les coordonnées des emplacements (colonnes: mat, x, y)"></i>
                        </label>
                        <input type="file" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400" 
                               id="locations_file" name="locations_file" 
                               accept=".xlsx,.xls,.csv">
                        <p class="text-sm text-gray-500 mt-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            Format requis: Colonnes "mat", "x", "y" (matériel, coordonnée X, coordonnée Y)
                        </p>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="font-semibold text-blue-800 mb-2">
                            <i class="fas fa-file-excel mr-2"></i>Format du fichier Excel
                        </h4>
                        <div class="text-sm text-blue-700">
                            <p class="mb-2">Votre fichier Excel doit contenir les colonnes suivantes :</p>
                            <ul class="list-disc list-inside space-y-1">
                                <li><strong>mat</strong> : Code matériel ou référence (optionnel)</li>
                                <li><strong>x</strong> : Coordonnée X (optionnel)</li>
                                <li><strong>y</strong> : Coordonnée Y (optionnel)</li>
                            </ul>
                            <p class="mt-2 text-xs">
                                <i class="fas fa-lightbulb mr-1"></i>
                                Toutes les colonnes sont optionnelles, mais au moins une doit être remplie.
                            </p>
                        </div>
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
                    <a href="{{ route('articles.index') }}" 
                       class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300">
                        <i class="fas fa-times"></i>
                        <span>Annuler</span>
                    </a>
                    <button type="button" 
                            id="saveDraftBtn"
                            onclick="saveAsDraft()"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-gray-600 to-gray-700 text-white rounded-xl hover:from-gray-700 hover:to-gray-800 transition-all duration-300 transform hover:scale-105 shadow-lg">
                        <i class="fas fa-file-alt"></i>
                        <span>Enregistrer comme brouillon</span>
                    </button>
                    <button type="button" 
                            id="nextBtn" 
                            onclick="changeStep(1)"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-lg"
                            style="display: inline-flex !important; visibility: visible !important; opacity: 1 !important;">
                        <span>Suivant</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                    <button type="submit" 
                            id="submitBtn"
                            class="hidden inline-flex items-center gap-3 px-6 py-3 text-white rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg"
                            style="background: linear-gradient(to right, #059669, #047857);"
                            onmouseover="this.style.background='linear-gradient(to right, #047857, #065f46)'"
                            onmouseout="this.style.background='linear-gradient(to right, #059669, #047857)'">
                        <i class="fas fa-save"></i>
                        <span class="font-semibold">Créer l'Article</span>
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

@push('styles')
<style>
    .form-input {
        background-image: none;
    }
    
    .form-input:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    /* Prevent duplicate error messages */
    .form-group .text-red-500:not(:first-child) {
        display: none;
    }
    
    
</style>
@endpush

@push('scripts')
<script>
let productCount = 0;

// Toggle numero_adjudication field based on type selection
function toggleNumeroAdjudication() {
    const typeSelect = document.getElementById('type');
    const numeroAdjudicationGroup = document.getElementById('numero_adjudication_group');
    
    if (typeSelect.value === 'appel_doffre') {
        numeroAdjudicationGroup.style.display = 'block';
    } else {
        numeroAdjudicationGroup.style.display = 'none';
        document.getElementById('numero_adjudication').value = '';
    }
}

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

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('articleForm');
    const submitBtn = document.getElementById('submitBtn');
    
    // Initialize numero_adjudication toggle
    toggleNumeroAdjudication();
    
    // Simple field validation
    function validateField(field) {
        const value = field.value.trim();
        
        // Remove existing validation classes
        field.classList.remove('is-valid', 'is-invalid');
        
        if (field.hasAttribute('required') && value === '') {
            field.classList.add('is-invalid');
            return false;
        }
        
        if (value !== '') {
            field.classList.add('is-valid');
        }
        
        return true;
    }
    
    // Real-time validation
    form.querySelectorAll('input, select, textarea').forEach(field => {
        field.addEventListener('blur', function() {
            validateField(this);
        });
        
        field.addEventListener('input', function() {
            if (this.classList.contains('is-invalid')) {
                validateField(this);
            }
        });
    });
    
    // Form validation
    form.addEventListener('submit', function(e) {
        const fields = form.querySelectorAll('input[required], select[required], textarea[required]');
        let isValid = true;
        
        fields.forEach(field => {
            let hasValue = false;
            
            if (field.type === 'checkbox' || field.type === 'radio') {
                hasValue = field.checked;
            } else if (field.multiple) {
                // For multi-select fields, check if at least one option is selected
                hasValue = field.selectedOptions.length > 0;
            } else {
                hasValue = field.value.trim() !== '';
            }
            
            if (!hasValue) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
                field.classList.add('is-valid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Veuillez remplir tous les champs obligatoires.');
        }
    });
    
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
});

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
        const draftsStr = localStorage.getItem('article_drafts');
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
        localStorage.setItem('article_drafts', JSON.stringify(drafts));
    } catch (e) {
        console.error('Error saving drafts:', e);
        alert('Erreur lors de l\'enregistrement des brouillons');
    }
}

// Collect form data
function collectFormData() {
    const form = document.getElementById('articleForm');
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
    
    const form = document.getElementById('articleForm');
    if (!form) return false;
    
    // Restore form fields
    Object.keys(draftData).forEach(key => {
        if (key === 'products' || key === 'currentStep' || key === 'savedAt' || key === 'name' || key === 'id') {
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
    
    // Restore dynamic products
    if (draftData.products && Array.isArray(draftData.products)) {
        // Clear existing products
        document.querySelectorAll('.product-row').forEach(row => row.remove());
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
    
    // Reset dropdown
    const dropdown = document.getElementById('draftsDropdown');
    if (dropdown) {
        dropdown.value = '';
    }
    
    return true;
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

// Delete draft by ID
function deleteDraft(draftId) {
    deleteDraftFromDropdown(draftId);
}

// Clear all drafts (for form submission)
function clearAllDrafts() {
    localStorage.removeItem('article_drafts');
    checkDraftsExists();
}

// Initialize form on page load
document.addEventListener('DOMContentLoaded', function() {
    // Check if drafts exist
    checkDraftsExists();
    
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
    
    // Initialize numero_adjudication toggle
    if (typeof toggleNumeroAdjudication === 'function') {
        toggleNumeroAdjudication();
    }
    
    // Handle form submission
    const articleForm = document.getElementById('articleForm');
    if (articleForm) {
        articleForm.addEventListener('submit', function(e) {
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