@extends('layouts.app')

@section('title', 'Nouveau Suivi Contract Programme - DEFATP')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center">
                <i class="fas fa-clipboard-list text-white text-2xl"></i>
            </div>
            <div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                    Nouveau Suivi Contract Programme
                </h1>
                <p class="text-gray-600 text-lg mt-2">Créez un nouveau suivi de contrat programme</p>
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
        <form action="{{ route('suivi-contract-programmes.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Informations de Base -->
            <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-6 border border-indigo-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informations de Base</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="partenariat_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            Partenariat <span class="text-red-500">*</span>
                        </label>
                        <select name="partenariat_id" id="partenariat_id" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">Sélectionner</option>
                            @foreach($partenariats as $p)
                                <option value="{{ $p->id }}" {{ (old('partenariat_id', $partenariat?->id) == $p->id) ? 'selected' : '' }}>
                                    {{ $p->nom_association ?? 'Partenariat #' . $p->id }} - {{ $p->num_contract ?? 'N/A' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="localisation_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            Localisation
                        </label>
                        <select name="localisation_id" id="localisation_id" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">Sélectionner</option>
                            @foreach($localisations as $localisation)
                                <option value="{{ $localisation->id }}" {{ old('localisation_id') == $localisation->id ? 'selected' : '' }}>
                                    {{ $localisation->CODE }} - {{ $localisation->ENTITE }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="foret_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            Forêt
                        </label>
                        <select name="foret_id" id="foret_id" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">Sélectionner</option>
                            @foreach($forets as $foret)
                                <option value="{{ $foret->id }}" {{ old('foret_id') == $foret->id ? 'selected' : '' }}>
                                    {{ $foret->foret }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="Année" class="block text-sm font-semibold text-gray-700 mb-2">
                            Année
                        </label>
                        <input type="number" name="Année" id="Année" value="{{ old('Année', date('Y')) }}" 
                               min="1900" max="2100"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
            </div>

            <!-- Informations Projet -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informations Projet</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="CT" class="block text-sm font-semibold text-gray-700 mb-2">
                            CT
                        </label>
                        <input type="text" name="CT" id="CT" value="{{ old('CT') }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="DPF" class="block text-sm font-semibold text-gray-700 mb-2">
                            DPF
                        </label>
                        <input type="text" name="DPF" id="DPF" value="{{ old('DPF') }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="Parcelle" class="block text-sm font-semibold text-gray-700 mb-2">
                            Parcelle
                        </label>
                        <input type="text" name="Parcelle" id="Parcelle" value="{{ old('Parcelle') }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="Projet_CP" class="block text-sm font-semibold text-gray-700 mb-2">
                            Projet CP
                        </label>
                        <input type="text" name="Projet_CP" id="Projet_CP" value="{{ old('Projet_CP') }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
            </div>

            <!-- Superficie et Montants Prévus -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Superficie et Montants Prévus</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="Superficie_prévue_CP_ha" class="block text-sm font-semibold text-gray-700 mb-2">
                            Superficie Prévue CP (ha)
                        </label>
                        <input type="number" name="Superficie_prévue_CP_ha" id="Superficie_prévue_CP_ha" value="{{ old('Superficie_prévue_CP_ha') }}" 
                               step="0.01" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="Montant_prévu_CP_dh" class="block text-sm font-semibold text-gray-700 mb-2">
                            Montant Prévu CP (DH)
                        </label>
                        <input type="number" name="Montant_prévu_CP_dh" id="Montant_prévu_CP_dh" value="{{ old('Montant_prévu_CP_dh') }}" 
                               step="0.01" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
            </div>

            <!-- Superficie et Montants Engagés -->
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-2xl p-6 border border-purple-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Superficie et Montants Engagés</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="Superficie_engagée_ha" class="block text-sm font-semibold text-gray-700 mb-2">
                            Superficie Engagée (ha)
                        </label>
                        <input type="number" name="Superficie_engagée_ha" id="Superficie_engagée_ha" value="{{ old('Superficie_engagée_ha') }}" 
                               step="0.01" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="Montant_engagé_dh" class="block text-sm font-semibold text-gray-700 mb-2">
                            Montant Engagé (DH)
                        </label>
                        <input type="number" name="Montant_engagé_dh" id="Montant_engagé_dh" value="{{ old('Montant_engagé_dh') }}" 
                               step="0.01" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
            </div>

            <!-- Superficie et Montants Payés -->
            <div class="bg-gradient-to-r from-orange-50 to-yellow-50 rounded-2xl p-6 border border-orange-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Superficie et Montants Payés</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="Superficie_payée_ha" class="block text-sm font-semibold text-gray-700 mb-2">
                            Superficie Payée (ha)
                        </label>
                        <input type="number" name="Superficie_payée_ha" id="Superficie_payée_ha" value="{{ old('Superficie_payée_ha') }}" 
                               step="0.01" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="Montant_payé_dh" class="block text-sm font-semibold text-gray-700 mb-2">
                            Montant Payé (DH)
                        </label>
                        <input type="number" name="Montant_payé_dh" id="Montant_payé_dh" value="{{ old('Montant_payé_dh') }}" 
                               step="0.01" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="Superficie_non_payée" class="block text-sm font-semibold text-gray-700 mb-2">
                            Superficie Non Payée (ha)
                        </label>
                        <input type="number" name="Superficie_non_payée" id="Superficie_non_payée" value="{{ old('Superficie_non_payée') }}" 
                               step="0.01" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div class="md:col-span-2">
                        <label for="Motif_du_Non_paiement" class="block text-sm font-semibold text-gray-700 mb-2">
                            Motif du Non Paiement
                        </label>
                        <textarea name="Motif_du_Non_paiement" id="Motif_du_Non_paiement" rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('Motif_du_Non_paiement') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-4 pt-6 border-t border-gray-200">
                <button type="submit" 
                        class="inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-save"></i>
                    <span class="font-semibold">Créer</span>
                </button>
                @if($partenariat)
                    <a href="{{ route('partenariats.show', $partenariat->id) }}" 
                       class="inline-flex items-center gap-3 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300">
                        <i class="fas fa-arrow-left"></i>
                        <span>Retour</span>
                    </a>
                @else
                    <a href="{{ route('partenariats.index') }}" 
                       class="inline-flex items-center gap-3 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300">
                        <i class="fas fa-arrow-left"></i>
                        <span>Retour</span>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection

