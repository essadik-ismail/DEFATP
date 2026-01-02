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
                        <label for="type" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Type <span class="text-red-500">*</span></span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Sélectionnez le type d'article (Appel d'Offre, Adjudication, Marche Negocié)"></i>
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
                        <label for="numero_adjudication" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Numéro d'Adjudication</span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Numéro juridique de l'adjudication"></i>
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
                        <label for="annee" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Année <span class="text-red-500">*</span></span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Année de l'article"></i>
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
                        <label for="date_adjudication" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Date d'Adjudication <span class="text-red-500">*</span></span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Format: jj/mm/aaaa (ex: 01/01/2024)"></i>
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
                        <label for="lot" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Lot</span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Numéro de lot"></i>
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
                        <label for="numero" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Numéro d'Article <span class="text-red-500">*</span></span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Numéro unique de l'article"></i>
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
                        <label for="situation_administrative_ids" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Situations Administratives <span class="text-red-500">*</span></span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Sélectionnez une ou plusieurs situations administratives"></i>
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
                        <label for="foret_ids" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Forêts <span class="text-red-500">*</span></span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Sélectionnez une ou plusieurs forêts"></i>
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
                        <label for="essence_ids" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Essences <span class="text-red-500">*</span></span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Sélectionnez une ou plusieurs essences"></i>
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
                        <label for="nature_de_coupe_ids" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Natures de Coupe <span class="text-red-500">*</span></span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Sélectionnez une ou plusieurs natures de coupe"></i>
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
                        <label for="nature_juridique" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Nature Juridique</span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Nature juridique de l'article"></i>
                        </label>
                        <!-- Removed: Nature Juridique - column was removed -->
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
                        <label for="parcelle" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Parcelle</span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Numéro de parcelle"></i>
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
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="form-group">
                        <label for="superficie" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Superficie</span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Superficie en hectares"></i>
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
                        <label for="fourniture_mise_charge" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Fourniture mise en charge</span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Fourniture mise en charge"></i>
                        </label>
                        <input type="number" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400" 
                               id="fourniture_mise_charge" name="fourniture_mise_charge" value="{{ old('fourniture_mise_charge', $article->fourniture_mise_charge) }}" 
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
                        @if($article->products && $article->products->count() > 0)
                            @foreach($article->products as $index => $product)
                                <div class="product-row flex items-center gap-4 mb-4 p-4 bg-gray-50 rounded-xl border border-gray-200">
                                    <div class="flex-1">
                                        <select name="products[{{ $index }}][name]" 
                                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400"
                                               required>
                                            <option value="">Sélectionner un produit</option>
                                            @foreach($products as $prod)
                                                <option value="{{ $prod->name }}" {{ old("products.{$index}.name", $product->name) == $prod->name ? 'selected' : '' }}>
                                                    {{ $prod->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="w-32">
                                        <input type="number" 
                                               name="products[{{ $index }}][quantity]" 
                                               placeholder="Quantité" 
                                               min="0.01" 
                                               step="0.01"
                                               value="{{ old("products.{$index}.quantity", $product->pivot->quantity ?? $product->quantity ?? 1) }}"
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


   

            <!-- Section Suivi -->
            <div class="bg-gradient-to-r from-orange-50 to-amber-50 rounded-2xl p-6 border border-orange-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-amber-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-clipboard-check text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-orange-900">Suivi</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Nommer à la vente -->
                    <div class="form-group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-tags text-orange-500 mr-2"></i>Nommer à la vente
                        </label>
                        <div class="flex items-center space-x-4">
                            <label class="flex items-center">
                                <input type="radio" name="nommer_a_la_vente" value="0" {{ old('nommer_a_la_vente', $article->nommer_a_la_vente) == 0 ? 'checked' : '' }} class="form-radio text-orange-500">
                                <span class="ml-2 text-gray-700">Non</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="nommer_a_la_vente" value="1" {{ old('nommer_a_la_vente', $article->nommer_a_la_vente) == 1 ? 'checked' : '' }} class="form-radio text-orange-500">
                                <span class="ml-2 text-gray-700">Oui</span>
                            </label>
                        </div>
                    </div>


                    <!-- Removed: Invendu - column was removed -->

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
                    

                    <!-- Removed: Prix de vente, DC, Date de déchéance, Prix de retrait, RC, Date de résiliation - columns were removed -->

                    <!-- Taxe refection chemins -->
                    <div class="form-group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-road text-orange-500 mr-2"></i>Taxe refection chemins
                        </label>
                        <input type="number" name="taxe_refection_chemins" value="{{ old('taxe_refection_chemins', $article->taxe_refection_chemins) }}" 
                               step="0.01" min="0" class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    </div>

                    <!-- Service rendu ANEF -->
                    <div class="form-group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-handshake text-orange-500 mr-2"></i>Service rendu ANEF
                        </label>
                        <input type="number" name="service_rendu_anef" value="{{ old('service_rendu_anef', $article->service_rendu_anef) }}" 
                               step="0.01" min="0" class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    </div>

                    <!-- Bois chauffage volume -->
                    <div class="form-group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-fire text-orange-500 mr-2"></i>Bois chauffage volume
                        </label>
                        <input type="number" name="bois_chauffage_volume" value="{{ old('bois_chauffage_volume', $article->bois_chauffage_volume) }}" 
                               step="0.01" min="0" class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    </div>

                    <!-- Bois chauffage destination -->
                    <div class="form-group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-map-marker-alt text-orange-500 mr-2"></i>Bois chauffage destination
                        </label>
                        <input type="text" name="bois_chauffage_destination" value="{{ old('bois_chauffage_destination', $article->bois_chauffage_destination) }}" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    </div>

                    <!-- Date payement service ANEF -->
                    <div class="form-group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calendar-check text-orange-500 mr-2"></i>Date payement service ANEF
                        </label>
                        <input type="date" name="date_payement_service_anef" value="{{ old('date_payement_service_anef', $article->date_payement_service_anef) }}" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                               placeholder="jj/mm/aaaa">
                    </div>

                    <!-- Date livraison mise en charge BF -->
                    <div class="form-group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-truck text-orange-500 mr-2"></i>Date livraison mise en charge BF
                        </label>
                        <input type="date" name="date_livaison_mise_en_charge_bf" value="{{ old('date_livaison_mise_en_charge_bf', $article->date_livaison_mise_en_charge_bf) }}" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                               placeholder="jj/mm/aaaa">
                    </div>

                    <!-- ZDTF -->
                    <div class="form-group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-map text-orange-500 mr-2"></i>ZDTF
                        </label>
                        <select name="zdtf_id" class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                            <option value="">Sélectionner un ZDTF</option>
                            @foreach($zdtfs as $zdtf)
                                <option value="{{ $zdtf->id }}" {{ old('zdtf_id', $article->zdtf_id) == $zdtf->id ? 'selected' : '' }}>
                                    {{ $zdtf->name }}@if($zdtf->dpanef) - {{ $zdtf->dpanef->name }}@endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Mode Exploitations -->
                    <div class="form-group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-tools text-orange-500 mr-2"></i>Mode d'Exploitation
                        </label>
                        <select multiple name="mode_exploitation_ids[]" class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                            @foreach($modeExploitations as $modeExploitation)
                                <option value="{{ $modeExploitation->id }}" {{ collect(old('mode_exploitation_ids', $article->modeExploitations->pluck('id')->toArray()))->contains($modeExploitation->id) ? 'selected' : '' }}>
                                    {{ $modeExploitation->name }}
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
let productCount = {{ $article->products ? $article->products->count() : 0 }};

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