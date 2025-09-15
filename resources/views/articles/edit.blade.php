@extends('layouts.app')

@section('title', 'Modifier un Article')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center">
                <i class="fas fa-file-alt text-white text-2xl"></i>
            </div>
            <div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
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
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-edit text-white text-xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Formulaire de modification</h2>
                <p class="text-gray-600">Modifiez les informations de l'article forestier</p>
            </div>
        </div>

        <form action="{{ route('articles.update', $article) }}" method="POST" id="articleForm" class="space-y-8" data-server-validation>
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
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
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
                    <div class="form-group">
                        <label for="date_adjudication" class="block text-sm font-semibold text-gray-700 mb-2">
                            Date d'Adjudication <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="date_adjudication" name="date_adjudication" 
                               value="{{ old('date_adjudication', $article->date_adjudication) }}" required>
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
                        <label for="localisation_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            Localisation <span class="text-red-500">*</span>
                        </label>
                        <select class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 hover:border-gray-400" 
                                id="localisation_id" name="localisation_id" required>
                            <option value="">Sélectionner une localisation</option>
                            @foreach($localisations as $localisation)
                                <option value="{{ $localisation->id }}" {{ old('localisation_id', $article->localisation_id) == $localisation->id ? 'selected' : '' }}>
                                    {{ $localisation->CODE }} - {{ $localisation->DRANEF }} - {{ $localisation->ENTITE }}
                                </option>
                            @endforeach
                        </select>
                        @error('localisation_id')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="situation_administrative_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            Situation Administrative <span class="text-red-500">*</span>
                        </label>
                        <select class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 hover:border-gray-400" 
                                id="situation_administrative_id" name="situation_administrative_id" required>
                            <option value="">Sélectionner une situation</option>
                            @foreach($situationAdministratives as $situation)
                                <option value="{{ $situation->id }}" {{ old('situation_administrative_id', $article->situation_administrative_id) == $situation->id ? 'selected' : '' }}>
                                    {{ $situation->commune }} - {{ $situation->province }}
                                </option>
                            @endforeach
                        </select>
                        @error('situation_administrative_id')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div class="form-group">
                        <label for="foret_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            Forêt <span class="text-red-500">*</span>
                        </label>
                        <select class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 hover:border-gray-400" 
                                id="foret_id" name="foret_id" required>
                            <option value="">Sélectionner une forêt</option>
                            @foreach($forets as $foret)
                                <option value="{{ $foret->id }}" {{ old('foret_id', $article->foret_id) == $foret->id ? 'selected' : '' }}>
                                    {{ $foret->foret }}
                                </option>
                            @endforeach
                        </select>
                        @error('foret_id')
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
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="form-group">
                        <label for="essence_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            Essence <span class="text-red-500">*</span>
                        </label>
                        <select class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400" 
                                id="essence_id" name="essence_id" required>
                            <option value="">Sélectionner une essence</option>
                            @foreach($essences as $essence)
                                <option value="{{ $essence->id }}" {{ old('essence_id', $article->essence_id) == $essence->id ? 'selected' : '' }}>
                                    {{ $essence->essence }}
                                </option>
                            @endforeach
                        </select>
                        @error('essence_id')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="nature_de_coupe_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nature de Coupe
                        </label>
                        <select class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400" 
                                id="nature_de_coupe_id" name="nature_de_coupe_id">
                            <option value="">Sélectionner une nature de coupe</option>
                            @foreach($natureDeCoupes as $nature)
                                <option value="{{ $nature->id }}" {{ old('nature_de_coupe_id', $article->nature_de_coupe_id) == $nature->id ? 'selected' : '' }}>
                                    {{ $nature->nature_de_coupe }}
                                </option>
                            @endforeach
                        </select>
                        @error('nature_de_coupe_id')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="exploitant_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            Exploitant
                        </label>
                        <select class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400" 
                                id="exploitant_id" name="exploitant_id">
                            <option value="">Sélectionner un exploitant</option>
                            @foreach($exploitants as $exploitant)
                                <option value="{{ $exploitant->id }}" {{ old('exploitant_id', $article->exploitant_id) == $exploitant->id ? 'selected' : '' }}>
                                    {{ $exploitant->nom }} {{ $exploitant->prenom }}
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
                    <div class="form-group">
                        <label for="type" class="block text-sm font-semibold text-gray-700 mb-2">
                            Type
                        </label>
                        <select class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400" 
                                id="type" name="type">
                            <option value="">Sélectionner un type</option>
                            <option value="Appel d'Offre" {{ old('type', $article->type) == 'Appel d\'Offre' ? 'selected' : '' }}>Appel d'Offre</option>
                            <option value="Vente Directe" {{ old('type', $article->type) == 'Vente Directe' ? 'selected' : '' }}>Vente Directe</option>
                            <option value="Contrat" {{ old('type', $article->type) == 'Contrat' ? 'selected' : '' }}>Contrat</option>
                        </select>
                        @error('type')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">
                    <div class="form-group">
                        <label for="volume" class="block text-sm font-semibold text-gray-700 mb-2">
                            Volume
                        </label>
                        <input type="number" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400" 
                               id="volume" name="volume" value="{{ old('volume', $article->volume) }}" 
                               min="0" step="0.01" placeholder="Volume en m³">
                        @error('volume')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="poids" class="block text-sm font-semibold text-gray-700 mb-2">
                            Poids
                        </label>
                        <input type="number" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400" 
                               id="poids" name="poids" value="{{ old('poids', $article->poids) }}" 
                               min="0" step="0.01" placeholder="Poids en kg">
                        @error('poids')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="prix_retrait" class="block text-sm font-semibold text-gray-700 mb-2">
                            Prix de Retrait
                        </label>
                        <input type="number" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400" 
                               id="prix_retrait" name="prix_retrait" value="{{ old('prix_retrait', $article->prix_retrait) }}" 
                               min="0" step="0.01" placeholder="Prix de retrait">
                        @error('prix_retrait')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="prix_vente" class="block text-sm font-semibold text-gray-700 mb-2">
                            Prix de Vente
                        </label>
                        <input type="number" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400" 
                               id="prix_vente" name="prix_vente" value="{{ old('prix_vente', $article->prix_vente) }}" 
                               min="0" step="0.01" placeholder="Prix de vente">
                        @error('prix_vente')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 4: Informations Supplémentaires -->
            <div class="bg-gradient-to-r from-orange-50 to-red-50 rounded-2xl p-6 border border-orange-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-info-circle text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-orange-900">Section 4: Informations Supplémentaires</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label for="statut" class="block text-sm font-semibold text-gray-700 mb-2">
                            Statut
                        </label>
                        <select class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 hover:border-gray-400" 
                                id="statut" name="statut">
                            <option value="">Sélectionner un statut</option>
                            <option value="En attente" {{ old('statut', $article->statut) == 'En attente' ? 'selected' : '' }}>En attente</option>
                            <option value="En cours" {{ old('statut', $article->statut) == 'En cours' ? 'selected' : '' }}>En cours</option>
                            <option value="Terminé" {{ old('statut', $article->statut) == 'Terminé' ? 'selected' : '' }}>Terminé</option>
                            <option value="Annulé" {{ old('statut', $article->statut) == 'Annulé' ? 'selected' : '' }}>Annulé</option>
                        </select>
                        @error('statut')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 4: Produits -->
            <div class="bg-gradient-to-r from-purple-50 to-violet-50 rounded-2xl p-6 border border-purple-200">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-violet-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-box text-white"></i>
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

                <div id="products-container" class="space-y-4">
                    @if(old('products') || $article->products->count() > 0)
                        @php
                            $products = old('products', $article->products->map(function($product) {
                                return ['name' => $product->name, 'quantity' => $product->quantity];
                            })->toArray());
                        @endphp
                        @foreach($products as $index => $product)
                            <div class="product-row flex items-center gap-4 mb-4 p-4 bg-gray-50 rounded-xl border border-gray-200">
                                <div class="flex-1">
                                    <input type="text" 
                                           name="products[{{ $index }}][name]" 
                                           value="{{ $product['name'] ?? '' }}"
                                           placeholder="Nom du produit" 
                                           class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400"
                                           required>
                                </div>
                                <div class="w-32">
                                    <input type="number" 
                                           name="products[{{ $index }}][quantity]" 
                                           value="{{ $product['quantity'] ?? 1 }}"
                                           placeholder="Quantité" 
                                           min="1" 
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

    <!-- Article Information -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-3xl p-8 mt-8 border border-blue-200 shadow-xl">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-info-circle text-white text-xl"></i>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-blue-900">Informations de l'Article</h3>
                <p class="text-blue-700">Détails et statistiques</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-2xl p-6 border border-blue-200">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-calendar text-white"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900">Créé le</h4>
                        <p class="text-gray-600 text-sm">{{ $article->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl p-6 border border-blue-200">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-clock text-white"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900">Modifié le</h4>
                        <p class="text-gray-600 text-sm">{{ $article->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl p-6 border border-blue-200">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-violet-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-database text-white"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900">ID</h4>
                        <p class="text-gray-600 text-sm">#{{ $article->id }}</p>
                    </div>
                </div>
            </div>
        </div>
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
    
    /* Dark mode support */
    .dark-mode .form-input {
        background-color: var(--dark-bg-secondary);
        border-color: var(--dark-border);
        color: var(--dark-text-primary);
    }
    
    .dark-mode .form-input:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('articleForm');
    const submitBtn = document.getElementById('submitBtn');
    
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
});

// Product management
let productCount = {{ old('products') ? count(old('products')) : ($article->products->count() ?? 0) }};

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
</script>
@endpush