@extends('layouts.app')

@section('title', 'Modifier un Article')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #059669, #047857);">
                <i class="fas fa-file-alt text-white text-2xl"></i>
            </div>
            <div>
                <h1 class="text-4xl font-bold bg-clip-text text-transparent" style="background: linear-gradient(to right, #059669, #047857); -webkit-background-clip: text; background-clip: text;">
                    Modifier l'Article
                </h1>
                <p class="text-gray-600 text-lg mt-2">Modifiez les informations de l'article "{{ $article->numero }}"</p>
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

    <!-- Edit Form -->
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #059669, #047857);">
                <i class="fas fa-edit text-white text-xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold bg-clip-text text-transparent" style="background: linear-gradient(to right, #059669, #047857); -webkit-background-clip: text; background-clip: text;">Formulaire de modification</h2>
                <p class="text-gray-600">Modifiez les informations de l'article forestier</p>
            </div>
        </div>

        <form action="{{ route('articles.update', $article) }}" method="POST" id="articleForm" class="space-y-8" data-server-validation enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <!-- Section 1: Informations de Base -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-info-circle text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-blue-900">Section 1: Informations de Base</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="form-group">
                        <label for="type" class="block text-sm font-semibold text-gray-700 mb-2">
                            Type <span class="text-red-500">*</span>
                        </label>
                        <select class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                                id="type" name="type" required onchange="toggleNumeroAdjudication()">
                            <option value="">Sélectionner un type</option>
                            <option value="appel_doffre" {{ old('type', $article->type) == 'appel_doffre' ? 'selected' : '' }}>Appel d'Offre</option>
                            <option value="adjudication" {{ old('type', $article->type) == 'adjudication' ? 'selected' : '' }}>Adjudication</option>
                        </select>
                        @error('type')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group" id="numero_adjudication_group" style="display: none;">
                        <label for="numero_adjudication" class="block text-sm font-semibold text-gray-700 mb-2">
                            Numéro d'Adjudication
                        </label>
                        <input type="text" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="numero_adjudication" name="numero_adjudication" 
                               value="{{ old('numero_adjudication', $article->numero_adjudication) }}" 
                               placeholder="Numéro d'adjudication">
                        @error('numero_adjudication')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="annee" class="block text-sm font-semibold text-gray-700 mb-2">
                            Année <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="annee" name="annee" value="{{ old('annee', $article->annee) }}" 
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
                        <label for="date_adjudication" class="block text-sm font-semibold text-gray-700 mb-2">
                            Date d'Adjudication <span class="text-red-500">*</span>
                            <i class="fas fa-question-circle mx-1 text-gray-400" title="Format: jj/mm/aaaa (ex: 01/01/2024)"></i>
                        </label>
                        <input type="date" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="date_adjudication" name="date_adjudication" 
                               value="{{ old('date_adjudication', $article->date_adjudication) }}" 
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
                        <label for="lot" class="block text-sm font-semibold text-gray-700 mb-2">
                            Lot
                        </label>
                        <input type="number" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="lot" name="lot" value="{{ old('lot', $article->lot) }}" 
                               min="0" placeholder="Numéro de lot">
                        @error('lot')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="numero" class="block text-sm font-semibold text-gray-700 mb-2">
                            Numéro d'Article <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="numero" name="numero" value="{{ old('numero', $article->numero) }}" 
                               placeholder="Ex: ART001" required>
                        @error('numero')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 2: Localisation -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-map-marker-alt text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-green-900">Section 2: Localisation</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label for="localisation_ids" class="block text-sm font-semibold text-gray-700 mb-2">
                            Localisations <span class="text-red-500">*</span>
                        </label>
                        <input type="text" placeholder="Rechercher..." class="form-input w-full mb-2 px-4 py-2 border border-gray-300 rounded-lg" onkeyup="filterSelectOptions(this, 'localisation_ids')">
                        <select multiple
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 hover:border-gray-400" 
                                id="localisation_ids" name="localisation_ids[]">
                            @php($selectedLocalisations = old('localisation_ids', optional($article->localisations)->pluck('id')->toArray() ?? []))
                            @foreach($localisations as $localisation)
                                <option value="{{ $localisation->id }}" {{ in_array($localisation->id, $selectedLocalisations) ? 'selected' : '' }}>
                                    {{ $localisation->CODE }} - {{ $localisation->DRANEF }} - {{ $localisation->ENTITE }}
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
                        <label for="situation_administrative_ids" class="block text-sm font-semibold text-gray-700 mb-2">
                            Situations Administratives <span class="text-red-500">*</span>
                        </label>
                        <input type="text" placeholder="Rechercher..." class="form-input w-full mb-2 px-4 py-2 border border-gray-300 rounded-lg" onkeyup="filterSelectOptions(this, 'situation_administrative_ids')">
                        <select multiple
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 hover:border-gray-400" 
                                id="situation_administrative_ids" name="situation_administrative_ids[]">
                            @php($selectedSituations = old('situation_administrative_ids', optional($article->situationsAdministratives)->pluck('id')->toArray() ?? []))
                            @foreach($situationAdministratives as $situation)
                                <option value="{{ $situation->id }}" {{ in_array($situation->id, $selectedSituations) ? 'selected' : '' }}>
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
                        <label for="foret_ids" class="block text-sm font-semibold text-gray-700 mb-2">
                            Forêts <span class="text-red-500">*</span>
                        </label>
                        <input type="text" placeholder="Rechercher..." class="form-input w-full mb-2 px-4 py-2 border border-gray-300 rounded-lg" onkeyup="filterSelectOptions(this, 'foret_ids')">
                        <select multiple
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 hover:border-gray-400" 
                                id="foret_ids" name="foret_ids[]">
                            @php($selectedForets = old('foret_ids', optional($article->forets)->pluck('id')->toArray() ?? []))
                            @foreach($forets as $foret)
                                <option value="{{ $foret->id }}" {{ in_array($foret->id, $selectedForets) ? 'selected' : '' }}>
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
                        <label for="essence_ids" class="block text-sm font-semibold text-gray-700 mb-2">
                            Essences <span class="text-red-500">*</span>
                        </label>
                        <input type="text" placeholder="Rechercher..." class="form-input w-full mb-2 px-4 py-2 border border-gray-300 rounded-lg" onkeyup="filterSelectOptions(this, 'essence_ids')">
                        <select multiple
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 hover:border-gray-400" 
                                id="essence_ids" name="essence_ids[]">
                            @php($selectedEssences = old('essence_ids', optional($article->essences)->pluck('id')->toArray() ?? []))
                            @foreach($essences as $essence)
                                <option value="{{ $essence->id }}" {{ in_array($essence->id, $selectedEssences) ? 'selected' : '' }}>
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
                        <label for="nature_de_coupe_ids" class="block text-sm font-semibold text-gray-700 mb-2">
                            Natures de Coupe <span class="text-red-500">*</span>
                        </label>
                        <input type="text" placeholder="Rechercher..." class="form-input w-full mb-2 px-4 py-2 border border-gray-300 rounded-lg" onkeyup="filterSelectOptions(this, 'nature_de_coupe_ids')">
                        <select multiple
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 hover:border-gray-400" 
                                id="nature_de_coupe_ids" name="nature_de_coupe_ids[]">
                            @php($selectedNatures = old('nature_de_coupe_ids', optional($article->naturesDeCoupe)->pluck('id')->toArray() ?? []))
                            @foreach($natureDeCoupes as $natureDeCoupe)
                                <option value="{{ $natureDeCoupe->id }}" {{ in_array($natureDeCoupe->id, $selectedNatures) ? 'selected' : '' }}>
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
                        <label for="nature_juridique" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nature Juridique
                        </label>
                        <input type="text" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 hover:border-gray-400" 
                               id="nature_juridique" name="nature_juridique" value="{{ old('nature_juridique', $article->nature_juridique) }}" 
                               placeholder="Nature juridique">
                        @error('nature_juridique')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div class="form-group">
                        <label for="exploitant_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            Exploitant
                        </label>
                        <select class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 hover:border-gray-400" 
                                id="exploitant_id" name="exploitant_id">
                            <option value="">Sélectionner un exploitant</option>
                            @foreach($exploitants as $exploitant)
                                <option value="{{ $exploitant->id }}" {{ old('exploitant_id', $article->exploitant_id) == $exploitant->id ? 'selected' : '' }}>
                                    {{ $exploitant->nom_complet }}
                                </option>
                            @endforeach
                        </select>
                        @error('exploitant_id')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                    <div class="form-group">
                        <label for="parcelle" class="block text-sm font-semibold text-gray-700 mb-2">
                            Parcelle
                        </label>
                        <input type="number" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 hover:border-gray-400" 
                               id="parcelle" name="parcelle" value="{{ old('parcelle', $article->parcelle) }}" 
                               min="0" placeholder="Numéro de parcelle">
                        @error('parcelle')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="lat" class="block text-sm font-semibold text-gray-700 mb-2">
                            Latitude
                        </label>
                        <input type="text" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 hover:border-gray-400" 
                               id="lat" name="lat" value="{{ old('lat', $article->lat) }}" 
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
                               id="log" name="log" value="{{ old('log', $article->log) }}" 
                               placeholder="Longitude">
                        @error('log')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 3: Détails Techniques -->
            <div class="bg-gradient-to-r from-purple-50 to-violet-50 rounded-2xl p-6 border border-purple-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-violet-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-cogs text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-purple-900">Section 3: Détails Techniques</h3>
                </div>
                
                <!-- Technical Measurements -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="form-group">
                        <label for="superficie" class="block text-sm font-semibold text-gray-700 mb-2">
                            Superficie
                        </label>
                        <input type="number" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400" 
                               id="superficie" name="superficie" value="{{ old('superficie', $article->superficie) }}" 
                               min="0" step="0.01" placeholder="Superficie en ha">
                        @error('superficie')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="bo_m3" class="block text-sm font-semibold text-gray-700 mb-2">
                            BO (m³)
                        </label>
                        <input type="number" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400" 
                               id="bo_m3" name="bo_m3" value="{{ old('bo_m3', $article->bo_m3) }}" 
                               min="0" step="0.01" placeholder="BO en m³">
                        @error('bo_m3')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="bi_m3" class="block text-sm font-semibold text-gray-700 mb-2">
                            BI (m³)
                        </label>
                        <input type="number" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400" 
                               id="bi_m3" name="bi_m3" value="{{ old('bi_m3', $article->bi_m3) }}" 
                               min="0" step="0.01" placeholder="BI en m³">
                        @error('bi_m3')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="bf_st" class="block text-sm font-semibold text-gray-700 mb-2">
                            BF(ST)
                        </label>
                        <input type="number" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400" 
                               id="bf_st" name="bf_st" value="{{ old('bf_st', $article->bf_st) }}" 
                               min="0" step="0.01" placeholder="BF (ST)">
                        @error('bf_st')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Product Quantities -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    <div class="form-group">
                        <label for="tanin_t" class="block text-sm font-semibold text-gray-700 mb-2">
                            Tanin (T)
                        </label>
                        <input type="number" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400" 
                               id="tanin_t" name="tanin_t" value="{{ old('tanin_t', $article->tanin_t) }}" 
                               min="0" step="0.01" placeholder="Tanin en tonnes">
                        @error('tanin_t')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="fleur_acacia_t" class="block text-sm font-semibold text-gray-700 mb-2">
                            Fleur Acacia (T)
                        </label>
                        <input type="number" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400" 
                               id="fleur_acacia_t" name="fleur_acacia_t" value="{{ old('fleur_acacia_t', $article->fleur_acacia_t) }}" 
                               min="0" step="0.01" placeholder="Fleur Acacia en tonnes">
                        @error('fleur_acacia_t')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="caroube_t" class="block text-sm font-semibold text-gray-700 mb-2">
                            Caroube (T)
                        </label>
                        <input type="number" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400" 
                               id="caroube_t" name="caroube_t" value="{{ old('caroube_t', $article->caroube_t) }}" 
                               min="0" step="0.01" placeholder="Caroube en tonnes">
                        @error('caroube_t')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="romarin_t" class="block text-sm font-semibold text-gray-700 mb-2">
                            Romarin (T)
                        </label>
                        <input type="number" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400" 
                               id="romarin_t" name="romarin_t" value="{{ old('romarin_t', $article->romarin_t) }}" 
                               min="0" step="0.01" placeholder="Romarin en tonnes">
                        @error('romarin_t')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="liége_st" class="block text-sm font-semibold text-gray-700 mb-2">
                            Liège (ST)
                        </label>
                        <input type="number" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400" 
                               id="liége_st" name="liége_st" value="{{ old('liége_st', $article->liége_st) }}" 
                               min="0" step="0.01" placeholder="Liège en ST">
                        @error('liége_st')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="charbon_bois_ox" class="block text-sm font-semibold text-gray-700 mb-2">
                            Charbon Bois (OX)
                        </label>
                        <input type="number" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400" 
                               id="charbon_bois_ox" name="charbon_bois_ox" value="{{ old('charbon_bois_ox', $article->charbon_bois_ox) }}" 
                               min="0" step="0.01" placeholder="Charbon Bois en OX">
                        @error('charbon_bois_ox')
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
                            <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-violet-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-box text-white text-sm"></i>
                            </div>
                            <h4 class="text-lg font-bold text-purple-900">Produits</h4>
                        </div>
                        <button type="button" 
                                onclick="addProduct()" 
                                class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-purple-600 to-violet-600 text-white rounded-lg hover:from-purple-700 hover:to-violet-700 transition-all duration-300 text-sm">
                            <i class="fas fa-plus"></i>
                            Ajouter Produit
                        </button>
                    </div>
                    
                    <div id="products-container">
                        <!-- Products will be added dynamically here -->
                    </div>
                </div>
            </div>

            <!-- Section 4: Plan du Situation -->
            <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-2xl p-6 border border-purple-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-map-marked-alt text-white text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Plan du Situation</h3>
                        <p class="text-gray-600">Importez un fichier Excel avec les coordonnées des emplacements</p>
                    </div>
                </div>

                <div class="bg-white/60 backdrop-blur-sm rounded-xl p-6 border border-white/40">
                    <div class="mb-4">
                        <label for="locations_file" class="block text-sm font-semibold text-gray-700 mb-2">
                            Fichier Excel des Emplacements
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


   

            <!-- Section Suivi -->
            <div class="bg-gradient-to-r from-orange-50 to-amber-50 rounded-2xl p-6 border border-orange-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-amber-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-clipboard-check text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-orange-900">Suivi</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Invendu -->
                    <div class="form-group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-times-circle text-orange-500 mr-2"></i>Invendu
                        </label>
                        <div class="flex items-center space-x-4">
                            <label class="flex items-center">
                                <input type="radio" name="invendu" value="0" {{ old('invendu', $article->invendu) == 0 ? 'checked' : '' }} class="form-radio text-orange-500">
                                <span class="ml-2 text-gray-700">Non</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="invendu" value="1" {{ old('invendu', $article->invendu) == 1 ? 'checked' : '' }} class="form-radio text-orange-500">
                                <span class="ml-2 text-gray-700">Oui</span>
                            </label>
                        </div>
                    </div>

                    <!-- Prix de retrait -->
                    <div class="form-group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-money-bill-wave text-orange-500 mr-2"></i>Prix de retrait
                        </label>
                        <input type="number" name="prix_de_retrait" value="{{ old('prix_de_retrait', $article->prix_de_retrait) }}" 
                               step="0.01" class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    </div>

                    <!-- Prix de vente -->
                    <div class="form-group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-tag text-orange-500 mr-2"></i>Prix de vente
                        </label>
                        <input type="number" name="prix_vente" value="{{ old('prix_vente', $article->prix_vente) }}" 
                               step="0.01" class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    </div>

                    <!-- Fourniture mise en charge -->
                    <div class="form-group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-hand-holding-usd text-orange-500 mr-2"></i>Fourniture mise en charge
                        </label>
                        <input type="number" name="fourniture_mise_charge" value="{{ old('fourniture_mise_charge', $article->fourniture_mise_charge) }}" 
                               step="0.01" class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    </div>

                    <!-- DC -->
                    <div class="form-group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-check-circle text-orange-500 mr-2"></i>DC
                        </label>
                        <div class="flex items-center space-x-4">
                            <label class="flex items-center">
                                <input type="radio" name="dc" value="0" {{ old('dc', $article->dc) == 0 ? 'checked' : '' }} class="form-radio text-orange-500">
                                <span class="ml-2 text-gray-700">Non</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="dc" value="1" {{ old('dc', $article->dc) == 1 ? 'checked' : '' }} class="form-radio text-orange-500">
                                <span class="ml-2 text-gray-700">Oui</span>
                            </label>
                        </div>
                    </div>

                    <!-- RC -->
                    <div class="form-group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-shield-alt text-orange-500 mr-2"></i>RC
                        </label>
                        <div class="flex items-center space-x-4">
                            <label class="flex items-center">
                                <input type="radio" name="rc" value="0" {{ old('rc', $article->rc) == 0 ? 'checked' : '' }} class="form-radio text-orange-500">
                                <span class="ml-2 text-gray-700">Non</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="rc" value="1" {{ old('rc', $article->rc) == 1 ? 'checked' : '' }} class="form-radio text-orange-500">
                                <span class="ml-2 text-gray-700">Oui</span>
                            </label>
                        </div>
                    </div>

                    <!-- Date de résiliation -->
                    <div class="form-group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calendar-times text-orange-500 mr-2"></i>Date de résiliation
                            <i class="fas fa-question-circle mx-1 text-gray-400" title="Format: jj/mm/aaaa (ex: 01/01/2024)"></i>
                        </label>
                        <input type="date" name="date_de_resiliation" value="{{ old('date_de_resiliation', $article->date_de_resiliation) }}" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                               placeholder="jj/mm/aaaa">
                    </div>

                    <!-- Date de déchéance -->
                    <div class="form-group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calendar-exclamation text-orange-500 mr-2"></i>Date de déchéance
                            <i class="fas fa-question-circle mx-1 text-gray-400" title="Format: jj/mm/aaaa (ex: 01/01/2024)"></i>
                        </label>
                        <input type="date" name="date_de_decheance" value="{{ old('date_de_decheance', $article->date_de_decheance) }}" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                               placeholder="jj/mm/aaaa">
                    </div>

                    <!-- Exploitant -->
                    <div class="form-group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-user-tie text-orange-500 mr-2"></i>Exploitant
                        </label>
                        <select name="exploitant_id" class="form-select w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                            <option value="">Sélectionner un exploitant</option>
                            @foreach(\App\Models\Exploitant::all() as $exploitant)
                                <option value="{{ $exploitant->id }}" {{ old('exploitant_id', $article->exploitant_id) == $exploitant->id ? 'selected' : '' }}>
                                    {{ $exploitant->nom_complet }} ({{ $exploitant->numero }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

                     <!-- Form Actions -->
            <div class="flex items-center gap-4 pt-6 border-t border-gray-200">
                <button 
                    type="submit" 
                    id="submitBtn"
                    class="inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 transform hover:scale-105 shadow-lg"
                >
                    <i class="fas fa-save"></i>
                    <span class="font-semibold">Mettre à jour</span>
                </button>
                
                <a 
                    href="{{ route('articles.index') }}" 
                    class="inline-flex items-center gap-3 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300"
                >
                    <i class="fas fa-arrow-left"></i>
                    <span>Retour</span>
                </a>
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
            if (!field.value.trim()) {
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
</script>
@endpush