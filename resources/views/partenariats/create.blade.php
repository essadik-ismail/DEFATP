@extends('layouts.app')

@section('title', 'Nouveau Partenariat - DEFATP')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center">
                <i class="fas fa-handshake text-white text-2xl"></i>
            </div>
            <div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                    Nouveau Partenariat
                </h1>
                <p class="text-gray-600 text-lg mt-2">Créez un nouveau partenariat</p>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium">Veuillez corriger les erreurs suivantes :</h3>
                    <ul class="mt-2 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20">
        <form action="{{ route('partenariats.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Informations Association -->
            <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-6 border border-indigo-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informations Association</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="nom_association" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nom Association
                        </label>
                        <input type="text" name="nom_association" id="nom_association" value="{{ old('nom_association') }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="nombre_adherents_association" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nombre d'Adhérents
                        </label>
                        <input type="number" name="nombre_adherents_association" id="nombre_adherents_association" value="{{ old('nombre_adherents_association') }}" 
                               min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="date_creation_association" class="block text-sm font-semibold text-gray-700 mb-2">
                            Date de Création
                        </label>
                        <input type="date" name="date_creation_association" id="date_creation_association" value="{{ old('date_creation_association') }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="localisation_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            Localisation
                        </label>
                        <select name="localisation_id" id="localisation_id" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">Sélectionner</option>
                            @foreach($localisations as $localisation)
                                <option value="{{ $localisation->id }}" {{ old('localisation_id') == $localisation->id ? 'selected' : '' }}>
                                    {{ $localisation->CODE }} - {{ $localisation->ENTITE }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Informations Terrain -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informations Terrain</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="superficie" class="block text-sm font-semibold text-gray-700 mb-2">
                            Superficie
                        </label>
                        <input type="number" name="superficie" id="superficie" value="{{ old('superficie') }}" 
                               step="0.01" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="nom_périmètre" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nom Périmètre
                        </label>
                        <input type="text" name="nom_périmètre" id="nom_périmètre" value="{{ old('nom_périmètre') }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="essence_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            Essence
                        </label>
                        <select name="essence_id" id="essence_id" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">Sélectionner</option>
                            @foreach($essences as $essence)
                                <option value="{{ $essence->id }}" {{ old('essence_id') == $essence->id ? 'selected' : '' }}>
                                    {{ $essence->essence }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="Superficie_ha" class="block text-sm font-semibold text-gray-700 mb-2">
                            Superficie (ha)
                        </label>
                        <input type="number" name="Superficie_ha" id="Superficie_ha" value="{{ old('Superficie_ha') }}" 
                               step="0.01" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
            </div>

            <!-- Informations Contrat -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informations Contrat</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="object_cmd" class="block text-sm font-semibold text-gray-700 mb-2">
                            Object CMD
                        </label>
                        <textarea name="object_cmd" id="object_cmd" rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('object_cmd') }}</textarea>
                    </div>
                    <div>
                        <label for="num_contract" class="block text-sm font-semibold text-gray-700 mb-2">
                            Numéro Contrat
                        </label>
                        <input type="text" name="num_contract" id="num_contract" value="{{ old('num_contract') }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="date_signature_contract" class="block text-sm font-semibold text-gray-700 mb-2">
                            Date Signature Contrat
                        </label>
                        <input type="date" name="date_signature_contract" id="date_signature_contract" value="{{ old('date_signature_contract') }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
            </div>

            <!-- Informations Avenant -->
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-2xl p-6 border border-purple-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informations Avenant</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="num_avenant" class="block text-sm font-semibold text-gray-700 mb-2">
                            Numéro Avenant
                        </label>
                        <input type="text" name="num_avenant" id="num_avenant" value="{{ old('num_avenant') }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="nombre_avenant" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nombre d'Avenants
                        </label>
                        <input type="number" name="nombre_avenant" id="nombre_avenant" value="{{ old('nombre_avenant') }}" 
                               min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="date_signature_avenant" class="block text-sm font-semibold text-gray-700 mb-2">
                            Date Signature Avenant
                        </label>
                        <input type="date" name="date_signature_avenant" id="date_signature_avenant" value="{{ old('date_signature_avenant') }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="Superficie_Contrat_avenant" class="block text-sm font-semibold text-gray-700 mb-2">
                            Superficie Contrat Avenant
                        </label>
                        <input type="number" name="Superficie_Contrat_avenant" id="Superficie_Contrat_avenant" value="{{ old('Superficie_Contrat_avenant') }}" 
                               step="0.01" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
            </div>

            <!-- État et Évaluation -->
            <div class="bg-gradient-to-r from-orange-50 to-yellow-50 rounded-2xl p-6 border border-orange-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">État et Évaluation</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="Date_PV_etat_des_lieux" class="block text-sm font-semibold text-gray-700 mb-2">
                            Date PV État des Lieux
                        </label>
                        <input type="date" name="Date_PV_etat_des_lieux" id="Date_PV_etat_des_lieux" value="{{ old('Date_PV_etat_des_lieux') }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="Taux_de_réussite" class="block text-sm font-semibold text-gray-700 mb-2">
                            Taux de Réussite (%)
                        </label>
                        <input type="number" name="Taux_de_réussite" id="Taux_de_réussite" value="{{ old('Taux_de_réussite') }}" 
                               step="0.01" min="0" max="100"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="Etat_de_la_clôture" class="block text-sm font-semibold text-gray-700 mb-2">
                            État de la Clôture
                        </label>
                        <input type="text" name="Etat_de_la_clôture" id="Etat_de_la_clôture" value="{{ old('Etat_de_la_clôture') }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="Etat_peuplement" class="block text-sm font-semibold text-gray-700 mb-2">
                            État Peuplement
                        </label>
                        <input type="text" name="Etat_peuplement" id="Etat_peuplement" value="{{ old('Etat_peuplement') }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div class="md:col-span-2">
                        <label for="PV_évaluation" class="block text-sm font-semibold text-gray-700 mb-2">
                            PV Évaluation
                        </label>
                        <textarea name="PV_évaluation" id="PV_évaluation" rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('PV_évaluation') }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label for="observations" class="block text-sm font-semibold text-gray-700 mb-2">
                            Observations
                        </label>
                        <textarea name="observations" id="observations" rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('observations') }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label for="Contraintes" class="block text-sm font-semibold text-gray-700 mb-2">
                            Contraintes
                        </label>
                        <textarea name="Contraintes" id="Contraintes" rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('Contraintes') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-4 pt-6 border-t border-gray-200">
                <button type="submit" 
                        class="inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-save"></i>
                    <span class="font-semibold">Créer</span>
                </button>
                <a href="{{ route('partenariats.index') }}" 
                   class="inline-flex items-center gap-3 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300">
                    <i class="fas fa-arrow-left"></i>
                    <span>Retour</span>
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

